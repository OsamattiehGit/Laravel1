/**
 * Contact Form AJAX Handler
 * Provides real-time form validation and submission
 */

document.addEventListener('DOMContentLoaded', () => {
    const contactForm = document.querySelector('.contact-form');
    const submitButton = contactForm?.querySelector('.btn-submit');
    
    if (!contactForm || !submitButton) return;

    // Add real-time validation
    const inputs = contactForm.querySelectorAll('input, select, textarea');
    inputs.forEach(input => {
        input.addEventListener('blur', () => validateField(input));
        input.addEventListener('input', () => clearFieldError(input));
    });

    // Handle form submission
    contactForm.addEventListener('submit', handleFormSubmission);

    async function handleFormSubmission(e) {
        e.preventDefault();
        
        // Check if user is authenticated
        if (!window.isAuthenticated) {
            showLoginModal('You must be logged in to send a message.');
            return;
        }
        
        // Validate all fields before submission
        const isValid = validateForm();
        if (!isValid) {
            Toast.error('Please fix the errors before submitting.');
            return;
        }

        // Disable submit button and show loading state
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="loading-spinner"></span> Sending...';

        try {
            const result = await FormHelper.submitForm(contactForm, {
                url: '/contact',
                method: 'POST',
                onSuccess: (data) => {
                    // Show success message
                    Toast.success('Thank you! Your message has been sent successfully. We\'ll get back to you soon.');
                    
                    // Reset form
                    contactForm.reset();
                    clearAllErrors();
                    
                    // Show success state in form
                    showSuccessState();
                },
                onError: (error) => {
                    // Handle authentication errors
                    if (error.status === 401 || error.status === 403) {
                        showLoginModal('Your session has expired. Please log in again.');
                        return;
                    }
                    
                    console.error('Contact form error:', error);
                    Toast.error('Failed to send message. Please try again.');
                }
            });

        } catch (error) {
            console.error('Contact form submission error:', error);
            
            // Check for authentication errors
            if (error.message && (error.message.includes('401') || error.message.includes('403'))) {
                showLoginModal('You must be logged in to send a message.');
            } else {
                Toast.error('Network error. Please check your connection and try again.');
            }
        } finally {
            // Re-enable submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    }

    function validateForm() {
        let isValid = true;
        
        inputs.forEach(input => {
            if (!validateField(input)) {
                isValid = false;
            }
        });
        
        return isValid;
    }

    function validateField(field) {
        const value = field.value.trim();
        const fieldName = field.name;
        let errorMessage = '';

        // Clear previous errors
        clearFieldError(field);

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            errorMessage = 'This field is required.';
        }
        // Email validation
        else if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                errorMessage = 'Please enter a valid email address.';
            }
        }
        // Phone validation
        else if (field.name === 'phone' && value) {
            const phoneRegex = /^[+]?[0-9\s\-\(\)]{10,}$/;
            if (!phoneRegex.test(value)) {
                errorMessage = 'Please enter a valid phone number.';
            }
        }
        // Name validation
        else if (field.name === 'name' && value) {
            if (value.length < 2) {
                errorMessage = 'Name must be at least 2 characters long.';
            }
        }
        // Message validation
        else if (field.name === 'message' && value) {
            if (value.length < 10) {
                errorMessage = 'Message must be at least 10 characters long.';
            }
        }

        if (errorMessage) {
            showFieldError(field, errorMessage);
            return false;
        }

        showFieldSuccess(field);
        return true;
    }

    function showFieldError(field, message) {
        field.classList.add('error');
        field.classList.remove('success');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

    function showFieldSuccess(field) {
        field.classList.remove('error');
        field.classList.add('success');
        clearFieldError(field);
    }

    function clearFieldError(field) {
        field.classList.remove('error');
        const errorDiv = field.parentNode.querySelector('.field-error');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    function clearAllErrors() {
        inputs.forEach(input => {
            input.classList.remove('error', 'success');
            clearFieldError(input);
        });
    }

    function showSuccessState() {
        const formContainer = contactForm.querySelector('.form-grid');
        const originalContent = formContainer.innerHTML;
        
        formContainer.innerHTML = `
            <div class="success-message" style="text-align: center; padding: 2rem;">
                <div style="font-size: 3rem; color: #28a745; margin-bottom: 1rem;">✓</div>
                <h3 style="color: #28a745; margin-bottom: 1rem;">Message Sent Successfully!</h3>
                <p style="color: #666; margin-bottom: 2rem;">
                    Thank you for contacting us. We'll get back to you within 24 hours.
                </p>
                <button type="button" class="btn-submit" onclick="location.reload()">
                    Send Another Message
                </button>
            </div>
        `;
        
        // Auto-refresh after 5 seconds
        setTimeout(() => {
            location.reload();
        }, 5000);
    }
});

console.log('📧 Contact Form AJAX loaded successfully!');
