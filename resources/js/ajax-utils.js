/**
 * Global AJAX Utilities for EzySkills
 * Provides common AJAX functions, error handling, and UI feedback
 */

// Global configuration
window.AjaxConfig = {
    baseUrl: window.location.origin,
    timeout: 10000,
    retryAttempts: 3,
    retryDelay: 1000
};

// Global loading state management
window.LoadingManager = {
    activeRequests: new Set(),
    
    start(requestId = 'default') {
        this.activeRequests.add(requestId);
        this.updateGlobalLoadingState();
    },
    
    stop(requestId = 'default') {
        this.activeRequests.delete(requestId);
        this.updateGlobalLoadingState();
    },
    
    updateGlobalLoadingState() {
        const isLoading = this.activeRequests.size > 0;
        document.body.classList.toggle('ajax-loading', isLoading);
        
        // Update any global loading indicators
        const globalLoader = document.getElementById('global-loader');
        if (globalLoader) {
            globalLoader.style.display = isLoading ? 'block' : 'none';
        }
    }
};

// Enhanced Toast System
window.Toast = {
    show(message, type = 'info', duration = 3000) {
        const toast = this.create(message, type);
        document.body.appendChild(toast);
        
        // Trigger animation
        setTimeout(() => toast.classList.add('show'), 10);
        
        // Auto-remove
        setTimeout(() => this.remove(toast), duration);
        
        return toast;
    },
    
    create(message, type) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <span class="toast-icon">${this.getIcon(type)}</span>
                <span class="toast-message">${message}</span>
                <button class="toast-close" onclick="window.Toast.remove(this.parentElement.parentElement)">×</button>
            </div>
        `;
        return toast;
    },
    
    remove(toast) {
        toast.classList.add('hide');
        setTimeout(() => {
            if (toast.parentElement) {
                toast.parentElement.removeChild(toast);
            }
        }, 300);
    },
    
    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✗',
            warning: '⚠',
            info: 'ℹ',
            loading: '⟳'
        };
        return icons[type] || icons.info;
    },
    
    // Convenience methods
    success(message, duration) { return this.show(message, 'success', duration); },
    error(message, duration) { return this.show(message, 'error', duration); },
    warning(message, duration) { return this.show(message, 'warning', duration); },
    info(message, duration) { return this.show(message, 'info', duration); },
    loading(message) { return this.show(message, 'loading', 0); }
};

// Enhanced AJAX Request Function
window.AjaxRequest = {
    async make(url, options = {}) {
        const requestId = `req_${Date.now()}_${Math.random()}`;
        
        const defaults = {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        };
        
        // Add CSRF token for non-GET requests
        if (options.method && options.method !== 'GET') {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            if (csrfToken) {
                defaults.headers['X-CSRF-TOKEN'] = csrfToken;
            }
        }
        
        const config = { ...defaults, ...options };
        config.headers = { ...defaults.headers, ...options.headers };
        
        LoadingManager.start(requestId);
        
        try {
            const response = await this.fetchWithRetry(url, config);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            
            const contentType = response.headers.get('content-type');
            let data;
            
            if (contentType && contentType.includes('application/json')) {
                data = await response.json();
            } else {
                data = await response.text();
            }
            
            LoadingManager.stop(requestId);
            return { success: true, data, response };
            
        } catch (error) {
            LoadingManager.stop(requestId);
            console.error('AJAX Request failed:', error);
            
            // Handle different error types
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                Toast.error('Network error. Please check your connection.');
            } else if (error.message.includes('401')) {
                Toast.error('Session expired. Please log in again.');
                setTimeout(() => window.location.href = '/login', 2000);
            } else if (error.message.includes('403')) {
                Toast.error('Access denied. You don\'t have permission for this action.');
            } else if (error.message.includes('404')) {
                Toast.error('Resource not found.');
            } else if (error.message.includes('422')) {
                // Validation errors - will be handled by the calling function
            } else if (error.message.includes('500')) {
                Toast.error('Server error. Please try again later.');
            } else {
                Toast.error(error.message || 'An unexpected error occurred.');
            }
            
            return { success: false, error: error.message, originalError: error };
        }
    },
    
    async fetchWithRetry(url, config, attempt = 1) {
        try {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), AjaxConfig.timeout);
            
            const response = await fetch(url, {
                ...config,
                signal: controller.signal
            });
            
            clearTimeout(timeoutId);
            return response;
            
        } catch (error) {
            if (attempt < AjaxConfig.retryAttempts && 
                (error.name === 'TypeError' || error.name === 'AbortError')) {
                
                console.log(`Retry attempt ${attempt} for ${url}`);
                await new Promise(resolve => setTimeout(resolve, AjaxConfig.retryDelay * attempt));
                return this.fetchWithRetry(url, config, attempt + 1);
            }
            throw error;
        }
    },
    
    // Convenience methods
    get(url, options = {}) {
        return this.make(url, { ...options, method: 'GET' });
    },
    
    post(url, data, options = {}) {
        return this.make(url, {
            ...options,
            method: 'POST',
            body: data ? JSON.stringify(data) : undefined
        });
    },
    
    put(url, data, options = {}) {
        return this.make(url, {
            ...options,
            method: 'PUT',
            body: data ? JSON.stringify(data) : undefined
        });
    },
    
    delete(url, options = {}) {
        return this.make(url, { ...options, method: 'DELETE' });
    }
};

// Form Helper Functions
window.FormHelper = {
    async submitForm(form, options = {}) {
        const formData = new FormData(form);
        const url = options.url || form.action;
        const method = options.method || form.method.toUpperCase();
        
        // Convert FormData to JSON if needed
        let body;
        if (options.json !== false) {
            const data = {};
            formData.forEach((value, key) => {
                if (data[key]) {
                    if (Array.isArray(data[key])) {
                        data[key].push(value);
                    } else {
                        data[key] = [data[key], value];
                    }
                } else {
                    data[key] = value;
                }
            });
            body = data;
        } else {
            body = formData;
        }
        
        const result = await AjaxRequest.make(url, {
            method,
            body: options.json !== false ? JSON.stringify(body) : body,
            headers: options.json !== false ? {} : { 'Content-Type': 'multipart/form-data' }
        });
        
        if (result.success) {
            this.clearErrors(form);
            if (options.onSuccess) {
                options.onSuccess(result.data);
            }
        } else {
            this.displayErrors(form, result.error);
            if (options.onError) {
                options.onError(result.error);
            }
        }
        
        return result;
    },
    
    clearErrors(form) {
        form.querySelectorAll('.error-message').forEach(el => el.remove());
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    },
    
    displayErrors(form, errors) {
        this.clearErrors(form);
        
        if (typeof errors === 'object') {
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'error-message text-danger';
                    errorDiv.textContent = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    input.parentNode.appendChild(errorDiv);
                }
            });
        }
    }
};

// Debounce utility
window.debounce = function(func, wait, immediate) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            timeout = null;
            if (!immediate) func(...args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func(...args);
    };
};

// Initialize global styles for AJAX components
document.addEventListener('DOMContentLoaded', () => {
    if (!document.getElementById('ajax-styles')) {
        const styles = document.createElement('style');
        styles.id = 'ajax-styles';
        styles.textContent = `
            /* Global Loading State */
            .ajax-loading {
                cursor: wait;
            }
            
            .ajax-loading * {
                pointer-events: none;
            }
            
            /* Toast Styles */
            .toast {
                position: fixed;
                top: 20px;
                right: 20px;
                min-width: 300px;
                max-width: 400px;
                padding: 0;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            }
            
            .toast.show {
                opacity: 1;
                transform: translateX(0);
            }
            
            .toast.hide {
                opacity: 0;
                transform: translateX(100%);
            }
            
            .toast-content {
                display: flex;
                align-items: center;
                padding: 16px;
                gap: 12px;
            }
            
            .toast-success { background: #d4edda; border-left: 4px solid #28a745; color: #155724; }
            .toast-error { background: #f8d7da; border-left: 4px solid #dc3545; color: #721c24; }
            .toast-warning { background: #fff3cd; border-left: 4px solid #ffc107; color: #856404; }
            .toast-info { background: #cce5ff; border-left: 4px solid #007bff; color: #004085; }
            .toast-loading { background: #e2e3e5; border-left: 4px solid #6c757d; color: #383d41; }
            
            .toast-icon {
                font-weight: bold;
                font-size: 18px;
            }
            
            .toast-message {
                flex: 1;
                font-weight: 500;
            }
            
            .toast-close {
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
                opacity: 0.7;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .toast-close:hover {
                opacity: 1;
            }
            
            /* Loading Spinner */
            .loading-spinner {
                display: inline-block;
                width: 16px;
                height: 16px;
                border: 2px solid #f3f3f3;
                border-top: 2px solid #007bff;
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            /* Form Validation */
            .is-invalid {
                border-color: #dc3545 !important;
            }
            
            .error-message {
                font-size: 0.875rem;
                margin-top: 0.25rem;
            }
        `;
        document.head.appendChild(styles);
    }
});

// Authentication utilities
window.showLoginModal = function(message = 'You must be logged in to continue.') {
    // Create login modal if it doesn't exist
    let modal = document.getElementById('login-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'login-modal';
        modal.className = 'login-modal-backdrop';
        modal.innerHTML = `
            <div class="login-modal-content">
                <div class="login-modal-header">
                    <h3>Login Required</h3>
                </div>
                <div class="login-modal-body">
                    <p id="login-modal-message">${message}</p>
                </div>
                <div class="login-modal-footer">
                    <button id="login-modal-cancel" class="btn btn-outline">Cancel</button>
                    <button id="login-modal-login" class="btn btn-solid">Login</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Add modal styles
        const style = document.createElement('style');
        style.textContent = `
            .login-modal-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            }
            .login-modal-content {
                background: white;
                border-radius: 12px;
                padding: 2rem;
                max-width: 400px;
                width: 90%;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            }
            .login-modal-header h3 {
                margin: 0 0 1rem 0;
                color: #003F7D;
                text-align: center;
            }
            .login-modal-body {
                margin-bottom: 2rem;
                text-align: center;
            }
            .login-modal-footer {
                display: flex;
                gap: 1rem;
                justify-content: center;
            }
            .login-modal-footer .btn {
                padding: 0.75rem 1.5rem;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
            }
            .login-modal-footer .btn-outline {
                background: transparent;
                border: 2px solid #FF7F00;
                color: #FF7F00;
            }
            .login-modal-footer .btn-outline:hover {
                background: #FF7F00;
                color: white;
            }
            .login-modal-footer .btn-solid {
                background: #FF7F00;
                border: 2px solid #FF7F00;
                color: white;
            }
            .login-modal-footer .btn-solid:hover {
                background: #e56a00;
                border-color: #e56a00;
            }
        `;
        document.head.appendChild(style);
        
        // Add event listeners
        modal.querySelector('#login-modal-cancel').addEventListener('click', () => {
            modal.style.display = 'none';
        });
        
        modal.querySelector('#login-modal-login').addEventListener('click', () => {
            window.location.href = '/login';
        });
        
        // Close on backdrop click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    } else {
        // Update message
        modal.querySelector('#login-modal-message').textContent = message;
    }
    
    modal.style.display = 'flex';
};

console.log('🚀 AJAX Utils loaded successfully!');
