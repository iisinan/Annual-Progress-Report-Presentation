<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Student Profile: {{ $student->user->name }}
        </h2>
    </x-slot>

    <style>
        .profile-dash {
            min-height: 100vh;
            background-color: #f4f7f5; /* Light subtle gray */
            color: #1f2937;
            padding: 2rem;
            font-family: 'Inter', sans-serif;
            margin: -1.5rem;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .btn-back {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
            font-size: 0.875rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .btn-back:hover {
            background: #f8fafc;
            transform: translateX(-3px);
            color: #1e293b;
        }

        .light-card {
            background: #ffffff;
            border: 1px solid rgba(26, 122, 50, 0.1);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .card-header-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px dashed rgba(0,0,0,0.05);
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            color: #64748b;
            font-size: 0.875rem;
        }
        .detail-value {
            color: #1f2937;
            font-weight: 500;
            text-align: right;
            font-size: 0.9rem;
        }

        .avatar-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #1a7a32, #145e27);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 auto 1rem auto;
            color: white;
            box-shadow: 0 4px 12px rgba(26, 122, 50, 0.2);
        }

        .badge-custom {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        .badge-green { background: rgba(16, 185, 129, 0.1); color: #059669; border: 1px solid rgba(16, 185, 129, 0.2); }
        .badge-red { background: rgba(239, 68, 68, 0.1); color: #dc2626; border: 1px solid rgba(239, 68, 68, 0.2); }
        .badge-orange { background: rgba(245, 158, 11, 0.1); color: #d97706; border: 1px solid rgba(245, 158, 11, 0.2); }
        .badge-blue { background: rgba(59, 130, 246, 0.1); color: #2563eb; border: 1px solid rgba(59, 130, 246, 0.2); }
        .badge-gray { background: rgba(148, 163, 184, 0.1); color: #475569; border: 1px solid rgba(148, 163, 184, 0.2); }

        .supervisor-card {
            background: #f8fafc;
            border: 1px solid rgba(0,0,0,0.05);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .supervisor-info h6 {
            margin: 0;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.95rem;
        }
        .supervisor-info small {
            color: #64748b;
            font-size: 0.75rem;
        }

        .btn-action {
            background: linear-gradient(135deg, #1a7a32, #145e27);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-decoration: none;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(26, 122, 50, 0.2);
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 122, 50, 0.3);
            color: white;
        }

        /* Timeline */
        .timeline-item {
            border-left: 2px solid rgba(26, 122, 50, 0.3);
            padding-left: 1.5rem;
            margin-bottom: 1.5rem;
            position: relative;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #1a7a32;
            box-shadow: 0 0 0 4px rgba(26, 122, 50, 0.1);
        }
    </style>

    <div class="profile-dash">
        <div class="top-bar">
            <a href="{{ auth()->user()->hasRole('Administrator') ? route('admin.students') : route('examiner.students') }}" class="btn-back">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Students
            </a>
            
            @if($student->presentation && $student->presentation->file_path)
            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#pptActionModal">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Presentation Options
            </button>
            @endif
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="light-card text-center">
                    <div class="avatar-circle">
                        {{ strtoupper(substr($student->user->name, 0, 1)) }}
                    </div>
                    <h4 class="fw-bold mb-1" style="font-size:1.25rem;">{{ $student->user->name }}</h4>
                    <p style="color:#64748b; font-size:0.875rem; margin-bottom:1rem;">{{ $student->user->email }}</p>
                    <span class="badge-custom badge-blue">{{ $student->matric_number }}</span>
                </div>

                <div class="light-card">
                    <div class="card-header-title">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#1a7a32;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Academic Details
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Programme</span>
                        <span class="detail-value">{{ $student->programme->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Department</span>
                        <span class="detail-value">{{ $student->department->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Admission Year</span>
                        <span class="detail-value">{{ $student->year_of_admission }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Intake</span>
                        <span class="detail-value">Batch {{ $student->intake }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <!-- Highlighted Supervisors Card -->
                <div class="light-card" style="border: 2px solid rgba(26, 122, 50, 0.2);">
                    <div class="card-header-title" style="color: #1a7a32;">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Supervisors & Approvals
                    </div>
                    
                    @if($student->supervisors->count() > 0)
                        <div class="row">
                            @foreach($student->supervisors as $supervisor)
                            <div class="col-md-6">
                                <div class="supervisor-card">
                                    <div class="supervisor-info">
                                        <h6>{{ $supervisor->name }}</h6>
                                        <small>Assigned Supervisor</small>
                                    </div>
                                    <div>
                                        @if($supervisor->pivot->status == 'approved')
                                            <span class="badge-custom badge-green">Approved</span>
                                        @elseif($supervisor->pivot->status == 'rejected')
                                            <span class="badge-custom badge-red" title="{{ $supervisor->pivot->comments }}">Rejected</span>
                                        @else
                                            <span class="badge-custom badge-orange">Pending</span>
                                        @endif
                                    </div>
                                </div>
                                @if($supervisor->pivot->comments)
                                    <p class="text-sm mt-1 mb-3" style="color: #475569; font-size:0.8rem; margin-left: 1rem;">
                                        <em>"{{ $supervisor->pivot->comments }}"</em>
                                    </p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <span class="badge-custom badge-gray text-muted">No Supervisors Assigned</span>
                        </div>
                    @endif
                </div>

                <div class="light-card">
                    <div class="card-header-title">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#1a7a32;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Research Information
                    </div>
                    
                    <div class="mb-4">
                        <div class="detail-label mb-1">Research Title</div>
                        <div class="detail-value text-start" style="font-size:1.05rem;">{{ $student->research_title }}</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="detail-row">
                                <span class="detail-label">Current Stage</span>
                                <span class="badge-custom badge-blue">{{ $student->current_research_stage }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @php $durationInfo = $student->duration_info; @endphp
                            @if($durationInfo)
                            <div class="detail-row">
                                <span class="detail-label">Duration Status</span>
                                @if($durationInfo['status'] === 'Eligible to Graduate')
                                    <span class="badge-custom badge-green">Eligible to Graduate</span>
                                @elseif($durationInfo['status'] === 'Overstayed')
                                    <span class="badge-custom badge-red">Overstayed</span>
                                @else
                                    <span class="badge-custom badge-blue">In Progress</span>
                                @endif
                            </div>
                            <div class="text-end text-sm" style="color: #64748b; font-size:0.75rem; margin-top:0.25rem;">
                                Semesters: <strong>{{ $durationInfo['semesters_spent'] }}</strong> / <strong>{{ $durationInfo['min_required'] }}</strong> required
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Examiner Reviews -->
                <div class="light-card">
                    <div class="card-header-title">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#1a7a32;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        Examiner Reviews
                    </div>
                    
                    @if($student->reviews && $student->reviews->count() > 0)
                        @foreach($student->reviews as $review)
                        <div class="mb-3 p-3 rounded" style="background: #f8fafc; border: 1px solid rgba(0,0,0,0.05);">
                            <div class="d-flex justify-content-between mb-2">
                                <h6 class="fw-bold mb-0 text-dark">{{ $review->examiner->name }}</h6>
                                <span class="badge-custom badge-blue">Total Score: {{ $review->total_score }}/100</span>
                            </div>
                            
                            <div class="row g-2 mb-2 text-sm text-muted">
                                <div class="col-6 col-md-3"><strong>Presentation:</strong> {{ $review->presentation_score }}/25</div>
                                <div class="col-6 col-md-3"><strong>Content:</strong> {{ $review->research_content_score }}/25</div>
                                <div class="col-6 col-md-3"><strong>Methodology:</strong> {{ $review->methodology_score }}/25</div>
                                <div class="col-6 col-md-3"><strong>Q&A:</strong> {{ $review->qa_score }}/25</div>
                            </div>
                            
                            @if($review->remarks)
                                <div class="mt-2 text-sm p-2 rounded" style="background: #ffffff; border: 1px solid rgba(0,0,0,0.02); color:#475569;">
                                    <em>"{{ $review->remarks }}"</em>
                                </div>
                            @endif
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <span class="badge-custom badge-gray text-muted">No reviews submitted yet.</span>
                        </div>
                    @endif
                </div>

                <!-- Examiner Comments -->
                <div class="light-card">
                    <div class="card-header-title">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:#1a7a32;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        Examiner Comments
                    </div>
                    
                    @if(auth()->user()->hasRole('Examiner'))
                        <div class="mb-4 text-center">
                            <button class="btn-action" data-bs-toggle="modal" data-bs-target="#commentModal">
                                Add Comments
                            </button>
                        </div>
                    @endif

                    @if($student->comments && $student->comments->count() > 0)
                        <div class="mt-4">
                            @foreach($student->comments as $comment)
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between">
                                    <h6 class="fw-bold mb-1 text-dark">{{ $comment->user->name }} (Examiner)</h6>
                                    <small style="color: #64748b;">{{ $comment->created_at->format('M d, Y h:i A') }}</small>
                                </div>
                                <p class="mb-0" style="color: #475569;">{{ $comment->body }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <span class="badge-custom badge-gray text-muted">No examiner comments yet.</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <style>
        .modal-content { background-color: #ffffff; color: #1f2937; border: none; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); }
        .modal-header { border-bottom: 1px solid rgba(0,0,0,0.05); background: #f8fafc; }
        .modal-footer { border-top: 1px solid rgba(0,0,0,0.05); background: #f8fafc; }
        .form-control, .form-select { background-color: #ffffff; color: #1f2937; border: 1px solid #cbd5e1; border-radius: 8px; }
        .form-control:focus, .form-select:focus { border-color: #1a7a32; box-shadow: 0 0 0 3px rgba(26, 122, 50, 0.1); }
        .form-label { color: #475569; font-weight: 500; }
        .btn-close { filter: none; }
    </style>

    @if(auth()->user()->hasRole('Examiner'))
    <!-- Comment Modal -->
    <div class="modal fade" id="commentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('examiner.comments.store', $student->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-semibold text-dark">Add Comment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Comment (Visible to Student and Admin)</label>
                            <textarea class="form-control" name="body" rows="4" required placeholder="Type your observations, questions, or feedback here..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="background: #ffffff; color: #475569; border: 1px solid #cbd5e1;">Cancel</button>
                        <button type="submit" class="btn-action" style="padding: 0.5rem 1rem;">Save Comment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- PPT Action Modal -->
    @if($student->presentation && $student->presentation->file_path)
    <div class="modal fade" id="pptActionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-semibold text-dark">Presentation Options</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="mb-4 text-muted" style="color: #64748b !important;">What would you like to do with this presentation file?</p>
                    <div class="d-grid gap-3 d-sm-flex justify-content-sm-center">
                        <button class="btn-action" data-bs-target="#pptPreviewModal" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: #ffffff; color: #1a7a32; border: 1px solid rgba(26, 122, 50, 0.2);">
                            View Presentation
                        </button>
                        <a href="{{ route('presentations.download', $student->presentation->id) }}" class="btn-action">
                            Download Presentation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PPT Preview Modal -->
    <div class="modal fade" id="pptPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content" style="height: 90vh;">
                <div class="modal-header">
                    <h5 class="modal-title font-semibold text-dark">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="background: #f8fafc;">
                    <iframe src="{{ route('presentations.view', $student->presentation->id) }}" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-target="#pptActionModal" data-bs-toggle="modal" data-bs-dismiss="modal" style="background: #ffffff; color: #475569; border: 1px solid #cbd5e1;">
                        Back to Options
                    </button>
                    <a href="{{ route('presentations.download', $student->presentation->id) }}" class="btn-action" style="padding: 0.5rem 1rem;">
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</x-app-layout>
