document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        isCollapsed: true,

        init() {
            this.checkViewport();
            window.addEventListener('resize', () => (this.checkViewport()));
        },

        checkViewport() {
            const isDesktop = window.innerWidth > 1024;
            if (!isDesktop) {
                this.isCollapsed = true;
            }
        },

        toggle() {
            this.isCollapsed = !this.isCollapsed;
        }
    }));

    Alpine.bind('mainContent', () => ({
        ':class'() {
            return {
                'lg:ml-64': !this.isCollapsed,
                'lg:ml-16': this.isCollapsed,
            };
        }
    }));
    // Sidebar bindings
    Alpine.bind('sidebar', () => ({
        ':class'() {
            return {
                'collapsed': this.isCollapsed,
                // '-translate-x-full lg:translate-x-0 lg:w-16': this.isCollapsed,
                // 'translate-x-0 lg:translate-x-0 lg:w-64': !this.isCollapsed,
            };
        },

        '@resize.window'() {
            this.checkViewport();
        }
    }));

    // Menu item bindings
    Alpine.bind('sidebarItem', () => ({
        // ':class'() {
        //     return {
        //         'bg-gray-900': this.isActive,
        //         'hover:bg-gray-700': true
        //     };
        // },

        '@click'() {
            if (this.hasChildren && this.isCollapsed) {
                this.isCollapsed = false;
                this.$nextTick(() => {
                    this.open = true;
                });
            }
        }
    }));

    // Toggle button bindings
    Alpine.bind('sidebarToggle', () => ({
        '@click.stop'() {
            this.toggle();
        }
    }));

    // Sidebar backdrop bindings
    Alpine.bind('sidebarBackdrop', () => ({
        'x-cloak'() { },
        '@click.stop'() {
            this.isCollapsed = true;
        },
        'x-show'() {
            return !this.isCollapsed && window.innerWidth <= 1024;
        },
        'x-transition:enter'() {
            return 'transition-opacity duration-75 ease-out';
        },

        'x-transition:enter-start'() {
            return 'opacity-0';
        },
        'x-transition:enter-end'() {
            return 'opacity-100';
        },
        'x-transition:leave'() {
            return 'transition-opacity duration-75 ease-in';
        },
        'x-transition:leave-start'() {
            return 'opacity-100';
        },
        'x-transition:leave-end'() {
            return 'opacity-0';
        }
    }));

    // Add new dropdown menu component
    Alpine.data('sidebarDropdown', (initialOpen = false) => ({
        open: initialOpen,
        showAsPopup: false,

        init() {
            this.checkPopupMode();
            window.addEventListener('resize', () => this.checkPopupMode());
            this.$watch('isCollapsed', () => this.checkPopupMode());
        },

        checkPopupMode() {
            const wasShowingAsPopup = this.showAsPopup;
            this.showAsPopup = window.innerWidth > 1024 && this.isCollapsed;
            if (!this.showAsPopup && wasShowingAsPopup) {
                this.open = false;
            }
        },

        trigger: {
            ['@mouseover']() {
                return this.showAsPopup && (this.open = true)
            },
            ['@mouseleave']() {
                return this.showAsPopup && (this.open = false)
            },
            [':aria-expanded']() {
                return this.open;
            },
            [':class']() {
                return {
                    'active': this.open,
                    // '': !this.open,
                    'justify-center': this.isCollapsed
                    // 'border-primary': initialOpen
                }
            },
            ['@click']() {
                this.open = !this.open
            }
        },
        popOver: {
            ['x-show']() { return this.open && this.showAsPopup },
            ['x-transition']() { },
            ['x-anchor.right-start.offset.20']() {
                return this.$refs.trigger
            },
            ['@mouseover']() {
                return this.showAsPopup && (this.open = true)
            },
            ['@mouseleave']() {
                return this.showAsPopup && (this.open = false)
            },
            [':data-placement']() {
                return 'right-start'
            }
        }
    }));
});