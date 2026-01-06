// Doctor Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        new bootstrap.Tooltip(tooltip);
    });

    // Initialize popovers
    const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
    popovers.forEach(popover => {
        new bootstrap.Popover(popover);
    });

    // Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    if (backToTopButton) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.style.display = 'block';
            } else {
                backToTopButton.style.display = 'none';
            }
        });

        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Appointment Actions
    const appointmentActions = {
        // Confirm appointment
        confirm: async function(appointmentId) {
            try {
                const response = await fetch(`/doctor/appointments/${appointmentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: 'confirmed' })
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('success', 'Appointment confirmed successfully');
                    // Refresh the appointments list
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Failed to confirm appointment');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while confirming the appointment');
            }
        },

        // Cancel appointment
        cancel: async function(appointmentId) {
            if (!confirm('Are you sure you want to cancel this appointment?')) {
                return;
            }

            try {
                const response = await fetch(`/doctor/appointments/${appointmentId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('success', 'Appointment cancelled successfully');
                    // Refresh the appointments list
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Failed to cancel appointment');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while cancelling the appointment');
            }
        }
    };

    // Add event listeners for appointment actions
    document.querySelectorAll('.confirm-appointment').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.dataset.appointmentId;
            appointmentActions.confirm(appointmentId);
        });
    });

    document.querySelectorAll('.cancel-appointment').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const appointmentId = this.dataset.appointmentId;
            appointmentActions.cancel(appointmentId);
        });
    });

    // Alert helper function
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        
        const container = document.querySelector('.container');
        container.insertAdjacentElement('afterbegin', alertDiv);

        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
            alert.close();
        }, 5000);
    }

    // Schedule Management
    const scheduleManagement = {
        // Toggle availability
        toggleAvailability: async function(scheduleId) {
            try {
                const response = await fetch(`/doctor/schedules/${scheduleId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('success', 'Schedule availability updated successfully');
                    // Update the UI
                    const button = document.querySelector(`[data-schedule-id="${scheduleId}"]`);
                    if (button) {
                        button.classList.toggle('btn-success');
                        button.classList.toggle('btn-danger');
                        button.textContent = button.classList.contains('btn-success') ? 'Available' : 'Unavailable';
                    }
                } else {
                    showAlert('danger', data.message || 'Failed to update schedule availability');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while updating schedule availability');
            }
        }
    };

    // Add event listeners for schedule management
    document.querySelectorAll('.toggle-availability').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const scheduleId = this.dataset.scheduleId;
            scheduleManagement.toggleAvailability(scheduleId);
        });
    });

    // Patient Records Management
    const patientRecords = {
        // View medical record
        viewRecord: function(recordId) {
            window.location.href = `/doctor/medical-records/${recordId}`;
        },

        // Update medical record
        updateRecord: async function(recordId, formData) {
            try {
                const response = await fetch(`/doctor/medical-records/${recordId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(Object.fromEntries(formData))
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('success', 'Medical record updated successfully');
                    // Refresh the page or update the UI
                    location.reload();
                } else {
                    showAlert('danger', data.message || 'Failed to update medical record');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred while updating the medical record');
            }
        }
    };

    // Add event listeners for patient records
    document.querySelectorAll('.view-record').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const recordId = this.dataset.recordId;
            patientRecords.viewRecord(recordId);
        });
    });

    // Form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });

        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateInput(this);
            });

            input.addEventListener('blur', function() {
                validateInput(this);
            });
        });
    });

    // Input validation helper
    function validateInput(input) {
        if (input.checkValidity()) {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        } else {
            input.classList.remove('is-valid');
            input.classList.add('is-invalid');
        }
    }

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }

    // Initialize any date pickers
    const datePickers = document.querySelectorAll('input[type="date"]');
    datePickers.forEach(input => {
        // Set min date to today
        input.min = new Date().toISOString().split('T')[0];
    });

    // Initialize any time pickers
    const timePickers = document.querySelectorAll('input[type="time"]');
    timePickers.forEach(input => {
        // Add validation for business hours (e.g., 9 AM to 5 PM)
        input.addEventListener('change', function() {
            const time = this.value;
            const [hours, minutes] = time.split(':');
            const hour = parseInt(hours);

            if (hour < 9 || hour > 17) {
                this.setCustomValidity('Please select a time between 9 AM and 5 PM');
            } else {
                this.setCustomValidity('');
            }
        });
    });
}); 