<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_date',
        'type',
        'status',
        'reason',
        'notes'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function canBeEdited(): bool
    {
        // Only pending appointments can be edited
        return $this->status === 'pending';
    }

    public function canBeCancelled(): bool
    {
        // Only pending appointments can be cancelled
        return $this->status === 'pending';
    }

    public function isPast(): bool
    {
        return $this->appointment_date->isPast();
    }

    public function isFuture(): bool
    {
        return $this->appointment_date->isFuture();
    }

    public function isEmergency(): bool
    {
        return $this->type === 'emergency';
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'confirmed' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary'
        };
    }
} 