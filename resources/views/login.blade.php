<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Task Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8fafc; 
            color: #334155; 
            font-family: 'Inter', system-ui, sans-serif; 
        }
        .login-card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05); 
            background-color: #ffffff; 
        }
        .form-control:focus { 
            border-color: #3b82f6; 
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); 
        }
        .toggle-password { 
            cursor: pointer; 
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
    </style>
</head>
<body class="d-flex align-items-center vh-100">

    <div class="container" style="max-width: 420px;">
        
        <div class="text-center mb-4">
            <div class="system-logo mb-2">
                <i class="fa-solid fa-list-check"></i>
            </div>
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">TASK MANAGEMENT</h4>
            <p class="small text-muted">Welcome back! Please sign in to access your dashboard.</p>
        </div>

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

        <div class="card login-card p-3">
            <div class="card-body">
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf 
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted small"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" placeholder="name@example.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <label class="form-label small fw-semibold text-secondary mb-1">Password</label>
                        </div>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted small"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="loginPasswordField" name="password" class="form-control border-start-0 border-end-0 ps-0 bg-light" placeholder="••••••••" required>
                            <span class="input-group-text bg-light toggle-password" onclick="toggleLoginPassword()">
                                <i id="loginPasswordIcon" class="fa-regular fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-md w-100 fw-medium rounded-pill shadow-sm py-2">
                        Sign In<i class="fa-solid fa-arrow-right ms-2 small"></i>
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
        // Smooth local password toggle engine utility script
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