<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f8fafc; 
            color: #334155; 
            font-family: 'Inter', system-ui, -apple-system, sans-serif; 
        }
        .card-custom { 
            border: 1px solid #e2e8f0; 
            border-radius: 16px; 
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02); 
            background-color: #ffffff; 
        }
        .chart-container { 
            position: relative; 
            width: 100%; 
            height: 250px; 
            margin: 0 auto; 
        }
        .insight-text {
            font-size: 0.9rem;
            color: #475569;
            line-height: 1.5;
        }
        .table-responsive {
            border-radius: 12px;
            overflow: hidden;
        }
        .badge-priority {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            font-weight: 600;
            border-radius: 6px;
        }
    </style>
</head>
<body>

    @include('partials.nav')

    <div class="container my-5" style="max-width: 1200px;">
        
        @if (session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <span class="fw-medium small"><i class="fa-solid fa-circle-info me-2"></i>{{ session('toast.message') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4 mb-5">
            
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Task Status</h5>
                        <p class="small text-muted mb-0">Current status distribution of system assignments.</p>
                    </div>
                    <div class="chart-container my-auto" style="max-width: 210px;">
                        <canvas id="taskDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="card card-custom p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-chart-bar text-warning me-2"></i>Task Priority</h5>
                        <p class="small text-muted mb-0">Breakdown of workflow instances ordered by severity.</p>
                    </div>
                    <div class="chart-container my-auto">
                        <canvas id="taskPriorityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <div class="card card-custom p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-circle-nodes text-info me-2"></i>Task Categories</h5>
                        <p class="small text-muted mb-0">Departmental allocation of operational records.</p>
                    </div>
                    <div class="chart-container my-auto" style="max-width: 240px;">
                        <canvas id="taskCategoryChart"></canvas>
                    </div>
                </div>
            </div>
            
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-list-check text-secondary me-2"></i>Master Task Ledger</h5>
                            <p class="small text-muted mb-0">Real-time status overview of trackable database logs.</p>
                        </div>
                        <a href="{{ route('tasks.index') ?? '#' }}" class="btn btn-sm btn-outline-primary px-3" style="border-radius: 8px;">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Manage Tasks
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-muted small uppercase">
                                <tr>
                                    <th class="ps-3" style="width: 40%;">Task Details</th>
                                    <th>Category</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th class="text-end pe-3">Date Created</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tasks ?? [] as $task)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="fw-semibold text-dark d-block mb-0">{{ $task->title ?? $task['title'] }}</span>
                                            <span class="text-muted small text-truncate d-block" style="max-width: 340px;">{{ $task->description ?? $task['description'] ?? 'No extra context available.' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-secondary small fw-medium"><i class="fa-regular fa-folder me-1"></i>{{ $task->category ?? $task['category'] ?? 'General' }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $priority = $task->priority ?? $task['priority'] ?? 'Low';
                                                $pClass = $priority == 'High' ? 'bg-danger-subtle text-danger' : ($priority == 'Medium' ? 'bg-warning-subtle text-warning-emphasis' : 'bg-primary-subtle text-primary');
                                            @endphp
                                            <span class="badge badge-priority {{ $pClass }}">{{ $priority }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $status = $task->status ?? $task['status'] ?? 'Pending';
                                                $sIcon = $status == 'Completed' ? 'fa-circle-check text-success' : ($status == 'In Progress' ? 'fa-spinner text-warning spin-slow' : 'fa-circle text-secondary');
                                            @endphp
                                            <span class="small fw-medium text-dark"><i class="fa-solid {{ $sIcon }} me-1"></i> {{ $status }}</span>
                                        </td>
                                        <td class="text-end pe-3 text-muted small">
                                            {{ isset($task->created_at) ? $task->created_at->format('M d, Y') : date('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fa-regular fa-folder-open display-6 d-block mb-3 text-placeholder text-muted" style="opacity: 0.4;"></i>
                                            <span class="small">No database operational task entries discovered.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. STATUS BREAKDOWN DOUGHNUT ---
            const ctxDistribution = document.getElementById('taskDistributionChart').getContext('2d');
            new Chart(ctxDistribution, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed'],
                    datasets: [{
                        data: [
                            {{ $statusCounts['Pending'] ?? 0 }},
                            {{ $statusCounts['In Progress'] ?? 0 }},
                            {{ $statusCounts['Completed'] ?? 0 }}
                        ],
                        backgroundColor: ['#64748b', '#f59e0b', '#10b981'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { boxWidth: 10, padding: 10, font: { family: 'Inter', size: 11, weight: 500 } } 
                        }
                    },
                    cutout: '75%'
                }
            });

            // --- 2. PRIORITY HORIZONTAL BAR CHART ---
            const ctxPriority = document.getElementById('taskPriorityChart').getContext('2d');
            new Chart(ctxPriority, {
                type: 'bar',
                data: {
                    labels: ['Low', 'Medium', 'High'],
                    datasets: [{
                        data: [
                            {{ $priorityCounts['Low'] ?? 0 }},
                            {{ $priorityCounts['Medium'] ?? 0 }},
                            {{ $priorityCounts['High'] ?? 0 }}
                        ],
                        backgroundColor: ['#3b82f6', '#f97316', '#ef4444'],
                        borderRadius: 6,
                        barThickness: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, beginAtZero: true, ticks: { precision: 0, font: { family: 'Inter', size: 10 } } },
                        y: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 11, weight: 600 } } }
                    }
                }
            });

            // --- 3. NEW FEATURE: CATEGORY RADAR CHART ---
            const ctxCategory = document.getElementById('taskCategoryChart').getContext('2d');
            new Chart(ctxCategory, {
                type: 'radar',
                data: {
                    labels: {!! isset($categoryCounts) ? json_encode(array_keys($categoryCounts)) : "['Dev', 'Ops', 'Design', 'Admin', 'Marketing']" !!},
                    datasets: [{
                        label: 'Allocated tasks',
                        data: {!! isset($categoryCounts) ? json_encode(array_values($categoryCounts)) : "[15, 10, 6, 12, 5]" !!},
                        backgroundColor: 'rgba(6, 182, 212, 0.15)',
                        borderColor: '#06b6d4',
                        borderWidth: 2,
                        pointBackgroundColor: '#06b6d4',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        r: {
                            grid: { color: '#e2e8f0' },
                            angleLines: { color: '#e2e8f0' },
                            suggestedMin: 0,
                            ticks: { display: false },
                            pointLabels: { font: { family: 'Inter', size: 10, weight: 500 } }
                        }
                    }
                }
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>