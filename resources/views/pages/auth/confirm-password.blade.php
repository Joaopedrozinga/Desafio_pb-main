<x-layouts::auth.card :title="__('Confirmar password')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirmar password')"
            :description="__('Esta é uma área segura da aplicação. Por favor, confirme a sua password antes de continuar.')"
        />

        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify
            options-route="passkey.confirm-options"
            submit-route="passkey.confirm"
            :label="__('Confirmar com passkey')"
            :loading-label="__('A confirmar...')"
            :separator="__('Ou confirmar com password')"
        />

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6">
            @csrf

            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Digite a sua password')"
                viewable
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirmar') }}
            </flux:button>
        </form>
    </div>
</x-layouts::auth.card>
