<x-layouts::auth.card :title="__('Entrar')">
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Bem-vindo de volta')"
            :description="__('Digite o seu email e password para aceder à sua conta')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@exemplo.com"
            />

            <!-- Password -->
            <div class="flex flex-col gap-1">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Digite a sua password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link
                        class="text-sm text-right"
                        :href="route('password.request')"
                        wire:navigate
                    >
                        {{ __('Esqueceu a sua password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox
                name="remember"
                :label="__('Lembrar-me nesta sessão')"
                :checked="old('remember')"
            />

            <div class="flex items-center justify-end">
                <flux:button
                    variant="primary"
                    type="submit"
                    class="w-full"
                    data-test="login-button"
                >
                    {{ __('Entrar') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
            <span>{{ __('Ainda não tem conta?') }}</span>
            <flux:link :href="route('register')" wire:navigate>{{ __('Registar') }}</flux:link>
        </div>
    </div>
</x-layouts::auth.card>
