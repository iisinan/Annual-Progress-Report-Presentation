<x-app-layout>
    <x-slot name="header">Examiner Dashboard</x-slot>

    <!-- Welcome Banner -->
    <div class="page-title-bar mb-4">
        <div>
            <h1><i class="fa-solid fa-chalkboard-user me-2"></i> Examiner Dashboard</h1>
            <small style="color:rgba(255,255,255,0.75);">Welcome back, {{ Auth::user()->name }} &mdash; {{ now()->format('l, d F Y') }}</small>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon green"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Registered Students</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['registered_students'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon amber"><i class="fa-solid fa-file-pdf"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Uploaded PDFs</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['uploaded_presentations'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon red"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Pending Uploads</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['pending_uploads'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon blue"><i class="fa-solid fa-calendar-day"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Today's Presentations</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['todays_presentations'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Schedule Table -->
    <div class="card">
        <div class="card-header py-3" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
            <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-calendar-check me-2"></i>Today's Schedule</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Student Name</th>
                            <th>Programme</th>
                            <th>Research Title</th>
                            <th>Venue</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($todays_schedule as $slot)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}</td>
                            <td>{{ $slot->student->user->name }} <br><small class="text-muted">{{ $slot->student->matric_number }}</small></td>
                            <td>{{ $slot->student->programme->name }}</td>
                            <td>{{ $slot->student->research_title }}</td>
                            <td>{{ $slot->venue }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    @if($slot->student->presentation)
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#pptActionModal{{ $slot->id }}" title="Presentation Options">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </button>
                                    @else
                                        <span class="text-muted" title="Pending"><i class="fa-solid fa-triangle-exclamation text-warning"></i></span>
                                    @endif

                                    @php
                                        $review = $slot->student->reviews->first();
                                    @endphp

                                    @if($review)
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $slot->id }}">
                                            <i class="fa-solid fa-check"></i> {{ $review->total_score }}/100
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#gradeModal{{ $slot->id }}">
                                            <i class="fa-solid fa-clipboard-check"></i> Grade
                                        </button>
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

    <!-- Grading Modals -->
    @foreach($todays_schedule as $slot)
    @php
        $review = $slot->student->reviews->first();
    @endphp
    <div class="modal fade" id="gradeModal{{ $slot->id }}" tabindex="-1" aria-labelledby="gradeModalLabel{{ $slot->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('examiner.reviews.store', $slot->student->id) }}" method="POST">
                    @csrf
                    <div class="modal-header text-white" style="background:linear-gradient(135deg,var(--acetel-green-dark),var(--acetel-green));">
                        <h5 class="modal-title" id="gradeModalLabel{{ $slot->id }}">
                            <i class="fa-solid fa-graduation-cap me-2"></i>Grade: {{ $slot->student->user->name }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info py-2">
                            <strong>Research Title:</strong> {{ $slot->student->research_title }}
                        </div>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Presentation & Delivery (Max 25)</label>
                                <input type="number" class="form-control" name="presentation_score" value="{{ $review->presentation_score ?? 0 }}" min="0" max="25" required>
                                <small class="text-muted">Clarity, timing, visual aids.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Research Content (Max 25)</label>
                                <input type="number" class="form-control" name="research_content_score" value="{{ $review->research_content_score ?? 0 }}" min="0" max="25" required>
                                <small class="text-muted">Depth of literature, originality, gap identification.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Methodology (Max 25)</label>
                                <input type="number" class="form-control" name="methodology_score" value="{{ $review->methodology_score ?? 0 }}" min="0" max="25" required>
                                <small class="text-muted">Appropriateness of methods, data collection.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Q&A Handling (Max 25)</label>
                                <input type="number" class="form-control" name="qa_score" value="{{ $review->qa_score ?? 0 }}" min="0" max="25" required>
                                <small class="text-muted">Ability to defend and clarify research.</small>
                            </div>
                            <div class="col-12 mt-4">
                                <label class="form-label fw-bold">Examiner Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3" placeholder="Constructive feedback for the student...">{{ $review->remarks ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Grade</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($slot->student->presentation && $slot->student->presentation->file_path)
    <!-- PPT Action Modal for Slot {{ $slot->id }} -->
    <div class="modal fade" id="pptActionModal{{ $slot->id }}" tabindex="-1" aria-labelledby="pptActionModalLabel{{ $slot->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="pptActionModalLabel{{ $slot->id }}"><i class="fa-solid fa-file-pdf me-2"></i> Presentation File Options</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="mb-4 text-muted">What would you like to do with this presentation file?</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <a href="{{ Storage::disk('r2')->url($slot->student->presentation->file_path) }}" target="_blank" class="btn btn-primary px-4 py-2">
                            <i class="fa-solid fa-eye me-2"></i> View Presentation
                        </a>
                        <a href="{{ route('presentations.download', $slot->student->presentation->id) }}" class="btn btn-outline-success px-4 py-2">
                            <i class="fa-solid fa-download me-2"></i> Download Presentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    @endpush

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
                    <h6 class="mb-1 fw-bold">{{ $announcement->title }}</h6>
                    <small class="text-muted">{{ $announcement->created_at->diffForHumans() }}</small>
                </div>
                <p class="mb-1 mt-2">{{ $announcement->content }}</p>
                <small class="text-muted">Posted by {{ $announcement->creator->name }}</small>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</x-app-layout>
