<?php

namespace App\Http\Controllers;

use App\Models\PatientFile;
use App\Models\PatientFileShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Carbon\Carbon;

class PatientFileController extends Controller
{
    /**
     * Display the patient's file management page
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->user_role !== User::ROLE_PATIENT) {
            abort(403, 'Access denied. Patients only.');
        }

        $files = PatientFile::where('user_id', $user->id)
            ->where('is_active', true)
            ->with(['shares.doctor'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Patient/MyFiles', [
            'files' => $files->map(function ($file) {
                return [
                    'id' => $file->id,
                    'original_file_name' => $file->original_file_name,
                    'file_type' => $file->file_type,
                    'file_size' => $file->formatted_file_size,
                    'purpose' => $file->purpose,
                    'description' => $file->description,
                    'uploaded_at' => $file->created_at->format('M d, Y H:i'),
                    'shares' => $file->activeShares->map(function ($share) {
                        return [
                            'id' => $share->id,
                            'doctor_name' => $share->doctor->surname . ' ' . $share->doctor->otherNames,
                            'shared_at' => $share->shared_at->format('M d, Y H:i'),
                            'notes' => $share->notes,
                            'expires_at' => $share->access_expires_at ? $share->access_expires_at->format('M d, Y H:i') : null,
                        ];
                    })
                ];
            }),
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ]
        ]);
    }

    /**
     * Upload a new file
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->user_role !== User::ROLE_PATIENT) {
            abort(403, 'Access denied. Patients only.');
        }

        $request->validate([
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt,xls,xlsx',
            'purpose' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        
        // Store file in private directory
        $filePath = $file->storeAs('patient-files/' . $user->id, $fileName, 'local');

        PatientFile::create([
            'user_id' => $user->id,
            'file_name' => $fileName,
            'original_file_name' => $originalName,
            'file_path' => $filePath,
            'file_size' => $file->getSize(),
            'file_type' => $file->getMimeType(),
            'purpose' => $request->purpose,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('success', 'File uploaded successfully!');
    }

    /**
     * Download a file
     */
    public function download(PatientFile $file)
    {
        $user = Auth::user();
        
        // Check if user has permission to download
        if ($user->user_role === User::ROLE_PATIENT) {
            // Patients can download their own files
            if ($file->user_id !== $user->id) {
                abort(403, 'Access denied.');
            }
        } elseif ($user->user_role === User::ROLE_DOCTOR) {
            // Doctors can download files shared with them
            if (!$file->isSharedWithDoctor($user->id)) {
                abort(403, 'File not shared with you.');
            }
        } else {
            abort(403, 'Access denied.');
        }

        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($file->file_path, $file->original_file_name);
    }

    /**
     * Share file with a doctor
     */
    public function shareWithDoctor(Request $request, PatientFile $file)
    {
        $user = Auth::user();
        
        if ($user->user_role !== User::ROLE_PATIENT || $file->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:500',
            'expires_in_days' => 'nullable|integer|min:1|max:365'
        ]);

        // Verify the doctor exists and is actually a doctor
        $doctor = User::where('id', $request->doctor_id)
            ->where('user_role', User::ROLE_DOCTOR)
            ->first();

        if (!$doctor) {
            return redirect()->back()->withErrors(['doctor_id' => 'Invalid doctor selected.']);
        }

        // Check for existing share
        $existingShare = PatientFileShare::where('patient_file_id', $file->id)
            ->where('doctor_id', $doctor->id)
            ->first();

        $expiresAt = $request->expires_in_days 
            ? Carbon::now()->addDays((int) $request->expires_in_days)
            : null;

        if ($existingShare) {
            // Update existing share instead of creating a new one
            $existingShare->update([
                'is_active' => true,
                'shared_at' => Carbon::now(),
                'access_expires_at' => $expiresAt,
                'notes' => $request->notes,
            ]);
        } else {
            // Create new share
            PatientFileShare::create([
                'patient_file_id' => $file->id,
                'patient_id' => $user->id,
                'doctor_id' => $doctor->id,
                'shared_at' => Carbon::now(),
                'access_expires_at' => $expiresAt,
                'notes' => $request->notes,
            ]);
        }

