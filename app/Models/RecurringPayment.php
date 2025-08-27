<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Payments;
use APP\Models\User;

class RecurringPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'status',
        'start_date',
        'end_date',
        'frequency',
        'payment_method',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payments::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

}
