<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            if (!passwordInput || !confirmPasswordInput) return;

            // Add global styles for light/dark mode on first run
            if (!document.getElementById('password-requirements-styles')) {
                const styleElement = document.createElement('style');
                styleElement.id = 'password-requirements-styles';
                styleElement.textContent = `
                    /* Light mode styles */
                    .password-requirements {
                        margin-top: 0.5rem; 
                        font-size: 0.875rem; 
                        background-color: #f9fafb; 
                        padding: 0.75rem; 
                        border-radius: 0.375rem; 
                        border: 1px solid #e5e7eb; 
                        color: #374151;
                    }
                    .password-requirement {
                        display: flex; 
                        align-items: center; 
                        margin-bottom: 0.5rem; 
                        color: #6b7280;
                    }
                    .password-requirement-header {
                        font-weight: 500; 
                        margin-bottom: 0.5rem; 
                        color: #374151;
                    }
                    .password-requirement-met {
                        color: #059669;
                        font-weight: 500;
                    }
                    .password-requirement-icon {
                        height: 1.25rem;
                        width: 1.25rem;
                        margin-right: 0.5rem;
                        stroke: #6b7280;
                    }
                    .password-requirement-icon-met {
                        height: 1.25rem;
                        width: 1.25rem;
                        margin-right: 0.5rem;
                        fill: #059669;
                    }
                    
                    /* Dark mode styles */
                    .dark .password-requirements {
                        background-color: #334155 !important;
                        border-color: #475569 !important;
                        color: #f1f5f9 !important;
                    }
                    .dark .password-requirement {
                        color: #e2e8f0 !important;
                    }
                    .dark .password-requirement-header {
                        color: #f8fafc !important;
                    }
                    .dark .password-requirement-met {
                        color: #4ade80 !important;
                    }
                    .dark .password-requirement-icon {
                        stroke: #e2e8f0 !important;
                    }
                    .dark .password-requirement-icon-met {
                        fill: #4ade80 !important;
                    }
                `;
                document.head.appendChild(styleElement);
            }
            
            // Check current theme
            function checkTheme() {
                return document.documentElement.classList.contains('dark');
            }
            
            // Apply theme styling to a container
            function applyThemeToContainer(container) {
                // Clear any inline styles that might override our CSS classes
                container.removeAttribute('style');
                container.style.display = 'block';
            }
            
            // Listen for theme changes 
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        // Re-render all requirements with current theme
                        updateAllRequirements();
                        
                        // Force update container styles based on current theme
                        const passwordReqContainers = document.querySelectorAll('.password-requirements');
                        passwordReqContainers.forEach(container => {
                            applyThemeToContainer(container);
                        });
                    }
                });
            });
            
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });

            addPasswordRequirements();
            addConfirmPasswordRequirements();

            passwordInput.addEventListener('input', function() {
                updatePasswordRequirements(this.value);
                updateConfirmPasswordRequirements();
            });

            passwordInput.addEventListener('focus', function() {
                const requirementsContainer = document.getElementById('password-requirements-container');
                if (requirementsContainer) {
                    requirementsContainer.style.display = 'block';
                }
                updatePasswordRequirements(this.value);
            });

            confirmPasswordInput.addEventListener('input', function() {
                updateConfirmPasswordRequirements();
            });

            confirmPasswordInput.addEventListener('focus', function() {
                const requirementsContainer = document.getElementById('confirm-password-requirements-container');
                if (requirementsContainer) {
                    requirementsContainer.style.display = 'block';
                }
                updateConfirmPasswordRequirements();
            });
            
            function updateAllRequirements() {
                if (passwordInput.value) {
                    updatePasswordRequirements(passwordInput.value);
                }
                updateConfirmPasswordRequirements();
            }

            function addPasswordRequirements() {
                const requirementsContainer = document.getElementById('password-requirements-container');
                if (!requirementsContainer) return;

                // Add appropriate classes for styling
                requirementsContainer.classList.add('password-requirements');
                applyThemeToContainer(requirementsContainer);
                requirementsContainer.className = 'mt-2 text-sm password-requirements';

                const header = document.createElement('p');
                header.classList.add('password-requirement-header');
                header.textContent = 'Password requirements:';

                const requirementsList = document.createElement('ul');
                requirementsList.style.listStyleType = 'none';
                requirementsList.style.padding = '0';
                requirementsList.style.margin = '0';

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
                    item.classList.add('password-requirement');
                    item.setAttribute('data-text', req.text);

                    const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    icon.classList.add('password-requirement-icon');
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
            }

            function addConfirmPasswordRequirements() {
                const requirementsContainer = document.getElementById('confirm-password-requirements-container');
                if (!requirementsContainer) return;

                // Make the container have consistent styling
                requirementsContainer.classList.add('password-requirements');
                applyThemeToContainer(requirementsContainer);
                
                const requirementsList = document.createElement('ul');
                requirementsList.style.listStyleType = 'none';
                requirementsList.style.padding = '0';
                requirementsList.style.margin = '0';

                const item = document.createElement('li');
                item.id = 'req-confirm-match';
                item.classList.add('password-requirement');
                item.setAttribute('data-text', 'Passwords must match');

                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.classList.add('password-requirement-icon');
                icon.setAttribute('fill', 'none');
                icon.setAttribute('viewBox', '0 0 24 24');
                icon.setAttribute('stroke', 'currentColor');

                const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                path.setAttribute('stroke-linecap', 'round');
                path.setAttribute('stroke-linejoin', 'round');
                path.setAttribute('stroke-width', '2');
                path.setAttribute('d', 'M6 18L18 6M6 6l12 12');

                icon.appendChild(path);

                const text = document.createTextNode('Passwords must match');

                item.appendChild(icon);
                item.appendChild(text);

                requirementsList.appendChild(item);

                requirementsContainer.appendChild(requirementsList);
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

            function updateConfirmPasswordRequirements() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                const isMatch = password === confirmPassword && password !== '';

                const requirementsContainer = document.getElementById('confirm-password-requirements-container');
                if (requirementsContainer) {
                    requirementsContainer.style.display = 'block';
                }

                updateRequirementItem('req-confirm-match', isMatch);
            }

            function updateRequirementItem(id, isMet) {
                const item = document.getElementById(id);
                if (!item) return;

                const text = item.getAttribute('data-text');

                try {
                    while (item.firstChild) {
                        item.removeChild(item.firstChild);
                    }

                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');

                    if (isMet) {
                        svg.classList.add('password-requirement-icon-met');
                        svg.setAttribute('viewBox', '0 0 20 20');
                        
                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('fill-rule', 'evenodd');
                        path.setAttribute('d', 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z');
                        path.setAttribute('clip-rule', 'evenodd');

                        svg.appendChild(path);

                        item.classList.add('password-requirement-met');
                    } else {
                        svg.classList.add('password-requirement-icon');
                        svg.setAttribute('viewBox', '0 0 24 24');
                        svg.setAttribute('fill', 'none');
                        svg.setAttribute('stroke', 'currentColor');

                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('stroke-linecap', 'round');
                        path.setAttribute('stroke-linejoin', 'round');
                        path.setAttribute('stroke-width', '2');
                        path.setAttribute('d', 'M6 18L18 6M6 6l12 12');

                        svg.appendChild(path);

                        item.classList.remove('password-requirement-met');
                    }

                    const textNode = document.createTextNode(text);
                    item.appendChild(svg);
                    item.appendChild(textNode);
                } catch (e) {
                    console.error('Error updating requirement item:', e);
                }
            }
        });
    </script>
</x-guest-layout>
