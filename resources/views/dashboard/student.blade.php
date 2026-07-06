<x-app-layout>
    <x-slot name="header">Student Dashboard</x-slot>

    <!-- Welcome Banner -->
    <div class="page-title-bar mb-4">
        <div>
            <h1><i class="fa-solid fa-user-graduate me-2"></i> Student Dashboard</h1>
            <small style="color:rgba(255,255,255,0.75);">{{ $student->matric_number }} &mdash; {{ $student->programme->name }} &middot; {{ $student->department->name }}</small>
        </div>
        <div class="d-flex gap-2">
            @if($status['powerpoint'] === 'Pending')
                <a href="{{ route('student.upload') }}" class="btn btn-sm fw-bold" style="background:#f8b400;color:#145e27;border-radius:8px;"><i class="fa-solid fa-upload me-1"></i> Upload PDF</a>
            @else
                <a href="{{ route('student.slip') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);border-radius:8px;"><i class="fa-solid fa-download me-1"></i> Download Slip</a>
            @endif
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon green"><i class="fa-solid fa-check-circle"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Registration Status</div>
                        <div class="fw-bold text-success" style="font-size:1.3rem;">{{ $status['registration'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon {{ $status['powerpoint'] == 'Uploaded' ? 'green' : 'amber' }}"><i class="fa-solid fa-file-pdf"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">PDF Status</div>
                        <div class="fw-bold {{ $status['powerpoint'] == 'Uploaded' ? 'text-success' : 'text-warning' }}" style="font-size:1.3rem;">{{ $status['powerpoint'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon blue"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Presentation Date</div>
                        <div class="fw-bold" style="font-size:1.1rem;">{{ $status['presentation_date'] }}</div>
                        <div class="text-muted" style="font-size:0.8rem;">{{ $status['presentation_time'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon amber"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div style="min-width:0;">
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Venue</div>
                        @php
                            $venueText = e($status['venue']);
                            $venueHtml = preg_replace('/(https?:\/\/[^\s\)]+)/', '<a href="$1" target="_blank" class="text-decoration-underline" style="color:var(--acetel-amber);">$1</a>', $venueText);
                        @endphp
                        <div class="fw-bold" style="font-size:1rem;word-break:break-word;">{!! $venueHtml !!}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results -->
    @if(isset($results))
    <div class="card mb-4" style="border-left:4px solid var(--acetel-green);">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background:var(--acetel-green-pale);border-bottom:1px solid rgba(26,122,50,0.15);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-graduation-cap me-2"></i> Presentation Results</h6>
            <span class="badge px-3 py-2" style="background:var(--acetel-green);font-size:0.9rem;">Score: {{ $results['total_score'] }} / 100</span>
        </div>
        <div class="card-body">
            <div class="row text-center mb-4">
                <div class="col-md-3 mb-3">
                    <div class="p-3 rounded-3" style="background:var(--acetel-green-pale);border:1px solid rgba(26,122,50,0.15);">
                        <h3 class="fw-bold mb-1" style="color:var(--acetel-green);">{{ $results['presentation_score'] }}<small class="text-muted fs-6">/25</small></h3>
                        <div class="text-xs fw-bold text-muted text-uppercase">Presentation Quality</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 rounded-3" style="background:var(--acetel-green-pale);border:1px solid rgba(26,122,50,0.15);">
                        <h3 class="fw-bold mb-1" style="color:var(--acetel-green);">{{ $results['research_content_score'] }}<small class="text-muted fs-6">/25</small></h3>
                        <div class="text-xs fw-bold text-muted text-uppercase">Research Content</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 rounded-3" style="background:var(--acetel-green-pale);border:1px solid rgba(26,122,50,0.15);">
                        <h3 class="fw-bold mb-1" style="color:var(--acetel-green);">{{ $results['methodology_score'] }}<small class="text-muted fs-6">/25</small></h3>
                        <div class="text-xs fw-bold text-muted text-uppercase">Methodology</div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="p-3 rounded-3" style="background:var(--acetel-green-pale);border:1px solid rgba(26,122,50,0.15);">
                        <h3 class="fw-bold mb-1" style="color:var(--acetel-green);">{{ $results['qa_score'] }}<small class="text-muted fs-6">/25</small></h3>
                        <div class="text-xs fw-bold text-muted text-uppercase">Q &amp; A Handling</div>
                    </div>
                </div>
            </div>
            @if($results['remarks'])
            <div class="p-3 rounded-3" style="background:#f0fdf4;border-left:3px solid var(--acetel-green);">
                <h6 class="fw-bold mb-2" style="color:var(--acetel-green);"><i class="fa-solid fa-comments me-2"></i>Examiner Remarks:</h6>
                <p class="mb-0">{{ $results['remarks'] }}</p>
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Research Details -->
    <div class="card mb-4">
        <div class="card-header py-3" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-flask me-2"></i>Research Details</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3 py-2 border-bottom">
                <div class="col-md-3 text-muted fw-semibold" style="font-size:0.85rem;">Research Title</div>
                <div class="col-md-9 fw-bold">{{ $student->research_title }}</div>
            </div>
            <div class="row mb-3 py-2 border-bottom">
                <div class="col-md-3 text-muted fw-semibold" style="font-size:0.85rem;">Abstract</div>
                <div class="col-md-9">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div>{{ $presentation->presentation_title ?? 'N/A' }}</div>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAbstractModal" style="flex-shrink:0;">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </button>
                    </div>
                </div>
            </div>
            <div class="row mb-3 py-2 border-bottom">
                <div class="col-md-3 text-muted fw-semibold" style="font-size:0.85rem;">Supervisor</div>
                <div class="col-md-9">{{ $student->supervisor_name }}</div>
            </div>
            <div class="row py-2 border-bottom">
                <div class="col-md-3 text-muted fw-semibold" style="font-size:0.85rem;">Current Stage</div>
                <div class="col-md-9"><span class="badge px-3 py-2" style="background:var(--acetel-green);">{{ $student->current_research_stage }}</span></div>
            </div>
            @php $durationInfo = $student->duration_info; @endphp
            @if($durationInfo)
            <div class="row py-2 pt-3">
                <div class="col-md-3 text-muted fw-semibold" style="font-size:0.85rem;">Duration Status</div>
                <div class="col-md-9">
                    @if($durationInfo['status'] === 'Eligible to Graduate')
                        <span class="badge bg-success px-3 py-2">Eligible to Graduate</span>
                    @elseif($durationInfo['status'] === 'Overstayed')
                        <span class="badge bg-danger px-3 py-2">Overstayed</span>
                    @else
                        <span class="badge bg-primary px-3 py-2">In Progress</span>
                    @endif
                    <small class="text-muted ms-2 d-block d-sm-inline mt-2 mt-sm-0">
                        Semesters Spent: <strong>{{ $durationInfo['semesters_spent'] }}</strong> / Min Required: <strong>{{ $durationInfo['min_required'] }}</strong>
                    </small>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Examiner Comments Timeline -->
    <div class="card mb-4">
        <div class="card-header py-3" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-comments me-2"></i>Examiner Comments</h6>
        </div>
        <div class="card-body">
            @if($student->comments && $student->comments->count() > 0)
                <div class="timeline">
                    @foreach($student->comments as $comment)
                    <div class="border-start border-3 ps-3 mb-4" style="border-color: var(--acetel-green) !important;">
                        <div class="d-flex justify-content-between">
                            <h6 class="fw-bold mb-1">{{ $comment->user->name }} (Examiner)</h6>
                            <small class="text-muted">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                        </div>
                        <p class="mb-0 text-muted">{{ $comment->body }}</p>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0 text-center py-3">No examiner comments yet.</p>
            @endif
        </div>
    </div>

    <!-- Announcements -->
    @if(isset($announcements) && $announcements->count() > 0)
    <div class="card mt-4">
        <div class="card-header py-3" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-bullhorn me-2"></i> Important Announcements</h6>
        </div>
        <div class="list-group list-group-flush">
            @foreach($announcements as $announcement)
            <div class="list-group-item p-4">
                <div class="d-flex w-100 justify-content-between">
                    <h6 class="mb-1 fw-bold" style="color:var(--acetel-green);">{{ $announcement->title }}</h6>
                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1 mt-2">{{ $announcement->content }}</p>
                <small class="text-muted">Posted by <strong>{{ $announcement->creator->name }}</strong></small>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    </div>

    <!-- Edit Abstract Modal -->
    <div class="modal fade" id="editAbstractModal" tabindex="-1" aria-labelledby="editAbstractModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('student.abstract.update') }}" method="POST">
                    @csrf
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="editAbstractModalLabel"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Abstract</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="presentation_title" class="form-label fw-bold">Abstract</label>
                            <textarea class="form-control" id="presentation_title" name="presentation_title" rows="5" required>{{ $presentation->presentation_title ?? '' }}</textarea>
                            <small class="text-muted">Update your abstract details here.</small>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
