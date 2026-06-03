<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Analytics - Platform Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --glass-bg: #ffffff;
            --surface-color: #f8fafc;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            
            /* Strict Brand Tokens */
            --accent-primary: #3b82f6;
            --state-pending: #475569;
            --state-progress: #f59e0b;
            --state-missed: #ef4444;
            --state-completed: #10b981;
        }

        body { 
            background-color: #f1f5f9; 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        /* Hero Wrapper Panel */
        .workspace-hero-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.1);
        }

        /* Unified Modern Dashboard Panels */
        .panel-glass-card {
            background-color: var(--glass-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.25s ease;
            overflow: hidden;
        }

        .interactive-row-card {
            border-left: 4px solid transparent;
            transition: all 0.2s ease;
        }
        .interactive-row-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08);
            background-color: #fafafa;
        }

        /* Rigid Operational Context Colors */
        .interactive-row-card.accent-pending { border-left-color: var(--state-pending); }
        .interactive-row-card.accent-progress { border-left-color: var(--state-progress); }
        .interactive-row-card.accent-missed { border-left-color: var(--state-missed); }
        .interactive-row-card.accent-completed { border-left-color: var(--state-completed); }

        .section-header-pill {
            font-size: 0.725rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Custom Scroll Areas */
        .scroll-panel-container {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        /* Smooth UI Pill Elements */
        .badge-premium {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 0.8rem;
            border-radius: 8px;
        }

        .action-icon-btn {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-primary-premium {
            background-color: var(--accent-primary);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            padding: 0.6rem 1.25rem;
            transition: all 0.2s ease;
        }
        .btn-primary-premium:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>

    @include('partials.nav') 

    <div class="container my-5 px-3 px-lg-4">
        
        @if(session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 14px;">
                <div class="d-flex align-items-center gap-2 small">
                    <i class="fa-solid fa-bolt-lightning"></i>
                    <span class="fw-semibold">{{ session('toast.message') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="workspace-hero-card d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-4">
            <div>
                <h2 class="fw-bold text-white mb-1">My Workspace</h2>
                <p class="text-white-50 small mb-0">Operational workflow control, task processing & pipeline telemetry.</p>
            </div>
            <button class="btn btn-primary-premium d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                <i class="fa-solid fa-plus-square"></i> <span>Initialize Task</span>
            </button>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-7">
                <div class="mb-3">
                    <span class="section-header-pill"><i class="fa-solid fa-chart-simple"></i> Live Pipeline Logs</span>
                </div>

                <?php if(empty($active_tasks)): ?>
                    <div class="panel-glass-card p-5 text-center text-muted border-dashed">
                        <div class="py-4">
                            <i class="fa-solid fa-cubes-stacked fa-2x mb-3 text-light text-secondary"></i>
                            <p class="small fw-medium mb-0">No operational records identified inside this execution channel.</p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach($active_tasks as $task): 
                            $statusAccent = ($task['status'] === 'In Progress') ? 'accent-progress' : 'accent-pending';
                        ?>
                            <div class="panel-glass-card interactive-row-card <?= $statusAccent ?> p-4">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div class="w-75">
                                        <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($task['title']) ?></h6>
                                        <p class="text-muted small mb-0 text-wrap"><?= htmlspecialchars($task['description'] ?? 'Empty description field context.') ?></p>
                                    </div>
                                    <div>
                                        <?php if($task['status'] === 'In Progress'): ?>
                                            <span class="badge bg-warning-subtle text-warning badge-premium">In Progress</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary-subtle text-secondary badge-premium">Pending</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top border-light-subtle">
                                    <div class="small text-muted d-flex align-items-center gap-2">
                                        <i class="fa-regular fa-clock text-primary"></i>
                                        <span class="fw-semibold">
                                            <?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'No Lifespan Configured' ?>
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-light border action-icon-btn edit-task-btn text-dark" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editTaskModal" 
                                                data-id="<?= $task['id'] ?>" 
                                                data-title="<?= htmlspecialchars($task['title']) ?>" 
                                                data-desc="<?= htmlspecialchars($task['description']) ?>" 
                                                data-date="<?= $task['due_date'] ?>" 
                                                data-status="<?= $task['status'] ?>"
                                                title="Edit Entry">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </button>
                                        <a href="{{ route('tasks.process.get', ['action' => 'delete', 'id' => $task['id']]) }}" 
                                           class="btn btn-outline-danger action-icon-btn border-0" 
                                           onclick="return confirm('Permanently drop this active task tracking record?');"
                                           title="Drop Entry">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="d-flex justify-content-between align-items-center mt-5 mb-3">
                    <span class="section-header-pill"><i class="fa-regular fa-message"></i> System Context Notes</span>
                    <button class="btn btn-outline-dark btn-sm fw-semibold" style="border-radius: 8px; font-size:0.8rem;" data-bs-toggle="modal" data-bs-target="#addTaskNoteModal">+ Append Note</button>
                </div>

                <div class="panel-glass-card">
                    <div class="scroll-panel-container">
                        <?php if(empty($logs)): ?>
                            <div class="p-4 text-center text-muted small fw-medium">No contextual updates cataloged on this server node.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($logs as $log): ?>
                                    <div class="list-group-item p-3 border-light bg-transparent">
                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <div class="w-100">
                                                <span class="badge bg-dark-subtle text-dark border-0 small mb-2 text-truncate" style="max-width: 220px; font-size: 0.7rem;"><i class="fa-solid fa-code-fork me-1"></i><?= htmlspecialchars($log['task_title']) ?></span>
                                                <p class="text-secondary small mb-0"><?= htmlspecialchars($log['remarks']) ?></p>
                                            </div>
                                            <a href="{{ route('tasks.process.get', ['action' => 'delete_log', 'id' => $log['id']]) }}" class="text-danger p-1" onclick="return confirm('Delete this custom note component?');"><i class="fa-regular fa-trash-can small"></i></a>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                
                <div class="mb-3">
                    <span class="section-header-pill text-danger"><i class="fa-solid fa-triangle-exclamation"></i> Overdue Target Disruptions</span>
                </div>

                <div class="panel-glass-card interactive-row-card accent-missed mb-4">
                    <div class="scroll-panel-container">
                        <?php if(empty($missed_tasks)): ?>
                            <div class="p-4 text-center text-success small fw-semibold"><i class="fa-solid fa-shield-halved me-2"></i>Infrastructure stable. Zero exceptions.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($missed_tasks as $mt): ?>
                                    <div class="list-group-item p-3 bg-transparent border-light d-flex justify-content-between align-items-center gap-2">
                                        <div class="w-70">
                                            <h6 class="fw-bold text-dark mb-1 small text-wrap"><?= htmlspecialchars($mt['title']) ?></h6>
                                            <span class="text-danger fw-bold d-block" style="font-size: 0.75rem;"><i class="fa-solid fa-history me-1"></i>Breached: <?= date('M d, Y', strtotime($mt['due_date'])) ?></span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger badge-premium"><?= $mt['status'] ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="section-header-pill text-success"><i class="fa-solid fa-box-archive"></i> Completion Records Registry</span>
                </div>

                <div class="panel-glass-card interactive-row-card accent-completed">
                    <div class="scroll-panel-container">
                        <?php if(empty($completed_tasks)): ?>
                            <div class="p-4 text-center text-muted small fw-medium">Vault clear. No historical entries tracked yet.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach($completed_tasks as $ct): ?>
                                    <div class="list-group-item p-3 bg-transparent border-light d-flex justify-content-between align-items-center gap-2">
                                        <div class="w-70">
                                            <h6 class="fw-bold text-muted text-decoration-line-through mb-1 small text-wrap"><?= htmlspecialchars($ct['title']) ?></h6>
                                            <span class="text-success fw-semibold d-block" style="font-size: 0.75rem;"><i class="fa-solid fa-circle-check me-1"></i>Terminated Cleanly</span>
                                        </div>
                                        <span class="badge bg-success text-white badge-premium">Verified</span>
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