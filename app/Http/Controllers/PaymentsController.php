<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        return response()->json([
            'success' => true, // Indicates the request was successful
            'data' => auth()->user()->payments()->latest()->paginate(10) // Paginate the payments for the authenticated user
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:pending,completed,failed',
            'proof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048' // Max size 2MB
        ]);

        $payment = auth()->user()->payments()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Payment created successfully',
            'data' => $payment
        ], 201);
    }
    public function show(Payments $payment)
    {
        $this->authorize('view', $payment);
        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }
    public function update(Request $request, Payments $payment)
    {
        $this->authorize('update', $payment);

        $data = $request->validate([
            'amount' => 'numeric|min:0',
            'description' => 'string|max:255',
            'status' => 'in:pending,completed,failed',
            'proof' => 'file|mimes:jpg,jpeg,png,pdf|max:2048' // Max size 2MB
        ]);

        $payment->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Payment updated successfully',
            'data' => $payment
        ]);
    }
    public function destroy(Payments $payment)
    {
        $this->authorize('delete', $payment);

        $payment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ]);
    }

}
