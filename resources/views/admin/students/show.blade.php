<x-app-layout>
    <x-slot name="header">
        Student Profile: {{ $student->user->name }}
    </x-slot>
    <x-slot name="actions">
        <a href="{{ route('admin.students') }}" class="btn btn-secondary shadow-sm"><i class="fa-solid fa-arrow-left me-2"></i> Back to Students</a>
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
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">Presentation & Schedule</h6>
                    @if($student->presentation && $student->presentation->file_path)
                        <a href="{{ Storage::disk('r2')->url($student->presentation->file_path) }}" target="_blank" class="btn btn-sm btn-outline-success">
                            <i class="fa-solid fa-download me-1"></i> Download PPT
                        </a>
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
        </div>
    </div>
</x-app-layout>
