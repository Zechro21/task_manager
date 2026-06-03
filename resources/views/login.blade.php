<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f8fafc; 
            color: #334155; 
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif; 
        }
        .login-card { 
            border: 1px solid #e2e8f0; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.03), 0 8px 10px -6px rgba(15, 23, 42, 0.03); 
            background-color: #ffffff; 
        }
        
        /* Modernized Focus State Across Group Elements */
        .input-group-custom {
            transition: all 0.2s ease;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #cbd5e1;
        }
        .input-group-custom:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .input-group-custom .form-control, 
        .input-group-custom .input-group-text {
            border: none !important;
            background-color: #f8fafc !important;
        }
        .input-group-custom .form-control:focus {
            box-shadow: none !important;
        }

        .toggle-password { 
            cursor: pointer; 
            transition: color 0.15s ease;
        }
        .toggle-password:hover i {
            color: #0f172a !important;
        }

        .system-logo {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .btn-submit {
            background-color: #2563eb;
            border: none;
            transition: all 0.15s ease;
        }
        .btn-submit:hover {
            background-color: #1d4ed8;
            transform: translateY(-1px);
        }
        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }
    </style>
</head>
<body class="d-flex align-items-center vh-100">

    <div class="container" style="max-width: 420px;">
        
        <!-- Header Branding Header -->
        <div class="text-center mb-4">
            <div class="system-logo mb-2">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">TASK MANAGEMENT</h4>
            <p class="small text-muted">Welcome back! Please sign in to access your dashboard.</p>
        </div>

        <!-- Notification Alerts -->
        @if (session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm text-center mb-3 small" role="alert">
                <span class="fw-medium">
                    @if(session('toast.type') == 'danger')
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                    @else
                        <i class="fa-solid fa-circle-check me-2"></i>
                    @endif
                    {{ session('toast.message') }}
                </span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Identity Interactive Form Card Wrapper -->
        <div class="card login-card p-2">
            <div class="card-body">
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf 
                    
                    <!-- Identification Segment -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email Address</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text text-muted small"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control ps-1" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                        </div>
                    </div>
                    
                    <!-- Credential Authentication Segment -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label small fw-semibold text-dark mb-0">Password</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-medium" style="font-size: 0.75rem;">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text text-muted small"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="loginPasswordField" name="password" class="form-control ps-1" placeholder="••••••••" required>
                            <span class="input-group-text toggle-password" onclick="toggleLoginPassword()">
                                <i id="loginPasswordIcon" class="fa-regular fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Persistence Layer Configuration -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="rememberMeCheckbox">
                            <label class="form-check-label small text-secondary fw-medium" for="rememberMeCheckbox" style="user-select: none;">
                                Remember this device
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-submit btn-md w-100 fw-medium rounded-pill shadow-sm py-2">
                        Sign In <i class="fa-solid fa-arrow-right ms-1 small"></i>
                    </button>
                </form>
                
                <p class="text-center mt-4 mb-0 small text-muted">
                    Don't have an account? <a href="{{ route('register') }}" class="text-primary fw-medium text-decoration-none">Register here</a>
                </p>
            </div>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth password field visualization switcher
        function toggleLoginPassword() {
            const passwordInput = document.getElementById('loginPasswordField');
            const toggleIcon = document.getElementById('loginPasswordIcon');
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>