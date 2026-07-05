<x-app-layout>
    <x-slot name="header">
        Presentation Schedule
    </x-slot>

    <x-slot name="actions">
        <div class="d-flex gap-2">
            @role('Administrator')
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#generateScheduleModal">
                <i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate Schedule
            </button>
            @endrole
            <a href="{{ route('admin.reports.schedule.pdf') }}" class="btn btn-primary shadow-sm">
                <i class="fa-solid fa-download me-2"></i> Export PDF
            </a>
            @role('Administrator')
            <form method="POST" action="{{ route('admin.schedule.clear') }}" onsubmit="return confirm('WARNING: This will delete ALL generated schedules. Are you sure you want to clear the entire schedule?');">
                @csrf
                <button type="submit" class="btn btn-danger shadow-sm">
                    <i class="fa-solid fa-trash me-2"></i> Clear Schedule
                </button>
            </form>
            @endrole
        </div>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="scheduleTable" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Student Details</th>
                            <th>Programme</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->presentation_date->format('d M, Y') }}</td>
                            <td>{{ $schedule->start_time->format('h:i A') }} - {{ $schedule->end_time->format('h:i A') }}</td>
                            <td>
                                <strong>{{ $schedule->student->user->name }}</strong><br>
                                <small class="text-muted">{{ $schedule->student->matric_number }}</small>
                            </td>
                            <td>{{ $schedule->student->programme->name }}</td>
                            <td>
                                @php
                                    $venueText = e($schedule->venue);
                                    $venueHtml = preg_replace('/(https?:\/\/[^\s\)]+)/', '<a href="$1" target="_blank" class="text-primary text-decoration-underline">$1</a>', $venueText);
                                @endphp
                                {!! $venueHtml !!}
                            </td>
                            <td>
                                @if($schedule->status == 'scheduled')
                                    <span class="badge bg-primary">Scheduled</span>
                                @elseif($schedule->status == 'presented')
                                    <span class="badge bg-success">Presented</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($schedule->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editScheduleModal{{ $schedule->id }}">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    @if(auth()->user()->hasRole('Administrator'))
                                    <form action="{{ route('admin.schedule.destroy', $schedule->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this presentation schedule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancel Schedule">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>


                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @foreach($schedules as $schedule)
    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal{{ $schedule->id }}" tabindex="-1" aria-labelledby="editScheduleModalLabel{{ $schedule->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.schedule.update', $schedule->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editScheduleModalLabel{{ $schedule->id }}">Edit Schedule - {{ $schedule->student->user->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control" name="presentation_date" value="{{ $schedule->presentation_date->format('Y-m-d') }}" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="start_time" value="{{ $schedule->start_time->format('H:i') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-control" name="end_time" value="{{ $schedule->end_time->format('H:i') }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Venue / Zoom Link</label>
                            <input type="text" class="form-control" name="venue" value="{{ $schedule->venue }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="scheduled" {{ $schedule->status == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="presented" {{ $schedule->status == 'presented' ? 'selected' : '' }}>Presented</option>
                                <option value="missed" {{ $schedule->status == 'missed' ? 'selected' : '' }}>Missed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @role('Administrator')
    <!-- Generate Schedule Modal -->
    <div class="modal fade" id="generateScheduleModal" tabindex="-1" aria-labelledby="generateScheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.schedule.generate') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="generateScheduleModalLabel"><i class="fa-solid fa-wand-magic-sparkles me-2"></i> Generate Schedule</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <small>This will assign time slots to all students currently without a schedule. Weekends (Sat/Sun) will be automatically skipped.</small>
                        </div>
                        <div class="mb-3">
                            <label for="venue" class="form-label">Venue / Zoom Link</label>
                            <input type="text" class="form-control" id="venue" name="venue" value="Virtual (Zoom: [Enter Link Here])" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ \App\Models\SystemSetting::where('key', 'presentation_start_date')->value('value') ?? date('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">Daily Start Time</label>
                                <input type="time" class="form-control" id="start_time" name="start_time" value="09:30" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="students_per_day" class="form-label">Students Per Day</label>
                                <input type="number" class="form-control" id="students_per_day" name="students_per_day" value="5" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="duration" class="form-label">Duration (Minutes)</label>
                                <input type="number" class="form-control" id="duration" name="duration" value="30" min="10" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Generate Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endrole

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#scheduleTable').DataTable({
                "pageLength": 25,
                "ordering": false // Custom ordering is tricky with formatted dates, so disable default for simplicity or configure properly
            });
        });
    </script>
    @endpush
</x-app-layout>
