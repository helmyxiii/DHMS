// Appointments JavaScript

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

    // Form Validation
    const appointmentForm = document.getElementById('appointmentForm');
    if (appointmentForm) {
        appointmentForm.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });

        // Real-time validation
        const inputs = appointmentForm.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
        });
    }

    // Dynamic Time Slot Loading
    const doctorSelect = document.getElementById('doctor');
    const dateInput = document.getElementById('appointment_date');
    const timeSlotSelect = document.getElementById('time_slot');

    if (doctorSelect && dateInput && timeSlotSelect) {
        async function fetchTimeSlots(doctorId, date) {
            try {
                const response = await fetch(`/api/doctors/${doctorId}/available-slots?date=${date}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                
                // Clear existing options
                timeSlotSelect.innerHTML = '<option value="">Select a time slot</option>';
                
                if (data.slots && data.slots.length > 0) {
                    // Add new options
                    data.slots.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSlotSelect.appendChild(option);
                    });
                    timeSlotSelect.disabled = false;
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'No available slots for this date';
                    timeSlotSelect.appendChild(option);
                    timeSlotSelect.disabled = true;
                }
            } catch (error) {
                console.error('Error fetching time slots:', error);
                timeSlotSelect.innerHTML = '<option value="">Error loading time slots</option>';
                timeSlotSelect.disabled = true;
            }
        }

        // Event listeners for dynamic time slot updates
        doctorSelect.addEventListener('change', function() {
            if (this.value && dateInput.value) {
                fetchTimeSlots(this.value, dateInput.value);
            }
        });

        dateInput.addEventListener('change', function() {
            if (doctorSelect.value && this.value) {
                fetchTimeSlots(doctorSelect.value, this.value);
            }
        });

        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        dateInput.min = today;
    }

    // Appointment Cancellation
    const cancelButtons = document.querySelectorAll('.cancel-appointment');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            if (confirm('Are you sure you want to cancel this appointment? This action cannot be undone.')) {
                // Show loading state
                const originalText = this.innerHTML;
                this.innerHTML = '<span class="loading-spinner"></span> Cancelling...';
                this.disabled = true;

                // Submit form
                form.submit();
            }
        });
    });

    // AJAX Form Submission
    const ajaxForms = document.querySelectorAll('form[data-ajax]');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const submitButton = form.querySelector('[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="loading-spinner"></span> Processing...';

                // Submit form via AJAX
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);
                    
                    // Reset form if needed
                    if (data.reset) {
                        form.reset();
                    }

                    // Redirect if needed
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    // Show error message
                    showAlert('danger', data.message || 'An error occurred. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'An error occurred. Please try again.');
            } finally {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });

    // Alert Helper Function
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

    // Print Functionality
    const printButtons = document.querySelectorAll('.print-button');
    printButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.dataset.printTarget);
            if (target) {
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <html>
                        <head>
                            <title>Print Appointment</title>
                            <link rel="stylesheet" href="${window.location.origin}/css/appointments.css">
                            <style>
                                @media print {
                                    body { padding: 20px; }
                                    .no-print { display: none; }
                                }
                            </style>
                        </head>
                        <body>
                            ${target.innerHTML}
                            <script>
                                window.onload = function() {
                                    window.print();
                                    window.onafterprint = function() {
                                        window.close();
                                    };
                                };
                            </script>
                        </body>
                    </html>
                `);
                printWindow.document.close();
            }
        });
    });

    // Export Functionality
    const exportButtons = document.querySelectorAll('.export-button');
    exportButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const format = this.dataset.format;
            const target = document.querySelector(this.dataset.exportTarget);
            
            if (target) {
                let content = '';
                const rows = target.querySelectorAll('tbody tr');
                
                // Get headers
                const headers = Array.from(target.querySelectorAll('thead th'))
                    .map(th => th.textContent.trim());
                content += headers.join(',') + '\n';
                
                // Get data
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowData = Array.from(cells).map(cell => {
                        // Remove any HTML and trim
                        const text = cell.textContent.trim();
                        // Escape commas and quotes
                        return `"${text.replace(/"/g, '""')}"`;
                    });
                    content += rowData.join(',') + '\n';
                });

                const blob = new Blob([content], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `appointments-${new Date().toISOString().split('T')[0]}.${format}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }
        });
    });
}); 