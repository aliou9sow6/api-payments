<?php

namespace App\Http\Controllers;

use App\Models\Payments;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Payments",
 *     type="object",
 *     title="Payments",
 *     description="Payments schema",
 *     @OA\Property(
 *         property="id",
 *         type="integer",
 *         description="ID du paiement"
 *     ),
 *     @OA\Property(
 *         property="amount",
 *         type="number",
 *         format="float",
 *         description="Montant du paiement"
 *     ),
 *     @OA\Property(
 *         property="date",
 *         type="string",
 *         format="date-time",
 *         description="Date du paiement"
 *     )
 * )
 */
class PaymentsController extends Controller
{
    use AuthorizesRequests;

    /**
     * @OA\Get(
     *     path="/payments",
     *     summary="Get paginated list of payments",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payments retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Payments")),
     *             @OA\Property(property="meta", type="object", @OA\Property(property="current_page", type="integer", example=1))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        return response()->json([
            'success' => true, // Indicates the request was successful
            'message' => 'Payments retrieved successfully',
            'data' => auth()->user()->payments()->paginate(10) // Paginate the payments for the authenticated user
        ]);
    }

    /**
     * @OA\Post(
     *     path="/payments",
     *     summary="Create a new payment",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"amount","status"},
     *                 @OA\Property(property="amount", type="number", format="float", example=100.50),
     *                 @OA\Property(property="description", type="string", example="Payment for services"),
     *                 @OA\Property(property="currency", type="string", enum={"USD","EUR","XOF"}),
     *                 @OA\Property(property="payment_method", type="string", enum={"credit_card","paypal","bank_transfer","crypto","Diokopay"}),
     *                 @OA\Property(property="status", type="string", enum={"pending","completed","failed"}),
     *                 @OA\Property(property="proof", type="string", format="binary", description="Payment proof file (jpg, jpeg, png, pdf)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Payment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment created successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->authorize('create', Payments::class);
        
        $data = $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255',
            'currency' => 'required|string|size:3',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer,crypto,Diokopay',
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

    /**
     * @OA\Get(
     *     path="/payments/{id}",
     *     summary="Get a specific payment",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function show(Payments $payment)
    {
        $this->authorize('view', $payment);
        
        return response()->json([
            'success' => true,
            'data' => $payment
        ]);
    }

    /**
     * @OA\Put(
     *     path="/payments/{id}",
     *     summary="Update a payment",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="amount", type="number", format="float", example=150.75),
     *                 @OA\Property(property="description", type="string", example="Updated payment description"),
     *                 @OA\Property(property="status", type="string", enum={"pending","completed","failed"}),
     *                 @OA\Property(property="proof", type="string", format="binary", description="Payment proof file (jpg, jpeg, png, pdf)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment updated successfully"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function update(Request $request, Payments $payment)
    {
        $this->authorize('update', $payment);

        $data = $request->validate([
            'amount' => 'numeric|min:0',
            'description' => 'string|max:255',
            'currency' => 'string|size:3',
            'payment_method' => 'in:credit_card,paypal,bank_transfer,crypto,Diokopay',
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

    /**
     * @OA\Delete(
     *     path="/payments/{id}",
     *     summary="Delete a payment",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Payment ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payment deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function destroy(Payments $payment)
    {
        $this->authorize('delete', $payment);

        $payment->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully'
        ]);
    }
    /**
     * Filter payments by past payments
     * @OA\Get(
     *     path="/payments/filter/past",
     *     summary="Filter payments by past payments",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date to filter payments",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="YYYY-MM-DD")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payments retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Payments retrieved successfully"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Payments"))
     *         ) 
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No payments found"
     *     )
     * )
     */
    public function filterByPastPayments(Request $request)
    {
        $this->authorize('viewAny', Payments::class);

        $date = $request->input('date');
        $payments = auth()->user()->payments()->where('created_at', '<', $date)->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
    /**
     * Filter payments by date range
     * @OA\Get(
     *     path="/payments/filter/date-range",
     *     summary="Filter payments by date range",
     *     tags={"Payments"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Start date to filter payments",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="YYYY-MM-DD")
     *     ),   
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="End date to filter payments",
     *         required=true,
     *         @OA\Schema(type="string", format="date", example="YYYY-MM-DD")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payments retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Payments"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No payments found"
     *     )
     * )
     */
    public function filterByDateRange(Request $request)
    {
        $this->authorize('viewAny', Payments::class);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $payments = auth()->user()->payments()->whereBetween('created_at', [$startDate, $endDate])->get();

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }
}