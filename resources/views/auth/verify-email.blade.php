<x-guest-layout>
    <h4 class="mb-3">{{ __('Verify Email') }}</h4>

    <p class="text-muted small mb-4">
        {{ __('Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success mb-4">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>{{ __('Resend Verification Email') }}</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-secondary-button type="submit">{{ __('Log Out') }}</x-secondary-button>
        </form>
    </div>
</x-guest-layout>
