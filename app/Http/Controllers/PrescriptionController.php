<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new prescription for a medical record
     */
    public function store(Request $request, MedicalRecord $record)
    {
        // Only doctors can prescribe
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'duration' => 'required|string',
            'instructions' => 'required|string',
            'prescribed_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:prescribed_date'
        ]);

        // Create and associate prescription
        $record->prescriptions()->create($request->all());

        return back()->with('success', 'Prescription added successfully');
    }

    /**
     * Update an existing prescription for a medical record
     */
    public function update(Request $request, MedicalRecord $record, Prescription $prescription)
    {
        // Only doctors associated with this record can update prescriptions
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage' => 'required|string',
            'frequency' => 'required|string',
            'duration' => 'required|string',
            'instructions' => 'required|string',
            'prescribed_date' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:prescribed_date'
        ]);

        // Update prescription
        $prescription->update($request->all());

        return back()->with('success', 'Prescription updated successfully');
    }

    /**
     * Delete a prescription from a medical record
     */
    public function destroy(MedicalRecord $record, Prescription $prescription)
    {
        // Only doctors associated with the record can delete prescriptions
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Delete prescription
        $prescription->delete();

        return back()->with('success', 'Prescription deleted successfully');
    }
}
