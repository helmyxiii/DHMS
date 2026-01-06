<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Store a new treatment for a medical record
     */
    public function store(Request $request, MedicalRecord $record)
    {
        // Only doctors can add treatments to records
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'treatment_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        // Create and associate treatment
        $record->treatments()->create($request->all());

        return back()->with('success', 'Treatment added successfully');
    }

    /**
     * Update an existing treatment for a medical record
     */
    public function update(Request $request, MedicalRecord $record, Treatment $treatment)
    {
        // Only doctors associated with this record can update treatments
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'treatment_name' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        // Update treatment
        $treatment->update($request->all());

        return back()->with('success', 'Treatment updated successfully');
    }

    /**
     * Delete a treatment from a medical record
     */
    public function destroy(MedicalRecord $record, Treatment $treatment)
    {
        // Only doctors associated with the record can delete treatments
        if (!Auth::user()->isDoctor() || $record->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Delete treatment
        $treatment->delete();

        return back()->with('success', 'Treatment deleted successfully');
    }
}
