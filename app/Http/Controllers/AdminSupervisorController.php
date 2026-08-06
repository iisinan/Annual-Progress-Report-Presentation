<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSupervisorController extends Controller
{
    /**
     * List all supervisor accounts with their students.
     */
    public function index()
    {
        $supervisors = User::role('Supervisor')
            ->withCount('supervisees')
            ->orderByDesc('supervisees_count')
            ->get();

        // Detect potential duplicates: supervisors sharing a similar name (case-insensitive)
        $potentialDuplicates = [];
        $grouped = $supervisors->groupBy(function ($s) {
            return strtolower(trim(preg_replace('/\s+/', ' ', $s->name)));
        })->filter(fn($g) => $g->count() > 1);

        foreach ($grouped as $name => $group) {
            $potentialDuplicates[] = $group;
        }

        return view('admin.supervisors.index', compact('supervisors', 'potentialDuplicates'));
    }

    /**
     * Show one supervisor and all their assigned students.
     */
    public function show(User $supervisor)
    {
        $supervisor->load('supervisees.student.department', 'supervisees.student.programme', 'supervisees.student.presentation');
        return view('admin.supervisors.show', compact('supervisor'));
    }

    /**
     * Merge a duplicate supervisor account into a primary one.
     * All students of the duplicate are moved to the primary, then the duplicate is deleted.
     */
    public function merge(Request $request)
    {
        $request->validate([
            'primary_id'   => 'required|exists:users,id',
            'duplicate_id' => 'required|exists:users,id|different:primary_id',
        ]);

        $primary   = User::findOrFail($request->primary_id);
        $duplicate = User::findOrFail($request->duplicate_id);

        // Safety: both must be supervisors
        if (!$primary->hasRole('Supervisor') || !$duplicate->hasRole('Supervisor')) {
            return back()->with('error', 'Both accounts must be Supervisors to merge.');
        }

        DB::transaction(function () use ($primary, $duplicate) {
            // Get all student IDs linked to the duplicate
            $duplicateStudents = DB::table('student_supervisor')
                ->where('user_id', $duplicate->id)
                ->get();

            foreach ($duplicateStudents as $pivot) {
                // Check if primary is already linked to this student
                $alreadyLinked = DB::table('student_supervisor')
                    ->where('user_id', $primary->id)
                    ->where('student_id', $pivot->student_id)
                    ->exists();

                if ($alreadyLinked) {
                    // If duplicate's status is 'approved', upgrade primary's status too
                    if ($pivot->status === 'approved') {
                        DB::table('student_supervisor')
                            ->where('user_id', $primary->id)
                            ->where('student_id', $pivot->student_id)
                            ->update(['status' => 'approved']);
                    }
                } else {
                    // Re-assign the student to primary supervisor
                    DB::table('student_supervisor')
                        ->where('user_id', $duplicate->id)
                        ->where('student_id', $pivot->student_id)
                        ->update(['user_id' => $primary->id]);
                }
            }

            // Remove any remaining pivot rows for duplicate (already handled above, but clean up)
            DB::table('student_supervisor')->where('user_id', $duplicate->id)->delete();

            // Delete the duplicate user
            $duplicate->forceDelete();
        });

        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => "Merged Supervisor Account: '{$duplicate->email}' into '{$primary->email}'",
            'model_type' => 'User',
            'model_id'   => $primary->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.supervisors.index')
            ->with('success', "Account for '{$duplicate->name}' ({$duplicate->email}) has been merged into '{$primary->name}' successfully.");
    }
}
