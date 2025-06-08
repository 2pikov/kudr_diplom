<x-guest-layout>
    <div class="auth-container">
        <h2 class="auth-title">Вход</h2>
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf

            <div class="auth-form-group">
                 <x-input-label for="login" :value="__('Логин')" class="auth-label" />
                <x-text-input id="login" class="auth-input" type="text" name="login" :value="old('login')" required autofocus autocomplete="username" />
                 <x-input-error :messages="$errors->get('login')" class="auth-error" />
            </div>


            <div class="auth-form-group">
                <x-input-label for="password" :value="__('Пароль')" class="auth-label" />
                <x-text-input id="password" class="auth-input" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            <div class="auth-form-group flex items-center">
               <input id="remember_me" type="checkbox" class="auth-checkbox" name="remember">
                  <label for="remember_me" class="ms-2 text-sm text-gray-600 auth-label">{{ __('Запомнить меня') }}</label>
            </div>

             <div class="auth-action-buttons flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="auth-link" href="{{ route('password.request') }}">
                            {{ __('Забыли пароль?') }}
                        </a>
                    @endif

                 <x-primary-button class="auth-button">
                       {{ __('Войти') }}
                   </x-primary-button>
           </div>

        </form>
    </div>
</x-guest-layout>