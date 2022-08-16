<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReservationHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'reservation_start',
        'expiration_date',
        'return_date',
        'book_id',
        'user_id'
    ];

    protected $casts = [
        'reservation_start' => 'datetime',
        'expiration_date' => 'datetime',
        'return_date' => 'datetime',
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
