<?php

namespace App\Http\Controllers;

use App\Models\RecurringPayment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="RecurringPayment",
 *     type="object",
 *     title="RecurringPayment",
 *     description="Recurring payment schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID of the recurring payment"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="ID of the user"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         description="Amount of the recurring payment"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         enum={"active","inactive"},
 *         description="Status of the recurring payment"
 *     ),
 *     @OA\Property(
 *         property="start_date",
 *         type="string",
 *         format="date",
 *         description="Start date of the recurring payment"
 *     ),
 *     @OA\Property(
 *         property="end_date",
 *         type="string",
 *         format="date",
 *         description="End date of the recurring payment"
 *     ),
 *     @OA\Property(
 *         property="frequency",
 *         type="string",
 *         enum={"daily","weekly","monthly"},
 *         description="Frequency of the recurring payment"
 *     ),
 *     @OA\Property(
 *         property="payment_method",
 *         type="string",
 *         enum={"credit_card","debit_card","paypal"},
 *         description="Payment method for the recurring payment"
 *     )
 * )
 */
class RecurringPaymentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Handle recurring payment creation
     * @OA\Post(
     *     path="/recurring-payments",
     *     summary="Create a new recurring payment",
     *     tags={"Recurring Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/RecurringPayment")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Recurring payment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/RecurringPayment")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('create', RecurringPayment::class);
        
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'frequency' => 'required|in:daily,weekly,monthly',
            'payment_method' => 'required|in:credit_card,debit_card,paypal,crypto,Diokopay',
        ]);

        // Associer automatiquement à l'utilisateur connecté
        $data['user_id'] = auth()->id();
        
        $recurringPayment = RecurringPayment::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Recurring payment created successfully',
            'data' => $recurringPayment
        ], 201);
    }

    // Handle fetching user's recurring payments
    /**
     * @OA\Get(
     *     path="/recurring-payments",
     *     summary="Get user's recurring payments",
     *     tags={"Recurring Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Recurring payments retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/RecurringPayment"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', RecurringPayment::class);
        
        $recurringPayments = $request->user()->recurringPayments()->get();

        return response()->json([
            'success' => true,
            'data' => $recurringPayments
        ]);
    }

    /**
     * @OA\Get(
     *     path="/recurring-payments/{id}",
     *     summary="Get a specific recurring payment",
     *     tags={"Recurring Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Recurring Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurring payment retrieved successfully"
     *     )
     * )
     */
    public function show(RecurringPayment $recurringPayment)
    {
        $this->authorize('view', $recurringPayment);
        
        return response()->json([
            'success' => true,
            'data' => $recurringPayment
        ]);
    }

    /**
     * @OA\Put(
     *     path="/recurring-payments/{id}",
     *     summary="Update a recurring payment",
     *     tags={"Recurring Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Recurring Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurring payment updated successfully"
     *     )
     * )
     */
    public function update(Request $request, RecurringPayment $recurringPayment)
    {
        $this->authorize('update', $recurringPayment);

        $data = $request->validate([
            'amount' => 'numeric|min:0',
            'status' => 'in:active,inactive,paused,cancelled',
            'end_date' => 'nullable|date|after:start_date',
            'frequency' => 'in:daily,weekly,monthly,quarterly,yearly',
            'payment_method' => 'in:credit_card,debit_card,paypal,crypto,Diokopay',
        ]);

        $recurringPayment->update($data);
        
        return response()->json([
            'success' => true,
            'message' => 'Recurring payment updated successfully',
            'data' => $recurringPayment
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/recurring-payments/{id}",
     *     summary="Delete a recurring payment",
     *     tags={"Recurring Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Recurring Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Recurring payment deleted successfully"
     *     )
     * )
     */
    public function destroy(RecurringPayment $recurringPayment)
    {
        $this->authorize('delete', $recurringPayment);

        $recurringPayment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Recurring payment deleted successfully'
        ]);
    }


}