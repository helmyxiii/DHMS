<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Patients</h5>
                <h2 class="mb-0">{{ $report->data['total_patients'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">New Patients</h5>
                <h2 class="mb-0">{{ $report->data['new_patients'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Active Patients</h5>
                <h2 class="mb-0">{{ $report->data['active_patients'] }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Patient Activity</h5>
            </div>
            <div class="card-body">
                <canvas id="patientActivityChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Patient Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="patientDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Patient Overview</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Metric</th>
                                <th>Value</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Patients</td>
                                <td>{{ $report->data['total_patients'] }}</td>
                                <td>100%</td>
                            </tr>
                            <tr>
                                <td>New Patients</td>
                                <td>{{ $report->data['new_patients'] }}</td>
                                <td>
                                    {{ number_format(($report->data['new_patients'] / $report->data['total_patients']) * 100, 1) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Active Patients</td>
                                <td>{{ $report->data['active_patients'] }}</td>
                                <td>
                                    {{ number_format(($report->data['active_patients'] / $report->data['total_patients']) * 100, 1) }}%
                                </td>
                            </tr>
                            <tr>
                                <td>Inactive Patients</td>
                                <td>{{ $report->data['total_patients'] - $report->data['active_patients'] }}</td>
                                <td>
                                    {{ number_format((($report->data['total_patients'] - $report->data['active_patients']) / $report->data['total_patients']) * 100, 1) }}%
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
    // Patient Activity Chart
    const activityCtx = document.getElementById('patientActivityChart').getContext('2d');
    
    new Chart(activityCtx, {
        type: 'line',
        data: {
            labels: ['Total', 'New', 'Active', 'Inactive'],
            datasets: [{
                label: 'Patient Count',
                data: [
                    {{ $report->data['total_patients'] }},
                    {{ $report->data['new_patients'] }},
                    {{ $report->data['active_patients'] }},
                    {{ $report->data['total_patients'] - $report->data['active_patients'] }}
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

    // Patient Distribution Chart
    const distributionCtx = document.getElementById('patientDistributionChart').getContext('2d');
    
    new Chart(distributionCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [
                    {{ $report->data['active_patients'] }},
                    {{ $report->data['total_patients'] - $report->data['active_patients'] }}
                ],
                backgroundColor: [
                    '#1cc88a',
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
});
</script>
@endpush 