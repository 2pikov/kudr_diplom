<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav me-auto" id="mainNav">
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('welcome') }}">Главная</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('about') ? 'active' : '' }}"  href="{{ route('about') }}">О нас</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('catalog') ? 'active' : '' }}"
                       href="{{ route('catalog') }}">Каталог</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('contacts') ? 'active' : '' }}" href="{{ route('where') }}">Контакты</a>
                </li>
                @auth
                <li class="nav-item">
                    <a class="nav-link {{ Request::is('calculator') ? 'active' : '' }}" href="{{ route('calculator') }}">Калькулятор</a>
                </li>
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto" id="authNav">
                 <li class="nav-item">
                      @guest
                            <div class="d-flex gap-1">
                                 <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                                  <a class="nav-link" href="{{ route('login') }}">Вход</a>
                             </div>
                        @endguest
                  @auth
                        <div class="d-flex gap-2">
                             <a class="nav-link {{ Request::is('user') ? 'active' : '' }}" href="{{ route('user') }}">Профиль</a>
                            <a class="nav-link {{ Request::is('cart') ? 'active' : '' }}" href="{{ route('cart') }}">Корзина</a>
                        </div>
                  @endauth
               </li>
            </ul>
        </div>
    </div>
</nav>

@auth
    @if (Auth::user()->is_admin === 1)
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="/product-create" class="nav-link {{ Request::is('product-create') ? 'active' : '' }}"
                           aria-current="page">Создать товар</a>
                    </li>
                    <li class="nav-item">
                        <a href="/products" class="nav-link {{ Request::is('products') ? 'active' : '' }}">Управление
                            товарами</a>
                    </li>
                    <li class="nav-item">
                        <a href="/category-create" class="nav-link {{ Request::is('category-create') ? 'active' : '' }}">Создать
                            категорию</a>
                    </li>
                    <li class="nav-item">
                        <a href="/categories" class="nav-link {{ Request::is('categories') ? 'active' : '' }}">Управление
                            категориями</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.orders') }}" class="nav-link {{ Request::is('orders') ? 'active' : '' }}">Управление заказами</a>
                    </li>
                </ul>
            </div>
        </nav>
    @endif
@endauth