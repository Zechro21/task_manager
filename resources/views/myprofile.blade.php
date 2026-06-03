<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; color: #334155; font-family: 'Inter', system-ui, sans-serif; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); background-color: #ffffff; }
        
        /* Premium Avatar Frame Style */
        .avatar-wrapper {
            position: relative;
            width: 130px;
            height: 130px;
            margin: 0 auto;
        }
        .profile-avatar-big { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            border: 4px solid #ffffff; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.1); 
            transition: all 0.3s ease;
        }
        /* Custom image picker icon overlay button */
        .avatar-hover-picker {
            position: absolute;
            bottom: 2px;
            right: 2px;
            background: #2563eb;
            color: #ffffff;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        .avatar-hover-picker:hover { background: #1d4ed8; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15); }
        .toggle-password { cursor: pointer; }
    </style>
</head>
<body>

    @include('partials.nav')

    <div class="container my-5" style="max-width: 1000px;">
        
        @if (session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <span class="fw-medium small"><i class="fa-solid fa-circle-check me-2"></i>{{ session('toast.message') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            
            <div class="col-lg-4">
                <div class="card card-custom p-4 text-center h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="text-start mb-4">
                            <h6 class="fw-bold text-dark mb-1"><i class="fa-regular fa-image text-muted me-2"></i>Profile Identity</h6>
                            <p class="small text-muted mb-0">Manage your avatar display image.</p>
                        </div>
                        
                        <form action="{{ route('profile.update_avatar') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="avatar-wrapper mb-4">
                                <img id="avatarPreview" 
                                    src="{{ (!empty($user->profile_pic) && file_exists(public_path('uploads/' . $user->profile_pic))) ? asset('uploads/' . $user->profile_pic) : asset('uploads/default.png') }}" 
                                    class="rounded-circle profile-avatar-big" 
                                    alt="User Avatar">
                                
                                <label for="profileFileField" class="avatar-hover-picker" title="Change Photo">
                                    <i class="fa-solid fa-camera size-xs"></i>
                                </label>
                                <input type="file" id="profileFileField" name="profile_pic" class="d-none" accept="image/*" required>
                            </div>

                            <div id="uploadActionGroup" class="d-none">
                                <p class="small text-success mb-3 fw-medium"><i class="fa-solid fa-circle-info me-1"></i>New file selected!</p>
                                <button type="submit" class="btn btn-primary btn-sm px-4 fw-medium shadow-sm w-100 rounded-pill mb-2">
                                    <i class="fa-solid fa-cloud-arrow-up me-2"></i>Save Avatar Photo
                                </button>
                                <button type="button" id="cancelUploadBtn" class="btn btn-light btn-sm w-100 rounded-pill text-secondary small">Cancel Change</button>
                            </div>
                        </form>
                    </div>

                    <div class="pt-4 border-top text-start mt-4">
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Registered Username</small>
                            <span class="fw-bold text-dark"><i class="fa-regular fa-circle-user me-2 text-primary"></i>{{ $user->username }}</span>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Account Role</small>
                            <span class="badge bg-soft-primary text-primary px-2 py-1 rounded-pill bg-light border"><i class="fa-solid fa-user-shield me-1"></i>Standard User</span>
                        </div>
                        <div>
                            <small class="text-muted d-block mb-1">Account Contact</small>
                            <span class="fw-semibold text-secondary small"><i class="fa-regular fa-envelope me-2"></i>{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                
                <div class="card card-custom p-4 mb-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-1"><i class="fa-regular fa-id-card text-muted me-2"></i>Account Configurations</h6>
                        <p class="small text-muted mb-0">Keep your login parameters and primary contact details updated.</p>
                    </div>
                    
                    <form action="{{ route('profile.update_info') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Username</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text bg-light border-end-0 text-muted small"><i class="fa-regular fa-user"></i></span>
                                    <input type="text" name="username" class="form-control border-start-0 ps-0 bg-light" value="{{ $user->username }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Email Address</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text bg-light border-end-0 text-muted small"><i class="fa-regular fa-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light" value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="col-12 text-end pt-2">
                                <button type="submit" class="btn btn-dark btn-sm px-4 fw-medium rounded-pill shadow-sm">
                                    <i class="fa-regular fa-floppy-disk me-2"></i>Save Profile Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card card-custom p-4">
                    <div class="mb-4">
                        <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-shield-halved me-2"></i>Modify Security Password</h6>
                        <p class="small text-muted mb-0">Change your key secret combinations periodically to protect task logs.</p>
                    </div>
                    
                    <form action="{{ route('profile.update_password') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">New Password</label>
                                <div class="input-group">
                                    <input type="password" id="newPassField" name="new_password" class="form-control" placeholder="••••••••" required minlength="6">
                                    <span class="input-group-text bg-light toggle-password" onclick="toggleVisibility('newPassField', 'newPassIcon')">
                                        <i id="newPassIcon" class="fa-regular fa-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" id="confirmPassField" name="confirm_password" class="form-control" placeholder="••••••••" required minlength="6">
                                    <span class="input-group-text bg-light toggle-password" onclick="toggleVisibility('confirmPassField', 'confirmPassIcon')">
                                        <i id="confirmPassIcon" class="fa-regular fa-eye text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-12 text-end pt-2">
                                <button type="submit" class="btn btn-success text-white btn-sm px-4 fw-medium rounded-pill shadow-sm">
                                    <i class="fa-solid fa-key me-2"></i>Update Access Key
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('profileFileField');
            const previewImg = document.getElementById('avatarPreview');
            const actionGroup = document.getElementById('uploadActionGroup');
            const cancelBtn = document.getElementById('cancelUploadBtn');
            const originalSrc = previewImg.src;

            // Instantly captures file picking events to render live image previews
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        actionGroup.classList.remove('d-none');
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Revert changes seamlessly if clicked cancel
            cancelBtn.addEventListener('click', function() {
                fileInput.value = '';
                previewImg.src = originalSrc;
                actionGroup.classList.add('d-none');
            });
        });

        // Simple visibility switcher utility for password fields
        function toggleVisibility(fieldId, iconId) {
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