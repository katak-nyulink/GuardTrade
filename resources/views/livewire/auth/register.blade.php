<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new 
#[Layout('components.layouts.auth')] 
#[Title('Register')]
class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'password_confirmation'=>['required', 'string']
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // log_activity('User.registered');

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>
<div>
    <x-card-content class="px-10 py-8">
        <x-auth-header :title="__('auth.register.title')" :description="__('auth.register.description')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="register" class="flex flex-col gap-6">
            <!-- Name -->
            <x-input
                wire:model="name"
                :label="__('auth.inputs.name.label')"
                type="text"
                
                autofocus
                autocomplete="name"
                :placeholder="__('auth.inputs.name.placeholder')"
            />

            <!-- Email Address -->
            <x-input
                wire:model="email"
                :label="__('auth.inputs.email.label')"
                type="email"
                
                autocomplete="email"
                :placeholder="__('auth.inputs.email.placeholder')"
            />

            <!-- Password -->
            <x-input
                wire:model="password"
                :label="__('auth.inputs.password.label')"
                type="password"
                
                autocomplete="new-password"
                :placeholder="__('auth.inputs.password.placeholder')"
            />

            <!-- Confirm Password -->
            <x-input
                wire:model="password_confirmation"
                :label="__('auth.inputs.password_confirmation.label')"
                type="password"
                
                autocomplete="new-password"
                :placeholder="__('auth.inputs.password_confirmation.placeholder')"
            />

            <div class="flex items-center justify-end">
                <x-button type="submit" class="w-full cursor-pointer" spinner="register">
                    {{ __('auth.register.register_button') }}
                </x-button>
            </div>
        </form>

        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('auth.register.already_have_account') }}
            <a class="text-primary/90 hover:text-primary" href="{{ route('login') }}" wire:navigate>{{ __('auth.register.login_here') }}</a>
        </div>
    </x-card-content>
</div>
