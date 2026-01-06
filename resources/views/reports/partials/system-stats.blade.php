<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <h2 class="mb-0">{{ $report->data['total_users'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Appointments</h5>
                <h2 class="mb-0">{{ $report->data['total_appointments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Medical Records</h5>
                <h2 class="mb-0">{{ $report->data['total_medical_records'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">System Health</h5>
                <h2 class="mb-0">
                    {{ number_format(100 - $report->data['system_health']['memory_usage'], 1) }}%
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Resources</h5>
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <h6>Disk Usage</h6>
                    <div class="progress">
                        <div class="progress-bar bg-{{ $report->data['system_health']['disk_usage'] > 80 ? 'danger' : 'success' }}" 
                             role="progressbar" 
                             style="width: {{ $report->data['system_health']['disk_usage'] }}%"
                             aria-valuenow="{{ $report->data['system_health']['disk_usage'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($report->data['system_health']['disk_usage'], 1) }}%
                        </div>
                    </div>
                </div>
                <div>
                    <h6>Memory Usage</h6>
                    <div class="progress">
                        <div class="progress-bar bg-{{ $report->data['system_health']['memory_usage'] > 80 ? 'danger' : 'success' }}" 
                             role="progressbar" 
                             style="width: {{ $report->data['system_health']['memory_usage'] }}%"
                             aria-valuenow="{{ $report->data['system_health']['memory_usage'] }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            {{ number_format($report->data['system_health']['memory_usage'], 1) }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Activity</h5>
            </div>
            <div class="card-body">
                <canvas id="systemActivityChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Users</td>
                                <td>{{ $report->data['total_users'] }}</td>
                                <td>
                                    <span class="badge bg-success">Healthy</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Appointments</td>
                                <td>{{ $report->data['total_appointments'] }}</td>
                                <td>
                                    <span class="badge bg-success">Healthy</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Medical Records</td>
                                <td>{{ $report->data['total_medical_records'] }}</td>
                                <td>
                                    <span class="badge bg-success">Healthy</span>
                                </td>
                            </tr>
                            <tr>
                                <td>Disk Usage</td>
                                <td>{{ number_format($report->data['system_health']['disk_usage'], 1) }}%</td>
                                <td>
                                    <span class="badge bg-{{ $report->data['system_health']['disk_usage'] > 80 ? 'danger' : 'success' }}">
                                        {{ $report->data['system_health']['disk_usage'] > 80 ? 'Warning' : 'Healthy' }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>Memory Usage</td>
                                <td>{{ number_format($report->data['system_health']['memory_usage'], 1) }}%</td>
                                <td>
                                    <span class="badge bg-{{ $report->data['system_health']['memory_usage'] > 80 ? 'danger' : 'success' }}">
                                        {{ $report->data['system_health']['memory_usage'] > 80 ? 'Warning' : 'Healthy' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('systemActivityChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Users', 'Appointments', 'Medical Records'],
            datasets: [{
                label: 'System Metrics',
                data: [
                    {{ $report->data['total_users'] }},
                    {{ $report->data['total_appointments'] }},
                    {{ $report->data['total_medical_records'] }}
                ],
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                tension: 0.4,
                fill: true
            }]
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
                    display: false
                }
            }
        }
    });
});
</script>
@endpush 