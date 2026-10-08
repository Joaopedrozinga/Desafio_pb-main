<x-layouts::auth.card :title="__('Redefinir password')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Redefinir password')" :description="__('Introduza a sua nova password abaixo')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:input
                name="email"
                value="{{ request('email') }}"
                :label="__('Email')"
                type="email"
                required
                autocomplete="email"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Nova password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Digite a sua nova password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmar password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirme a sua nova password')"
                passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('Redefinir password') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth.card>
