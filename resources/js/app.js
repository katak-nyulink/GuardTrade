import './bootstrap';

import './theme';
import './sidebar';

// import Alpine from 'alpinejs';

Alpine.magic('dodo', () => {
    return {
        say(message) {
            console.log('Dodo says:', message);
            return message;
        },
        // applyAppearance(appearance) {
        //     applyAppearance(appearance);
        //     return appearance;
        // }
    }
});

// function applyAppearance(appearance) {
//     let applyDark = () => document.documentElement.classList.add('dark')
//     let applyLight = () => document.documentElement.classList.remove('dark')

//     if (appearance === 'system') {
//         let media = window.matchMedia('(prefers-color-scheme: dark)')

//         window.localStorage.removeItem('dodo.appearance')

//         media.matches ? applyDark() : applyLight()
//     } else if (appearance === 'dark') {
//         window.localStorage.setItem('dodo.appearance', 'dark')

//         applyDark()
//     } else if (appearance === 'light') {
//         window.localStorage.setItem('dodo.appearance', 'light')

//         applyLight()
//     }
// }

// applyAppearance(window.localStorage.getItem('dodo.appearance') || 'system');

// document.addEventListener('alpine:init', () => {
//     // Add Sidebar Component
//     Alpine.data('sidebar', () => ({
//         sidebarOpen: window.innerWidth > 1024,
//         sidebarMini: window.innerWidth > 1024,

//         init() {
//             this.$watch('sidebarOpen', value => {
//                 if (value) {
//                     document.body.classList.add('overflow-hidden');
//                 } else {
//                     document.body.classList.remove('overflow-hidden');
//                 }
//             });

//             window.addEventListener('resize', () => {
//                 this.sidebarOpen = window.innerWidth > 1024;
//                 this.sidebarMini = window.innerWidth > 1024;
//             });
//         },
//         toggle() {
//             this.sidebarOpen = !this.sidebarOpen;
//         },
//         open() {
//             this.sidebarOpen = true;
//         },
//         close() {
//             this.sidebarOpen = false;
//         },
//     }));

//     Alpine.bind('sidebarToggle', () => ({
//         ['@click.prevent']() {
//             this.toggle();
//         }
//     }));
//     Alpine.bind('sidebar', () => ({
//         [':class']() {
//             return this.sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'
//             // this.sidebarMini && this.sidebarOpen ? 'lg:w-20 lg:hover:w-64' : 'w-64 lg:translate-x-0 '
//         }
//     }));
//     Alpine.bind('sidebarBackdrop', () => ({
//         ['x-cloak']() { },
//         ['@click.prevent']() {
//             this.close()
//         },
//         [':class']() {
//             this.sidebarOpen ? 'fixed inset-0 z-40 bg-black opacity-50' : 'hidden'
//         },
//         ['x-show']() {
//             return this.sidebarOpen
//         },
//         ['x-transition:enter']() {
//             return 'transition-opacity duration-300 ease-out'
//         },

//         ['x-transition:enter-start']() {
//             return 'opacity-0'
//         },
//         ['x-transition:enter-end']() {
//             return 'opacity-100'
//         },
//         ['x-transition:leave']() {
//             return 'transition-opacity duration-300 ease-in'
//         },
//         ['x-transition:leave-start']() {
//             return 'opacity-100'
//         },
//         ['x-transition:leave-end']() {
//             return 'opacity-0'
//         }


//     }));

//     Alpine.data('ehczlyothi', () => ({
//         selectedId: null,
//         init() { this.$nextTick(() => this.select(this.$id('fdsdafdafdafdsda', 1))) },
//         select(id) { this.selectedId = id }, isSelected(id) { return this.selectedId === id },
//         whichChild(el, parent) { return Array.from(parent.children).indexOf(el) + 1 }
//     }))

//     Alpine.bind('zxnjvumrjl', () => ({
//         ['x-ref']: 'tablist',
//         ['@keydown.right.prevent.stop']() { this.$focus.wrap().next() },
//         ['@keydown.home.prevent.stop']() { this.$focus.first() },
//         ['@keydown.page-up.prevent.stop']() { this.$focus.first() },
//         ['@keydown.left.prevent.stop']() { this.$focus.wrap().prev() },
//         ['@keydown.end.prevent.stop']() { this.$focus.last() },
//         ['@keydown.page-down.prevent.stop']() { this.$focus.last() },
//     }))

//     Alpine.bind('qlzoqbzppb', () => ({
//         [':id']() { return this.$id('fdsdafdafdafdsda', this.whichChild(this.$el.parentElement, this.$refs.tablist)) },
//         ['@click']() { this.select(this.$el.id) },
//         ['@focus']() { this.select(this.$el.id) },
//         [':tabindex']() { return this.isSelected(this.$el.id) ? 0 : -1 },
//         [':aria-selected']() { return this.isSelected(this.$el.id) },
//         [':class']() { return this.isSelected(this.$el.id) ? 'border-black bg-white' : 'border-transparent' },
//     }))
// })
