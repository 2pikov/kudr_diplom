<x-guest-layout>
    <div class="auth-container">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Забыли пароль? Без проблем. Просто сообщите нам свой адрес электронной почты, и мы отправим вам ссылку для сброса пароля, которая позволит выбрать новый.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <x-input-label for="email" value="{{ __('Email') }}" />
                <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="btn btn-primary">
                    {{ __('Отправить ссылку для сброса пароля') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

<style>
.auth-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 30px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    font-family: 'Exo 2', sans-serif;
}

.auth-container h1 {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
    font-size: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

.form-control {
    display: block;
    width: 100%;
    padding: 10px 15px;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 4px;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.form-control:focus {
    border-color: #d20d0d;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(210, 13, 13, 0.25);
}

.btn-primary {
    background-color: #d20d0d;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.2s ease;
}

.btn-primary:hover {
    background-color: #b00b0b;
}

.text-gray-600 {
    color: #6c757d;
}

.text-sm {
    font-size: 0.875em;
}

.mt-2 {
    margin-top: 0.5rem;
}

.mt-4 {
    margin-top: 1rem;
}

.mb-4 {
     margin-bottom: 1rem;
}

.flex {
    display: flex;
}

.items-center {
    align-items: center;
}

.justify-end {
    justify-content: flex-end;
}

.w-full {
    width: 100%;
}

/* Стили для ошибок валидации */
.mt-2.text-sm.text-red-600 {
    color: #dc3545;
    font-size: 0.875em;
}

/* Стили для сообщений о статусе (успех) */
.mb-4.font-medium.text-sm.text-green-600 {
    color: #28a745;
    font-size: 0.875em;
    font-weight: 500;
}
</style>
