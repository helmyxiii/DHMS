<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthTip;

class HealthTipSeeder extends Seeder
{
    public function run(): void
    {
        // Tips for Sleep
        HealthTip::create([
            'title' => 'Get Consistent Sleep',
            'content' => 'Go to bed and wake up at the same time every day to regulate your body clock.',
            'category' => 'Tips for Sleep',
            'is_published' => true
        ]);
        HealthTip::create([
            'title' => 'Limit Screen Time Before Bed',
            'content' => 'Avoid screens at least 30 minutes before bedtime to improve sleep quality.',
            'category' => 'Tips for Sleep',
            'is_published' => true
        ]);
        // Tips for Nutrition
        HealthTip::create([
            'title' => 'Eat More Vegetables',
            'content' => 'Include a variety of colorful vegetables in your meals for better nutrition.',
            'category' => 'Tips for Nutrition',
            'is_published' => true
        ]);
        HealthTip::create([
            'title' => 'Stay Hydrated',
            'content' => 'Drink at least 8 glasses of water a day to keep your body hydrated.',
            'category' => 'Tips for Nutrition',
            'is_published' => true
        ]);
        // Tips for Heart Patients
        HealthTip::create([
            'title' => 'Monitor Your Blood Pressure',
            'content' => 'Check your blood pressure regularly and keep a log to share with your doctor.',
            'category' => 'Tips for Heart Patients',
            'is_published' => true
        ]);
        HealthTip::create([
            'title' => 'Limit Salt Intake',
            'content' => 'Reduce salt in your diet to help control blood pressure and reduce heart strain.',
            'category' => 'Tips for Heart Patients',
            'is_published' => true
        ]);
        // Tips for Sugar (Diabetes) Patients
        HealthTip::create([
            'title' => 'Monitor Blood Sugar Levels',
            'content' => 'Check your blood sugar as advised and keep a record for your healthcare provider.',
            'category' => 'Tips for Sugar Patients',
            'is_published' => true
        ]);
        HealthTip::create([
            'title' => 'Eat Balanced Meals',
            'content' => 'Include whole grains, lean proteins, and plenty of vegetables in your diet.',
            'category' => 'Tips for Sugar Patients',
            'is_published' => true
        ]);
        // Routine Care (RTC)
        HealthTip::create([
            'title' => 'Regular Exercise',
            'content' => 'Engage in at least 30 minutes of moderate exercise most days of the week.',
            'category' => 'Routine Care',
            'is_published' => true
        ]);
        HealthTip::create([
            'title' => 'Annual Health Checkup',
            'content' => 'Visit your doctor for a full health checkup every year, even if you feel healthy.',
            'category' => 'Routine Care',
            'is_published' => true
        ]);
    }
} 