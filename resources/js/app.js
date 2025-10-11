import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Import mini-cart component
import './components/miniCart';

// Import small add-to-cart helper which will wire product forms to AJAX
import './components/addToCart';
// Import cart actions (update/remove) for AJAX behavior on cart page
import './components/cartActions';

Alpine.start();

import './register-validation';

// Dark Mode Toggle
document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtn = document.getElementById('theme-toggle');
    
    if (themeToggleBtn) {
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        const applyTheme = (theme) => {
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                if(themeToggleLightIcon) themeToggleLightIcon.classList.remove('hidden');
                if(themeToggleDarkIcon) themeToggleDarkIcon.classList.add('hidden');
            } else {
                document.documentElement.classList.remove('dark');
                if(themeToggleDarkIcon) themeToggleDarkIcon.classList.remove('hidden');
                if(themeToggleLightIcon) themeToggleLightIcon.classList.add('hidden');
            }
        };

        const savedTheme = localStorage.getItem('color-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const initialTheme = savedTheme ? savedTheme : (prefersDark ? 'dark' : 'light');
        
        applyTheme(initialTheme);

        themeToggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            applyTheme(newTheme);
            localStorage.setItem('color-theme', newTheme);
        });
    }
});
