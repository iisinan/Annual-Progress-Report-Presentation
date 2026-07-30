<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Dashboard') }}
        </h2>
    </x-slot>

    <style>
        .sup-dash {
            min-height: 100vh;
            background-color: #f4f7f5; /* Light subtle gray */
            color: #1f2937; /* Dark slate text */
            padding: 2rem;
            font-family: 'Inter', sans-serif;
            margin: -1.5rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(26, 122, 50, 0.1); /* ACETEL Green light border */
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .icon-blue { background: rgba(59, 130, 246, 0.1); color: #2563eb; }
        .icon-green { background: rgba(26, 122, 50, 0.1); color: #1a7a32; }
        .icon-purple { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }
        .icon-amber { background: rgba(245, 158, 11, 0.1); color: #d97706; }

        .stat-content h3 {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 0.25rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .stat-content .value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            font-family: 'Outfit', sans-serif;
            line-height: 1;
        }

        .main-card {
            background: #ffffff;
            border: 1px solid rgba(26, 122, 50, 0.1);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .main-card-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .main-card-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #111827;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modern-table {
            width: 100%;
            border-collapse: collapse;
        }
        .modern-table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .modern-table td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            color: #334155;
            background: #ffffff;
        }
        .modern-table tr:hover td {
            background: #f1f5f9;
        }
        .modern-table tr:last-child td {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.025em;
        }
        .status-approved { background: rgba(16, 185, 129, 0.1); color: #059669; }
        .status-rejected { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
        .status-pending { background: rgba(245, 158, 11, 0.1); color: #d97706; }

        .btn-action {
            background: rgba(26, 122, 50, 0.1);
            color: #1a7a32;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            transition: all 0.2s;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
        }
        .btn-action:hover {
            background: #1a7a32;
            color: white;
        }
        .btn-action-primary {
            background: linear-gradient(135deg, #1a7a32, #145e27);
            color: white;
            box-shadow: 0 4px 12px rgba(26, 122, 50, 0.2);
        }
        .btn-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26, 122, 50, 0.3);
            color: white;
        }

        /* Iframe Modal styles */
        .modal-content { border-radius: 12px; overflow: hidden; border: none; }
        .modal-header { background: #f8fafc; border-bottom: 1px solid rgba(0,0,0,0.05); color: #1e293b; }
        .modal-footer { background: #f8fafc; border-top: 1px solid rgba(0,0,0,0.05); }
        .form-control, .form-select { background: #ffffff; border: 1px solid #cbd5e1; color: #1e293b; border-radius: 8px; }
        .form-control:focus, .form-select:focus { border-color: #1a7a32; box-shadow: 0 0 0 3px rgba(26, 122, 50, 0.1); }
        .form-label { color: #475569; font-weight: 500; }
    </style>

    <div class="sup-dash">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon icon-blue">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </div>
                <div class="stat-content">
                    <h3>Assigned Students</h3>
                    <div class="value">{{ $stats['total'] }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-amber">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-content">
                    <h3>Pending Review</h3>
                    <div class="value">{{ $stats['pending'] }}</div>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon icon-green">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-content">
                    <h3>Approved</h3>
                    <div class="value">{{ $stats['approved'] }}</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon icon-purple">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <div class="stat-content">
                    <h3>Scheduled</h3>
                    <div class="value">{{ $stats['scheduled'] }}</div>
                </div>
            </div>
        </div>

        <div class="main-card">
            <div class="main-card-header">
                <div class="main-card-title">
                    <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: #1a7a32;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    My Supervisees
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="modern-table">
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Research Details</th>
                            <th>Presentation</th>
                            <th>My Approval Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($students as $student)
                        <tr>
                            <td>
                                <div style="font-weight:600; color:#111827;">{{ $student->user->name }}</div>
                                <div style="font-size:0.75rem; color:#6b7280; margin-top:0.25rem;">{{ $student->matric_number }}</div>
                            </td>
                            <td>
                                <div style="font-weight:500; font-size:0.875rem; margin-bottom:0.25rem;">{{ $student->research_title }}</div>
                                <span class="status-badge" style="background:rgba(59,130,246,0.1); color:#2563eb;">
                                    {{ $student->current_research_stage }}
                                </span>
                            </td>
                            <td>
                                @if($student->presentation && $student->presentation->file_path)
                                    <div class="d-flex flex-column gap-2">
                                        <button class="btn-action" data-bs-toggle="modal" data-bs-target="#previewModal-{{ $student->presentation->id }}">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Preview PPT
                                        </button>
                                        <div style="font-size:0.7rem; color:#94a3b8; text-align:center;">
                                            Uploaded: {{ $student->presentation->updated_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    
                                    <!-- Preview Modal -->
                                    <div class="modal fade" id="previewModal-{{ $student->presentation->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-xl modal-dialog-centered">
                                            <div class="modal-content" style="height: 90vh;">
                                                <div class="modal-header">
                                                    <h5 class="modal-title fw-bold text-dark">
                                                        <i class="fa-solid fa-file-pdf me-2 text-danger"></i> {{ $student->user->name }} - Presentation
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-0">
                                                    <iframe src="{{ route('presentations.view', $student->presentation->id) }}" style="width: 100%; height: 100%; border: none;"></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="status-badge" style="background:rgba(100,116,139,0.1); color:#64748b;">Not Uploaded</span>
                                @endif
                            </td>
                            <td>
                                @if($student->pivot->status == 'approved')
                                    <span class="status-badge status-approved">Approved</span>
                                @elseif($student->pivot->status == 'rejected')
                                    <span class="status-badge status-rejected" title="{{ $student->pivot->comments }}">Rejected</span>
                                @else
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($student->presentation && $student->presentation->file_path)
                                    <button class="btn-action btn-action-primary" data-bs-toggle="modal" data-bs-target="#actionModal-{{ $student->id }}">
                                        Take Action
                                    </button>
                                    
                                    <!-- Action Modal -->
                                    <div class="modal fade" id="actionModal-{{ $student->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('supervisor.students.update-status', $student->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title font-semibold text-dark">Presentation Decision</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-4">
                                                            <label class="form-label text-dark">Decision Status</label>
                                                            <select class="form-select" name="status" required>
                                                                <option value="">Select a decision...</option>
                                                                <option value="approved" {{ $student->pivot->status == 'approved' ? 'selected' : '' }}>Approve for Scheduling</option>
                                                                <option value="rejected" {{ $student->pivot->status == 'rejected' ? 'selected' : '' }}>Reject (Needs Revision)</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label text-dark">Comments / Feedback</label>
                                                            <textarea class="form-control" name="comments" rows="3" placeholder="Provide feedback to the student...">{{ $student->pivot->comments }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary text-white" data-bs-dismiss="modal" style="border:none;">Cancel</button>
                                                        <button type="submit" class="btn-action btn-action-primary">Save Decision</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-slate-500">Waiting for PPT</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="text-slate-500">
                                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="mx-auto mb-3 opacity-50"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <p>No students assigned to you yet.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
