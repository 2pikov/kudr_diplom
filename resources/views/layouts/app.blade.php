<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'МастерСтрой') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Unbounded:wght@200..900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <link href="{{ Vite::asset('resources/css/notifications.css') }}" rel="stylesheet">
        @stack('styles')
    </head>
    <body>
        <div>
            @include('layouts.obloz')
            @include('layouts.navigation')
            
            
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            
            <main class="container mt-4">
                @yield('content')
            </main>
        </div>
        
        @include('layouts.footer')
        </div>

        <style>
        .telegram-widget-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }

        .telegram-toggle-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #0088cc;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: transform 0.2s;
        }

        .telegram-toggle-btn:hover {
            transform: scale(1.1);
            background: #006699;
        }

        #telegram-widget {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 350px;
            height: 450px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .telegram-header {
            padding: 15px;
            background: #0088cc;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 12px 12px 0 0;
        }

        .telegram-header h3 {
            margin: 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
            opacity: 0.8;
            transition: opacity 0.2s;
        }

        .close-btn:hover {
            opacity: 1;
        }

        .telegram-frame-container {
            height: calc(100% - 52px);
            overflow: hidden;
        }
        </style>

        <script>
        function toggleTelegramWidget() {
            const widget = document.getElementById('telegram-widget');
            widget.style.display = widget.style.display === 'none' ? 'block' : 'none';
        }
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="{{ Vite::asset('resources/js/notifications.js') }}"></script>
        @stack('scripts')

        <div id="notification" class="notification">
            <i class="fas fa-check-circle"></i>
            <span class="notification-text"></span>
        </div>

        <!-- Модальное окно авторизации -->
        <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="authModalLabel">Требуется авторизация</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Для продолжения зарегистрируйтесь или авторизуйтесь</p>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('login') }}" class="btn btn-primary">Войти</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary">Зарегистрироваться</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
