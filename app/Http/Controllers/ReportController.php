<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Role;
use App\Models\User;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        $roleId = null;

        if ($user && $user->roles && $user->roles->isNotEmpty()) {
            $roleId = $user->roles->first()->id;
        }

        // If roleId is still null here, it means the user has no roles.
        // We'll redirect back with an error message.
        if ($roleId === null) {
            return redirect()->back()->with('error', 'You do not have an assigned role to view reports.');
        }

        $reports = Report::with(['generator', 'role'])
            ->where('role_id', $roleId)
            ->latest()
            ->paginate(10);

        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $this->authorize('view', $report);
            return view('reports.show', compact('report'));
    }

    public function create()
    {
        $reportTypes = $this->getReportTypes();
        return view('reports.create', compact('reportTypes'));
    }

    public function store(Request $request)
    {
        $reportTypes = $this->getReportTypes();
        $allowedReportTypes = array_keys($reportTypes);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|string|in:' . implode(',', $allowedReportTypes),
            'date_range' => 'required|array',
            'date_range.start' => 'required|date',
            'date_range.end' => 'required|date|after_or_equal:date_range.start',
        ]);

        $data = $this->generateReportData($validated['type'], $validated['date_range']);
        
        $report = Report::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'type' => $validated['type'],
            'data' => $data,
            'generated_by' => auth()->id(),
            'role_id' => auth()->user()->roles->first()->id,
            'status' => 'completed',
            'generated_at' => now(),
        ]);

        return redirect()->route('doctor.reports.show', $report)
            ->with('success', 'Report generated successfully.');
    }

    public function edit(Report $report)
    {
        if (!Auth::user()->isDoctor() || $report->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        return view('reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        if (!Auth::user()->isDoctor() || $report->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        $request->validate([
            'report_type' => 'required|string|max:255',
            'description' => 'required|string',
            'report_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'report_date' => 'required|date'
        ]);

        if ($request->hasFile('report_file')) {
            // Delete old file
            Storage::disk('public')->delete($report->file_path);
            
            // Store new file
            $file = $request->file('report_file');
            $path = $file->store('reports', 'public');
            
            $report->file_path = $path;
        }

        $report->update([
            'report_type' => $request->report_type,
            'description' => $request->description,
            'report_date' => $request->report_date
        ]);

        return redirect()->route('reports.show', $report)
            ->with('success', 'Report updated successfully');
    }

    public function destroy(Report $report)
    {
        if (!Auth::user()->isDoctor() || $report->doctor_id !== Auth::user()->doctor->id) {
            abort(403);
        }

        // Delete file from storage
        Storage::disk('public')->delete($report->file_path);
        
        $report->delete();
        return redirect()->route('reports.index')
            ->with('success', 'Report deleted successfully');
    }

    public function download(Report $report)
    {
        $this->authorize('view', $report);
        
        $pdf = \PDF::loadView('reports.pdf', compact('report'));
        return $pdf->download("report-{$report->id}.pdf");
    }

    protected function getReportTypes(): array
    {
        $types = [
            'user_stats' => 'User Statistics',
            'appointment_stats' => 'Appointment Statistics',
            'medical_stats' => 'Medical Records Statistics',
        ];

        if (auth()->user()->isAdmin()) {
            $types['role_stats'] = 'Role Statistics';
            $types['system_stats'] = 'System Statistics';
        }

        if (auth()->user()->isDoctor()) {
            $types['patient_stats'] = 'Patient Statistics';
            $types['treatment_stats'] = 'Treatment Statistics';
        }

        return $types;
    }

    protected function generateReportData(string $type, array $dateRange): array
    {
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        return match($type) {
            'user_stats' => $this->generateUserStats($startDate, $endDate),
            'appointment_stats' => $this->generateAppointmentStats($startDate, $endDate),
            'medical_stats' => $this->generateMedicalStats($startDate, $endDate),
            'role_stats' => $this->generateRoleStats(),
            'system_stats' => $this->generateSystemStats(),
            'patient_stats' => $this->generatePatientStats($startDate, $endDate),
            'treatment_stats' => $this->generateTreatmentStats($startDate, $endDate),
            default => throw new \InvalidArgumentException('Invalid report type'),
        };
    }

    protected function generateUserStats(string $startDate, string $endDate): array
    {
        return [
            'total_users' => User::count(),
            'new_users' => User::whereBetween('users.created_at', [$startDate, $endDate])->count(),
            'active_users' => User::whereHas('appointments', function($query) use ($startDate, $endDate) {
                $query->whereBetween('appointments.created_at', [$startDate, $endDate]);
            })->count(),
            'users_by_role' => Role::withCount(['users' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('users.created_at', [$startDate, $endDate]);
            }])->get()->pluck('users_count', 'name'),
        ];
    }

    protected function generateAppointmentStats(string $startDate, string $endDate): array
    {
        return [
            'total_appointments' => Appointment::whereBetween('created_at', [$startDate, $endDate])->count(),
            'completed_appointments' => Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')->count(),
            'cancelled_appointments' => Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'cancelled')->count(),
            'appointments_by_doctor' => Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->select('doctor_id', DB::raw('count(*) as total'))
                ->groupBy('doctor_id')
                ->with('doctor:id,name')
                ->get()
                ->pluck('total', 'doctor.name'),
        ];
    }

    protected function generateMedicalStats(string $startDate, string $endDate): array
    {
        return [
            'total_records' => MedicalRecord::whereBetween('created_at', [$startDate, $endDate])->count(),
            'records_by_type' => MedicalRecord::whereBetween('created_at', [$startDate, $endDate])
                ->select('record_type', DB::raw('count(*) as total'))
                ->groupBy('record_type')
                ->get()
                ->pluck('total', 'record_type'),
            'records_by_doctor' => MedicalRecord::whereBetween('created_at', [$startDate, $endDate])
                ->select('doctor_id', DB::raw('count(*) as total'))
                ->groupBy('doctor_id')
                ->with('doctor:id,name')
                ->get()
                ->pluck('total', 'doctor.name'),
        ];
    }

    protected function generateRoleStats(): array
    {
        return [
            'total_roles' => Role::count(),
            'users_by_role' => Role::withCount('users')->get()->pluck('users_count', 'name'),
            'active_users_by_role' => Role::withCount(['users' => function($query) {
                $query->whereHas('appointments', function($q) {
                    $q->where('created_at', '>=', now()->subDays(30));
                });
            }])->get()->pluck('users_count', 'name'),
        ];
    }

    protected function generateSystemStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_appointments' => Appointment::count(),
            'total_medical_records' => MedicalRecord::count(),
            'system_health' => [
                'disk_usage' => disk_free_space('/') / disk_total_space('/') * 100,
                'memory_usage' => memory_get_usage(true) / memory_get_peak_usage(true) * 100,
            ],
        ];
    }

    protected function generatePatientStats(string $startDate, string $endDate): array
    {
        return [
            'total_patients' => User::whereHas('roles', function($query) {
                $query->where('slug', 'patient');
            })->count(),
            'new_patients' => User::whereHas('roles', function($query) {
                $query->where('slug', 'patient');
            })->whereBetween('users.created_at', [$startDate, $endDate])->count(),
            'active_patients' => User::whereHas('roles', function($query) {
                $query->where('slug', 'patient');
            })->whereHas('appointments', function($query) use ($startDate, $endDate) {
                $query->whereBetween('appointments.created_at', [$startDate, $endDate]);
            })->count(),
        ];
    }

    protected function generateTreatmentStats(string $startDate, string $endDate): array
    {
        return [
            'total_treatments' => Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')->count(),
            'treatments_by_type' => Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->select('treatment_type', DB::raw('count(*) as total'))
                ->groupBy('treatment_type')
                ->get()
                ->pluck('total', 'treatment_type'),
            'success_rate' => Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->where('treatment_success', true)
                ->count() / Appointment::whereBetween('created_at', [$startDate, $endDate])
                ->where('status', 'completed')
                ->count() * 100,
        ];
    }
} 