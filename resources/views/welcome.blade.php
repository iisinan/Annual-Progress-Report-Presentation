<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACETEL Progress Report Presentation</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --acetel-green:       #1a7a32;
            --acetel-green-dark:  #145e27;
            --acetel-green-light: #22a041;
            --acetel-green-pale:  #eaf7ed;
            --acetel-amber:       #e07020;
            --acetel-amber-light: #f8b400;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            background-color: #f4f7f5;
        }
        
        /* Navbar */
        .navbar-brand {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            color: var(--acetel-green) !important;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 16px rgba(26,122,50,0.1);
            border-bottom: 3px solid var(--acetel-green-pale);
        }
        .navbar-brand .brand-logo-nav {
            width: 38px; height: 38px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--acetel-green);
            box-shadow: 0 2px 8px rgba(26,122,50,0.2);
        }

        /* Buttons */
        .btn-acetel {
            background: var(--acetel-green);
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-acetel:hover {
            background: var(--acetel-green-dark);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(26,122,50,0.35);
        }
        .btn-outline-acetel {
            color: var(--acetel-green);
            border: 2px solid var(--acetel-green);
            font-weight: 700;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-outline-acetel:hover {
            background-color: var(--acetel-green);
            color: white;
            transform: translateY(-3px);
        }

        /* Hero */
        .hero-section {
            background: linear-gradient(145deg, var(--acetel-green-dark) 0%, var(--acetel-green) 60%, var(--acetel-green-light) 100%);
            color: white;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        .hero-section::before {
            content: '';
            position: absolute;
            top: -100px; right: -100px;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(248,180,0,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
        }
        .hero-badge {
            background: rgba(248,180,0,0.2);
            border: 1px solid rgba(248,180,0,0.5);
            color: var(--acetel-amber-light);
            font-weight: 700;
            font-size: 0.8rem;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-block;
            margin-bottom: 1.25rem;
            letter-spacing: 0.5px;
        }
        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3.2rem;
            font-weight: 800;
            margin-bottom: 1.25rem;
            line-height: 1.15;
        }
        .hero-subtitle {
            font-size: 1.1rem;
            font-weight: 300;
            opacity: 0.88;
            margin-bottom: 2.5rem;
            line-height: 1.7;
        }

        /* Cards */
        .info-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 24px rgba(26,122,50,0.08);
            height: 100%;
            border-top: 4px solid var(--acetel-green);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .info-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(26,122,50,0.15);
        }
        .info-icon {
            color: var(--acetel-green);
            background: var(--acetel-green-pale);
            width: 60px; height: 60px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 14px;
            margin-bottom: 20px;
        }

        /* Deadlines */
        .date-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            transition: box-shadow 0.2s;
        }
        .date-box:hover { box-shadow: 0 4px 16px rgba(26,122,50,0.12); }
        .date-icon {
            font-size: 1.8rem;
            color: var(--acetel-amber);
            margin-right: 20px;
        }

        /* Section headers */
        .section-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--acetel-green);
            margin-bottom: 8px;
        }
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: #111827;
        }

        /* Footer */
        .footer {
            background: linear-gradient(160deg, var(--acetel-green-dark), var(--acetel-green));
            color: rgba(255,255,255,0.75);
            padding: 48px 0;
            text-align: center;
        }
        .footer-amber-strip {
            height: 4px;
            background: linear-gradient(90deg, var(--acetel-amber), var(--acetel-amber-light));
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: rgba(26,122,50,0.3); border-radius: 10px; }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg py-3 sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" class="brand-logo-nav me-1">
                ACETEL
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @if (Route::has('login'))
                        @auth
                            <li class="nav-item">
                                <a href="{{ url('/dashboard') }}" class="btn btn-acetel px-4">Go to Dashboard</a>
                            </li>
                        @else
                            <li class="nav-item me-2">
                                <a href="{{ route('login') }}" class="btn btn-outline-acetel px-4">Log in</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a href="{{ route('register') }}" class="btn btn-acetel px-4">Student Registration</a>
                                </li>
                            @endif
                        @endauth
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container" style="position:relative;z-index:1;">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-badge">📅 Academic Session: {{ $settings['academic_session'] ?? 'Current' }}</div>
                    <h1 class="hero-title">Annual Progress<br>Report Presentation</h1>
                    <p class="hero-subtitle">
                        Welcome to the official portal for the Africa Centre of Excellence on Technology Enhanced Learning (ACETEL). Register and upload your presentation before the deadline.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="{{ route('register') }}" class="btn btn-acetel btn-lg px-5" style="background:#f8b400;color:#145e27;">Register Now <i class="fa-solid fa-arrow-right ms-2"></i></a>
                        <a href="#guidelines" class="btn btn-outline-light btn-lg px-5" style="border-radius:50px;">View Guidelines</a>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block text-center" style="position:relative;">
                    <img src="https://illustrations.popsy.co/amber/student-going-to-school.svg" alt="Student Illustration" class="img-fluid" style="filter: drop-shadow(0 16px 32px rgba(0,0,0,0.25)); max-height: 420px;">
                    <!-- Logo badge overlay -->
                    <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" style="position:absolute;bottom:10px;right:20px;width:72px;height:72px;border-radius:50%;object-fit:cover;border:3px solid rgba(255,255,255,0.8);box-shadow:0 4px 16px rgba(0,0,0,0.3);">
                </div>
            </div>
        </div>
    </section>

    <!-- Guidelines -->
    <section id="guidelines" class="py-5" style="background:#f4f7f5;">
        <div class="container my-5">
            <div class="row mb-5 text-center">
                <div class="col-12">
                    <p class="section-label">How It Works</p>
                    <h2 class="section-title">Presentation Guidelines</h2>
                    <p class="text-muted">What you need to know before registering.</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fa-solid fa-users fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Who Should Register?</h4>
                        <p class="text-muted">All active MSc and PhD students conducting research under ACETEL are required to participate in this mandatory progress reporting exercise.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fa-solid fa-file-powerpoint fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Presentation Format</h4>
                        <p class="text-muted">Students are required to upload a <strong>PDF version</strong> of their presentation (exported from PowerPoint), containing a maximum of 15 slides summarizing their research progress. Max size: 100MB.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fa-solid fa-clock fa-2x"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Scheduling</h4>
                        <p class="text-muted">Once registered and successfully uploaded, students will be automatically assigned a time slot and venue. An acknowledgement slip must be downloaded and printed.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Deadlines Section -->
    <section class="py-5" style="background:white;">
        <div class="container my-4">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <p class="section-label">Important Dates</p>
                    <h2 class="section-title mb-3">Key Deadlines</h2>
                    <p class="text-muted mb-4">Please take note of the following deadlines to ensure your participation. Late submissions will not be accommodated.</p>
                    <a href="{{ route('register') }}" class="btn btn-acetel btn-lg px-5">Begin Registration <i class="fa-solid fa-arrow-right ms-2"></i></a>
                </div>
                <div class="col-lg-6">
                    <div class="date-box shadow-sm">
                        <div class="date-icon"><i class="fa-regular fa-calendar-check"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Registration Closes</h5>
                            <p class="mb-0 text-muted">{{ isset($settings['registration_close_date']) ? \Carbon\Carbon::parse($settings['registration_close_date'])->format('l, d F Y') : 'TBA' }}</p>
                        </div>
                    </div>
                    
                    <div class="date-box shadow-sm">
                        <div class="date-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Upload Portal Closes</h5>
                            <p class="mb-0 text-muted">{{ isset($settings['upload_close_date']) ? \Carbon\Carbon::parse($settings['upload_close_date'])->format('l, d F Y') : 'TBA' }}</p>
                        </div>
                    </div>

                    <div class="date-box shadow-sm border-start border-4 border-warning">
                        <div class="date-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
                        <div>
                            <h5 class="fw-bold mb-1">Presentations Begin</h5>
                            <p class="mb-0 text-muted">{{ isset($settings['presentation_start_date']) ? \Carbon\Carbon::parse($settings['presentation_start_date'])->format('l, d F Y') : 'TBA' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <div class="footer-amber-strip"></div>
    <footer class="footer">
        <div class="container">
            <div class="mb-4">
                <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" class="mb-3 rounded-circle shadow" style="height: 64px; width: 64px; object-fit: cover; border: 3px solid rgba(255,255,255,0.6);">
                <h4 class="fw-bold text-white mb-1 mt-2" style="font-family:'Outfit',sans-serif;">ACETEL</h4>
                <p class="small mb-0">Africa Centre of Excellence on Technology Enhanced Learning</p>
            </div>
            <p class="mb-0 small">&copy; {{ date('Y') }} National Open University of Nigeria (NOUN). All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
