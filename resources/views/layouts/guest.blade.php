<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'APRMS') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Styles -->
    <style>
        :root {
            --acetel-green:       #1a7a32;
            --acetel-green-dark:  #145e27;
            --acetel-green-light: #22a041;
            --acetel-amber:       #e07020;
            --acetel-amber-light: #f8b400;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f5;
            overflow-x: hidden;
        }
        .login-container {
            min-height: 100vh;
            display: flex;
        }
        .login-form-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            background: #ffffff;
        }
        .login-form-wrapper {
            width: 100%;
            max-width: 460px;
        }
        .login-image-side {
            flex: 1;
            background: linear-gradient(160deg, var(--acetel-green-dark) 0%, var(--acetel-green) 50%, var(--acetel-green-light) 100%);
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            padding: 3rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        /* Decorative circle pattern */
        .login-image-side::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 350px; height: 350px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .login-image-side::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -60px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(248,180,0,0.12);
        }
        @media (min-width: 992px) {
            .login-image-side {
                display: flex;
            }
        }
        /* Amber strip at top of form side */
        .login-form-side::before {
            content: '';
            display: block;
            height: 4px;
            width: 100%;
            background: linear-gradient(90deg, var(--acetel-green), var(--acetel-amber));
            position: absolute;
            top: 0; left: 0;
        }
        .login-form-side { position: relative; }
        .bg-primary-acetel { background-color: var(--acetel-green) !important; }
        .text-primary-acetel { color: var(--acetel-green) !important; }
        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 12px 40px rgba(26,122,50,0.1);
        }
        .btn-primary {
            background-color: var(--acetel-green);
            border-color: var(--acetel-green);
            font-weight: 600;
            transition: all 0.3s ease;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: var(--acetel-green-dark);
            border-color: var(--acetel-green-dark);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(26,122,50,0.35);
        }
        .btn-outline-acetel {
            color: var(--acetel-green);
            border: 2px solid var(--acetel-green);
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-outline-acetel:hover {
            background-color: var(--acetel-green);
            color: white;
        }
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(26, 122, 50, 0.2);
            border-color: var(--acetel-green);
        }
        .brand-title {
            font-family: 'Outfit', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: white;
            letter-spacing: -0.5px;
        }
        .brand-tagline {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.75);
            max-width: 340px;
            line-height: 1.6;
        }
        .feature-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 0.85rem;
            color: white;
            margin: 4px;
            backdrop-filter: blur(4px);
        }
        .feature-pill i { color: var(--acetel-amber-light); }
        .form-label { font-weight: 600; font-size: 0.88rem; color: #374151; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: rgba(26,122,50,0.3); border-radius: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Side: Branding -->
        <div class="login-image-side">
            <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" class="mb-4 shadow-sm rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid rgba(255,255,255,0.8); position: relative; z-index: 1;">
            <h1 class="brand-title mb-2" style="position:relative;z-index:1;">ACETEL</h1>
            <p class="brand-tagline mb-5" style="position:relative;z-index:1;">Africa Centre of Excellence on Technology Enhanced Learning — Progress Report Management System</p>
            <div style="position:relative;z-index:1;">
                <div class="feature-pill"><i class="fa-solid fa-shield-check"></i> Secure & Role-Based Access</div>
                <div class="feature-pill"><i class="fa-solid fa-cloud-arrow-up"></i> Cloud-Powered Storage</div>
                <div class="feature-pill"><i class="fa-solid fa-calendar-check"></i> Smart Scheduling</div>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="login-form-side">
            <div class="login-form-wrapper">
                <div class="text-center d-lg-none mb-4">
                    <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="ACETEL Logo" class="mb-3 rounded-circle shadow" style="width: 72px; height: 72px; object-fit: cover;">
                    <h2 class="h4 mb-0 fw-bold text-primary-acetel" style="font-family:'Outfit',sans-serif;">ACETEL APRMS</h2>
                </div>

                {{ $slot }}
                
                <p class="mt-5 mb-3 text-muted text-center small">&copy; {{ date('Y') }} National Open University of Nigeria (NOUN)</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
