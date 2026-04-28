<section>
    <h5 class="text-danger">{{ __('Delete Account') }}</h5>
    <p class="text-muted small mb-4">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>

    <x-danger-button data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">
        {{ __('Delete Account') }}
    </x-danger-button>

    <!-- Confirm User Deletion Modal -->
    <div class="modal fade" id="confirmUserDeletion" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Are you sure you want to delete your account?') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="text-muted small">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.') }}</p>

                        <div class="mb-3">
                            <x-input-label for="password" :value="__('Password')" class="visually-hidden" />
                            <x-text-input id="password" name="password" type="password" placeholder="{{ __('Password') }}" />
                            <x-input-error :messages="$errors->userDeletion->get('password')" />
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
