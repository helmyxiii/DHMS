<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Roles</h5>
                <h2 class="mb-0">{{ $report->data['total_roles'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="mb-0">{{ array_sum($report->data['users_by_role']) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Active Users</h5>
                <h2 class="mb-0">{{ array_sum($report->data['active_users_by_role']) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Users by Role</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Total Users</th>
                                <th>Active Users</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->data['users_by_role'] as $role => $count)
                                <tr>
                                    <td>{{ $role }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ $report->data['active_users_by_role'][$role] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Role Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="roleDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">User Activity by Role</h5>
            </div>
            <div class="card-body">
                <canvas id="userActivityChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Role Distribution Chart
    const distributionCtx = document.getElementById('roleDistributionChart').getContext('2d');
    const distributionData = @json($report->data['users_by_role']);
    
    new Chart(distributionCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(distributionData),
            datasets: [{
                data: Object.values(distributionData),
                backgroundColor: [
                    '#4e73df',
                    '#1cc88a',
                    '#36b9cc',
                    '#f6c23e',
                    '#e74a3b'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // User Activity Chart
    const activityCtx = document.getElementById('userActivityChart').getContext('2d');
    const roles = Object.keys(@json($report->data['users_by_role']));
    const totalUsers = Object.values(@json($report->data['users_by_role']));
    const activeUsers = Object.values(@json($report->data['active_users_by_role']));
    
    new Chart(activityCtx, {
        type: 'bar',
        data: {
            labels: roles,
            datasets: [
                {
                    label: 'Total Users',
                    data: totalUsers,
                    backgroundColor: '#4e73df',
                    borderColor: '#4e73df',
                    borderWidth: 1
                },
                {
                    label: 'Active Users',
                    data: activeUsers,
                    backgroundColor: '#1cc88a',
                    borderColor: '#1cc88a',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
});
</script>
@endpush 