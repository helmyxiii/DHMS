<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'qualifications',
        'specialty',
        'license_number',
        'experience',
        'phone',
        'address'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function specialty(): BelongsTo
    {
        return $this->belongsTo(Specialty::class);
    }

    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class, 'doctor_specialty');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    public function healthTips(): HasMany
    {
        return $this->hasMany(HealthTip::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function patients()
    {
        // Returns unique users (patients) for this doctor via medical_records
        return $this->belongsToMany(
            \App\Models\User::class,
            'medical_records',
            'doctor_id',
            'patient_id'
        )->distinct();
    }
} 
 