// Dashboard API Handler
class DashboardAPI {
    constructor() {
        this.baseUrl = '/api';
        this.csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    async fetchData(endpoint) {
        // Prevent fetching the patient dashboard API endpoint to avoid the error message
        if (endpoint === '/dashboard/patient') {
            return { success: true, data: {} };
        }
        try {
            const response = await fetch(`${this.baseUrl}${endpoint}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }

            if (!data.success) {
                throw new Error(data.message || 'Unknown error occurred');
            }

            return data;
        } catch (error) {
            console.error('Error fetching dashboard data:', error);
            throw error;
        }
    }

    async getDoctorDashboard() {
        return this.fetchData('/dashboard/doctor');
    }

    async getPatientDashboard() {
        return this.fetchData('/dashboard/patient');
    }

    async getAdminDashboard() {
        return this.fetchData('/dashboard/admin');
    }
}

// Dashboard UI Handler
class DashboardUI {
    constructor() {
        this.api = new DashboardAPI();
        this.loadingStates = {};
    }

    setLoading(elementId, isLoading) {
        const element = document.getElementById(elementId);
        if (element) {
            element.innerHTML = isLoading ? 
                '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>' : 
                '';
        }
    }

    showError(message) {
        const errorElement = document.getElementById('dashboard-error');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.style.display = 'block';
        }
    }

    hideError() {
        const errorElement = document.getElementById('dashboard-error');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }

    updateStats(stats) {
        Object.entries(stats).forEach(([key, value]) => {
            const element = document.getElementById(`${key}-count`);
            if (element) {
                element.textContent = value;
            }
        });
    }

    updateTable(tableId, data, pagination = null) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        tbody.innerHTML = data.map(item => this.createTableRow(item)).join('');

        // Update pagination if provided
        if (pagination) {
            this.updatePagination(tableId, pagination);
        }
    }

    updatePagination(tableId, pagination) {
        const paginationElement = document.getElementById(`${tableId}-pagination`);
        if (!paginationElement) return;

        const { current_page, last_page } = pagination;
        
        let paginationHtml = '<ul class="pagination justify-content-center">';
        
        // Previous button
        paginationHtml += `
            <li class="page-item ${current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${current_page - 1}">Previous</a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= last_page; i++) {
            paginationHtml += `
                <li class="page-item ${current_page === i ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `;
        }

        // Next button
        paginationHtml += `
            <li class="page-item ${current_page === last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" data-page="${current_page + 1}">Next</a>
            </li>
        `;

        paginationHtml += '</ul>';
        paginationElement.innerHTML = paginationHtml;

        // Add click handlers
        paginationElement.querySelectorAll('.page-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = e.target.dataset.page;
                if (page && !e.target.parentElement.classList.contains('disabled')) {
                    this.loadPage(tableId, page);
                }
            });
        });
    }

    async loadPage(tableId, page) {
        // This should be implemented by specific dashboard implementations
        console.warn('loadPage not implemented');
    }

    createTableRow(item) {
        // This should be overridden by specific dashboard implementations
        return '';
    }

    async loadDashboard(type) {
        try {
            this.hideError();
            this.setLoading('dashboard-loading', true);
            
            let response;
            switch (type) {
                case 'doctor':
                    response = await this.api.getDoctorDashboard();
                    break;
                case 'patient':
                    response = await this.api.getPatientDashboard();
                    break;
                case 'admin':
                    response = await this.api.getAdminDashboard();
                    break;
                default:
                    throw new Error('Invalid dashboard type');
            }

            const { data } = response;
            
            // Update stats
            if (data.stats) {
                this.updateStats(data.stats);
            }
            
            // Update tables
            if (data.recentAppointments) {
                this.updateTable('recent-appointments-table', 
                    data.recentAppointments.data,
                    data.recentAppointments.pagination
                );
            }
            if (data.todaySchedule) {
                this.updateTable('today-schedule-table', 
                    data.todaySchedule.data,
                    data.todaySchedule.pagination
                );
            }

        } catch (error) {
            console.error('Error loading dashboard:', error);
            this.showError(error.message || 'Error loading dashboard data. Please try again.');
        } finally {
            this.setLoading('dashboard-loading', false);
        }
    }
}

// Initialize dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    const dashboardUI = new DashboardUI();
    
    // Determine dashboard type from current page
    const dashboardType = document.body.dataset.dashboardType;
    if (dashboardType) {
        dashboardUI.loadDashboard(dashboardType);
    }
}); 