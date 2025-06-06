<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\DoctorController;
use \App\Http\Controllers\AdminController;
use \App\Http\Controllers\PatientController;
use \App\Http\Controllers\AppointmentDepartmentDoctorController;
use \App\Http\Controllers\PatientPrescriptionController;
use \App\Http\Controllers\UserPlanController;
use \App\Http\Controllers\InvoiceController;
use \App\Http\Controllers\AppointmentController;
use \App\Http\Controllers\MedicalRecordsController;
use \App\Http\Controllers\PlansController;
use \App\Http\Controllers\ServicesController;
use \App\Http\Controllers\PricingController;
use \App\Http\Controllers\AboutUsController;
use \App\Http\Controllers\ContactUsController;
use App\Http\Controllers\PaystackController;
use \App\Http\Controllers\FaqController;
use \App\Http\Controllers\PrivacyPolicyController;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


//guest Links
Route::get('/services', [ServicesController::class, 'index'])->name('services.index');
Route::get('/pricing', [PricingController::class, 'index'])->name('pricing.index');
Route::get('/about', [AboutUsController::class, 'index'])->name('about.index');
Route::get('/contact', [ContactUsController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactUsController::class, 'submit']);
Route::get('/faq', [faqController::class, 'faq'])->name('faq.index');
Route::get('/privacypolicy', [PrivacyPolicyController::class, 'privacypolicy'])->name('privacypolicy.index');



