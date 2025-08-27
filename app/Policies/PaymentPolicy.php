<?php

namespace App\Policies;

use App\Models\Payments;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any payments.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir leurs propres paiements
        return true;
    }

    /**
     * Determine whether the user can view the payment.
     */
    public function view(User $user, Payments $payment): bool
    {
        // L'utilisateur peut voir seulement ses propres paiements
        return $user->id === $payment->user_id;
    }

    /**
     * Determine whether the user can create payments.
     */
    public function create(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent créer des paiements
        // Vérifier que l'utilisateur a un rôle valide
        return $user && in_array($user->role ?? 'user', ['user', 'admin']);
    }

    /**
     * Determine whether the user can update the payment.
     */
    public function update(User $user, Payments $payment): bool
    {
        // L'utilisateur peut modifier seulement ses propres paiements
        // et seulement si le statut est 'pending'
        return $user->id === $payment->user_id && $payment->status === 'pending';
    }

    /**
     * Determine whether the user can delete the payment.
     */
    public function delete(User $user, Payments $payment): bool
    {
        // L'utilisateur peut supprimer seulement ses propres paiements
        // et seulement si le statut est 'pending' ou 'failed'
        return $user->id === $payment->user_id && 
               in_array($payment->status, ['pending', 'failed']);
    }

    /**
     * Determine whether the user can restore the payment.
     */
    public function restore(User $user, Payments $payment): bool
    {
        return $user->id === $payment->user_id;
    }

    /**
     * Determine whether the user can permanently delete the payment.
     */
    public function forceDelete(User $user, Payments $payment): bool
    {
        return $user->id === $payment->user_id;
    }
}