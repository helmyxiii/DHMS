<div class="row">
    <div class="col-md-4">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Records</h5>
                <h2 class="mb-0">{{ $report->data['total_records'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Record Types</h5>
                <h2 class="mb-0">{{ count($report->data['records_by_type']) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Active Doctors</h5>
                <h2 class="mb-0">{{ count($report->data['records_by_doctor']) }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Records by Type</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->data['records_by_type'] as $type => $count)
                                <tr>
                                    <td>{{ ucwords(str_replace('_', ' ', $type)) }}</td>
                                    <td>{{ $count }}</td>
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
                <h5 class="mb-0">Records by Doctor</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Records</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report->data['records_by_doctor'] as $doctor => $count)
                                <tr>
                                    <td>{{ $doctor }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Record Types Distribution</h5>
            </div>
            <div class="card-body">
                <canvas id="recordTypesChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Doctor Activity</h5>
            </div>
            <div class="card-body">
                <canvas id="doctorActivityChart"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Record Types Chart
    const typesCtx = document.getElementById('recordTypesChart').getContext('2d');
    const typesData = @json($report->data['records_by_type']);
    
    new Chart(typesCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(typesData).map(type => ucwords(type.replace('_', ' '))),
            datasets: [{
                data: Object.values(typesData),
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

    // Doctor Activity Chart
    const doctorsCtx = document.getElementById('doctorActivityChart').getContext('2d');
    const doctorsData = @json($report->data['records_by_doctor']);
    
    new Chart(doctorsCtx, {
        type: 'bar',
        data: {
            labels: Object.keys(doctorsData),
            datasets: [{
                label: 'Records',
                data: Object.values(doctorsData),
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
});

function ucwords(str) {
    return str.replace(/\b\w/g, l => l.toUpperCase());
}
</script>
@endpush 