<x-guest-layout>
    <h4 class="mb-3">{{ __('Forgot Password') }}</h4>
    <p class="text-muted small mb-4">{{ __('Forgotten your password? Enter your email and we\'ll send a reset link.') }}</p>

    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="d-flex justify-content-end">
            <x-primary-button>{{ __('Email Password Reset Link') }}</x-primary-button>
        </div>
    </form>
</x-guest-layout>
