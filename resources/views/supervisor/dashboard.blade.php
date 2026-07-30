<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Supervisor Dashboard') }}
        </h2>
    </x-slot>

    <style>
        .sup-dash {
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1a2744 50%, #0f172a 100%);
            padding: 2.5rem 1.5rem;
        }
        .sup-container { max-width: 1000px; margin: 0 auto; }

        /* Header */
        .sup-header { margin-bottom: 2rem; }
        .sup-title { font-size: 2rem; font-weight: 800; color: #fff; margin: 0 0 0.25rem; }
        .sup-subtitle { color: #64748b; font-size: 0.95rem; margin: 0; }

        /* Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media(max-width:600px){ .stats-grid { grid-template-columns: 1fr; } }
        .stat-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 14px;
            padding: 1.1rem 1.4rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .stat-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .stat-icon svg { width: 20px; height: 20px; }
        .stat-label { color: #64748b; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; }
        .stat-value { color: #fff; font-size: 1.6rem; font-weight: 800; line-height: 1; }

        /* Student Cards */
        .student-card {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .student-card:hover {
            border-color: rgba(99,102,241,0.35);
            box-shadow: 0 0 24px rgba(99,102,241,0.08);
        }
        .avatar {
            width: 50px; height: 50px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 1rem; color: #fff; flex-shrink: 0;
        }
        .student-info { flex: 1; min-width: 0; }
        .student-name { color: #f1f5f9; font-weight: 700; font-size: 1rem; margin: 0 0 0.15rem; }
        .student-meta { color: #64748b; font-size: 0.8rem; margin: 0 0 0.4rem; }
        .student-research { color: #94a3b8; font-size: 0.8rem; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 320px; }
        .prog-badge {
            display: inline-flex; align-items: center;
            background: rgba(99,102,241,0.2); color: #a5b4fc;
            font-size: 0.7rem; font-weight: 700; letter-spacing: 0.05em;
            padding: 0.2rem 0.6rem; border-radius: 999px; margin-left: 0.5rem;
            border: 1px solid rgba(99,102,241,0.3);
        }
        .card-actions { display: flex; align-items: center; gap: 0.75rem; flex-shrink: 0; }

        /* Status badges */
        .status-badge {
            padding: 0.3rem 0.9rem; border-radius: 999px;
            font-size: 0.72rem; font-weight: 700; letter-spacing: 0.04em; text-transform: uppercase;
        }
        .status-pending  { background: rgba(234,179,8,0.15);  color: #fbbf24; border: 1px solid rgba(234,179,8,0.3); }
        .status-approved { background: rgba(34,197,94,0.15);  color: #4ade80; border: 1px solid rgba(34,197,94,0.3); }
        .status-rejected { background: rgba(239,68,68,0.15);  color: #f87171; border: 1px solid rgba(239,68,68,0.3); }

        /* Buttons */
        .btn-view {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: rgba(59,130,246,0.15); color: #60a5fa;
            border: 1px solid rgba(59,130,246,0.3); border-radius: 8px;
            padding: 0.45rem 1rem; font-size: 0.8rem; font-weight: 600;
            text-decoration: none; transition: background 0.2s;
            white-space: nowrap;
        }
        .btn-view:hover { background: rgba(59,130,246,0.28); color: #93c5fd; }
        .btn-review {
            display: inline-flex; align-items: center; gap: 0.4rem;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            color: #fff; border: none; border-radius: 8px;
            padding: 0.45rem 1.1rem; font-size: 0.8rem; font-weight: 700;
            cursor: pointer; transition: opacity 0.2s, transform 0.15s;
            white-space: nowrap; box-shadow: 0 2px 12px rgba(99,102,241,0.35);
        }
        .btn-review:hover { opacity: 0.9; transform: translateY(-1px); }

        /* Empty state */
        .empty-state {
            text-align: center; padding: 4rem 2rem;
            background: rgba(255,255,255,0.03); border: 1px dashed rgba(255,255,255,0.1);
            border-radius: 16px;
        }
        .empty-state p { color: #475569; font-size: 1rem; margin: 1rem 0 0; }

        /* Flash messages */
        .flash-success {
            background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.3);
            color: #4ade80; border-radius: 10px; padding: 0.85rem 1.25rem;
            margin-bottom: 1.5rem; font-size: 0.9rem;
        }
        .flash-error {
            background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
            color: #f87171; border-radius: 10px; padding: 0.85rem 1.25rem;
            margin-bottom: 1.5rem; font-size: 0.9rem;
        }

        /* Modal Overlay */
        .modal-overlay {
            display: none; position: fixed; inset: 0; z-index: 50;
            background: rgba(0,0,0,0.7); backdrop-filter: blur(4px);
            align-items: center; justify-content: center;
        }
        .modal-overlay.active { display: flex; }
        .modal-box {
            background: #1e293b; border: 1px solid rgba(255,255,255,0.12);
            border-radius: 20px; padding: 2rem; width: 100%; max-width: 480px;
            box-shadow: 0 25px 60px rgba(0,0,0,0.5);
            animation: modalIn 0.2s ease;
        }
        @keyframes modalIn { from { opacity:0; transform: scale(0.95) translateY(10px); } to { opacity:1; transform: scale(1) translateY(0); } }
        .modal-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; }
        .modal-title { color: #f1f5f9; font-size: 1.15rem; font-weight: 700; margin: 0; }
        .modal-close {
            background: none; border: none; color: #64748b; cursor: pointer; padding: 0.25rem;
            border-radius: 6px; transition: color 0.15s;
        }
        .modal-close:hover { color: #f1f5f9; }
        .modal-student { color: #94a3b8; font-size: 0.85rem; margin-bottom: 1.25rem; }
        .modal-label { display: block; color: #94a3b8; font-size: 0.78rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem; }
        .modal-textarea {
            width: 100%; background: rgba(15,23,42,0.6); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 10px; color: #f1f5f9; font-size: 0.9rem;
            padding: 0.75rem 1rem; outline: none; resize: vertical; min-height: 100px;
            transition: border-color 0.2s, box-shadow 0.2s; box-sizing: border-box;
        }
        .modal-textarea:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.2); }
        .modal-textarea::placeholder { color: #475569; }
        .modal-actions { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
        .btn-reject {
            flex: 1; padding: 0.75rem; border-radius: 10px; border: none; cursor: pointer;
            background: rgba(239,68,68,0.15); color: #f87171;
            border: 1px solid rgba(239,68,68,0.3); font-weight: 700; font-size: 0.9rem;
            transition: background 0.2s;
        }
        .btn-reject:hover { background: rgba(239,68,68,0.28); }
        .btn-approve {
            flex: 1; padding: 0.75rem; border-radius: 10px; border: none; cursor: pointer;
            background: linear-gradient(135deg, #16a34a, #22c55e); color: #fff;
            font-weight: 700; font-size: 0.9rem;
            box-shadow: 0 2px 12px rgba(34,197,94,0.3); transition: opacity 0.2s;
        }
        .btn-approve:hover { opacity: 0.9; }
    </style>

    <div class="sup-dash">
        <div class="sup-container">

            {{-- Header --}}
            <div class="sup-header">
                <h1 class="sup-title">Supervisor Dashboard</h1>
                <p class="sup-subtitle">Manage and review your assigned students' presentations.</p>
            </div>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="flash-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-error">✗ {{ session('error') }}</div>
            @endif

            {{-- Stats --}}
            @php
                $total    = $students->count();
                $pending  = $students->where('pivot.status', 'pending')->count();
                $approved = $students->where('pivot.status', 'approved')->count();
            @endphp
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(99,102,241,0.2);">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#a5b4fc"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Total Students</div>
                        <div class="stat-value">{{ $total }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(234,179,8,0.15);">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#fbbf24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Pending Review</div>
                        <div class="stat-value">{{ $pending }}</div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background:rgba(34,197,94,0.15);">
                        <svg fill="none" viewBox="0 0 24 24" stroke="#4ade80"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <div class="stat-label">Approved</div>
                        <div class="stat-value">{{ $approved }}</div>
                    </div>
                </div>
            </div>

            {{-- Student Cards --}}
            @if($students->isEmpty())
                <div class="empty-state">
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#334155" style="margin:0 auto;display:block;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <p>You have no students assigned yet.</p>
                </div>
            @else
                @php
                    $avatarColors = ['#4f46e5','#0891b2','#059669','#d97706','#dc2626','#7c3aed','#db2777'];
                @endphp
                @foreach($students as $index => $student)
                    @php
                        $name     = optional($student->user)->name ?? 'N/A';
                        $initials = collect(explode(' ', $name))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->implode('');
                        $color    = $avatarColors[$index % count($avatarColors)];
                        $status   = $student->pivot->status ?? 'pending';
                        $statusClass = ['pending'=>'status-pending','approved'=>'status-approved','rejected'=>'status-rejected'][$status] ?? 'status-pending';
                    @endphp
                    <div class="student-card">
                        {{-- Avatar --}}
                        <div class="avatar" style="background:{{ $color }};">{{ $initials }}</div>

                        {{-- Info --}}
                        <div class="student-info">
                            <div class="student-name">
                                {{ $name }}
                                <span class="prog-badge">{{ optional($student->programme)->name ?? 'N/A' }}</span>
                            </div>
                            <div class="student-meta">{{ $student->matric_number }}</div>
                            <p class="student-research" title="{{ $student->research_title }}">{{ $student->research_title }}</p>
                        </div>

                        {{-- Actions --}}
                        <div class="card-actions">
                            @if($student->presentation && $student->presentation->file_path)
                                @php
                                    try {
                                        // Try temporaryUrl first (signed), fall back to direct url
                                        $pptUrl = Storage::disk('r2')->temporaryUrl(
                                            $student->presentation->file_path,
                                            now()->addHours(2)
                                        );
                                    } catch (\Exception $e) {
                                        try {
                                            $pptUrl = Storage::disk('r2')->url($student->presentation->file_path);
                                        } catch (\Exception $e2) {
                                            $pptUrl = null;
                                        }
                                    }
                                @endphp
                                @if($pptUrl)
                                    <a href="{{ $pptUrl }}" target="_blank" class="btn-view">
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        View PPT
                                    </a>
                                @else
                                    <span style="color:#475569;font-size:0.78rem;">Link Error</span>
                                @endif
                            @else
                                <span style="color:#475569;font-size:0.78rem;">No PPT</span>
                            @endif

                            <span class="status-badge {{ $statusClass }}">{{ ucfirst($status) }}</span>

                            <button
                                class="btn-review"
                                onclick="openReviewModal('{{ $student->id }}', '{{ addslashes($name) }}', '{{ $student->matric_number }}')"
                            >
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Review
                            </button>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

    {{-- Review Modal --}}
    <div id="reviewModal" class="modal-overlay" onclick="handleOverlayClick(event)">
        <div class="modal-box">
            <div class="modal-header">
                <h3 class="modal-title">Review Presentation</h3>
                <button class="modal-close" onclick="closeReviewModal()">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <p class="modal-student">Student: <strong id="modal-student-name" style="color:#e2e8f0;"></strong> &mdash; <span id="modal-student-matric" style="color:#64748b;"></span></p>

            <form id="approveForm" method="POST" action="">
                @csrf
                <label class="modal-label" for="comments">Comments <span style="color:#475569;">(required for rejection)</span></label>
                <textarea id="comments" name="comments" class="modal-textarea" placeholder="Enter your comments here..."></textarea>

                <div class="modal-actions">
                    <button type="button" class="btn-reject" onclick="submitReview('reject')">
                        ✕ Reject
                    </button>
                    <button type="button" class="btn-approve" onclick="submitReview('approve')">
                        ✓ Approve
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReviewModal(studentId, studentName, matricNumber) {
            document.getElementById('modal-student-name').textContent = studentName;
            document.getElementById('modal-student-matric').textContent = matricNumber;
            document.getElementById('comments').value = '';
            window.currentReviewStudentId = studentId;
            document.getElementById('reviewModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        function handleOverlayClick(e) {
            if (e.target === document.getElementById('reviewModal')) closeReviewModal();
        }

        function submitReview(action) {
            const form = document.getElementById('approveForm');
            const studentId = window.currentReviewStudentId;
            const comments = document.getElementById('comments').value;

            if (action === 'reject' && !comments.trim()) {
                document.getElementById('comments').style.borderColor = '#f87171';
                document.getElementById('comments').style.boxShadow = '0 0 0 3px rgba(239,68,68,0.2)';
                document.getElementById('comments').focus();
                return;
            }

            form.action = action === 'approve'
                ? `/supervisor/approve/${studentId}`
                : `/supervisor/reject/${studentId}`;

            form.submit();
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeReviewModal();
        });
    </script>
</x-app-layout>
