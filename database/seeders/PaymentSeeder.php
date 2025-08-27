<?php

namespace Database\Seeders;

use App\Models\Payments;
use App\Models\User;

use Hash;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        // deux utilisateurs tests de roles différents pour tester  
        $user = User::factory()->create([
            'name' => 'Gabriel AKAKE',
            'email' => 'testeur.admin@dioko.sn',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        $user = User::factory()->create([
            'name' => 'Aliou SOW',
            'email' => 'testeur.user@dioko.sn',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        $methods = ['credit_card', 'paypal', 'bank_transfer', 'diokopay'];
        $statuses = ['completed', 'pending', 'failed'];
        $currencies = ['XOF', 'USD', 'EUR'];

        foreach (range(1, 10) as $i) {
            Payments::create([
                'user_id' => $user->id,
                'amount' => rand(5000, 30000),
                'currency' => $currencies[array_rand($currencies)],
                'payment_method' => $methods[array_rand($methods)],
                'status' => $statuses[array_rand($statuses)],
                'description' => "Paiement test n°$i",
                'proof' => 'proofs/facture_' . $i . '.pdf',
                'created_at' => now()->subDays(rand(0, 30)),
            ]);
        }
    }
}
