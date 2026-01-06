<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\AppointmentChangeRequest;
use App\Models\Announcement;

class AdminController extends Controller
{
    public function doctors()
    {
        $doctors = \App\Models\Doctor::with(['user', 'specialties'])->paginate(15);
        $specialties = \App\Models\Specialty::all();
        return view('admin.doctors', compact('doctors', 'specialties'));
    }

    public function patients()
    {
        $patients = Patient::with('user')->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    public function appointments()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->paginate(10);
        return view('admin.appointments.index', compact('appointments'));
    }

    public function specialties()
    {
        $specialties = Specialty::withCount('doctors')->paginate(10);
        return view('admin.specialties.index', compact('specialties'));
    }

    public function createSpecialty()
    {
        return view('admin.specialties.create');
    }

    public function storeSpecialty(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties',
            'description' => 'required|string'
        ]);

        Specialty::create($request->all());
        return redirect()->route('admin.specialties')->with('success', 'Specialty created successfully');
    }

    public function editSpecialty(Specialty $specialty)
    {
        return view('admin.specialties.edit', compact('specialty'));
    }

    public function updateSpecialty(Request $request, Specialty $specialty)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name,' . $specialty->id,
            'description' => 'required|string'
        ]);

        $specialty->update($request->all());
        return redirect()->route('admin.specialties')->with('success', 'Specialty updated successfully');
    }

    public function deleteSpecialty(Specialty $specialty)
    {
        $specialty->delete();
        return redirect()->route('admin.specialties')->with('success', 'Specialty deleted successfully');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function updateProfile(Request $request)
    {
        $admin = auth('admin')->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'current_password' => 'required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed'
        ]);

        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect']);
            }
            $admin->password = Hash::make($request->new_password);
        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->save();

        return back()->with('success', 'Profile updated successfully');
    }

    public function users()
    {
        $users = User::query()
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when(request('role'), function($query, $role) {
                $query->where('role', $role);
            })
            ->paginate(10)
            ->withQueryString();
        $specialties = Specialty::all();
        return view('admin.users', compact('users', 'specialties'));
    }

    public function createUser()
    {
        $specialties = Specialty::all();
        return view('admin.users.create', compact('specialties'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['doctor', 'patient'])],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'specialties' => 'required_if:role,doctor|array',
            'specialties.*' => 'exists:specialties,id',
        ]);

        DB::transaction(function() use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ]);

            if ($validated['role'] === 'doctor' && isset($validated['specialties'])) {
                $user->specialties()->attach($validated['specialties']);
            }
        });

        return redirect()->route('admin.users')
            ->with('success', 'User created successfully');
    }

    public function editUser(User $user)
    {
        $specialties = Specialty::all();
        $userSpecialties = $user->specialties->pluck('id')->toArray();
        return view('admin.users.edit', compact('user', 'specialties', 'userSpecialties'));
    }

    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['doctor', 'patient'])],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'specialties' => 'required_if:role,doctor|array',
            'specialties.*' => 'exists:specialties,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        DB::transaction(function() use ($validated, $user) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
            ];

            if (isset($validated['password'])) {
                $userData['password'] = Hash::make($validated['password']);
            }

            $user->update($userData);

            if ($validated['role'] === 'doctor') {
                $user->specialties()->sync($validated['specialties'] ?? []);
            } else {
                $user->specialties()->detach();
            }
        });

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account');
        }

        $user->delete();
        return redirect()->route('admin.users')
            ->with('success', 'User deleted successfully');
    }

    public function reports()
    {
        // Get appointment statistics
        $appointmentStats = [
            'total' => Appointment::count(),
            'completed' => Appointment::where('status', 'completed')->count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'cancelled' => Appointment::where('status', 'cancelled')->count(),
            'monthly' => Appointment::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get(),
        ];

        // Get user statistics
        $userStats = [
            'total' => User::count(),
            'doctors' => User::where('role', 'doctor')->count(),
            'patients' => User::where('role', 'patient')->count(),
            'monthly_registrations' => User::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get(),
        ];

        // Get specialty statistics
        $specialtyStats = Specialty::withCount('doctors')->get();

        return view('admin.reports', compact('appointmentStats', 'userStats', 'specialtyStats'));
    }

    public function appointmentReports()
    {
        $appointments = Appointment::with(['patient', 'doctor'])
            ->when(request('status'), function($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('date_from'), function($query, $date) {
                $query->whereDate('appointment_date', '>=', $date);
            })
            ->when(request('date_to'), function($query, $date) {
                $query->whereDate('appointment_date', '<=', $date);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.appointments', compact('appointments'));
    }

    public function userReports()
    {
        $users = User::withCount(['appointments', 'medicalRecords'])
            ->when(request('role'), function($query, $role) {
                $query->where('role', $role);
            })
            ->when(request('search'), function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.reports.users', compact('users'));
    }

    public function settings()
    {
        $settings = [
            'hospital_name' => config('app.name'),
            'contact_email' => config('mail.from.address'),
            'contact_phone' => config('app.contact_phone'),
            'address' => config('app.address'),
            'working_hours' => config('app.working_hours'),
        ];

        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'hospital_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string',
            'working_hours' => 'required|string',
            'logo' => 'nullable|image|max:2048',
        ]);

        // Update settings in the database or config files
        // This is a simplified example. In a real application, you might want to:
        // 1. Use a settings table in the database
        // 2. Use a settings package
        // 3. Update .env file
        // 4. Use cache for better performance

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logos');
            $validated['logo'] = str_replace('public/', '', $path);
        }

        // Update settings (implementation depends on your storage method)
        // For example, using a Settings model:
        // Settings::updateOrCreate(['key' => 'hospital_name'], ['value' => $validated['hospital_name']]);

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully');
    }

    public function changeRequests()
    {
        $requests = AppointmentChangeRequest::with(['appointment.doctor', 'patient'])->latest()->paginate(20);
        return view('admin.change-requests', compact('requests'));
    }

    public function processChangeRequest(Request $request, AppointmentChangeRequest $changeRequest)
    {
        $request->validate(['action' => 'required|in:approved,rejected']);

        $changeRequest->status = $request->action;
        $changeRequest->save();

        // If approved and type is cancel, update appointment status
        if ($changeRequest->type === 'cancel' && $request->action === 'approved') {
            $changeRequest->appointment->update(['status' => 'cancelled']);
        }

        return back()->with('success', 'Request processed.');
    }

    // Approve a doctor
    public function approveDoctor(Doctor $doctor)
    {
        $doctor->approved = true;
        $doctor->save();
        return back()->with('success', 'Doctor approved successfully.');
    }

    // Unapprove a doctor
    public function unapproveDoctor(Doctor $doctor)
    {
        $doctor->approved = false;
        $doctor->save();
        return back()->with('success', 'Doctor unapproved successfully.');
    }

    // Edit doctor form
    public function editDoctor(Doctor $doctor)
    {
        $specialties = Specialty::all();
        $user = $doctor->user;
        $doctorSpecialties = $doctor->specialties->pluck('id')->toArray();
        return view('admin.doctors.edit', compact('doctor', 'user', 'specialties', 'doctorSpecialties'));
    }

    // Update doctor
    public function updateDoctor(Request $request, Doctor $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($doctor->user->id)],
            'specialties' => 'required|array',
            'specialties.*' => 'exists:specialties,id',
        ]);
        $user = $doctor->user;
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->save();
        $doctor->specialties()->sync($validated['specialties']);
        return redirect()->route('admin.doctors')->with('success', 'Doctor updated successfully.');
    }

    // Delete doctor
    public function deleteDoctor(Doctor $doctor)
    {
        $user = $doctor->user;
        $doctor->delete();
        $user->delete();
        return back()->with('success', 'Doctor deleted successfully.');
    }

    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Announcement sent to all patients!');
    }
} 