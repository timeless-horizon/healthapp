<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Unicodeveloper\Paystack\Paystack;
use Illuminate\Support\Facades\Log;
class PaystackController extends Controller
{
    private $paystack;

    public function __construct()
    {
        $this->paystack = new Paystack();
    }

    public function index()
    {
        return Inertia::render('Patient/PaystackPayment');
    }

    public function redirectToGateway(Request $request)
    {
        $request->validate([
            'invoice.invoice_number' => 'required|exists:invoices,invoice_number',
        ]);

        $invoice = Invoice::where('invoice_number', $request->input('invoice.invoice_number'))
            ->where('user_id', auth()->id())
            ->where('status', 'unpaid')
            ->first();

        if (!$invoice) {
            return response()->json(['error' => 'Invalid or already paid invoice'], 404);
        }

        $order_id = uniqid();

        try {
            $paymentData = [
                'amount' => $invoice->amount * 100,
                'email' => auth()->user()->email,
                'reference' => $order_id,
                'metadata' => [
                    'invoice_number' => $invoice->invoice_number,
                    'user_id' => $invoice->user_id,
                    'plan_id' => $invoice->plan_id,
                    'paid_at' => $invoice->paid_at,
                    'duration' => $invoice->duration,
                    'currency' => $invoice->currency,
                ],
            ];

            $this->paystack->getAuthorizationUrl($paymentData);

            return response()->json([
                'authorization_url' => $this->paystack->url
            ]);
        } catch (\Exception $e) {
            Log::error('Paystack payment initialization failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);

        }
    }

    public function handleGatewayCallback(Request $request)
    {
        try {
            $paymentData = $this->paystack->getPaymentData();

            if ($paymentData['status'] && $paymentData['data']['status'] === 'success') {
                $invoiceNumber = $paymentData['data']['metadata']['invoice_number'] ?? null;

                if (!$invoiceNumber) {
                    return response()->json(['error' => 'Invoice number missing from payment metadata'], 400);
                }

                $invoice = Invoice::where('invoice_number', $invoiceNumber)
                    ->where('status', 'unpaid')
                    ->first();

                if ($invoice) {
                    $invoice->update([
                        'reference' => $paymentData['data']['reference'],
                        'status' => 'paid',
                        'payment_method' => $paymentData['data']['channel'],
                        'currency' => $paymentData['data']['currency'],
                        'paid_at' => now(),
                    ]);

                    $existingPlan = UserPlan::where('user_id', $invoice->user_id)->first();
                    if ($existingPlan) {
                        $existingPlan->update([
                            'user_id' => $invoice->user_id,
                            'plans_id' => $invoice->plan_id,
                            'duration' => $invoice->duration,
                            'start_date' => $invoice->paid_at,
                            'end_date' => $invoice->due_date,
                            'status' => true,
                        ]);
                    } else {
                        UserPlan::create([
                            'user_id' => $invoice->user_id,
                            'plans_id' => $invoice->plan_id,
                            'duration' => $invoice->duration,
                            'start_date' => $invoice->paid_at,
                            'end_date' => $invoice->due_date,
                            'status' => true,
                        ]);
                    }

                    return redirect()->route('payment.success')->with([
                        'user_id' => $invoice->user_id,
                        'invoice' => $invoice->id,
                    ]);
                }
            }

            return redirect()->route('payment.failed')->with([
                'user_id' => auth()->id(),
                'invoice' => $invoice->id ?? null
            ]);
        } catch (\Exception $e) {
            return redirect()->route('payment.failed')->with([
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
        }
    }

    public function success(Request $request)
    {
        $user = User::find($request->session()->get('user_id'));
        $invoice = Invoice::find($request->session()->get('invoice'));

        if (!$user || !$invoice) {
            return redirect()->route('payment.failed');
        }
        return Inertia::render('Patient/PaymentSuccessPage', [
            'user' => $user,
            'invoice' => $invoice,
        ]);
    }
    // C:\laragon\www\privateH-master\resources\js\Pages\Patient\PaymentSuccess.vue
    public function failed(Request $request)
    {
        $user = User::find($request->session()->get('user_id'));
        $invoice = Invoice::find($request->session()->get('invoice'));

        return Inertia::render('Patient/PaymentFailed', [
            'user' => $user,
            'invoice' => $invoice,
            'error' => $request->session()->get('error')
        ]);
    }
}