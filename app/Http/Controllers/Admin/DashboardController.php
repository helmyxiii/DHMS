<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\MedicalRecord;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
} 