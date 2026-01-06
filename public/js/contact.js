// Contact Page JavaScript

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

    // Form Validation and Submission
    const contactForm = document.getElementById('contactForm');
    if (contactForm) {
        // Real-time validation
        const inputs = contactForm.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                validateInput(this);
            });

            input.addEventListener('blur', function() {
                validateInput(this);
            });
        });

        // Form submission
        contactForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!this.checkValidity()) {
                e.stopPropagation();
                this.classList.add('was-validated');
                return;
            }

            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                // Show loading state
                submitButton.disabled = true;
                submitButton.innerHTML = '<span class="loading-spinner"></span> Sending...';

                // Submit form via AJAX
                const formData = new FormData(this);
                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    showAlert('success', data.message);
                    
                    // Reset form
                    this.reset();
                    this.classList.remove('was-validated');
                    
                    // Remove validation classes
                    inputs.forEach(input => {
                        input.classList.remove('is-valid', 'is-invalid');
                    });
                } else {
                    // Show error message
                    showAlert('danger', data.message || 'An error occurred. Please try again.');
                    
                    // Show validation errors if any
                    if (data.errors) {
                        Object.keys(data.errors).forEach(field => {
                            const input = this.querySelector(`[name="${field}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = data.errors[field][0];
                                }
                            }
                        });
                    }
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
    }

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

    // Map interaction
    const mapIframe = document.querySelector('iframe');
    if (mapIframe) {
        mapIframe.addEventListener('load', function() {
            this.style.opacity = '1';
        });
    }

    // Contact information hover effects
    const contactIcons = document.querySelectorAll('.text-center i');
    contactIcons.forEach(icon => {
        icon.parentElement.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });

        icon.parentElement.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Print functionality
    const printButton = document.querySelector('.print-button');
    if (printButton) {
        printButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }

    // Form field character counters
    const textInputs = contactForm.querySelectorAll('input[type="text"], textarea');
    textInputs.forEach(input => {
        const maxLength = input.getAttribute('maxlength');
        if (maxLength) {
            const counter = document.createElement('small');
            counter.className = 'text-muted float-end';
            counter.textContent = `0/${maxLength}`;
            input.parentElement.appendChild(counter);

            input.addEventListener('input', function() {
                const remaining = maxLength - this.value.length;
                counter.textContent = `${this.value.length}/${maxLength}`;
                counter.className = `text-muted float-end ${remaining < 10 ? 'text-danger' : ''}`;
            });
        }
    });

    // Form field focus effects
    const formFields = contactForm.querySelectorAll('.form-control');
    formFields.forEach(field => {
        field.addEventListener('focus', function() {
            this.parentElement.classList.add('field-focused');
        });

        field.addEventListener('blur', function() {
            this.parentElement.classList.remove('field-focused');
        });
    });

    // Prevent form resubmission on page refresh
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
}); 