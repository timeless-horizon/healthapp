<?php

namespace App\Http\Controllers;

use App\Models\BillingCycle;
use App\Models\Invoice;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Models\Plans;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\Request;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Invoice $invoice_id, User $user_id)
    {
        return inertia::render('Patient/PaystackPayment', [
            'invoice' =>$invoice_id,
            'user'=>$user_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Plans $plan_id, User $user_id)
    {
        $data = $request->validate([
            'updatedPlan.id' => 'required|exists:plans,id',
            'updatedPlan.billing_cycle' => 'required|array|min:1',
            'updatedPlan.billing_cycle.*.id' => 'required|exists:billing_cycles,id',
            'updatedPlan.billing_cycle.*.duration' => 'required|integer|min:1',
            'updatedPlan.billing_cycle.*.price' => 'required|numeric|min:0',
        ]);

        $billingCycle = $data['updatedPlan']['billing_cycle'][0];

        $dbBillingCycle = BillingCycle::where('id', $billingCycle['id'])
            ->where('plans_id', $data['updatedPlan']['id'])
            ->first();

        if (!$dbBillingCycle) {
            return response()->json(['error' => 'Invalid billing cycle'], 422);
        }

        // Validate price per month
        $calculatedPrice = $billingCycle['price'] / $billingCycle['duration'];
        $dbCalculatedPrice = $dbBillingCycle->price;

        if (round($calculatedPrice, 2) != round($dbCalculatedPrice, 2)) {
            return response()->json(['error' => 'Invalid price calculation'], 422);
        }

        // Generate Unique Invoice Number
        do {
            $invoiceNumber = mt_rand(100000, 999999);
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

        $invoiceDate = now();
        $dueDate = Carbon::now()->addMonths($billingCycle['duration']);

        // Create the Invoice
        $invoice = Invoice::create([
            'user_id' => Auth::user()->id,
            'plan_id' => $plan_id->id,
            'invoice_number' => $invoiceNumber,
            'generated_at' => $invoiceDate,
            'duration' => $billingCycle['duration'],
            'due_date' => $dueDate,
            'amount' => $billingCycle['price'],
            'status' => 'unpaid',
        ]);

        return response()->json([
            'message' => 'Invoice Generated successfully',
            'data' => $data['updatedPlan'],
            'url' => '/patient-choose-plan/'.$invoice->id.'/'.$user_id->id,
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInvoiceRequest $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        //
    }
}
