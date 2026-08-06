<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeDuplicateSupervisors extends Command
{
    protected $signature   = 'supervisors:merge-duplicates {--dry-run : Preview what would be merged without making any changes}';
    protected $description = 'Automatically merge supervisor accounts that share the same name into a single account.';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info('');
        $this->info('══════════════════════════════════════════════════════');
        $this->info('  Supervisor Duplicate Merger');
        if ($dryRun) {
            $this->warn('  [DRY-RUN MODE] No changes will be made.');
        }
        $this->info('══════════════════════════════════════════════════════');
        $this->info('');

        // Load all supervisors with their student counts
        $supervisors = User::role('Supervisor')
            ->withCount('supervisees')
            ->orderByDesc('supervisees_count')
            ->get();

        // Group by normalised name (lowercase, collapsed whitespace)
        $groups = $supervisors->groupBy(function ($s) {
            return strtolower(trim(preg_replace('/\s+/', ' ', $s->name)));
        })->filter(fn($g) => $g->count() > 1);

        if ($groups->isEmpty()) {
            $this->info('✅  No duplicate supervisor accounts found. Nothing to do.');
            return self::SUCCESS;
        }

        $this->info("Found <comment>{$groups->count()}</comment> group(s) of duplicates:\n");

        $totalMerged   = 0;
        $totalDeleted  = 0;

        foreach ($groups as $normName => $group) {
            // Sort so the one with the MOST students is first → becomes primary
            $sorted  = $group->sortByDesc('supervisees_count')->values();
            $primary = $sorted->first();
            $dups    = $sorted->slice(1)->values();

            $this->line("  <fg=yellow>Name:</> <fg=white>{$primary->name}</>");
            $this->line("  <fg=green>Primary (keep):</> [{$primary->id}] {$primary->email} ({$primary->supervisees_count} students)");

            foreach ($dups as $dup) {
                $this->line("  <fg=red>Duplicate (delete):</> [{$dup->id}] {$dup->email} ({$dup->supervisees_count} students)");
            }

            if (!$dryRun) {
                if (!$this->confirm("  Merge {$dups->count()} duplicate(s) into primary?", true)) {
                    $this->line("  <fg=yellow>Skipped.</>\n");
                    continue;
                }

                DB::transaction(function () use ($primary, $dups, &$totalDeleted) {
                    foreach ($dups as $dup) {
                        $this->mergeDuplicateIntoPrimary($primary, $dup);
                        $totalDeleted++;
                    }
                });

                $this->info("  ✅  Merged successfully.\n");
                $totalMerged += $dups->count();
            } else {
                $this->line("  <fg=cyan>[DRY-RUN] Would merge {$dups->count()} duplicate(s) into primary.</>\n");
            }
        }

        $this->info('══════════════════════════════════════════════════════');
        if ($dryRun) {
            $this->warn("DRY-RUN complete. Run without --dry-run to apply changes.");
        } else {
            $this->info("Done. {$totalMerged} duplicate account(s) merged and {$totalDeleted} account(s) deleted.");
        }
        $this->info('══════════════════════════════════════════════════════');

        return self::SUCCESS;
    }

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

        // Clean up remaining rows then hard-delete the duplicate
        DB::table('student_supervisor')->where('user_id', $duplicate->id)->delete();
        $duplicate->forceDelete();

        $this->line("    <fg=gray>→ Deleted [{$duplicate->id}] {$duplicate->email}</>");
    }
}
