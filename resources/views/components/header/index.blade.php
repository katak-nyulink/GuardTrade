<div class="flex flex-col shadow-lg" :class="stickyHeader ? 'sticky top-0 z-10' : ''">
    <header
        class="flex justify-between items-center h-16 py-4 px-6 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 ">
        <!-- start::Mobile menu button -->
        <div class="flex items-center">
            {{-- <button x-bind="sidebarToggle" class="text-gray-500 hover:text-primary focus:outline-none transition duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
            </button> --}}

            <x-button icon="bars-3" xl gray outline x-bind="sidebarToggle" class="px-2 py-1.5 cursor-pointer" />
        </div>
        <!-- end::Mobile menu button -->

        <!-- start::Right side top menu -->
        <div class="flex items-center gap-3">
            <!-- start::Search input -->
            <form class="relative">
                <x-input placeholder="Search..." icon-right="magnifying-glass" />
                {{-- <button class="absolute right-2 top-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button> --}}
            </form>
            <!-- end::Search input -->

            <!-- start::Notifications -->
            {{-- <div x-data="{ linkActive: false }" class="relative">
                <!-- start::Main link -->
                <div @click="linkActive = !linkActive" class="cursor-pointer flex">
                    <x-heroicon-o-bell class="size-6"/>
                    <sub>
                        <span class="bg-red-600 text-gray-100 px-1.5 py-0.5 rounded-full -ml-1 animate-pulse">
                            4
                        </span>
                    </sub>
                </div>
                <!-- end::Main link -->
                
                <!-- start::Submenu -->
                <div  x-cloak x-show="linkActive" x-transition @click.away="linkActive = false" class="absolute right-0 w-96 top-11 border bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 z-10">
                    <!-- start::Submenu content -->
                    <div class="rounded max-h-96 overflow-y-scroll custom-scrollbar">
                        <!-- start::Submenu header -->
                        <div class="flex items-center justify-between px-4 py-2">
                            <span class="font-bold">Notifications</span>
                            <span class="text-xs px-1.5 py-0.5 bg-red-600 text-gray-100 rounded">
                                4 new
                            </span>
                        </div>
                        <hr>
                        <!-- end::Submenu header -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <x-heroicon-o-shopping-cart class="w-8 h-8 bg-primary/20 text-primary px-1.5 py-0.5 rounded-full" />
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Order Completed</p>
                                    <p class="text-xs">Your order is completed</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                5 min ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <img src="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" class="w-8 rounded-full">
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Maria sent you a message</p>
                                    <p class="text-xs">Hey there, how are you do...</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                30 min ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 bg-primary bg-opacity-20 text-primary px-1.5 py-0.5 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Order Completed</p>
                                    <p class="text-xs">Your order is completed</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                54 min ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <img src="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" class="w-8 rounded-full">
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Maria sent you a message</p>
                                    <p class="text-xs">Hey there, how are you do...</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                1 hour ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 bg-primary bg-opacity-20 text-primary px-1.5 py-0.5 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Order Completed</p>
                                    <p class="text-xs">Your order is completed</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                15 hours ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <img src="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" class="w-8 rounded-full">
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Maria sent you a message</p>
                                    <p class="text-xs">Hey there, how are you do...</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                12 day ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <svg class="w-8 h-8 bg-primary bg-opacity-20 text-primary px-1.5 py-0.5 rounded-full" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Order Completed</p>
                                    <p class="text-xs">Your order is completed</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                3 months ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="#" class="flex items-center justify-between py-4 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <img src="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" class="w-8 rounded-full">
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Maria sent you a message</p>
                                    <p class="text-xs">Hey there, how are you do...</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold">
                                10 months ago
                            </span>
                        </a>
                        <!-- end::Submenu link -->
                    </div>
                    <!-- end::Submenu content -->
                </div>
                <!-- end::Submenu -->
            </div> --}}

            <x-dropdown w-96 class="divide-y max-h-96 overflow-y-auto custom-scrollbar">
                @slot('trigger')
                    <x-button flat gray circle sm class="cursor-pointer p-0.5">
                        <x-icon name="bell" class="size-5" />
                        {{-- <sub> --}}
                        <span
                            class="absolute -top-1 -right-2 bg-red-600 text-gray-100 px-1.5 py-0.5 rounded-full animate-pulse">
                            4
                        </span>
                        {{-- </sub> --}}
                    </x-button>
                @endslot
                {{-- 
                <li class="flex items-center px-4 py-2 text-xs font-medium text-gray-500 bg-gray-50 dark:bg-gray-700 dark:text-gray-400 uppercase gap-x-2">
                    <span>{{ __('Notifications') }}</span>
                </li>
                <li>
                    <a href="#" class="inline-flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 gap-x-3 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-white group transition ease-in-out cursor-pointer">
                        <div class="flex items-center">
                        <x-icon name="shopping-cart" class="size-8 bg-primary/20 text-primary px-1.5 py-0.5 rounded-full"/>
                        <div class="text-sm ml-3">
                            <p class="font-bold capitalize group-hover:text-primary dark:group-hover:text-blue-500">Order Completed</p>
                            <p class="text-gray-600 dark:text-gray-500 text-xs">Your order is completed</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold">
                        5 min ago
                    </span>
                    </a>
                </li> --}}

                <li
                    class="flex items-center justify-between px-4 py-3 text-xs font-medium text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 uppercase gap-x-2 select-none">
                    <span>{{ __('Notifications') }}</span>
                    <a href="#" class="hover:text-gray-500 dark:hover:text-gray-400"> {{ __('View all') }}</a>
                </li>

                <x-dropdown-item class="justify-between">
                    <div class="flex items-center">
                        <x-icon name="shopping-cart"
                            class="size-8 bg-primary/20 text-primary px-1.5 py-0.5 rounded-full" />
                        <div class="text-sm ml-3">
                            <p class="font-bold capitalize group-hover:text-primary dark:group-hover:text-blue-500">
                                Order Completed</p>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Your order is completed</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold">
                        5 min ago
                    </span>
                </x-dropdown-item>

                <x-dropdown-item class="justify-between">
                    <div class="flex items-center">
                        <x-avatar image="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" rounded-full
                            sm />
                        <div class="text-sm ml-3">
                            <p class="font-bold capitalize group-hover:text-primary dark:group-hover:text-blue-500">
                                Maria sent you a message</p>
                            <p class="text-gray-600 dark:text-gray-400 text-xs">Hey there, how are you do...</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold">
                        30 min ago
                    </span>
                </x-dropdown-item>

            </x-dropdown>

            <x-dropdown label="Select theme">
                @slot('trigger')
                    <x-button flat gray circle sm class="cursor-pointer p-0.5">
                        <x-heroicon-o-sun class="block size-5 dark:hidden" />
                        <x-heroicon-o-moon class="hidden size-5 dark:block" />
                    </x-button>
                @endslot
                <x-dropdown-item label="Light" icon="sun" x-on:click="lightMode()" ::aria-selected="theme === 'light'"
                    ::class="{ 'text-sky-400 hover:text-sky-500 *:text-sky-400 *:group-hover:text-sky-500': theme === 'light' }" />
                <x-dropdown-item label="Dark" icon="moon" x-on:click="darkMode()" ::aria-selected="theme === 'dark'"
                    ::class="{ 'text-sky-400 hover:text-sky-500 *:text-sky-400 *:group-hover:text-sky-500': theme === 'dark' }" />
                <x-dropdown-item label="System" icon="computer-desktop" x-on:click="systemMode()" ::aria-selected="theme !== 'light' && theme !== 'dark'"
                    ::class="{ 'text-sky-400 hover:text-sky-500 *:text-sky-400 *:group-hover:text-sky-500': theme === 'system' }" />
            </x-dropdown>
            <!-- end::Notifications -->

            <!-- start::Profile -->
            <x-dropdown title="Account settings">
                @slot('trigger')
                    {{-- <x-button icon="bell" flat circle /> --}}
                    <x-button flat circle sm class="cursor-pointer p-0">
                        <x-avatar image="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" rounded-full sm />
                    </x-button>
                @endslot

                <x-dropdown-item :label="__('Profile')" link="#" icon="user" />
                <x-dropdown-item :label="__('Update password')" icon="key" />
                <x-separator />
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        {{-- <x-dropdown-item label="Log out" type="submit" icon="arrow-right-end-on-rectangle" wire:navigate/> --}}
                        <button type="submit"
                            class="inline-flex items-center w-full px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 gap-x-3 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-white group transition ease-in-out cursor-pointer">
                            <x-heroicon-s-arrow-right-end-on-rectangle
                                class="size-5 group-hover:text-gray-500 dark:group-hover:text-gray-300" />
                            {{ __('Log Out') }}
                        </button>
                    </form>
                </li>
            </x-dropdown>

            {{-- <div x-data="{ linkActive: false }" class="relative">
                <!-- start::Main link -->
                <div @click="linkActive = !linkActive" class="cursor-pointer">
                    <img src="https://vojislavd.com/ta-template-demo/assets/img/profile.jpg" class="w-10 rounded-full">
                </div>
                <!-- end::Main link -->
                
                <!-- start::Submenu -->
                <div  x-cloak x-show="linkActive" @click.away="linkActive = false" class="absolute right-0 w-40 top-11 border border-gray-300 z-20">
                    <!-- start::Submenu content -->
                    <div class="bg-white rounded">
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="./pages/profile.html" class="flex items-center justify-between py-2 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Profile</p>
                                </div>
                            </div>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="./pages/email/inbox.html" class="flex items-center justify-between py-2 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">
                                        Inbox
                                        <span class="bg-red-600 text-gray-100 text-xs px-1.5 py-0.5 ml-2 rounded">3</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                        <!-- end::Submenu link -->
                        <!-- start::Submenu link -->
                        <a x-data="{ linkHover: false }" href="./pages/settings.html" class="flex items-center justify-between py-2 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <div class="text-sm ml-3">
                                    <p class="text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">Settings</p>
                                </div>
                            </div>
                        </a>
                        <!-- end::Submenu link -->
                        
                        <hr>

                        <!-- start::Submenu link -->
                        <form method="POST" action="{{ route('logout') }}" x-data="{ linkHover: false }" class="flex items-center justify-between py-2 px-3 hover:bg-gray-100 bg-opacity-20" @mouseover="linkHover = true" @mouseleave="linkHover = false">
                            @csrf
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                <button type="submit" class="text-sm ml-3 text-gray-600 font-bold capitalize" :class=" linkHover ? 'text-primary' : ''">
                                    Log out
                                </button>
                            </div>
                        </form>
                        <!-- end::Submenu link -->
                    </div>
                    <!-- end::Submenu content -->
                </div>
                <!-- end::Submenu -->
            </div> --}}
            <!-- end::Profile -->
        </div>
        <!-- end::Right side top menu -->
    </header>
</div>
