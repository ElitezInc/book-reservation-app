<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'author',
        'title',
        'code',
        'description',
        'release_date',
        'image_name',
    ];

    protected $casts = [
        'release_date' => 'datetime',
    ];

    public function pastReservations() {
        return $this->hasMany(ReservationHistory::class);
    }

    public function reservation()
    {
        return $this->hasOne(Reservation::class);
    }
}
