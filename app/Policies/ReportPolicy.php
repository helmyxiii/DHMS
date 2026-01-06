<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Adjust as needed, e.g., only admins or specific roles can view all reports
        return $user->hasRole('admin') || $user->hasRole('doctor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Report $report): bool
    {
        // Allow if the user generated the report
        if ($user->id === $report->generated_by) {
            return true;
        }
        // Allow if the report's role_id matches the user's first assigned role's ID
        if ($user->roles()->exists() && $report->role_id === $user->roles->first()->id) {
            return true;
        }
        // Add additional checks if an admin should always be able to view
        // if ($user->hasRole('admin')) { // Assuming hasRole method exists on User model
        //     return true;
        // }
        return false; // Default to deny
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Example: Only doctors can create reports
        return $user->hasRole('doctor'); // Assuming hasRole method exists on User model
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        // Example: Only the user who generated the report or an admin can update
        return $user->id === $report->generated_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Report $report): bool
    {
        // Example: Only the user who generated the report or an admin can delete
        return $user->id === $report->generated_by || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Report $report): bool
    {
        // Adjust as needed
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Report $report): bool
    {
        // Adjust as needed
        return $user->hasRole('admin');
    }
}
