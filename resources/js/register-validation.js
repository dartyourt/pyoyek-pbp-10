document.addEventListener('DOMContentLoaded', function() {
    const registerForm = document.querySelector('form[action*="register"]');
    if (!registerForm) return;

    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const submitButton = registerForm.querySelector('button[type="submit"]');
    
    addValidationElements();
    
    if (nameInput) nameInput.addEventListener('input', validateName);
    if (emailInput) emailInput.addEventListener('input', validateEmail);
    if (emailInput) emailInput.addEventListener('blur', checkEmailAvailability);
    if (passwordInput) passwordInput.addEventListener('input', validatePassword);
    if (confirmPasswordInput) confirmPasswordInput.addEventListener('input', validateConfirmPassword);
    
    registerForm.addEventListener('submit', function(event) {
        const isNameValid = nameInput ? validateName() : true;
        const isEmailValid = emailInput ? validateEmail() : true;
        const isPasswordValid = passwordInput ? validatePassword() : true;
        const isConfirmPasswordValid = confirmPasswordInput ? validateConfirmPassword() : true;
        
        if (!isNameValid || !isEmailValid || !isPasswordValid || !isConfirmPasswordValid) {
            event.preventDefault();
            showFormError('Please fix the errors before submitting the form.');
        }
    });

    function addValidationElements() {
        if (passwordInput) {
            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            const requirementsContainer = document.createElement('div');
            requirementsContainer.id = 'password-requirements-container';
            if (isDarkMode) {
                requirementsContainer.setAttribute('style', 'margin-top: 0.5rem; font-size: 0.875rem; background-color: #1f2937; padding: 0.75rem; border-radius: 0.375rem; border: 1px solid #374151;');
            } else {
                requirementsContainer.setAttribute('style', 'margin-top: 0.5rem; font-size: 0.875rem; background-color: #f9fafb; padding: 0.75rem; border-radius: 0.375rem; border: 1px solid #e5e7eb;');
            }
            requirementsContainer.className = 'mt-2 text-sm';
            
            const header = document.createElement('p');
            if (isDarkMode) {
                header.setAttribute('style', 'font-weight: 500; margin-bottom: 0.5rem; color: #e5e7eb;');
            } else {
                header.setAttribute('style', 'font-weight: 500; margin-bottom: 0.5rem; color: #374151;');
            }
            header.textContent = 'Password requirements:';
            
            const requirementsList = document.createElement('ul');
            requirementsList.setAttribute('style', 'list-style-type: none; padding: 0; margin: 0;');
            
            const requirements = [
                { id: 'req-length', text: 'At least 8 characters' },
                { id: 'req-uppercase', text: 'At least one uppercase letter' },
                { id: 'req-lowercase', text: 'At least one lowercase letter' },
                { id: 'req-number', text: 'At least one number' },
                { id: 'req-special', text: 'At least one special character' }
            ];
            
            requirements.forEach(req => {
                const item = document.createElement('li');
                item.id = req.id;
                item.setAttribute('style', 'display: flex; align-items: center; margin-bottom: 0.5rem; color: #6b7280;');
                
                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.setAttribute('style', 'height: 1.25rem; width: 1.25rem; margin-right: 0.5rem;');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('stroke', 'currentColor');
                
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('stroke-width', '2');
                path.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                
                icon.appendChild(path);
                
                const text = document.createTextNode(req.text);
                
                item.appendChild(icon);
                item.appendChild(text);
                
                requirementsList.appendChild(item);
            });
            
            requirementsContainer.appendChild(header);
            requirementsContainer.appendChild(requirementsList);
            
            passwordInput.parentNode.insertBefore(requirementsContainer, passwordInput.nextSibling);
        }

        [nameInput, emailInput, passwordInput, confirmPasswordInput].forEach(input => {
            if (!input) return;
            
            input.parentNode.style.position = 'relative';
        });
    }

    function validateName() {
        if (!nameInput) return true;
        
        const name = nameInput.value.trim();
        
        if (name.length < 3) {
            showError(nameInput, 'Name must be at least 3 characters');
            return false;
        }
        
        if (name.length > 255) {
            showError(nameInput, 'Name cannot exceed 255 characters');
            return false;
        }
        
        hideError(nameInput);
        return true;
    }

    function validateEmail() {
        if (!emailInput) return true;
        
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email === '') {
            showError(emailInput, 'Email is required');
            return false;
        }
        
        if (!emailRegex.test(email)) {
            showError(emailInput, 'Please enter a valid email address');
            return false;
        }
        
        hideError(emailInput);
        return true;
    }
    
    function checkEmailAvailability() {
        if (!emailInput || !validateEmail()) return;
        
        const email = emailInput.value.trim();
        
        const loadingText = document.createElement('span');
        loadingText.textContent = 'Checking...';
        loadingText.className = 'text-gray-500 text-sm ml-2';
        loadingText.id = 'email-loading-text';
        
        const existingLoadingText = document.getElementById('email-loading-text');
        if (existingLoadingText) {
            existingLoadingText.remove();
        }
        
        emailInput.parentNode.appendChild(loadingText);
        
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        fetch('/check-email-availability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (loadingText.parentNode) {
                loadingText.remove();
            }
            
            if (data.available) {
                hideError(emailInput);
            } else {
                showError(emailInput, 'This email is already registered');
            }
        })
        .catch(() => {
            if (loadingText.parentNode) {
                loadingText.remove();
            }
            
        });
    }

    function validatePassword() {
        if (!passwordInput) return true;
        
        const password = passwordInput.value;
        
        if (confirmPasswordInput && confirmPasswordInput.value) {
            validateConfirmPassword();
        }
        
        if (password === '') {
            showError(passwordInput, 'Password is required');
            updatePasswordRequirements(password);
            return false;
        }
        
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[^a-zA-Z0-9]/.test(password);
        
        updatePasswordRequirements(password);
        
        const requirementsMet = [hasLength, hasUppercase, hasLowercase, hasNumber, hasSpecial].filter(Boolean).length;
        
        const isValid = requirementsMet >= 4;
        
        if (isValid) {
            hideError(passwordInput);
        } else {
            showError(passwordInput, 'Password doesn\'t meet the minimum requirements');
        }
        
        return isValid;
    }

    function updatePasswordRequirements(password) {

        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[^a-zA-Z0-9]/.test(password);
        
        const requirementsContainer = document.getElementById('password-requirements-container');
        if (requirementsContainer) {
            requirementsContainer.style.display = 'block';
        }
        
        updateRequirementItem('req-length', hasLength);
        updateRequirementItem('req-uppercase', hasUppercase);
        updateRequirementItem('req-lowercase', hasLowercase);
        updateRequirementItem('req-number', hasNumber);
        updateRequirementItem('req-special', hasSpecial);
    }
    
    function updateRequirementItem(id, isMet) {
        const item = document.getElementById(id);
        if (!item) {
            console.error(`Element with id "${id}" not found`);
            return;
        }
        
        try {
            while (item.firstChild) {
                item.removeChild(item.firstChild);
            }

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('style', 'height: 1.25rem; width: 1.25rem; margin-right: 0.5rem;');

            const isDarkMode = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            if (isMet) {

                svg.setAttribute('viewBox', '0 0 20 20');
                
                if (isDarkMode) {
                    svg.setAttribute('fill', '#34d399'); 
                } else {
                    svg.setAttribute('fill', '#059669'); 
                }
                
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('fill-rule', 'evenodd');
                path.setAttribute('d', 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z');
                path.setAttribute('clip-rule', 'evenodd');
                
                svg.appendChild(path);
                
                if (isDarkMode) {
                    item.style.color = '#34d399'; 
                } else {
                    item.style.color = '#059669';
                }
                item.style.fontWeight = '500';
            } else {
                svg.setAttribute('viewBox', '0 0 24 24');
                svg.setAttribute('fill', 'none');

                if (isDarkMode) {
                    svg.setAttribute('stroke', '#9ca3af');
                } else {
                    svg.setAttribute('stroke', '#6b7280');
                }
                
                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('stroke-width', '2');
                path.setAttribute('d', 'M6 18L18 6M6 6l12 12');
                
                svg.appendChild(path);
                
                if (isDarkMode) {
                    item.style.color = '#9ca3af';
                } else {
                    item.style.color = '#6b7280'; 
                }
                item.style.fontWeight = 'normal';
            }
            
            item.appendChild(svg);
            
            const requirements = {
                'req-length': 'At least 8 characters',
                'req-uppercase': 'At least one uppercase letter',
                'req-lowercase': 'At least one lowercase letter',
                'req-number': 'At least one number',
                'req-special': 'At least one special character'
            };
            
            const text = document.createTextNode(requirements[id] || '');
            item.appendChild(text);
            
            console.log(`Successfully updated requirement ${id} to ${isMet ? 'met' : 'not met'}`);
        } catch (error) {
            console.error(`Error updating requirement ${id}:`, error);
        }
    }

    function validateConfirmPassword() {
        if (!confirmPasswordInput || !passwordInput) return true;
        
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        if (confirmPassword === '') {
            showError(confirmPasswordInput, 'Please confirm your password');
            return false;
        }
        
        if (password !== confirmPassword) {
            showError(confirmPasswordInput, 'Passwords do not match');
            return false;
        }
        
        hideError(confirmPasswordInput);
        return true;
    }

    function showError(input, message) {
        if (input.id === 'password' && 
           message === 'Password doesn\'t meet the minimum requirements') {
            input.classList.add('border-red-500');
            return;
        }
        const errorElement = input.nextElementSibling;
        if (errorElement && errorElement.classList.contains('text-red-500')) {
            errorElement.textContent = message;
        } else {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'text-red-500 text-sm mt-1';
            errorDiv.textContent = message;
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
        }
        
        input.classList.add('border-red-500');
    }

    function hideError(input) {
        const errorElement = input.nextElementSibling;
        if (errorElement && errorElement.classList.contains('text-red-500')) {
            errorElement.textContent = '';
        }
        
        input.classList.remove('border-red-500');
    }

    function showFormError(message) {
        let errorContainer = document.getElementById('form-error-container');
        
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'form-error-container';
            errorContainer.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            errorContainer.setAttribute('role', 'alert');
            registerForm.insertBefore(errorContainer, registerForm.firstChild);
        }
        
        errorContainer.innerHTML = `<p>${message}</p>`;
    }
});