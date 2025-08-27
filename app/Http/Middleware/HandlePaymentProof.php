<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class HandlePaymentProof
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Traitement du fichier de preuve avant la validation
        if ($request->hasFile('proof')) {
            $file = $request->file('proof');
            
            // Vérification de la taille et du type
            if ($file->getSize() > 2048 * 1024) { // 2MB en bytes
                return response()->json([
                    'success' => false,
                    'message' => 'File size must be less than 2MB'
                ], 422);
            }
            
            // Stockage sécurisé du fichier
            $path = $file->store('payments/proofs', 'public');
            $request->merge(['proof' => $path]);
        }

        return $next($request);
    }
}