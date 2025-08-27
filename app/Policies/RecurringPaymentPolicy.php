<?php

namespace App\Policies;

use App\Models\RecurringPayment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecurringPaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any recurring payments.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the recurring payment.
     */
    public function view(User $user, RecurringPayment $recurringPayment): bool
    {
        return $user->id === $recurringPayment->user_id;
    }

    /**
     * Determine whether the user can create recurring payments.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the recurring payment.
     */
    public function update(User $user, RecurringPayment $recurringPayment): bool
    {
        // L'utilisateur peut modifier seulement ses propres paiements récurrents
        return $user->id === $recurringPayment->user_id;
    }

    /**
     * Determine whether the user can delete the recurring payment.
     */
    public function delete(User $user, RecurringPayment $recurringPayment): bool
    {
        return $user->id === $recurringPayment->user_id;
    }

    /**
     * Determine whether the user can pause/resume the recurring payment.
     */
    public function toggleStatus(User $user, RecurringPayment $recurringPayment): bool
    {
        return $user->id === $recurringPayment->user_id;
    }

    /**
     * Determine whether the user can restore the recurring payment.
     */
    public function restore(User $user, RecurringPayment $recurringPayment): bool
    {
        return $user->id === $recurringPayment->user_id;
    }

    /**
     * Determine whether the user can permanently delete the recurring payment.
     */
    public function forceDelete(User $user, RecurringPayment $recurringPayment): bool
    {
        return $user->id === $recurringPayment->user_id;
    }
}