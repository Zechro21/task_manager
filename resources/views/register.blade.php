<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Task Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; color: #334155; font-family: 'Inter', system-ui, sans-serif; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05); background-color: #ffffff; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        .toggle-password { cursor: pointer; }
        .system-icon { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 12px; font-size: 1.25rem; margin: 0 auto 1rem; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
    </style>
</head>
<body class="bg-light d-flex align-items-center vh-100">
    <div class="container" style="max-width: 420px;">
        
        <!-- Premium Modern Registration Shell Card -->
        <div class="card card-custom p-3">
            <div class="card-body">
                
                <!-- System Title Branding Node -->
                <div class="text-center mb-4">
                    <div class="system-icon">
                        <i class="fa-solid fa-list-check"></i>
                    </div>
                    <h5 class="fw-bold text-dark tracking-tight mb-1">TASK MANAGEMENT</h5>
                    <p class="small text-muted mb-0">Create an account to start tracking your assignments.</p>
                </div>
                
                <form action="{{ route('register') }}" method="POST">
                    @csrf 
                    
                    <!-- Input Block Group 1: Username -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 small"><i class="fa-regular fa-user"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-1 bg-light" placeholder="johndoe" required>
                        </div>
                    </div>

                    <!-- Input Block Group 2: Email -->
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 small"><i class="fa-regular fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control border-start-0 ps-1 bg-light" placeholder="name@example.com" required>
                        </div>
                    </div>
                    
                    <!-- Input Block Group 3: Password -->
                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-muted border-end-0 small"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" id="registerPasswordField" name="password" class="form-control border-start-0 border-end-0 ps-1 bg-light" placeholder="••••••••" required minlength="6">
                            <span class="input-group-text bg-light border-start-0 toggle-password text-muted" onclick="toggleRegisterPassword()">
                                <i id="registerPasswordIcon" class="fa-regular fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Submission Trigger Controls Component -->
                    <button type="submit" class="btn btn-primary btn-md w-100 fw-medium rounded-pill shadow-sm py-2">
                        Get Started <i class="fa-solid fa-user-plus ms-2 small"></i>
                    </button>
                </form>
                
                <!-- Redirection Path Router Links Context -->
                <p class="text-center mt-4 mb-1 small text-muted">
                    Already have an account? <a href="{{ route('login') }}" class="text-primary fw-semibold text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple visibility switcher utility for password fields
        function toggleRegisterPassword() {
            const passInputField = document.getElementById('registerPasswordField');
            const toggleIconNode = document.getElementById('registerPasswordIcon');
            
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