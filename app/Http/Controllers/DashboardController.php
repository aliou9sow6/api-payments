<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    
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
