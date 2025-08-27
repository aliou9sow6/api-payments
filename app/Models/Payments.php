<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payments extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'description',
        'status',
        'proof'
    ]; 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getProofUrlAttribute(): string
    {
        return $this->proof ? asset('storage/' . $this->proof) : null;
    }


}
