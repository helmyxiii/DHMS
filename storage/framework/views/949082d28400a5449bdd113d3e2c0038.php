

<?php $__env->startSection('title', 'Edit Doctor - Zawar'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <h1 class="fw-bold text-white mb-4">Edit Doctor</h1>
    <div class="card card-custom animate mb-4">
        <div class="card-body">
            <form method="POST" action="<?php echo e(route('admin.doctors.update', $doctor)); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" placeholder="Name" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" placeholder="Email" required>
                    </div>
                    <div class="col-md-4 mb-2">
                        <select name="specialties[]" class="form-control" multiple required>
                            <?php $__currentLoopData = $specialties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $specialty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($specialty->id); ?>" <?php echo e(in_array($specialty->id, $doctorSpecialties) ? 'selected' : ''); ?>><?php echo e($specialty->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <small class="text-white">Hold Ctrl (Windows) or Cmd (Mac) to select multiple specialties.</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-2">Update Doctor</button>
                <a href="<?php echo e(route('admin.doctors')); ?>" class="btn btn-secondary mt-2">Cancel</a>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\dhms\resources\views/admin/doctors/edit.blade.php ENDPATH**/ ?>