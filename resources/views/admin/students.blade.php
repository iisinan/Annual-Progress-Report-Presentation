<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registered Students') }}
        </h2>
    </x-slot>

    <style>
        .admin-dash {
            min-height: 100vh;
            background-color: #0f172a; /* Slate 900 */
            color: #f8fafc;
            padding: 2rem;
            font-family: 'Inter', sans-serif;
            margin: -1.5rem;
        }
        
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #22c55e, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .filter-form {
            display: flex;
            align-items: center;
            background: rgba(30, 41, 59, 0.5);
            padding: 0.5rem 1rem;
            border-radius: 999px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .filter-form select {
            background: transparent;
            border: none;
            color: #f8fafc;
            margin-left: 0.5rem;
            outline: none;
            cursor: pointer;
        }
        .filter-form select option {
            background: #1e293b;
            color: #f8fafc;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, #10b981, #059669);
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
        }
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: white;
        }

        .btn-secondary-custom {
            background: rgba(51, 65, 85, 0.8);
            color: white;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }
        .btn-secondary-custom:hover {
            background: rgba(71, 85, 105, 1);
            transform: translateY(-2px);
            color: white;
        }

        .glass-panel {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        /* Modern Table */
        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }
        .modern-table th {
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 0 1rem 0.5rem 1rem;
            text-align: left;
            border: none;
        }
        .modern-table td {
            background: rgba(15, 23, 42, 0.4);
            padding: 1rem;
            vertical-align: middle;
            border-top: 1px solid rgba(255,255,255,0.02);
            border-bottom: 1px solid rgba(255,255,255,0.02);
        }
        .modern-table tr td:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
            border-left: 1px solid rgba(255,255,255,0.02);
        }
        .modern-table tr td:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
            border-right: 1px solid rgba(255,255,255,0.02);
        }
        .modern-table tr:hover td {
            background: rgba(15, 23, 42, 0.8);
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        .status-approved {
            background: rgba(16, 185, 129, 0.1);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        .status-pending {
            background: rgba(245, 158, 11, 0.1);
            color: #fbbf24;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        /* General Badge */
        .general-badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 500;
            margin-right: 0.25rem;
            margin-bottom: 0.25rem;
        }
        .bg-blue { background: rgba(59, 130, 246, 0.1); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.2); }
        .bg-gray { background: rgba(148, 163, 184, 0.1); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.2); }

        .action-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #e2e8f0;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
        }
        .action-btn:hover {
            background: rgba(255,255,255,0.1);
            color: white;
        }
        .action-btn.delete:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border-color: rgba(239, 68, 68, 0.3);
        }

        /* DataTables overrides for dark mode */
        .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate {
            color: #cbd5e1 !important;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 6px;
            padding: 0.3rem 0.75rem;
            margin-left: 0.5rem;
        }
        .dataTables_wrapper .dataTables_length select {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            border-radius: 6px;
            padding: 0.3rem 1.5rem 0.3rem 0.75rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            color: #cbd5e1 !important;
            border-radius: 6px;
            margin: 0 0.15rem;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: rgba(16, 185, 129, 0.2) !important;
            border-color: rgba(16, 185, 129, 0.4) !important;
            color: #34d399 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: rgba(255,255,255,0.1) !important;
            color: white !important;
        }
    </style>

    <div class="admin-dash">
        <div class="top-bar">
            <h1 class="page-title">Student Directory</h1>
            
            <div class="d-flex gap-3 align-items-center flex-wrap">
                <form action="{{ route('admin.students') }}" method="GET" class="filter-form">
                    <span class="text-sm text-slate-400">Session:</span>
                    <select name="session" id="sessionFilter" onchange="this.form.submit()">
                        <option value="all" {{ $session === 'all' ? 'selected' : '' }}>All Sessions</option>
                        @foreach($sessions as $s)
                            @if($s)
                                <option value="{{ $s }}" {{ $session === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endif
                        @endforeach
                        @if(!in_array($currentSession, $sessions->toArray()))
                            <option value="{{ $currentSession }}" {{ $session === $currentSession ? 'selected' : '' }}>{{ $currentSession }}</option>
                        @endif
                    </select>
                </form>

                <button type="button" class="btn-secondary-custom" data-bs-toggle="modal" data-bs-target="#massEmailModal">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Mass Email
                </button>
                <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Student
                </button>
            </div>
        </div>

        <div class="glass-panel">
            <div class="table-responsive">
                <table class="modern-table" id="studentsTable" width="100%">
                    <thead>
                        <tr>
                            <th>Student Info</th>
                            <th>Programme / Dept</th>
                            <th>PPT Upload</th>
                            <th>Supervisor Approval</th>
                            <th>Schedule</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <div style="font-weight:600; color:#f8fafc;">{{ $student->user->name ?? 'Unknown (Deleted User)' }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $student->matric_number }}</div>
                                <div style="font-size:0.75rem; color:#64748b;">{{ $student->user->email ?? 'No email' }}</div>
                            </td>
                            <td>
                                <div style="font-weight:500; color:#e2e8f0; font-size:0.85rem;">{{ $student->programme->name }}</div>
                                <div style="font-size:0.75rem; color:#94a3b8;">{{ $student->department->name }}</div>
                            </td>
                            <td>
                                @if($student->presentation && $student->presentation->file_path)
                                    <span class="general-badge bg-blue">✓ Uploaded</span>
                                @else
                                    <span class="general-badge bg-gray">Not Uploaded</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $approvalStatus = $student->supervisor_approval_status;
                                @endphp
                                @if($approvalStatus === 'approved')
                                    <span class="status-badge status-approved">Approved</span>
                                @elseif($approvalStatus === 'rejected')
                                    <span class="status-badge status-rejected">Rejected</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($student->schedule)
                                    <span class="general-badge bg-blue">Scheduled</span>
                                @else
                                    <span class="general-badge bg-gray">Not Scheduled</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:0.5rem;">
                                    <a href="{{ route('admin.students.show', $student->id) }}" class="action-btn" title="View Profile">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </a>
                                    @if(auth()->user()->hasRole('Administrator'))
                                    <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this student and all their records?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete Student">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:3rem; color:#64748b;">No students found in this session.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Keep Existing Modals but apply base classes to ensure they still render fine -->
    <style>
        /* Slightly adapt bootstrap modals for dark mode if they open here */
        .modal-content { background-color: #1e293b; color: #f8fafc; border: 1px solid rgba(255,255,255,0.1); }
        .modal-header { border-bottom: 1px solid rgba(255,255,255,0.1); }
        .modal-footer { border-top: 1px solid rgba(255,255,255,0.1); }
        .form-control, .form-select { background-color: #0f172a; color: #f8fafc; border: 1px solid rgba(255,255,255,0.1); }
        .form-control:focus, .form-select:focus { background-color: #0f172a; color: white; border-color: #34d399; box-shadow: none; }
        .form-label { color: #cbd5e1; }
        .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
    </style>

    <!-- Add Student Modal (Kept structure exactly the same for backend logic) -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.students.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-semibold" id="addStudentModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="matric_number" class="form-label">Matric Number</label>
                                <input type="text" class="form-control" id="matric_number" name="matric_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="academic_session" class="form-label">Academic Session</label>
                                <select class="form-select" id="academic_session" name="academic_session" required>
                                    <option value="">Select Session</option>
                                    @php $currentYear = date('Y'); @endphp
                                    @for($year = $currentYear; $year >= 2020; $year--)
                                        <option value="{{ $year }}/{{ $year+1 }}">{{ $year }}/{{ $year+1 }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="year_of_admission" class="form-label">Year of Admission</label>
                                <select class="form-select" id="year_of_admission" name="year_of_admission" required>
                                    <option value="">Select Year</option>
                                    @for($year = $currentYear; $year >= 2010; $year--)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="intake" class="form-label">Intake Batch</label>
                                <select class="form-select" id="intake" name="intake" required>
                                    <option value="">Select Intake</option>
                                    <option value="1">Intake 1 (First Semester)</option>
                                    <option value="2">Intake 2 (Second Semester)</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="department_id" class="form-label">Department</label>
                                <select class="form-select" id="department_id" name="department_id" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="programme_id" class="form-label">Programme</label>
                                <select class="form-select" id="programme_id" name="programme_id" required>
                                    <option value="">Select Programme</option>
                                    @foreach($programmes as $prog)
                                        <option value="{{ $prog->id }}">{{ $prog->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="research_title" class="form-label">Research Title</label>
                            <textarea class="form-control" id="research_title" name="research_title" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="presentation_title" class="form-label">Abstract</label>
                            <textarea class="form-control" id="presentation_title" name="presentation_title" rows="2" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="current_research_stage" class="form-label">Current Research Stage</label>
                            <select class="form-select" id="current_research_stage" name="current_research_stage" required>
                                <option value="">Select Stage</option>
                                <option value="Proposal">Proposal</option>
                                <option value="Data Collection">Data Collection</option>
                                <option value="Data Analysis">Data Analysis</option>
                                <option value="Writing">Writing</option>
                                <option value="Final Review">Final Review</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom" style="border:none;">Create Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Mass Email Modal -->
    <div class="modal fade" id="massEmailModal" tabindex="-1" aria-labelledby="massEmailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.students.email.send') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title font-semibold" id="massEmailModalLabel">Send Mass Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-sm mb-3" style="color:#94a3b8;">This email will be sent to all registered students.</p>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-custom" style="border:none;">
                            Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                "pageLength": 25,
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search students...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ students",
                    "infoEmpty": "Showing 0 to 0 of 0 students",
                    "infoFiltered": "(filtered from _MAX_ total students)"
                },
                "drawCallback": function() {
                    $('.dataTables_paginate > .pagination').addClass('pagination-sm');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
