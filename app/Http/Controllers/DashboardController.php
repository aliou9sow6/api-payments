<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * @OA\Get(
     *     path="/dashboard",
     *     summary="Get dashboard data",
     *     tags={"Dashboard"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dashboard data retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="totalPaid", type="number", example=1500.50),
     *             @OA\Property(property="totalPending", type="integer", example=3),
     *             @OA\Property(property="recentPayments", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        return response()->json([
            'success' => true,
            'totalPaid' => $user->payments()->where('status', 'paid')->sum('amount'),
            'totalPending' => $user->payments()->where('status', 'pending')->count(),
            'recentPayments' => $user->payments()->latest()->take(5)->get()
        ]);
    }
}