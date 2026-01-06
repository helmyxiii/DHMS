<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Treatments</h5>
                <h2 class="mb-0">{{ $report->data['total_treatments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Treatment Types</h5>
                <h2 class="mb-0">{{ count($report->data['treatments_by_type']) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Success Rate</h5>
                <h2 class="mb-0">{{ number_format($report->data['success_rate'], 1) }}%</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Treatments by Type</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->data['treatments_by_type'] as $type => $count)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $type)) }}</td>
                                    <td>{{ $count }}</td>
                                    <td>
                                        {{ number_format(($count / $report->data['total_treatments']) * 100, 1) }}%
                                    </td>
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
                <h5 class="mb-0">Treatment Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="treatmentDistributionChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Treatment Overview</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>Success Rate</h6>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $report->data['success_rate'] >= 80 ? 'success' : ($report->data['success_rate'] >= 60 ? 'warning' : 'danger') }}" 
                                     role="progressbar" 
                                     style="width: {{ $report->data['success_rate'] }}%"
                                     aria-valuenow="{{ $report->data['success_rate'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    {{ number_format($report->data['success_rate'], 1) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6>Treatment Types Distribution</h6>
                            <canvas id="treatmentTypesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Treatment Distribution Chart
    const distributionCtx = document.getElementById('treatmentDistributionChart').getContext('2d');
    const distributionData = @json($report->data['treatments_by_type']);
    
    new Chart(distributionCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(distributionData).map(type => ucwords(type.replace('_', ' '))),
            datasets: [{
                label: 'Treatments',
                data: Object.values(distributionData),
                backgroundColor: '#4e73df',
                borderColor: '#4e73df',
                borderWidth: 1
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

    // Treatment Types Chart
    const typesCtx = document.getElementById('treatmentTypesChart').getContext('2d');
    
    new Chart(typesCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(distributionData).map(type => ucwords(type.replace('_', ' '))),
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
});

function ucwords(str) {
    return str.replace(/\b\w/g, l => l.toUpperCase());
}
</script>
@endpush 