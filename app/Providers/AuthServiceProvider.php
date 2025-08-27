<?php

namespace App\Providers;

use App\Models\Payments;
use App\Models\RecurringPayment;
use App\Models\User;
use App\Policies\PaymentPolicy;
use App\Policies\RecurringPaymentPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Payments::class => PaymentPolicy::class,
        RecurringPayment::class => RecurringPaymentPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}