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

    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Add loading state to buttons
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.classList.contains('btn-link')) {
                const originalText = this.innerHTML;
                this.classList.add('btn-loading');
                this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
                this.disabled = true;

                // Reset button after 2 seconds if no form submission
                if (!this.closest('form')) {
                    setTimeout(() => {
                        this.classList.remove('btn-loading');
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }, 2000);
                }
            }
        });
    });

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

    // Initialize any datepickers if present
    if (typeof flatpickr !== 'undefined') {
        document.querySelectorAll('.datepicker').forEach(element => {
            flatpickr(element, {
                dateFormat: 'Y-m-d',
                allowInput: true
            });
        });
    }

    // Handle form submissions with AJAX if needed
    document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitButton = form.querySelector('[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                submitButton.classList.add('btn-loading');
                submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
                submitButton.disabled = true;

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
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        // Handle success (e.g., show message, update UI)
                        if (typeof bootstrap !== 'undefined') {
                            const toast = new bootstrap.Toast(document.createElement('div'));
                            toast.show();
                        }
                    }
                } else {
                    // Handle error
                    throw new Error(data.message || 'An error occurred');
                }
            } catch (error) {
                console.error('Form submission error:', error);
                // Show error message
                if (typeof bootstrap !== 'undefined') {
                    const toast = new bootstrap.Toast(document.createElement('div'));
                    toast.show();
                }
            } finally {
                submitButton.classList.remove('btn-loading');
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }
        });
    });

    // Handle notifications if present
    const notificationBell = document.querySelector('.notification-bell');
    if (notificationBell) {
        notificationBell.addEventListener('click', async () => {
            try {
                const response = await fetch('/notifications');
                const data = await response.json();
                
                // Update notification count
                const badge = notificationBell.querySelector('.badge');
                if (badge) {
                    badge.textContent = data.unread_count;
                }

                // Update notification list
                const notificationList = document.querySelector('.notification-list');
                if (notificationList) {
                    notificationList.innerHTML = data.notifications.map(notification => `
                        <div class="notification-item ${notification.read ? 'read' : 'unread'}">
                            <div class="notification-content">${notification.message}</div>
                            <div class="notification-time">${notification.time_ago}</div>
                        </div>
                    `).join('');
                }
            } catch (error) {
                console.error('Error fetching notifications:', error);
            }
        });
    }
}); 