// Admin Dashboard JavaScript

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

    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.classList.add('fade-in');
    });

    // Handle table row hover effects
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fc';
        });
        row.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });

    // Handle delete confirmations
    const deleteButtons = document.querySelectorAll('.delete-confirm');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            
            if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                form.submit();
            }
        });
    });

    // Handle status updates
    const statusButtons = document.querySelectorAll('.status-update');
    statusButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const status = this.dataset.status;
            
            if (confirm(`Are you sure you want to mark this as ${status}?`)) {
                form.submit();
            }
        });
    });

    // Handle search form submission
    const searchForm = document.querySelector('#search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput.value.trim() === '') {
                return;
            }
            this.submit();
        });
    }

    // Handle date range filters
    const dateRangeInputs = document.querySelectorAll('.date-range');
    dateRangeInputs.forEach(input => {
        input.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });

    // Handle bulk actions
    const bulkActionForm = document.querySelector('#bulk-action-form');
    if (bulkActionForm) {
        const bulkActionSelect = bulkActionForm.querySelector('select[name="bulk_action"]');
        const bulkActionButton = bulkActionForm.querySelector('button[type="submit"]');
        
        bulkActionSelect.addEventListener('change', function() {
            bulkActionButton.textContent = this.options[this.selectedIndex].text;
        });

        // Handle select all checkbox
        const selectAllCheckbox = bulkActionForm.querySelector('.select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const checkboxes = bulkActionForm.querySelectorAll('input[name="selected_items[]"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });
        }
    }

    // Handle file upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const preview = document.querySelector(this.dataset.preview);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Handle password toggle
    const passwordToggles = document.querySelectorAll('.password-toggle');
    passwordToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling;
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    });

    // Handle dynamic form fields
    const addFieldButtons = document.querySelectorAll('.add-field');
    addFieldButtons.forEach(button => {
        button.addEventListener('click', function() {
            const template = document.querySelector(this.dataset.template);
            const container = document.querySelector(this.dataset.container);
            if (template && container) {
                const clone = template.content.cloneNode(true);
                container.appendChild(clone);
            }
        });
    });

    // Handle remove field buttons
    document.addEventListener('click', function(e) {
        if (e.target.matches('.remove-field')) {
            e.preventDefault();
            const field = e.target.closest('.dynamic-field');
            if (field) {
                field.remove();
            }
        }
    });

    // Handle form validation
    const forms = document.querySelectorAll('form[data-validate]');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    });

    // Handle AJAX form submissions
    const ajaxForms = document.querySelectorAll('form[data-ajax]');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitButton = form.querySelector('[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';

            // Submit form via AJAX
            fetch(form.action, {
                method: form.method,
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    form.insertAdjacentElement('beforebegin', alert);

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
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger alert-dismissible fade show';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    form.insertAdjacentElement('beforebegin', alert);
                }
            })
            .catch(error => {
                // Show error message
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    An error occurred. Please try again.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                form.insertAdjacentElement('beforebegin', alert);
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    });

    // Handle print functionality
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
                            <title>Print</title>
                            <link rel="stylesheet" href="${window.location.origin}/css/admin.css">
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

    // Handle export functionality
    const exportButtons = document.querySelectorAll('.export-button');
    exportButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const format = this.dataset.format;
            const target = document.querySelector(this.dataset.exportTarget);
            
            if (target) {
                let content = '';
                const rows = target.querySelectorAll('tbody tr');
                
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    const rowData = Array.from(cells).map(cell => cell.textContent.trim());
                    content += rowData.join(',') + '\n';
                });

                const blob = new Blob([content], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `export-${new Date().toISOString().split('T')[0]}.${format}`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);
            }
        });
    });
}); 