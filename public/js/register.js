document.addEventListener('DOMContentLoaded', function() {
    // Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    
    const toggleBackToTopButton = () => {
        if (window.scrollY > 300) {
            backToTopButton.style.display = 'flex';
        } else {
            backToTopButton.style.display = 'none';
        }
    };

    window.addEventListener('scroll', toggleBackToTopButton);
    
    backToTopButton.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // Password Strength Checker
    const passwordInput = document.getElementById('password');
    const passwordConfirmationInput = document.getElementById('password_confirmation');
    
    if (passwordInput) {
        // Create password strength indicator
        const strengthIndicator = document.createElement('div');
        strengthIndicator.className = 'password-strength';
        passwordInput.parentElement.appendChild(strengthIndicator);

        const checkPasswordStrength = (password) => {
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 1;
            
            // Contains number
            if (/\d/.test(password)) strength += 1;
            
            // Contains lowercase
            if (/[a-z]/.test(password)) strength += 1;
            
            // Contains uppercase
            if (/[A-Z]/.test(password)) strength += 1;
            
            // Contains special character
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;

            // Update strength indicator
            strengthIndicator.className = 'password-strength';
            if (strength <= 2) {
                strengthIndicator.classList.add('weak');
            } else if (strength === 3) {
                strengthIndicator.classList.add('medium');
            } else if (strength === 4) {
                strengthIndicator.classList.add('strong');
            } else {
                strengthIndicator.classList.add('very-strong');
            }
        };

        // Check password strength on input
        passwordInput.addEventListener('input', () => {
            checkPasswordStrength(passwordInput.value);
        });

        // Password visibility toggle
        const togglePassword = document.createElement('button');
        togglePassword.type = 'button';
        togglePassword.className = 'btn btn-link position-absolute end-0 top-50 translate-middle-y';
        togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
        togglePassword.style.right = '10px';
        togglePassword.style.color = '#6c757d';
        
        passwordInput.parentElement.style.position = 'relative';
        passwordInput.parentElement.appendChild(togglePassword);

        togglePassword.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePassword.innerHTML = `<i class="fas fa-eye${type === 'password' ? '' : '-slash'}"></i>`;
        });

        // Password confirmation validation
        if (passwordConfirmationInput) {
            const validatePasswordConfirmation = () => {
                if (passwordInput.value !== passwordConfirmationInput.value) {
                    passwordConfirmationInput.setCustomValidity('Passwords do not match');
                } else {
                    passwordConfirmationInput.setCustomValidity('');
                }
            };

            passwordInput.addEventListener('input', validatePasswordConfirmation);
            passwordConfirmationInput.addEventListener('input', validatePasswordConfirmation);
        }
    }

    // Form Validation
    const form = document.querySelector('.needs-validation');
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            if (!form.checkValidity()) {
                event.stopPropagation();
                form.classList.add('was-validated');
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                // Add loading state
                submitButton.classList.add('btn-loading');
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Creating account...';
                submitButton.disabled = true;

                // Get form data
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Show success message
                    if (typeof bootstrap !== 'undefined') {
                        const toast = new bootstrap.Toast(document.createElement('div'));
                        toast.show();
                    }

                    // Redirect if provided
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                } else {
                    // Show error message
                    throw new Error(data.message || 'Registration failed');
                }
            } catch (error) {
                console.error('Registration error:', error);
                
                // Show error message
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-danger alert-dismissible fade show';
                alertDiv.innerHTML = `
                    ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                
                form.insertBefore(alertDiv, form.firstChild);

                // Remove alert after 5 seconds
                setTimeout(() => {
                    alertDiv.remove();
                }, 5000);
            } finally {
                // Reset button state
                submitButton.classList.remove('btn-loading');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    }

    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Handle mobile menu toggle
    const mobileMenuToggle = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    if (mobileMenuToggle && navbarCollapse) {
        mobileMenuToggle.addEventListener('click', () => {
            navbarCollapse.classList.toggle('show');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navbarCollapse.contains(e.target) && !mobileMenuToggle.contains(e.target)) {
                navbarCollapse.classList.remove('show');
            }
        });
    }

    // Add active class to current navigation item
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-link').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });

    // Terms and conditions checkbox handling
    const termsCheckbox = document.getElementById('terms');
    if (termsCheckbox) {
        termsCheckbox.addEventListener('change', () => {
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.disabled = !termsCheckbox.checked;
        });
    }
}); 