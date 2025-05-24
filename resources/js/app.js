import './bootstrap';

import Alpine from 'alpinejs';

// Initialize Alpine.js
window.Alpine = Alpine;

// Dark mode store
document.addEventListener('alpine:init', () => {
    Alpine.store('darkMode', {
        on: localStorage.getItem('darkMode') === 'true',
        toggle() {
            this.on = !this.on;
            localStorage.setItem('darkMode', this.on);
            if (this.on) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    });
});

// Listen for dark mode toggle event
document.addEventListener('toggle-dark-mode', () => {
    Alpine.store('darkMode').toggle();
});

// Initialize dark mode on page load
if (localStorage.getItem('darkMode') === 'true') {
    document.documentElement.classList.add('dark');
}

Alpine.start();
