<x-app-layout>
    <x-slot name="header">
        Student Profile: {{ $student->user->name }}
    </x-slot>
    <x-slot name="actions">
        <a href="{{ auth()->user()->hasRole('Administrator') ? route('admin.students') : route('examiner.students') }}" class="btn btn-secondary shadow-sm"><i class="fa-solid fa-arrow-left me-2"></i> Back to Students</a>
    </x-slot>

    <div class="row">
        <!-- Student Info -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="rounded-circle bg-primary-acetel text-white d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px; font-size: 32px;">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <h5 class="fw-bold mb-1">{{ $student->user->name }}</h5>
                    <p class="text-muted mb-3">{{ $student->user->email }}</p>
                    <span class="badge bg-primary px-3 py-2">{{ $student->matric_number }}</span>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Academic Details</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Programme</span>
                            <span class="fw-bold">{{ $student->programme->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Department</span>
                            <span class="fw-bold">{{ $student->department->name }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted">Year of Admission</span>
                            <span class="fw-bold">{{ $student->year_of_admission }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Research & Schedule -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Research Information</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted fw-bold">Research Title</div>
                        <div class="col-md-8">{{ $student->research_title }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted fw-bold">Supervisor</div>
                        <div class="col-md-8">{{ $student->supervisor_name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 text-muted fw-bold">Current Stage</div>
                        <div class="col-md-8"><span class="badge bg-secondary">{{ $student->current_research_stage }}</span></div>
                    </div>
                    
                    @php $durationInfo = $student->duration_info; @endphp
                    @if($durationInfo)
                    <div class="row mb-3 align-items-center">
                        <div class="col-md-4 text-muted fw-bold">Duration Status</div>
                        <div class="col-md-8">
                            @if($durationInfo['status'] === 'Eligible to Graduate')
                                <span class="badge bg-success">Eligible to Graduate</span>
                            @elseif($durationInfo['status'] === 'Overstayed')
                                <span class="badge bg-danger">Overstayed</span>
                            @else
                                <span class="badge bg-primary">In Progress</span>
                            @endif
                            <small class="text-muted d-block mt-1">
                                Semesters Spent: <strong>{{ $durationInfo['semesters_spent'] }}</strong> / Min Required: <strong>{{ $durationInfo['min_required'] }}</strong>
                            </small>
                        </div>
                    </div>
                    @endif
                    @if($student->presentation && $student->presentation->presentation_title)
                    <div class="row mt-4 border-top pt-3">
                        <div class="col-12">
                            <h6 class="text-primary fw-bold mb-2"><i class="fa-solid fa-align-left me-2"></i>Abstract</h6>
                            <div class="p-3 bg-light border rounded text-muted" style="font-size: 0.95rem; line-height: 1.6; font-style: italic;">
                                {{ $student->presentation->presentation_title }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Presentation & Schedule</h6>
                    @if($student->presentation && $student->presentation->file_path)
                        <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#pptActionModal">
                            <i class="fa-solid fa-file-pdf me-1"></i> Presentation Options
                        </button>
                    @else
                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Not Uploaded</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($student->schedule)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="text-muted text-xs text-uppercase fw-bold mb-1">Presentation Date</div>
                                <h5>{{ $student->schedule->presentation_date->format('d M, Y') }}</h5>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="text-muted text-xs text-uppercase fw-bold mb-1">Time</div>
                                <h5>{{ $student->schedule->start_time->format('h:i A') }} - {{ $student->schedule->end_time->format('h:i A') }}</h5>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="text-muted text-xs text-uppercase fw-bold mb-1">Venue / Zoom Link</div>
                                @php
                                    $venueText = e($student->schedule->venue);
                                    $venueHtml = preg_replace('/(https?:\/\/[^\s\)]+)/', '<a href="$1" target="_blank" class="text-primary text-decoration-underline">$1</a>', $venueText);
                                @endphp
                                <h5>{!! $venueHtml !!}</h5>
                            </div>
                            <div class="col-md-12">
                                <div class="text-muted text-xs text-uppercase fw-bold mb-1">Status</div>
                                @if($student->schedule->status == 'scheduled')
                                    <span class="badge bg-primary">Scheduled</span>
                                @elseif($student->schedule->status == 'presented')
                                    <span class="badge bg-success">Presented</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($student->schedule->status) }}</span>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            This student has not been assigned a presentation schedule yet.
                        </div>
                    @endif
                </div>
            </div>

            <!-- Examiner Reviews -->
            <div class="card border-0 shadow-sm border-start border-4 border-info">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-info">Examiner Reviews ({{ $student->reviews->count() }})</h6>
                </div>
                <div class="card-body">
                    @if($student->reviews->count() > 0)
                        @foreach($student->reviews as $review)
                        <div class="mb-4 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">{{ $review->examiner->name }}</h6>
                                <span class="badge {{ $review->total_score >= 50 ? 'bg-success' : 'bg-danger' }}">Total: {{ $review->total_score }}/100</span>
                            </div>
                            
                            <div class="row g-2 mb-2 text-sm text-muted">
                                <div class="col-6 col-md-3"><strong>Presentation:</strong> {{ $review->presentation_score }}/25</div>
                                <div class="col-6 col-md-3"><strong>Content:</strong> {{ $review->research_content_score }}/25</div>
                                <div class="col-6 col-md-3"><strong>Methodology:</strong> {{ $review->methodology_score }}/25</div>
                                <div class="col-6 col-md-3"><strong>Q&A:</strong> {{ $review->qa_score }}/25</div>
                            </div>
                            
                            @if($review->remarks)
                                <div class="bg-light p-3 rounded mt-2 text-sm">
                                    <em>"{{ $review->remarks }}"</em>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fa-solid fa-clipboard-check fa-3x mb-3 text-gray-300"></i>
                            <p class="mb-0">No reviews have been submitted for this presentation yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Examiner Comments Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-info"><i class="fa-solid fa-comments me-2"></i> Examiner Comments</h6>
                </div>
                <div class="card-body">
                    @if(auth()->user()->hasRole('Examiner'))
                        <div class="mb-4 text-center">
                            <button class="btn btn-info btn-lg text-white shadow-sm px-5 py-3 fw-bold" data-bs-toggle="modal" data-bs-target="#commentModal" style="border-radius: 50px; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px;">
                                <i class="fa-solid fa-comment-medical me-2 fa-lg"></i> Add Comments
                            </button>
                        </div>
                    @endif

                    @if($student->comments && $student->comments->count() > 0)
                        <div class="timeline mt-4">
                            @foreach($student->comments as $comment)
                            <div class="border-start border-3 border-info ps-3 mb-4">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold mb-1">{{ $comment->user->name }} (Examiner)</h6>
                                    <small class="text-muted">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                <p class="mb-0 text-muted">{{ $comment->body }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 bg-light rounded-3">
                            <i class="fa-solid fa-comment-slash fa-3x text-muted mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">No examiner comments yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->hasRole('Examiner'))
    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-labelledby="commentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('examiner.comments.store', $student->id) }}" method="POST">
                    @csrf
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="commentModalLabel"><i class="fa-solid fa-comment-dots me-2"></i> Add Comment</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Comment (Visible to Student and Admin)</label>
                            <textarea class="form-control" name="body" rows="4" required placeholder="Type your observations, questions, or feedback here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info text-white">Save Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- PPT Action Modal -->
    @if($student->presentation && $student->presentation->file_path)
    <div class="modal fade" id="pptActionModal" tabindex="-1" aria-labelledby="pptActionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="pptActionModalLabel"><i class="fa-solid fa-file-pdf me-2"></i> Presentation File Options</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="mb-4 text-muted">What would you like to do with this presentation file?</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <button class="btn btn-primary px-4 py-2" data-bs-target="#pptPreviewModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                            <i class="fa-solid fa-eye me-2"></i> View Presentation
                        </button>
                        <a href="{{ route('presentations.download', $student->presentation->id) }}" class="btn btn-outline-success px-4 py-2">
                            <i class="fa-solid fa-download me-2"></i> Download Presentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- PPT Preview Modal -->
    @if($student->presentation && $student->presentation->file_path)
    <div class="modal fade" id="pptPreviewModal" tabindex="-1" aria-labelledby="pptPreviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="height: 90vh;">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="pptPreviewModalLabel"><i class="fa-solid fa-file-pdf me-2 text-danger"></i> Document Preview</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <iframe src="{{ route('presentations.view', $student->presentation->id) }}" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
                <div class="modal-footer bg-light">
                    <button class="btn btn-secondary" data-bs-target="#pptActionModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Options
                    </button>
                    <a href="{{ route('presentations.download', $student->presentation->id) }}" class="btn btn-success">
                        <i class="fa-solid fa-download me-1"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
