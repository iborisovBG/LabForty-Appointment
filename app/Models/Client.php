<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'egn', 'email', 'phone'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function scopeFutureAppointments(Builder $query): Builder
    {
        return $query->whereHas('appointments', function ($q) {
            $q->where('appointment_datetime', '>', now())->orderBy('appointment_datetime');
        });
    }
}
