<x-layouts::auth.card :title="__('Password esquecida')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Password esquecida')"
            :description="__('Introduza o seu email para receber um link de redefinição de password')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@exemplo.com"
            />

            <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('Enviar link de redefinição') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
            <span>{{ __('Ou, voltar para') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('entrar') }}</flux:link>
        </div>
    </div>
</x-layouts::auth.card>
