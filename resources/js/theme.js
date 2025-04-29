document.addEventListener('alpine:init', () => {
    Alpine.data('appearance', () => ({
        theme: applyAppearance,
        init() {
            this.theme = window.localStorage.getItem('appearance') || 'system'
        },
        darkMode() {
            this.theme = 'dark'
            applyAppearance('dark')
        },
        lightMode() {
            this.theme = 'light'
            applyAppearance('light')
        },
        systemMode() {
            this.theme = 'system'  // Fix typo from 'systen'
            applyAppearance('system')
        }
    }));
});