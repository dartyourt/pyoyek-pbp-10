<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <div id="password-requirements-container" class="mt-2 text-sm" style="display: none;"></div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <div id="confirm-password-requirements-container" class="mt-2 text-sm" style="display: none;"></div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('update_password_password');
            const confirmPasswordInput = document.getElementById('update_password_password_confirmation');
            if (!passwordInput || !confirmPasswordInput) return;
            
            // Listen for theme changes 
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                        const isDark = document.documentElement.classList.contains('dark');
                        updateAllRequirements();
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

                // Make the container have consistent styling 
                requirementsContainer.classList.add('password-requirements');
                requirementsContainer.setAttribute('style', 'margin-top: 0.5rem; font-size: 0.875rem; background-color: #f9fafb; padding: 0.75rem; border-radius: 0.375rem; border: 1px solid #e5e7eb; color: #374151;');
                
                // Add dark mode styles with !important to override inline styles
                const darkModeStyle = document.createElement('style');
                darkModeStyle.textContent = `
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
                document.head.appendChild(darkModeStyle);

                const header = document.createElement('p');
                header.classList.add('password-requirement-header');
                header.setAttribute('style', 'font-weight: 500; margin-bottom: 0.5rem; color: #374151;');
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
                    item.classList.add('password-requirement');
                    item.setAttribute('style', 'display: flex; align-items: center; margin-bottom: 0.5rem; color: #6b7280;');
                    item.setAttribute('data-text', req.text);

                    const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    icon.classList.add('password-requirement-icon');
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
            }

            function addConfirmPasswordRequirements() {
                const requirementsContainer = document.getElementById('confirm-password-requirements-container');
                if (!requirementsContainer) return;

                // Make the container have consistent styling
                requirementsContainer.classList.add('password-requirements');
                requirementsContainer.setAttribute('style', 'margin-top: 0.5rem; font-size: 0.875rem; background-color: #f9fafb; padding: 0.75rem; border-radius: 0.375rem; border: 1px solid #e5e7eb;');
                
                const requirementsList = document.createElement('ul');
                requirementsList.setAttribute('style', 'list-style-type: none; padding: 0; margin: 0;');

                const item = document.createElement('li');
                item.id = 'req-confirm-match';
                item.classList.add('password-requirement');
                item.setAttribute('style', 'display: flex; align-items: center; margin-bottom: 0.5rem; color: #6b7280;');
                item.setAttribute('data-text', 'Passwords must match');

                const icon = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                icon.classList.add('password-requirement-icon');
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
                    svg.setAttribute('style', 'height: 1.25rem; width: 1.25rem; margin-right: 0.5rem;');

                    if (isMet) {
                        svg.classList.add('password-requirement-icon-met');
                        svg.setAttribute('viewBox', '0 0 20 20');
                        svg.setAttribute('fill', '#059669'); // Default green for light mode
                        
                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('fill-rule', 'evenodd');
                        path.setAttribute('d', 'M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z');
                        path.setAttribute('clip-rule', 'evenodd');

                        svg.appendChild(path);

                        item.classList.add('password-requirement-met');
                        item.style.color = '#059669'; // Default green for light mode
                        item.style.fontWeight = '500';
                    } else {
                        svg.classList.add('password-requirement-icon');
                        svg.setAttribute('viewBox', '0 0 24 24');
                        svg.setAttribute('fill', 'none');
                        svg.setAttribute('stroke', '#6b7280'); // Default gray for light mode

                        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                        path.setAttribute('stroke-linecap', 'round');
                        path.setAttribute('stroke-linejoin', 'round');
                        path.setAttribute('stroke-width', '2');
                        path.setAttribute('d', 'M6 18L18 6M6 6l12 12');

                        svg.appendChild(path);

                        item.classList.remove('password-requirement-met');
                        item.style.color = '#6b7280'; // Default gray for light mode
                        item.style.fontWeight = 'normal';
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
</section>
