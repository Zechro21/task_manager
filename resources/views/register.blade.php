<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f8fafc; 
            color: #334155; 
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif; 
        }
        .card-custom { 
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

        .system-icon { 
            background: linear-gradient(135deg, #2563eb, #1d4ed8); 
            color: white; 
            width: 48px; 
            height: 48px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            border-radius: 12px; 
            font-size: 1.25rem; 
            margin: 0 auto 1rem; 
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
    </style>
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container" style="max-width: 420px; margin-top: 2rem; margin-bottom: 2rem;">
        
        <div class="card card-custom p-2">
            <div class="card-body">
                
                <div class="text-center mb-4">
                    <div class="system-icon">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark tracking-tight mb-1" style="letter-spacing: -0.5px;">TASK MANAGEMENT</h5>
                    <p class="small text-muted mb-0">Create an account to start tracking your assignments.</p>
                </div>
                
                <form action="{{ route('register') }}" method="POST">
                    @csrf 
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Username</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text text-muted small"><i class="fa-regular fa-user"></i></span>
                            <input type="text" name="username" class="form-control ps-1" placeholder="johndoe" value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Email Address</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text text-muted small"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control ps-1" placeholder="name@example.com" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-dark">Password</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text text-muted small"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="registerPasswordField" name="password" class="form-control ps-1" placeholder="••••••••" required minlength="6">
                            <span class="input-group-text toggle-password" onclick="toggleFieldPassword('registerPasswordField', 'registerPasswordIcon')">
                                <i id="registerPasswordIcon" class="fa-regular fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-dark">Confirm Password</label>
                        <div class="input-group input-group-custom">
                            <span class="input-group-text text-muted small"><i class="fa-solid fa-shield-halved"></i></span>
                            <input type="password" id="confirmPasswordField" name="password_confirmation" class="form-control ps-1" placeholder="••••••••" required minlength="6">
                            <span class="input-group-text toggle-password" onclick="toggleFieldPassword('confirmPasswordField', 'confirmPasswordIcon')">
                                <i id="confirmPasswordIcon" class="fa-regular fa-eye text-muted"></i>
                            </span>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-submit btn-md w-100 fw-medium rounded-pill shadow-sm py-2">
                        Get Started <i class="fa-solid fa-user-plus ms-1 small"></i>
                    </button>
                </form>
                
                <p class="text-center mt-4 mb-0 small text-muted">
                    Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Multi-field visibility switcher utility
        function toggleFieldPassword(fieldId, iconId) {
            const passInputField = document.getElementById(fieldId);
            const toggleIconNode = document.getElementById(iconId);
            
            if (passInputField.type === "password") {
                passInputField.type = "text";
                toggleIconNode.classList.remove('fa-eye');
                toggleIconNode.classList.add('fa-eye-slash');
            } else {
                passInputField.type = "password";
                toggleIconNode.classList.remove('fa-eye-slash');
                toggleIconNode.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>