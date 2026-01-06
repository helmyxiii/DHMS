@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Appointment Change/Cancellation Requests</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Patient</th>
                <th>Appointment</th>
                <th>Type</th>
                <th>Message</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($requests as $req)
            <tr>
                <td>{{ $req->patient->name ?? ($req->patient->user->name ?? 'Unknown') }}</td>
                <td>{{ $req->appointment->appointment_date ?? '' }}</td>
                <td>{{ ucfirst($req->type) }}</td>
                <td>{{ $req->message }}</td>
                <td>{{ ucfirst($req->status) }}</td>
                <td>
                    @if($req->status === 'pending')
                    <form method="POST" action="{{ route('doctor.changeRequests.process', $req) }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="action" value="approved">
                        <button class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('doctor.changeRequests.process', $req) }}" style="display:inline;">
                        @csrf
                        <input type="hidden" name="action" value="rejected">
                        <button class="btn btn-danger btn-sm">Reject</button>
                    </form>
                    @else
                        <span class="badge bg-{{ $req->status === 'approved' ? 'success' : 'danger' }}">{{ ucfirst($req->status) }}</span>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $requests->links() }}
</div>
@endsection 