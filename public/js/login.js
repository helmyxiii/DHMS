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
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Signing in...';
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
                    throw new Error(data.message || 'Invalid credentials');
                }
            } catch (error) {
                console.error('Login error:', error);
                
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

    // Password visibility toggle
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
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

    // Remember me checkbox handling
    const rememberCheckbox = document.getElementById('remember');
    if (rememberCheckbox) {
        // Check if there's a saved email
        const savedEmail = localStorage.getItem('rememberedEmail');
        if (savedEmail) {
            document.getElementById('email').value = savedEmail;
            rememberCheckbox.checked = true;
        }

        // Save email when checkbox is checked
        rememberCheckbox.addEventListener('change', () => {
            const emailInput = document.getElementById('email');
            if (rememberCheckbox.checked && emailInput.value) {
                localStorage.setItem('rememberedEmail', emailInput.value);
            } else {
                localStorage.removeItem('rememberedEmail');
            }
        });
    }
}); 