<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <!-- Welcome Banner -->

    <div class="page-title-bar mb-4">
        <div>
            <h1><i class="fa-solid fa-gauge me-2"></i> Admin Dashboard</h1>
            <small style="color:rgba(255,255,255,0.7);">Welcome back, {{ Auth::user()->name }} &mdash; {{ now()->format('l, d F Y') }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.schedule.index') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.3);border-radius:8px;"><i class="fa-solid fa-calendar-days me-1"></i> Schedule</a>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-sm" style="background:rgba(248,180,0,0.9);color:#145e27;font-weight:700;border-radius:8px;"><i class="fa-solid fa-chart-bar me-1"></i> Reports</a>
        </div>
    </div>

    <!-- Row 1: Key Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon green"><i class="fa-solid fa-users"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Total Students</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['total_students'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon blue"><i class="fa-solid fa-user-check"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Registered (Active)</div>
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
    </div>
    
    <!-- Row 2: Secondary Stats -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon blue"><i class="fa-solid fa-chalkboard-user"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Total Examiners</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['total_examiners'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon green"><i class="fa-solid fa-calendar-days"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Presentation Days</div>
                        <div class="fw-bold" style="font-size:1.9rem;line-height:1;">{{ $stats['presentation_days'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon green"><i class="fa-solid fa-graduation-cap"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Graded Presentations</div>
                        <div class="fw-bold text-success" style="font-size:1.9rem;line-height:1;">{{ $stats['graded_presentations'] }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center gap-3 p-4">
                    <div class="stat-card-icon amber"><i class="fa-solid fa-hourglass-half"></i></div>
                    <div>
                        <div class="text-muted fw-semibold" style="font-size:0.78rem;text-transform:uppercase;letter-spacing:0.5px;">Pending Evaluations</div>
                        <div class="fw-bold text-warning" style="font-size:1.9rem;line-height:1;">{{ $stats['pending_evaluations'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
                    <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-chart-line me-2"></i>Registration Overview</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="registrationChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header py-3 d-flex align-items-center justify-content-between" style="background:var(--acetel-green-pale);border-bottom:2px solid var(--acetel-green);">
                    <h6 class="m-0 fw-bold" style="color:var(--acetel-green);"><i class="fa-solid fa-chart-pie me-2"></i>Department Distribution</h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const acetelGreen = '#1a7a32';
        const acetelGreenLight = '#22a041';
        const acetelGreenPale = '#eaf7ed';
        const acetelAmber = '#e07020';
        const acetelAmberLight = '#f8b400';

        // Registration Chart
        const ctx1 = document.getElementById('registrationChart').getContext('2d');
        const gradient = ctx1.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(26,122,50,0.25)');
        gradient.addColorStop(1, 'rgba(26,122,50,0.02)');
        new Chart(ctx1, {
            type: 'line',
            data: {
                labels: {!! json_encode($months) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($registrationData) !!},
                    borderColor: acetelGreen,
                    backgroundColor: gradient,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: acetelGreen,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // Department Chart
        const ctx2 = document.getElementById('departmentChart').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($departmentLabels) !!},
                datasets: [{
                    data: {!! json_encode($departmentData) !!},
                    backgroundColor: [acetelGreen, acetelAmber, acetelAmberLight, '#1a73e8', '#d93025', '#9b59b6'],
                    borderWidth: 2,
                    borderColor: '#fff',
                    hoverOffset: 6
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom', labels: { padding: 12, usePointStyle: true } }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
