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
    </style>
</head>
<body>

    @include('partials.nav')

    <div class="container my-5" style="max-width: 1140px;">
        
        @if (session('toast'))
            <div class="alert alert-{{ session('toast.type') }} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
                <span class="fw-medium small"><i class="fa-solid fa-circle-info me-2"></i>{{ session('toast.message') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-4">
            
            <div class="col-lg-4 col-md-6">
                <div class="card card-custom p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-chart-pie text-primary me-2"></i>Task Status</h5>
                        <p class="small text-muted mb-0">Current status distribution of system assignments.</p>
                    </div>
                    <div class="chart-container my-auto" style="max-width: 230px;">
                        <canvas id="taskDistributionChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-6">
                <div class="card card-custom p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-solid fa-chart-bar text-warning me-2"></i>Task Priority</h5>
                        <p class="small text-muted mb-0">Breakdown of workflow instances ordered by severity levels.</p>
                    </div>
                    <div class="chart-container my-auto">
                        <canvas id="taskPriorityChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-12">
                <div class="card card-custom p-4 d-flex flex-column h-100">
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-1"><i class="fa-regular fa-lightbulb text-success me-2"></i>Quick Overview</h5>
                        <p class="small text-muted mb-0">Automated system diagnostics summary.</p>
                    </div>
                    
                    <div class="mt-2 flex-grow-1 d-flex flex-column justify-content-center">
                        <div class="p-3 bg-light rounded-3 mb-3 border-start border-primary border-3">
                            <span class="fw-semibold text-dark d-block small mb-1">Operational Progress</span>
                            <p class="insight-text mb-0">Track and manage milestones. Use the status breakdown wheel to identify project bottlenecks early.</p>
                        </div>

                        <div class="p-3 bg-light rounded-3 border-start border-warning border-3">
                            <span class="fw-semibold text-dark d-block small mb-1">Resource Allocation</span>
                            <p class="insight-text mb-0">High-priority records require immediate oversight. Ensure backlogs are reassigned to mitigate operational delays.</p>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- 1. SIMPLIFIED STATUS BREAKDOWN DOUGHNUT ---
            const ctxDistribution = document.getElementById('taskDistributionChart').getContext('2d');
            const distributionData = [
                {{ $statusCounts['Pending'] ?? 0 }},
                {{ $statusCounts['In Progress'] ?? 0 }},
                {{ $statusCounts['Completed'] ?? 0 }}
            ];

            new Chart(ctxDistribution, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'In Progress', 'Completed'],
                    datasets: [{
                        data: distributionData,
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
                            labels: { boxWidth: 12, padding: 15, font: { family: 'Inter', size: 12, weight: 500 } } 
                        }
                    },
                    cutout: '75%'
                }
            });

            // --- 2. PRIORITY BAR GRAPH FROM TASKS DATA ---
            const ctxPriority = document.getElementById('taskPriorityChart').getContext('2d');
            
            new Chart(ctxPriority, {
                type: 'bar',
                data: {
                    labels: ['Low', 'Medium', 'High'],
                    datasets: [{
                        label: 'Tasks',
                        data: [
                            {{ $priorityCounts['Low'] ?? 10 }},
                            {{ $priorityCounts['Medium'] ?? 18 }},
                            {{ $priorityCounts['High'] ?? 5 }}
                        ],
                        backgroundColor: ['#3b82f6', '#f97316', '#ef4444'],
                        borderRadius: 8,
                        barThickness: 24
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: 'y', // Makes the bar chart horizontal for a clean look
                    plugins: { 
                        legend: { display: false } 
                    },
                    scales: {
                        x: { 
                            grid: { display: false }, 
                            beginAtZero: true, 
                            ticks: { precision: 0, font: { family: 'Inter', size: 11 } } 
                        },
                        y: { 
                            grid: { display: false }, 
                            ticks: { font: { family: 'Inter', size: 12, weight: 600 } } 
                        }
                    }
                }
            });

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>