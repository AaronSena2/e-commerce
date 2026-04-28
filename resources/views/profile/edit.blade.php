<x-app-layout>
    <x-slot name="header">
        <h4 class="mb-0">{{ __('Profile') }}</h4>
    </x-slot>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="card mb-4">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
