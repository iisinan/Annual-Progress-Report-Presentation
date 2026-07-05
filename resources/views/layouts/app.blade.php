<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ACETEL APRMS — Progress Report Management System</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">

    <!-- Custom Styles -->
    <style>
        /* =============================================
           ACETEL APRMS — Global Design System
           Primary: #1a7a32 (ACETEL Forest Green)
           Accent:  #e07020 (ACETEL Amber/Orange)
           Surface: #ffffff (White)
           ============================================= */
        :root {
            --acetel-green:       #1a7a32;
            --acetel-green-dark:  #145e27;
            --acetel-green-light: #22a041;
            --acetel-green-pale:  #eaf7ed;
            --acetel-amber:       #e07020;
            --acetel-amber-light: #f8b400;
            --sidebar-width:      260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f5;
            overflow-x: hidden;
        }
        [data-bs-theme="dark"] body {
            background-color: #0d1710;
        }

        /* ---- Sidebar ---- */
        #sidebar {
            min-width: var(--sidebar-width);
            max-width: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(180deg, var(--acetel-green-dark) 0%, var(--acetel-green) 100%);
            color: #fff;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            position: relative;
            z-index: 100;
        }
        #sidebar.active { margin-left: calc(-1 * var(--sidebar-width)); }

        #sidebar .sidebar-header {
            padding: 24px 20px 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        #sidebar .sidebar-header .brand-logo {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.8);
            box-shadow: 0 4px 16px rgba(0,0,0,0.3);
            transition: transform 0.3s ease;
        }
        #sidebar .sidebar-header .brand-logo:hover { transform: scale(1.05); }
        #sidebar .sidebar-header h5 {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            margin-top: 10px;
            margin-bottom: 2px;
            color: #fff;
        }
        #sidebar .sidebar-header small {
            font-size: 0.7rem;
            color: rgba(255,255,255,0.6);
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Sidebar Nav Category Label */
        #sidebar .nav-label {
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.4);
            padding: 16px 20px 6px;
        }

        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.92rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.78);
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: all 0.2s ease;
        }
        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255,255,255,0.12);
            border-left-color: var(--acetel-amber-light);
        }
        #sidebar ul li.active > a {
            color: #fff;
            background: rgba(255,255,255,0.18);
            border-left: 3px solid var(--acetel-amber);
            font-weight: 600;
        }
        #sidebar ul li a i { width: 18px; text-align: center; font-size: 1rem; }

        /* Submenu */
        #sidebar ul ul a {
            padding-left: 52px;
            font-size: 0.87rem;
            color: rgba(255,255,255,0.65);
            border-left: none;
            background: rgba(0,0,0,0.1);
        }
        #sidebar ul ul a:hover {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-left: none;
        }

        /* Sidebar footer strip */
        #sidebar::after {
            content: '';
            display: block;
            height: 4px;
            background: linear-gradient(90deg, var(--acetel-amber), var(--acetel-amber-light));
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
        }

        /* ---- Top Navbar ---- */
        .navbar {
            background-color: #fff;
            box-shadow: 0 2px 12px rgba(26,122,50,0.08);
            border-bottom: 2px solid var(--acetel-green-pale);
        }
        [data-bs-theme="dark"] .navbar {
            background-color: #121f14;
            box-shadow: 0 2px 12px rgba(0,0,0,0.4);
            border-bottom: 1px solid #1e3322;
        }

        /* Sidebar toggle button */
        #sidebarCollapse {
            background: var(--acetel-green);
            border: none;
            border-radius: 8px;
            padding: 8px 14px;
            color: white;
            transition: background 0.2s ease;
        }
        #sidebarCollapse:hover { background: var(--acetel-green-dark); }

        /* ---- Content Area ---- */
        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            background-color: #f4f7f5;
        }
        [data-bs-theme="dark"] #content { background-color: #0d1710; }

        /* Page title bar */
        .page-title-bar {
            background: linear-gradient(135deg, var(--acetel-green) 0%, var(--acetel-green-light) 100%);
            border-radius: 12px;
            padding: 18px 24px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 16px rgba(26,122,50,0.25);
        }
        .page-title-bar h1 { color: #fff; font-family: 'Outfit', sans-serif; font-size: 1.4rem; font-weight: 700; margin: 0; }
        .page-title-bar .breadcrumb-item, .page-title-bar .breadcrumb-item.active { color: rgba(255,255,255,0.8); }
        .page-title-bar .breadcrumb-divider { color: rgba(255,255,255,0.5); }

        /* ---- Cards ---- */
        .card {
            border: none;
            border-radius: 14px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(26,122,50,0.13);
        }
        [data-bs-theme="dark"] .card {
            background-color: #152218;
            border: 1px solid #1e3322;
        }
        [data-bs-theme="dark"] .card:hover {
            box-shadow: 0 8px 28px rgba(0,0,0,0.5);
        }

        /* ---- Stat Cards ---- */
        .stat-card-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
        }
        .stat-card-icon.green  { background: var(--acetel-green-pale); color: var(--acetel-green); }
        .stat-card-icon.amber  { background: #fff4e0; color: var(--acetel-amber); }
        .stat-card-icon.blue   { background: #e8f0fe; color: #1a73e8; }
        .stat-card-icon.red    { background: #fde8e8; color: #d93025; }

        /* ---- Buttons ---- */
        .btn-acetel {
            background: var(--acetel-green);
            color: #fff;
            border: none;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.25s ease;
        }
        .btn-acetel:hover { background: var(--acetel-green-dark); color: #fff; transform: translateY(-1px); }

        .btn-acetel-outline {
            border: 2px solid var(--acetel-green);
            color: var(--acetel-green);
            background: transparent;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.25s ease;
        }
        .btn-acetel-outline:hover { background: var(--acetel-green); color: #fff; }

        /* Keep Bootstrap primary aligned to ACETEL green */
        .btn-primary { background: var(--acetel-green); border-color: var(--acetel-green); }
        .btn-primary:hover { background: var(--acetel-green-dark); border-color: var(--acetel-green-dark); }
        .btn-primary:focus, .btn-primary:active { background: var(--acetel-green-dark); border-color: var(--acetel-green-dark); box-shadow: 0 0 0 0.25rem rgba(26,122,50,0.35); }

        /* Bootstrap success also maps to ACETEL green */
        .bg-primary-acetel { background-color: var(--acetel-green) !important; }
        .text-primary-acetel { color: var(--acetel-green) !important; }
        .border-primary-acetel { border-color: var(--acetel-green) !important; }

        /* ---- Badges ---- */
        .badge.bg-success { background-color: var(--acetel-green) !important; }

        /* ---- Form controls ---- */
        .form-control:focus, .form-select:focus {
            border-color: var(--acetel-green);
            box-shadow: 0 0 0 0.25rem rgba(26,122,50,0.2);
        }

        /* ---- Tables ---- */
        .table thead th {
            background: var(--acetel-green-pale);
            color: var(--acetel-green-dark);
            font-weight: 700;
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--acetel-green);
        }
        [data-bs-theme="dark"] .table thead th {
            background: #1a3020;
            color: rgba(255,255,255,0.8);
        }

        /* ---- Responsive ---- */
        @media (max-width: 768px) {
            #sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            #sidebar.active { margin-left: 0; }
        }

        /* ---- Notification dot pulse ---- */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(224,112,32,0.7); }
            70% { box-shadow: 0 0 0 8px rgba(224,112,32,0); }
            100% { box-shadow: 0 0 0 0 rgba(224,112,32,0); }
        }
        .notif-pulse { animation: pulse 2s infinite; }

        /* ---- Scrollbar ---- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(26,122,50,0.35); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--acetel-green); }
    </style>
</head>
<body>
    <div class="d-flex w-100">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" class="brand-logo">
                <h5>ACETEL APRMS</h5>
                <small>Progress Report System</small>
            </div>

            <ul class="list-unstyled components mt-2">
                <li><div class="nav-label">Main Menu</div></li>
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a>
                </li>
                
                @role('Administrator')
                <li>
                    <a href="#adminSubmenu" data-bs-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <i class="fa-solid fa-users-gear"></i> Administration
                    </a>
                    <ul class="collapse list-unstyled {{ request()->is('admin/*') ? 'show' : '' }}" id="adminSubmenu">
                        <li><a href="{{ route('admin.students') }}"><i class="fa-solid fa-user-graduate"></i> Manage Students</a></li>
                        <li><a href="{{ route('admin.examiners') }}"><i class="fa-solid fa-chalkboard-user"></i> Manage Examiners</a></li>
                        <li><a href="{{ route('admin.schedule.index') }}"><i class="fa-solid fa-calendar-days"></i> Presentation Schedule</a></li>
                        <li><a href="{{ route('admin.reports.index') }}"><i class="fa-solid fa-chart-bar"></i> Reports</a></li>
                        <li><a href="{{ route('admin.announcements.index') }}"><i class="fa-solid fa-bullhorn"></i> Announcements</a></li>
                        <li><a href="{{ route('admin.audit-logs') }}"><i class="fa-solid fa-shield-halved"></i> Audit Trail</a></li>
                        <li><a href="{{ route('admin.settings') }}"><i class="fa-solid fa-gear"></i> System Settings</a></li>
                    </ul>
                </li>
                @endrole

                @role('Examiner')
                <li class="{{ request()->routeIs('examiner.students') ? 'active' : '' }}">
                    <a href="{{ route('examiner.students') }}"><i class="fa-solid fa-users"></i> Registered Students</a>
                </li>
                <li class="{{ request()->routeIs('examiner.schedule') ? 'active' : '' }}">
                    <a href="{{ route('examiner.schedule') }}"><i class="fa-solid fa-calendar-week"></i> Presentation Schedule</a>
                </li>
                @endrole

                @role('Student')
                <li class="{{ request()->routeIs('student.upload') ? 'active' : '' }}">
                    <a href="{{ route('student.upload') }}"><i class="fa-solid fa-upload"></i> Upload Presentation</a>
                </li>
                <li class="{{ request()->routeIs('student.slip') ? 'active' : '' }}">
                    <a href="{{ route('student.slip') }}"><i class="fa-solid fa-file-pdf"></i> Acknowledgement Slip</a>
                </li>
                @endrole
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content" class="w-100 bg-light">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg px-4 py-3">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div class="d-flex align-items-center ms-auto">
                        <!-- Dark Mode Toggle -->
                        <button class="btn btn-link text-secondary me-3" id="theme-toggle">
                            <i class="fa-solid fa-moon fs-5"></i>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div class="dropdown me-3">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-secondary" data-bs-toggle="dropdown" aria-expanded="false" style="position: relative;">
                                <i class="fa-solid fa-bell fs-5"></i>
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                        {{ Auth::user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                <li><h6 class="dropdown-header fw-bold text-dark">Notifications</h6></li>
                                @forelse(Auth::user()->unreadNotifications as $notification)
                                    <li>
                                        <a class="dropdown-item py-2 border-bottom" href="{{ $notification->data['action_url'] ?? '#' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="bg-primary-subtle text-primary p-2 rounded-circle me-3">
                                                    <i class="fa-solid {{ $notification->data['icon'] ?? 'fa-bell' }}"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-1" style="font-size: 0.9rem;">{{ $notification->data['title'] ?? 'Notification' }}</div>
                                                    <div class="text-muted small text-wrap lh-sm">{{ $notification->data['message'] ?? '' }}</div>
                                                    <div class="text-xs text-muted mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                @empty
                                    <li><div class="dropdown-item text-center text-muted py-3">No new notifications</div></li>
                                @endforelse
                                @if(Auth::user()->unreadNotifications->count() > 0)
                                    <li>
                                        <form action="{{ route('notifications.read') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-center fw-bold text-primary py-2 bg-light border-0 w-100">Mark all as read</button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>
                        
                        <!-- Profile Dropdown -->
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0b3d91&color=fff" alt="" width="32" height="32" class="rounded-circle me-2">
                                <strong>{{ Auth::user()->name }}</strong>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fa-solid fa-user me-2"></i> Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Content Area -->
            <div class="p-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-left:4px solid var(--acetel-green);border-radius:10px;">
                        <i class="fa-solid fa-circle-check fs-5" style="color:var(--acetel-green);"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert" style="border-left:4px solid #d93025;border-radius:10px;">
                        <i class="fa-solid fa-circle-xmark fs-5 text-danger"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(isset($header))
                    <div class="page-title-bar mb-4">
                        <div>
                            <h1>{{ $header }}</h1>
                        </div>
                        @if(isset($actions))
                            <div>
                                {{ $actions }}
                            </div>
                        @endif
                    </div>
                @endif

                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery for DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Sidebar Toggle
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
            });

            // Dark Mode Toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement;
            
            // Check for saved theme preference
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme) {
                htmlElement.setAttribute('data-bs-theme', savedTheme);
                updateThemeIcon(savedTheme);
            }
            
            themeToggleBtn.addEventListener('click', () => {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                
                htmlElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                
                updateThemeIcon(newTheme);
            });
            
            function updateThemeIcon(theme) {
                if (theme === 'dark') {
                    themeToggleBtn.innerHTML = '<i class="fa-solid fa-sun fs-5"></i>';
                    themeToggleBtn.classList.replace('text-secondary', 'text-warning');
                    $('#content').removeClass('bg-light');
                } else {
                    themeToggleBtn.innerHTML = '<i class="fa-solid fa-moon fs-5"></i>';
                    themeToggleBtn.classList.replace('text-warning', 'text-secondary');
                    $('#content').addClass('bg-light');
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
