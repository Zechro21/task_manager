<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Records - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8fafc; 
            color: #334155; 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
        }
        
        /* Mobile-first structural layout optimizations */
        .workspace-card {
            border: none;
            border-radius: 16px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
            margin-bottom: 1rem;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .workspace-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.04);
        }

        /* Task specific side status accents */
        .accent-pending { border-left: 5px solid #64748b; }
        .accent-progress { border-left: 5px solid #f59e0b; }
        .accent-missed { border-left: 5px solid #ef4444; }
        .accent-completed { border-left: 5px solid #10b981; }

        .card-header-clean {
            background-color: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.25rem 1rem;
        }

        /* Responsive custom card item wrapper */
        .task-item-body {
            padding: 1.25rem 1rem;
        }

        .mobile-action-btn {
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 8px;
            font-weight: 500;
        }

        .scroll-panel {
            max-height: 380px;
            overflow-y: auto;
        }

        /* Custom subtle badge layout styles */
        .badge-pill-custom {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
        }
    </style>
</head>
<body>

    @include('partials.nav') 

    <div class="container my-4 px-3 px-md-4">
        
        @if(session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <span class="fw-medium small"><i class="fa-solid fa-circle-info me-2"></i>{{ session('toast.message') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold text-dark mb-1">My Workspace</h4>
                <p class="text-muted small mb-0">Track pipeline records & project updates</p>
            </div>
            <button class="btn btn-primary px-3 py-2 fw-medium shadow-sm d-flex align-items-center gap-2" style="border-radius: 10px;" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fa-solid fa-plus"></i> <span class="d-none d-sm-inline">Add Task</span>
            </button>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-7">
                
                <div class="d-flex align-items-center gap-2 mb-3 px-1">
                    <i class="fa-solid fa-layer-group text-secondary small"></i>
                    <span class="text-uppercase tracking-wider font-semibold small text-muted fw-bold">Active Elements</span>
                </div>

                <?php if(empty($active_tasks)): ?>
                    <div class="workspace-card p-5 text-center text-muted">
                        <i class="fa-regular fa-folder-open fa-2x mb-3 text-black-50"></i>
                        <p class="small mb-0">No active operational tasks found inside your pipeline view.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($active_tasks as $task): 
                        $statusAccent = ($task['status'] === 'In Progress') ? 'accent-progress' : 'accent-pending';
                    ?>
                        <div class="workspace-card <?= $statusAccent ?>">
                            <div class="task-item-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div style="max-width: 75%;">
                                        <h6 class="fw-bold text-dark mb-1 text-wrap"><?= htmlspecialchars($task['title']) ?></h6>
                                        <p class="text-muted small mb-2 text-wrap"><?= htmlspecialchars($task['description'] ?? 'No descriptions provided.') ?></p>
                                    </div>
                                    
                                    <?php if($task['status'] === 'In Progress'): ?>
                                        <span class="badge bg-warning-subtle text-warning-emphasis badge-pill-custom">In Progress</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary-emphasis badge-pill-custom">Pending</span>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top border-light">
                                    <div class="d-flex align-items-center text-secondary small">
                                        <i class="fa-regular fa-calendar me-1.5 text-muted small"></i>
                                        <span class="fw-medium text-xs">
                                            <?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'No Deadline Target' ?>
                                        </span>
                                    </div>
                                    
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-light border mobile-action-btn edit-task-btn text-dark shadow-xs" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTaskModal" 
                                                data-id="<?= $task['id'] ?>" 
                                                data-title="<?= htmlspecialchars($task['title']) ?>" 
                                                data-desc="<?= htmlspecialchars($task['description']) ?>" 
                                                data-date="<?= $task['due_date'] ?>" 
                                                data-status="<?= $task['status'] ?>"><i class="fa-regular fa-pen-to-square me-1"></i>Edit</button>
                                        <a href="{{ route('tasks.process.get', ['action' => 'delete', 'id' => $task['id']]) }}" class="btn btn-outline-danger border-0 mobile-action-btn" onclick="return confirm('Permanently drop this active task tracking record?');"><i class="fa-regular fa-trash-can"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-4 mb-3 px-1">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-regular fa-comment-dots text-secondary small"></i>
                        <span class="text-uppercase tracking-wider small text-muted fw-bold">Progress Context Memos</span>
                    </div>
                    <button class="btn btn-dark btn-sm px-2.5 py-1 small fw-medium" style="border-radius: 8px;" data-bs-toggle="modal" data-bs-target="#addTaskNoteModal">+ Add Memo</button>
                </div>

                <div class="workspace-card p-0">
                    <div class="scroll-panel">
                        <?php if(empty($logs)): ?>
                            <div class="p-4 text-center text-muted small">No contextual task updates or history logged yet.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($logs as $log): ?>
                                    <div class="list-group-item p-3 border-light bg-transparent">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div>
                                                <span class="badge bg-light text-dark border small mb-2 d-inline-block text-truncate" style="max-width: 180px;"><i class="fa-solid fa-link me-1 text-muted"></i><?= htmlspecialchars($log['task_title']) ?></span>
                                                <p class="text-secondary small mb-0 text-wrap"><?= htmlspecialchars($log['remarks']) ?></p>
                                            </div>
                                            <a href="{{ route('tasks.process.get', ['action' => 'delete_log', 'id' => $log['id']]) }}" class="text-danger small text-decoration-none fw-medium" onclick="return confirm('Delete this custom note component?');"><i class="fa-regular fa-trash-can"></i></a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                
                <div class="d-flex align-items-center gap-2 mb-3 px-1">
                    <i class="fa-solid fa-triangle-exclamation text-danger small"></i>
                    <span class="text-uppercase tracking-wider small text-danger fw-bold">Breached Deadlines</span>
                </div>

                <div class="workspace-card accent-missed p-0 mb-4">
                    <div class="scroll-panel">
                        <?php if(empty($missed_tasks)): ?>
                            <div class="p-4 text-center text-muted small"><i class="fa-solid fa-circle-check text-success me-2"></i>Excellent workflow health! Zero delayed tasks.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($missed_tasks as $mt): ?>
                                    <div class="list-group-item p-3 bg-transparent border-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 small text-wrap"><?= htmlspecialchars($mt['title']) ?></h6>
                                            <span class="text-danger fw-medium style-xs" style="font-size: 0.8rem;"><i class="fa-solid fa-clock-rotate-left me-1"></i>Expired: <?= date('M d, Y', strtotime($mt['due_date'])) ?></span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger badge-pill-custom"><?= $mt['status'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-2 mb-3 px-1">
                    <i class="fa-regular fa-circle-check text-success small"></i>
                    <span class="text-uppercase tracking-wider small text-success fw-bold">Completed Vault</span>
                </div>

                <div class="workspace-card accent-completed p-0">
                    <div class="scroll-panel">
                        <?php if(empty($completed_tasks)): ?>
                            <div class="p-4 text-center text-muted small">No archives detected inside the completion logs registry.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($completed_tasks as $ct): ?>
                                    <div class="list-group-item p-3 bg-transparent border-light d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="fw-bold text-muted text-decoration-line-through mb-0 small text-wrap"><?= htmlspecialchars($ct['title']) ?></h6>
                                            <span class="text-success fw-medium style-xs d-block" style="font-size: 0.8rem;"><i class="fa-solid fa-check-double me-1"></i>Closed out cleanly</span>
                                        </div>
                                        <span class="badge bg-success text-white badge-pill-custom">Done</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-solid fa-circle-plus text-primary me-2"></i>Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="create">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Task Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="Type task title here..." style="border-radius: 10px; padding: 0.6rem 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Description Context</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Provide extra notes or assignment context details..." style="border-radius: 10px;"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Due Date</label>
                                <input type="date" name="due_date" class="form-control" style="border-radius: 10px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Status State</label>
                                <select name="status" class="form-select" style="border-radius: 10px;">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light fw-medium border px-3 btn-sm" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 btn-sm fw-medium shadow-sm" style="border-radius: 8px;">Save Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-regular fa-pen-to-square text-success me-2"></i>Modify Task Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTaskForm" action="{{ route('tasks.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="task_id" id="edit_task_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Task Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required style="border-radius: 10px; padding: 0.6rem 0.75rem;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" style="border-radius: 10px;"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Due Date</label>
                                <input type="date" name="due_date" id="edit_due_date" class="form-control" style="border-radius: 10px;">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Status</label>
                                <select name="status" id="edit_status" class="form-select" style="border-radius: 10px;">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light fw-medium border px-3 btn-sm" style="border-radius: 8px;" data-bs-dismiss="modal">Dismiss</button>
                        <button type="submit" class="btn btn-success text-white px-4 btn-sm fw-medium shadow-sm" style="border-radius: 8px;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTaskNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered px-3">
            <div class="modal-content border-0 shadow-lg" style="border-radius:20px;">
                <div class="modal-header border-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="fa-regular fa-comment-dots text-dark me-2"></i>Add Note to Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="create_log">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Select Target Task</label>
                            <select name="task_title" class="form-select" required style="border-radius: 10px;">
                                <option value="" disabled selected>Choose a task pipeline reference...</option>
                                <?php if(!empty($active_tasks)): ?>
                                    <?php foreach($active_tasks as $task): ?>
                                        <option value="<?= htmlspecialchars($task['title']) ?>"><?= htmlspecialchars($task['title']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary font-medium">Note / Comment Details</label>
                            <textarea name="remarks" class="form-control" rows="3" required placeholder="Type operational status notes, delays, or quick logs here..." style="border-radius: 10px;"></textarea>
                            <input type="hidden" name="log_type" value="General Note">
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3" style="border-radius: 0 0 20px 20px;">
                        <button type="button" class="btn btn-light fw-medium border px-3 btn-sm" style="border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-dark px-4 btn-sm fw-medium" style="border-radius: 8px;">Attach Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const editTaskButtons = document.querySelectorAll('.edit-task-btn');
            editTaskButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('edit_task_id').value = this.dataset.id;
                    document.getElementById('edit_title').value = this.dataset.title;
                    document.getElementById('edit_description').value = this.dataset.desc;
                    document.getElementById('edit_due_date').value = this.dataset.date;
                    document.getElementById('edit_status').value = this.dataset.status;
                });
            });
        });
    </script>
</body>
</html>