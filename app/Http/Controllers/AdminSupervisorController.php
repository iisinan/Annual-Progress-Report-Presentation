<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $potentialDuplicates[] = $group->values();
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
     * Merge ONE duplicate into a primary (existing route).
     */
    public function merge(Request $request)
    {
        $request->validate([
            'primary_id'   => 'required|exists:users,id',
            'duplicate_id' => 'required|exists:users,id|different:primary_id',
        ]);

        $primary   = User::findOrFail($request->primary_id);
        $duplicate = User::findOrFail($request->duplicate_id);

        if (!$primary->hasRole('Supervisor') || !$duplicate->hasRole('Supervisor')) {
            return back()->with('error', 'Both accounts must be Supervisors to merge.');
        }

        $mergedCount = 0;
        DB::transaction(function () use ($primary, $duplicate, &$mergedCount) {
            $this->mergeDuplicateIntoPrimary($primary, $duplicate);
            $mergedCount = 1;
        });

        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => "Merged Supervisor: '{$duplicate->email}' → '{$primary->email}'",
            'model_type' => 'User',
            'model_id'   => $primary->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.supervisors.index')
            ->with('success', "Account '{$duplicate->name}' ({$duplicate->email}) has been merged into '{$primary->name}' successfully.");
    }

    /**
     * Merge MULTIPLE duplicates into one primary at once.
     */
    public function mergeAll(Request $request)
    {
        $request->validate([
            'primary_id'     => 'required|exists:users,id',
            'duplicate_ids'  => 'required|array|min:1',
            'duplicate_ids.*'=> 'required|exists:users,id|different:primary_id',
        ]);

        $primary = User::findOrFail($request->primary_id);

        if (!$primary->hasRole('Supervisor')) {
            return back()->with('error', 'The primary account must be a Supervisor.');
        }

        $mergedEmails = [];

        DB::transaction(function () use ($primary, $request, &$mergedEmails) {
            foreach ($request->duplicate_ids as $dupId) {
                if ((int) $dupId === (int) $primary->id) continue;

                $duplicate = User::find($dupId);
                if (!$duplicate || !$duplicate->hasRole('Supervisor')) continue;

                $mergedEmails[] = $duplicate->email;
                $this->mergeDuplicateIntoPrimary($primary, $duplicate);
            }
        });

        if (empty($mergedEmails)) {
            return back()->with('error', 'No valid duplicate accounts were merged.');
        }

        AuditLog::create([
            'user_id'    => Auth::id(),
            'action'     => "Bulk Merged " . count($mergedEmails) . " Supervisor account(s) into '{$primary->email}': " . implode(', ', $mergedEmails),
            'model_type' => 'User',
            'model_id'   => $primary->id,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.supervisors.index')
            ->with('success', count($mergedEmails) . " duplicate account(s) have been merged into '{$primary->name}' successfully.");
    }

    /**
     * Core merge logic: move all students from $duplicate to $primary, then delete $duplicate.
     */
    private function mergeDuplicateIntoPrimary(User $primary, User $duplicate): void
    {
        $duplicateStudents = DB::table('student_supervisor')
            ->where('user_id', $duplicate->id)
            ->get();

        foreach ($duplicateStudents as $pivot) {
            $alreadyLinked = DB::table('student_supervisor')
                ->where('user_id', $primary->id)
                ->where('student_id', $pivot->student_id)
                ->exists();

            if ($alreadyLinked) {
                // Preserve best approval status
                if ($pivot->status === 'approved') {
                    DB::table('student_supervisor')
                        ->where('user_id', $primary->id)
                        ->where('student_id', $pivot->student_id)
                        ->update(['status' => 'approved']);
                }
            } else {
                // Move student to primary
                DB::table('student_supervisor')
                    ->where('user_id', $duplicate->id)
                    ->where('student_id', $pivot->student_id)
                    ->update(['user_id' => $primary->id]);
            }
        }

        // Clean up any remaining pivot rows then delete the duplicate
        DB::table('student_supervisor')->where('user_id', $duplicate->id)->delete();
        $duplicate->forceDelete();
    }
}
