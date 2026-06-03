<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Analytics - Executive Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --surface-color: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --text-muted: #64748b;
            
            --accent-primary: #2563eb;
            --state-pending: #64748b;
            --state-progress: #d97706;
            --state-missed: #dc2626;
            --state-completed: #059669;
        }

        body { 
            background-color: #f8fafc; 
            color: var(--text-main); 
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .workspace-hero-card {
            background: #0f172a;
            border: none;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .panel-glass-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            height: 100%;
        }

        .metric-card {
            background-color: var(--surface-color);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        }

        .section-header-pill {
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .scroll-panel-container {
            max-height: 420px; 
            overflow-y: auto;
            scrollbar-width: thin;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .timeline-item {
            position: relative;
            padding-left: 24px;
            border-left: 2px solid var(--border-color);
        }
        .timeline-item::after {
            content: '';
            position: absolute;
            left: -6px;
            top: 4px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: var(--text-muted);
        }
        .timeline-item.priority::after {
            background: var(--accent-primary);
        }

        .progress {
            border-radius: 6px;
            background-color: #f1f5f9;
        }
        .progress-bar {
            border-radius: 6px;
        }
    </style>
</head>
<body>

    @include('partials.nav') 

    <div class="container my-5 px-3 px-lg-4">
        
        <div class="workspace-hero-card d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-4">
            <div>
                <h2 class="fw-bold text-white mb-1">Operational Analytics</h2>
                <p class="text-white-50 small mb-0">Real-time status tracking metrics and task completions summary.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('tasks.index') }}" class="btn btn-light fw-semibold px-3 py-2 btn-sm" style="border-radius: 8px;">
                    <i class="fa-solid fa-tasks me-1"></i> Manage Workspace
                </a>
            </div>
        </div>

        <?php 
            $count_active = count($active_tasks ?? []);
            $count_overdue = count($missed_tasks ?? []);
            $count_completed = count($completed_tasks ?? []);
            $count_total = $count_active + $count_overdue + $count_completed;
            $rate_percentage = $count_total > 0 ? round(($count_completed / $count_total) * 100) : 0;
        ?>

        <div class="row g-4 mb-4">
            <div class="col-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-primary-subtle text-primary">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <span class="badge bg-light text-muted border">Active</span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= $count_active ?></h3>
                    <p class="text-muted small mb-0">Running allocations</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-success-subtle text-success">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success border-0">Archived</span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= $count_completed ?></h3>
                    <p class="text-muted small mb-0">Verified completions</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-danger-subtle text-danger">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger border-0">Exceptions</span>
                    </div>
                    <h3 class="fw-bold mb-1 text-danger"><?= $count_overdue ?></h3>
                    <p class="text-muted small mb-0">Overdue target tasks</p>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="metric-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="stat-icon bg-warning-subtle text-warning">
                            <i class="fa-solid fa-chart-pie"></i>
                        </div>
                        <span class="badge bg-light text-dark border">Efficiency</span>
                    </div>
                    <h3 class="fw-bold mb-1"><?= $rate_percentage ?>%</h3>
                    <p class="text-muted small mb-0">Task closure rate</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            
            <div class="col-lg-7">
                <div class="mb-3">
                    <span class="section-header-pill"><i class="fa-solid fa-gauge-high"></i> Resource Utilization Rate</span>
                </div>
                <div class="panel-glass-card p-4 mb-4">
                    <h6 class="fw-bold mb-3">General Completion Matrix</h6>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between text-muted small mb-2">
                            <span>Workspace Operations Completed</span>
                            <span class="fw-bold text-dark"><?= $count_completed ?> / <?= $count_total ?> Tasks</span>
                        </div>
                        <div class="progress" style="height: 12px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?= $rate_percentage ?>%;" aria-valuenow="<?= $rate_percentage ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <div class="border-top pt-3 mt-3">
                        <h6 class="fw-bold text-dark small mb-3">Status Matrix Breakdowns</h6>
                        <div class="row text-center g-2">
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <span class="text-muted d-block small mb-1">Total System Load</span>
                                    <span class="fw-bold text-dark"><?= $count_total ?></span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <span class="text-muted d-block small mb-1">In Progress</span>
                                    <span class="fw-bold text-warning">
                                        <?= count(array_filter($active_tasks ?? [], function($t) { return $t['status'] === 'In Progress'; })) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="bg-light p-3 rounded">
                                    <span class="text-muted d-block small mb-1">Pending Sync</span>
                                    <span class="fw-bold text-secondary">
                                        <?= count(array_filter($active_tasks ?? [], function($t) { return $t['status'] === 'Pending'; })) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <span class="section-header-pill"><i class="fa-solid fa-list-check"></i> High Priority Run Queue</span>
                </div>
                <div class="panel-glass-card">
                    <div class="scroll-panel-container p-3">
                        <?php if(empty($active_tasks)): ?>
                            <div class="text-center text-muted p-4 small">No active items processing in the queue.</div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-align-middle border-0 mb-0 small">
                                    <thead>
                                        <tr class="text-muted border-bottom" style="font-size: 0.75rem;">
                                            <th class="pb-2 border-0">TASK TARGET</th>
                                            <th class="pb-2 border-0">TIMELINE STATUS</th>
                                            <th class="pb-2 border-0 text-end">STATE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach(array_slice($active_tasks, 0, 5) as $task): ?>
                                            <tr class="border-bottom">
                                                <td class="py-3 border-0">
                                                    <span class="fw-bold text-dark d-block"><?= htmlspecialchars($task['title']) ?></span>
                                                    <span class="text-muted text-truncate d-inline-block" style="max-width: 250px; font-size: 0.75rem;"><?= htmlspecialchars($task['description'] ?? 'No context.') ?></span>
                                                </td>
                                                <td class="py-3 border-0">
                                                    <i class="fa-regular fa-clock text-primary me-1"></i> 
                                                    <?= $task['due_date'] ? date('M d, Y', strtotime($task['due_date'])) : 'Continuous' ?>
                                                </td>
                                                <td class="py-3 border-0 text-end">
                                                    <span class="badge <?= $task['status'] === 'In Progress' ? 'bg-warning-subtle text-warning' : 'bg-secondary-subtle text-secondary' ?> px-2 py-1">
                                                        <?= $task['status'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="mb-3">
                    <span class="section-header-pill"><i class="fa-solid fa-clock-rotate-left"></i> System Log Feed</span>
                </div>
                <div class="panel-glass-card p-4">
                    <div class="scroll-panel-container">
                        <?php if(empty($logs)): ?>
                            <div class="text-center text-muted py-5 small">No administrative notes recorded in sequence.</div>
                        <?php else: ?>
                            <div class="d-flex flex-column gap-3">
                                <?php foreach(array_slice($logs, 0, 8) as $log): ?>
                                    <div class="timeline-item priority pb-2">
                                        <div class="d-flex justify-content-between align-items-baseline mb-1">
                                            <span class="badge bg-light text-dark text-truncate fw-bold border" style="max-width: 180px; font-size: 0.65rem;">
                                                <i class="fa-solid fa-link me-1 text-muted"></i><?= htmlspecialchars($log['task_title']) ?>
                                            </span>
                                            <span class="text-muted" style="font-size: 0.65rem;">Verified Log</span>
                                        </div>
                                        <p class="text-secondary small mb-0 text-wrap"><?= htmlspecialchars($log['remarks']) ?></p>
                                    </div>
                                <?php endforeach; ?>
							</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>