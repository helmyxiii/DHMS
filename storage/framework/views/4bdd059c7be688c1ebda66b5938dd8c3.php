<?php $__env->startSection('title', 'Appointment Details'); ?>

<?php $__env->startPush('styles'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/appoint.css')); ?>">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('body-class'); ?>
bg-dashboard
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<style>
    body.bg-dashboard {
        background: linear-gradient(to right, rgba(47, 102, 127, 0.7), rgba(0, 198, 255, 0.7)), url('/images/background.webp') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }
    .dashboard-bg {
        background: rgba(0, 48, 80, 0.85);
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgba(0,0,0,0.2);
        padding: 2rem 2rem 1rem 2rem;
        margin-bottom: 2rem;
    }
    .card-custom {
        background: rgba(255,255,255,0.08);
        color: #ffffff;
        border: none;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: transform 0.5s ease, opacity 0.5s ease;
        opacity: 0;
        transform: translateY(40px);
    }
    .card-custom.animate {
        opacity: 1;
        transform: translateY(0);
    }
    h1.fw-bold, h5.text-white, h5.card-title {
        color: #fff;
        text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .btn {
        font-weight: 500;
    }
    .list-group-item {
        background: rgba(255,255,255,0.05);
        color: #fff;
        border: none;
    }
</style>
<div class="container py-5 dashboard-bg">
    <div class="card card-custom animate border-0" style="background:rgba(255,255,255,0.97); color:#222;">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white rounded-top">
            <h5 class="card-title mb-0">Appointment Details</h5>
            <div>
                <?php if($appointment->canBeEdited()): ?>
                    <a href="<?php echo e(route('appointments.edit', $appointment)); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                <?php endif; ?>
                <?php if($appointment->canBeCancelled()): ?>
                    <form action="<?php echo e(route('appointments.destroy', $appointment)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Doctor</h6>
                    <p class="mb-0 fw-semibold text-dark"><?php echo e($appointment->doctor->name); ?></p>
                    <small class="text-muted">
                        <?php echo e($appointment->doctor->specialty?->name ?? 'N/A'); ?>

                    </small>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Patient</h6>
                    <p class="mb-0 fw-semibold text-dark"><?php echo e($appointment->patient->name); ?></p>
                    <small class="text-muted">
                        <?php echo e($appointment->patient->email); ?>

                    </small>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Appointment Type</h6>
                    <p class="mb-0 text-dark"><?php echo e(ucfirst($appointment->appointment_type)); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Status</h6>
                    <span class="badge bg-<?php echo e($appointment->status_color); ?> px-3 py-2 fs-6">
                        <?php echo e(ucfirst($appointment->status)); ?>

                    </span>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Date & Time</h6>
                    <p class="mb-0 text-dark"><?php echo e($appointment->appointment_date->format('M d, Y h:i A')); ?></p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-2">Created</h6>
                    <p class="mb-0 text-dark"><?php echo e($appointment->created_at->format('M d, Y h:i A')); ?></p>
                </div>
            </div>
            <div class="mb-4">
                <h6 class="text-muted mb-2">Reason for Visit</h6>
                <p class="mb-0 text-dark"><?php echo e($appointment->reason); ?></p>
            </div>
            <?php if($appointment->notes): ?>
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Additional Notes</h6>
                    <p class="mb-0"><?php echo e($appointment->notes); ?></p>
                </div>
            <?php endif; ?>
            <?php if($appointment->doctor_notes): ?>
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Doctor's Notes</h6>
                    <p class="mb-0"><?php echo e($appointment->doctor_notes); ?></p>
                </div>
            <?php endif; ?>
            <?php if($appointment->medicalRecord): ?>
                <div class="mb-4">
                    <h6 class="text-muted mb-2">Medical Record</h6>
                    <a href="<?php echo e(route('medical-records.show', $appointment->medicalRecord)); ?>" class="btn btn-info btn-sm">
                        <i class="bi bi-file-medical"></i> View Medical Record
                    </a>
                </div>
            <?php elseif($appointment->status === 'completed' && (auth()->user()->isDoctor() || auth()->user()->isAdmin())): ?>
                <div class="mb-4">
                    <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-plus-circle"></i> Create Medical Record
                    </a>
                </div>
            <?php endif; ?>
            <div class="d-flex justify-content-between mt-4">
                <a href="<?php echo e(route('appointments.index')); ?>" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
                <?php if($appointment->status === 'scheduled' && auth()->user()->isDoctor()): ?>
                    <form action="<?php echo e(route('appointments.update', $appointment)); ?>" method="POST" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Mark this appointment as completed?')">
                            <i class="bi bi-check-circle"></i> Mark as Completed
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dhms\resources\views/appointments/show.blade.php ENDPATH**/ ?>