<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Records - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8fafc; color: #334155; font-family: 'Inter', system-ui, sans-serif; }
        .card-custom { border: none; border-radius: 14px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01); background-color: #ffffff; overflow: hidden; }
        .card-header-custom { background-color: #ffffff !important; border-bottom: 1px solid #f1f5f9 !important; padding: 1.25rem 1.5rem !important; }
        .table > :not(caption) > * > * { padding: 1rem 1.25rem; border-bottom-color: #f1f5f9; }
        .table th { font-size: 0.785rem; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; color: #64748b; background-color: #f8fafc !important; }
        .badge-status { font-weight: 500; padding: 0.4em 0.75em; border-radius: 6px; }
        .scroll-panel { max-height: 290px; overflow-y: auto; }
        .list-group-item-custom { padding: 1rem 1.25rem; border-color: #f1f5f9; background: transparent; }
    </style>
</head>
<body>

    @include('partials.nav') 

    <div class="container-fluid my-5 px-lg-5">
        
        @if(session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <span class="fw-medium small">{{ session('toast.message') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            
            <div class="col-xl-7">
                
                <div class="card card-custom mb-4">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0 fw-bold text-dark">Active Tasks</h5>
                            <p class="text-muted small m-0 p-0 d-none d-sm-block">Manage upcoming workflow operations elements.</p>
                        </div>
                        <button class="btn btn-primary btn-sm px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addTaskModal">+ Add Task</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Task Details</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($active_tasks)): ?>
                                        <tr><td colspan="4" class="text-center text-muted py-5">No active pipeline elements found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($active_tasks as $task): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <div class="fw-bold text-dark mb-0.5"><?= htmlspecialchars($task['title']) ?></div>
                                                    <div class="text-muted small text-truncate" style="max-width: 260px;"><?= htmlspecialchars($task['description'] ?? 'No details specified.') ?></div>
                                                </td>
                                                <td>
                                                    <span class="small fw-medium text-secondary">
                                                        <?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'No Deadline' ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($task['status'] === 'In Progress'): ?>
                                                        <span class="badge bg-warning-subtle text-warning-emphasis badge-status">In Progress</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary-subtle text-secondary-emphasis badge-status">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <div class="btn-group gap-1">
                                                        <button class="btn btn-sm btn-light border edit-task-btn text-dark font-medium" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#editTaskModal" 
                                                            data-id="<?= $task['id'] ?>" 
                                                            data-title="<?= htmlspecialchars($task['title']) ?>" 
                                                            data-desc="<?= htmlspecialchars($task['description']) ?>" 
                                                            data-date="<?= $task['due_date'] ?>" 
                                                            data-status="<?= $task['status'] ?>">Edit</button>
                                                        <a href="{{ route('tasks.process.get', ['action' => 'delete', 'id' => $task['id']]) }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('Permanently drop this task?');">Delete</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card card-custom">
                    <div class="card-header-custom d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="m-0 fw-bold text-dark">Task Progress Notes</h5>
                            <p class="text-muted small m-0 p-0 d-none d-sm-block">Quick contextual memos and remarks assigned to your tasks.</p>
                        </div>
                        <button class="btn btn-dark btn-sm px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#addTaskNoteModal">+ Add Note</button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Related Task</th>
                                        <th>Note Content / Remarks</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($logs)): ?>
                                        <tr><td colspan="3" class="text-center text-muted py-5">No progress notes added to tasks yet.</td></tr>
                                    <?php else: ?>
                                        <?php foreach($logs as $log): ?>
                                            <tr>
                                                <td class="ps-4">
                                                    <span class="fw-bold text-dark small bg-light px-2 py-1 rounded border"><?= htmlspecialchars($log['task_title']) ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-secondary small text-wrap d-block" style="max-width: 340px;"><?= htmlspecialchars($log['remarks']) ?></span>
                                                </td>
                                                <td class="text-end pe-4">
                                                    <a href="{{ route('tasks.process.get', ['action' => 'delete_log', 'id' => $log['id']]) }}" class="btn btn-sm btn-link text-danger p-0 text-decoration-none fw-medium" onclick="return confirm('Delete this note?');">Remove Note</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-5">
                <div class="card card-custom border-top border-danger border-4 mb-4">
                    <div class="card-header-custom py-3">
                        <h6 class="m-0 fw-bold text-danger d-flex align-items-center">
                            <span class="me-2">⚠️</span> Missed Deadlines Track
                        </h6>
                    </div>
                    <div class="card-body p-0 scroll-panel">
                        <ul class="list-group list-group-flush mb-0">
                            <?php if(empty($missed_tasks)): ?>
                                <li class="list-group-item list-group-item-custom text-center text-muted py-4 small">Excellent configuration context! Zero breached limits detected.</li>
                            <?php else: ?>
                                <?php foreach($missed_tasks as $mt): ?>
                                    <li class="list-group-item list-group-item-custom d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-dark mb-0.5"><?= htmlspecialchars($mt['title']) ?></div>
                                            <span class="text-danger small fw-medium">Breached out: <?= date('M d, Y', strtotime($mt['due_date'])) ?></span>
                                        </div>
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-1 small fw-medium"><?= $mt['status'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>

                <div class="card card-custom border-top border-success border-4">
                    <div class="card-header-custom py-3">
                        <h6 class="m-0 fw-bold text-success d-flex align-items-center">
                            <span class="me-2">✅</span> Completed Records Vault
                        </h6>
                    </div>
                    <div class="card-body p-0 scroll-panel">
                        <ul class="list-group list-group-flush mb-0">
                            <?php if(empty($completed_tasks)): ?>
                                <li class="list-group-item list-group-item-custom text-center text-muted py-4 small">No historic task files detected inside completed track.</li>
                            <?php else: ?>
                                <?php foreach($completed_tasks as $ct): ?>
                                    <li class="list-group-item list-group-item-custom d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-bold text-decoration-line-through text-muted mb-0.5"><?= htmlspecialchars($ct['title']) ?></div>
                                            <span class="text-success small fw-medium">Closed out cleanly</span>
                                        </div>
                                        <span class="badge bg-success text-white rounded-pill px-3 py-1 small fw-medium">Done</span>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark">Create New Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="create">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Task Title</label>
                            <input type="text" name="title" class="form-control" required placeholder="Describe task action context...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Description</label>
                            <textarea name="description" class="form-control" rows="2" placeholder="Provide description supporting context..."></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Due Date</label>
                                <input type="date" name="due_date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Status</label>
                                <select name="status" class="form-select">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3"><button type="submit" class="btn btn-primary px-4 btn-sm fw-medium">Save Task</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark">Modify Task Entry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editTaskForm" action="{{ route('tasks.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="task_id" id="edit_task_id">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Task Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Due Date</label>
                                <input type="date" name="due_date" id="edit_due_date" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-secondary">Status</label>
                                <select name="status" id="edit_status" class="form-select">
                                    <option value="Pending">Pending</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3"><button type="submit" class="btn btn-success text-white px-4 btn-sm fw-medium">Save Changes</button></div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addTaskNoteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
                <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                    <h5 class="modal-title fw-bold text-dark">Add Note to Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tasks.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="create_log">
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Select Target Task</label>
                            <select name="task_title" class="form-select" required>
                                <option value="" disabled selected>Choose a task...</option>
                                <?php if(!empty($active_tasks)): ?>
                                    <?php foreach($active_tasks as $task): ?>
                                        <option value="<?= htmlspecialchars($task['title']) ?>"><?= htmlspecialchars($task['title']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary font-medium">Note / Comment Details</label>
                            <textarea name="remarks" class="form-control" rows="3" required placeholder="Type notes, progress updates or remarks here..."></textarea>
                            <input type="hidden" name="log_type" value="General Note">
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0 p-3">
                        <button type="submit" class="btn btn-dark px-4 btn-sm fw-medium">Attach Note</button>
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