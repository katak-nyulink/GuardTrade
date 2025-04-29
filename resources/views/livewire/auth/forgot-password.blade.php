<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new
#[Layout('components.layouts.auth')] 
class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('auth.forgot_password.status'));
    }
}; ?>

<x-card-content class="flex flex-col gap-6">
    <x-auth-header :title="__('auth.forgot_password.title')" :description="__('auth.forgot_password.description')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <x-input
            wire:model="email"
            :label="__('auth.forgot_password.email')"
            type="email"
            autofocus
            placeholder="email@example.com"
        />

        <x-button type="submit" class="w-full">{{ __('auth.forgot_password.send_reset_link') }}</x-button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-gray-400">
        {{ __('auth.forgot_password.fallback') }}
        <a class="text-primary/90 hover:text-primary" href="{{ route('login') }}" wire:navigate>{{ __('auth.forgot_password.login_link') }}</a>
    </div>
</x-card-content>