        return redirect()->back()->with('success', 'File shared successfully with ' . $doctor->surname . ' ' . $doctor->otherNames . '!');
    }

    /**
     * Remove file share
     */
    public function removeShare(PatientFileShare $share)
    {
        $user = Auth::user();
        
        if ($user->user_role !== User::ROLE_PATIENT || $share->patient_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        $share->update(['is_active' => false]);

        return redirect()->back()->with('success', 'File share removed successfully!');
    }

    /**
     * Delete a file
     */
    public function destroy(PatientFile $file)
    {
        $user = Auth::user();
        
        if ($user->user_role !== User::ROLE_PATIENT || $file->user_id !== $user->id) {
            abort(403, 'Access denied.');
        }

        // Deactivate all shares first
        $file->shares()->update(['is_active' => false]);

        // Mark file as inactive
        $file->update(['is_active' => false]);

        // Optionally delete the physical file
        if (Storage::disk('local')->exists($file->file_path)) {
            Storage::disk('local')->delete($file->file_path);
        }

        return redirect()->back()->with('success', 'File deleted successfully!');
    }

    /**
     * Get available doctors for sharing (API endpoint)
     */
    public function getAvailableDoctors()
    {
        $doctors = User::where('user_role', User::ROLE_DOCTOR)
            ->select('id', 'surname', 'otherNames', 'email')
            ->orderBy('surname')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id' => $doctor->id,
                    'name' => $doctor->surname . ' ' . $doctor->otherNames,
                    'email' => $doctor->email
                ];
            });

        return response()->json($doctors);
    }

    /**
     * Doctor's shared files view
     */
    public function doctorSharedFiles()
    {
        $user = Auth::user();
        
        if ($user->user_role !== User::ROLE_DOCTOR) {
            abort(403, 'Access denied. Doctors only.');
        }

        $sharedFiles = PatientFileShare::where('doctor_id', $user->id)
            ->valid()
            ->with(['patientFile', 'patient'])
            ->orderBy('shared_at', 'desc')
            ->get();

        return Inertia::render('Doctors/SharedFiles', [
            'sharedFiles' => $sharedFiles->map(function ($share) {
                return [
                    'id' => $share->id,
                    'file_id' => $share->patientFile->id,
                    'file_name' => $share->patientFile->original_file_name,
                    'file_type' => $share->patientFile->file_type,
                    'file_size' => $share->patientFile->formatted_file_size,
                    'purpose' => $share->patientFile->purpose,
                    'description' => $share->patientFile->description,
                    'patient_name' => $share->patient->surname . ' ' . $share->patient->otherNames,
                    'patient_email' => $share->patient->email,
                    'shared_at' => $share->shared_at->format('M d, Y H:i'),
                    'expires_at' => $share->access_expires_at ? $share->access_expires_at->format('M d, Y H:i') : null,
                    'notes' => $share->notes,
                    'is_expired' => $share->is_expired
                ];
            })
        ]);
    }

    /**
     * Preview a file
     */
    public function preview(PatientFile $file)
    {
        $user = Auth::user();
        
        // Check if user has permission to preview
        if ($user->user_role === User::ROLE_PATIENT) {
            // Patients can only view their own files
            if ($file->user_id !== $user->id) {
                abort(403, 'Access denied.');
            }
        } elseif ($user->user_role === User::ROLE_DOCTOR) {
            // Doctors can view files shared with them that are still active
            $share = PatientFileShare::where('patient_file_id', $file->id)
                ->where('doctor_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$share || ($share->access_expires_at && Carbon::now()->isAfter($share->access_expires_at))) {
                abort(403, 'Access denied or share expired.');
            }
        } else {
            abort(403, 'Access denied.');
        }

        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        // For PDFs and images, we'll stream the file with appropriate headers
        if (in_array($file->file_type, ['application/pdf', 'image/jpeg', 'image/png', 'image/gif'])) {
            return response()->file(
                Storage::disk('local')->path($file->file_path),
                ['Content-Type' => $file->file_type]
            );
        }

        abort(400, 'File type not supported for preview.');
    }

    /**
     * Get file content (for text files)
     */
    public function getContent(PatientFile $file)
    {
        $user = Auth::user();
        
        // Check if user has permission to view content
        if ($user->user_role === User::ROLE_PATIENT) {
            // Patients can only view their own files
            if ($file->user_id !== $user->id) {
                abort(403, 'Access denied.');
            }
        } elseif ($user->user_role === User::ROLE_DOCTOR) {
            // Doctors can view files shared with them that are still active
            $share = PatientFileShare::where('patient_file_id', $file->id)
                ->where('doctor_id', $user->id)
                ->where('is_active', true)
                ->first();

            if (!$share || ($share->access_expires_at && Carbon::now()->isAfter($share->access_expires_at))) {
                abort(403, 'Access denied or share expired.');
            }
        } else {
            abort(403, 'Access denied.');
        }

        if (!Storage::disk('local')->exists($file->file_path)) {
            abort(404, 'File not found.');
        }

        // Only allow text file types
        if (!in_array($file->file_type, ['text/plain', 'text/csv']) && 
            !str_ends_with($file->original_file_name, '.txt') && 
            !str_ends_with($file->original_file_name, '.csv')) {
            abort(400, 'File type not supported for text preview.');
        }

        return response()->stream(function () use ($file) {
            echo Storage::disk('local')->get($file->file_path);
        }, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
