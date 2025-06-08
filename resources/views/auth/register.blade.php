<x-guest-layout>
    <div class="auth-container">
        <h2 class="auth-title">Регистрация</h2>
        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf

            <div class="auth-form-group">
                <x-input-label for="name" :value="__('Имя')" class="auth-label" />
                <x-text-input id="name" class="auth-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="auth-error" />
            </div>

             <div class="auth-form-group">
                <x-input-label for="surname" :value="__('Фамилия')" class="auth-label" />
                <x-text-input id="surname" class="auth-input" type="text" name="surname" :value="old('surname')" required autofocus autocomplete="surname" />
                <x-input-error :messages="$errors->get('surname')" class="auth-error" />
            </div>

             <div class="auth-form-group">
                <x-input-label for="patronymic" :value="__('Отчество')" class="auth-label" />
                <x-text-input id="patronymic" class="auth-input" type="text" name="patronymic" :value="old('patronymic')" required autofocus autocomplete="patronymic" />
                <x-input-error :messages="$errors->get('patronymic')" class="auth-error" />
            </div>

              <div class="auth-form-group">
                <x-input-label for="login" :value="__('Логин')" class="auth-label" />
                <x-text-input id="login" class="auth-input" type="text" name="login" :value="old('login')" required autofocus autocomplete="login" />
                <x-input-error :messages="$errors->get('login')" class="auth-error" />
            </div>


            <div class="auth-form-group">
                <x-input-label for="email" :value="__('Email')" class="auth-label" />
                <x-text-input id="email" class="auth-input" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="auth-error" />
            </div>

            <div class="auth-form-group">
                <x-input-label for="password" :value="__('Пароль')" class="auth-label" />
                <x-text-input id="password" class="auth-input" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="auth-error" />
            </div>

            <div class="auth-form-group">
                <x-input-label for="password_confirmation" :value="__('Повторите пароль')" class="auth-label" />
                <x-text-input id="password_confirmation" class="auth-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="auth-error" />
            </div>

            <div class="auth-form-group">
                <x-input-label for="rules_confirmation" :value="__('Согласие с правилами регистрации')" class="auth-label" />
                <x-text-input id="rules_confirmation" class="auth-checkbox" type="checkbox" name="rules_confirmation" required />
                 <x-input-error :messages="$errors->get('rules_confirmation')" class="auth-error" />
            </div>


             <div class="auth-action-buttons flex items-center justify-between mt-4">
            <a class="auth-link" href="{{ route('login') }}">
                 Уже есть аккаунт?
             </a>

            <x-primary-button class="auth-button">
               Зарегистрироваться
            </x-primary-button>
             </div>
        </form>
    </div>
</x-guest-layout>