// routes/web.php
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::middleware(['auth'])->group(function () {
// Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'email_verified' => Auth::user()?->hasVerifiedEmail() ?? false,
        ]);
    })->name('dashboard');


    //    patient links >>>>>>>>>>>
    Route::middleware(['auth', 'verified', RoleMiddleware::class . ':patient'])->group(function () {
        Route::post('/patient-schedule-appointment', [PatientController::class, 'createAppointments']);
        Route::post('/patient-lay-complain', [PatientController::class, 'submitComplain']);
        Route::get('/patient-dashboard-overview', [PatientController::class, 'index'])->name('patient.dashboard');
        Route::get('/patient-book-appointment', [PatientController::class, 'appointments']);
        Route::get('/patient-my-appointments', [PatientController::class, 'fectUserAppointments']);
        Route::get('/check-if-patient-plan-expires', [UserPlanController::class, 'getUserDashboardData']);
        Route::get('/patient-medical-records', [MedicalRecordsController::class, 'userRecords']);
        Route::get('/patient-medical-history', [PatientController::class, 'createMedicalHistory']);
        Route::post('/patient-medical-history', [PatientController::class, 'saveMedicalHistory']);
        Route::get('/patient-prescriptions', [PatientPrescriptionController::class, 'index']);
        Route::get('/patient-billing-and-payments', [PatientController::class, 'billsAndPayments']);
        Route::get('/patient-messages', [PatientController::class, 'fetchComplains']);
        Route::get('/patient-messages-create', [PatientController::class, 'layComplain']);
        Route::get('/patient-profile-settings', [PatientController::class, '']);
        Route::get('/patient-health-tracker', [PatientController::class, '']);
        Route::get('patient-view-reply/{user_id}/{complain_id}', [PatientController::class, 'userViewRecomendationFromDoctor']);
        Route::get('/patient-available-plans', [PlansController::class, 'availablePlans']);
        Route::get('/patient-choose-plan/{invoice_id}/{user_id}', [InvoiceController::class, 'index']);
        Route::post('/patient-choose-plan/{plan_id}/{user_id}', [InvoiceController::class, 'store']);
        Route::get('/pay', [PaystackController::class, 'index'])->name('pay');
        Route::post('/pay', [PaystackController::class, 'redirectToGateway'])->name('paystack.redirect');
        Route::get('/pay/callback', [PaystackController::class, 'handleGatewayCallback']);
        Route::get('/payment/success', [PaystackController::class, 'success'])->name('payment.success');
        Route::get('/payment/failed', [PaystackController::class, 'failed'])->name('payment.failed');
        Route::get('/patient-profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::get('/doctor-profile-and-availability', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Doctor's Links >>>>>>>>>>>>>>>>>>>>>>
    Route::middleware(['auth', 'verified', RoleMiddleware::class . ':doctor'])->group(function () {
        Route::get('/view-record/{user_id}/{record_id}', [MedicalRecordsController::class, 'viewUserRecord']);
        // Route::get('/check-if-patient-plan-expires', [UserPlanController::class, 'getUserDashboardData']);
        Route::get('/doctor-dashboard-overview', [DoctorController::class, 'index'])->name('doctor.dashboard');
        Route::get('/doctor-view-all-users', [PatientController::class, 'viewAllUsers']);
        Route::get('/doctor-appointments', [AppointmentController::class, 'index']);
        Route::get('/doctor-take-a-leave', [DoctorController::class, 'createTakeALeave']);
        Route::get('/doctor-messages', [DoctorController::class, 'fetchDepartmentMessages']);
        Route::get('/doctor-reply/{complain_id}/{complain_user_id}', [DoctorController::class, 'creatReplyMessage']);
        Route::post('/doctor-reply/save-complaints', [DoctorController::class, 'saveComplainMedications']);
        Route::post('/doctor-reschedule-appointment/{appointment_id}', [AppointmentDepartmentDoctorController::class, 'store']);
        Route::get('/doctor-medical-records', [MedicalRecordsController::class, 'index']);
        Route::get('/doctor-update-medical-records', [MedicalRecordsController::class, 'create']);
        Route::get('/view-user/{user_id}', [PatientController::class, 'viewOneUser']);
        Route::post('/doctor-update-medical-records', [MedicalRecordsController::class, 'store']);
    });

    //    Admin >>>>>>>>>>>
    Route::middleware(['auth', 'verified', RoleMiddleware::class . ':admin'])->group(function () {
        // Route::get('/check-if-patient-plan-expires', [UserPlanController::class, 'getUserDashboardData']);
        Route::get('/admin-dashboard', [AdminController::class, 'index']);
        Route::get('/admin-add-user', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/make-admin', [AdminController::class, 'makeUserAdmin']);
        Route::get('/create-doctor', [DoctorController::class, 'create']);
        Route::post('/add-doctor', [DoctorController::class, 'addDoctor']);
        Route::post('/admin-add-plan', [PlansController::class, 'store']);
        Route::get('/admin-add-plan', [PlansController::class, 'create']);
        Route::get('/admin-add-department', [AdminController::class, 'addDepartment']);
        Route::post('/admin-add-department', [AdminController::class, 'storeDepartment']);
        Route::get('/fetch-departments', [AdminController::class, 'fetchDepartments']);
        Route::delete('/departments/{department}', [AdminController::class, 'destroyDepartment'])->name('departments.destroy');
        Route::get('/admin-fetch-appointments', [AdminController::class, 'fetchAppointments']);
        Route::get('/admin-users', [AdminController::class, 'viewAllUsers']);
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::get('/admin-plans', [AdminController::class, 'viewAllPlans']);
        Route::delete('/plans/{plan}', [AdminController::class, 'destroy'])->name('plans.destroy');
        Route::get('/create-admin', [AdminController::class, 'createAdmin']);
        Route::get('/create-patient', [DoctorController::class, 'createTakeALeave']);
        Route::get('/run-migrations', function () {
            // Check if the user is authorized to run the migration (optional but highly recommended)
            if (auth()->user() && auth()->user()->user_role === 'admin') {
                Artisan::call('migrate');
                return 'Migrations have been run successfully!';
            }

            return 'Unauthorized action';
        });
        Route::get('/run-artisan/{command}', function ($command) {
            try {
                Artisan::call($command);
                return Artisan::output();
            } catch (\Exception $e) {
                return "Error: " . $e->getMessage();
            }
        });
        Route::get('/run-npm/{command}', function ($command) {
            try {
                // Sanitize the command (optional but recommended)
                $allowedCommands = ['install', 'run dev', 'run build'];
                if (!in_array($command, $allowedCommands)) {
                    return "Error: Command not allowed.";
                }

                // Execute the NPM command
                $output = shell_exec("npm $command 2>&1");
                return nl2br($output);
            } catch (\Exception $e) {
                return "Error: " . $e->getMessage();
            }
        });
    });
});

// Route::get('/change-password11', function () {
//     $user = \App\Models\User::where('email', 'doctor@timelesshealthcare247.com')->first();
//     if ($user) {
//         $user->password = bcrypt('pass1234');
//         $user->save();
//         return 'Password has been reset successfully!';
//     }
//     return 'User not found!';
// });



require __DIR__ . '/auth.php';
