<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Prescription extends Model
{
    protected $fillable = [
        'medical_record_id',
        'medicine_name',
        'dosage',
        'frequency',
        'duration',
        'instructions',
        'prescribed_date',
        'valid_until'
    ];

    protected $casts = [
        'prescribed_date' => 'date',
        'valid_until' => 'date',
    ];

    public function medicalRecord(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }
} 