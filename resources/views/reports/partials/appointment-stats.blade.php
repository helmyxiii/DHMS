<div class="row">
    <div class="col-md-3">
        <div class="card bg-primary text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Total Appointments</h5>
                <h2 class="mb-0">{{ $report->data['total_appointments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Completed</h5>
                <h2 class="mb-0">{{ $report->data['completed_appointments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Cancelled</h5>
                <h2 class="mb-0">{{ $report->data['cancelled_appointments'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white mb-4">
            <div class="card-body">
                <h5 class="card-title">Completion Rate</h5>
                <h2 class="mb-0">
                    {{ number_format(($report->data['completed_appointments'] / $report->data['total_appointments']) * 100, 1) }}%
                </h2>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Appointments by Doctor</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Total Appointments</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($report->data['appointments_by_doctor'] as $doctor => $count)
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

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Appointment Distribution Chart</h5>
    </div>
    <div class="card-body">
        <canvas id="appointmentDistributionChart"></canvas>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('appointmentDistributionChart').getContext('2d');
    const data = @json($report->data['appointments_by_doctor']);
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: 'Appointments',
                data: Object.values(data),
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
</script>
@endpush 