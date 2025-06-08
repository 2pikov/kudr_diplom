<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="{{ Vite::asset('resources/css/welcome.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/js/app.js'])
</head>

<body>
    @extends('layouts.app')
    @section('content')
    <div class="container">

    <div id="carouselExampleControls" class="carousel slide welcome-slider" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
      <img src="{{ Vite::asset('resources/media/images/slide1.svg')}}" class="slider_img d-block" alt="...">
    </div>
    <div class="carousel-item">
      <img src="{{ Vite::asset('resources/media/images/slide2.svg')}}" class="slider_img d-block" alt="...">
            </div>
            <div class="carousel-item">
      <img src="{{ Vite::asset('resources/media/images/slide3.svg')}}" class="slider_img d-block" alt="...">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Предыдущий</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls"  data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Следующий</span>
  </button>
</div>

<h2 class="plus-title zag_hits" style="text-align:center; margin: 8px 0 12px 0;">Плюсы нашего магазина</h2>
<div class="categories-section mb-5">
    <div class="container-fluid px-4">
        <div id="categoriesCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <!-- Слайд 1 -->
                <div class="carousel-item active">
                    <div class="categories-grid">
                        <!-- Стройматериалы -->
                        <a href="{{ route('catalog', ['category' => 1]) }}" class="category-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-hammer"></i>
                                </div>
                                <h3>Сухие смеси</h3>
                                <p>Все для строительства</p>
                            </div>
                        </a>

                        <!-- Отделка -->
                        <a href="{{ route('catalog', ['category' => 2]) }}" class="category-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-paint-roller"></i>
                                </div>
                                <h3>Клеевая продукция</h3>
                                <p>Материалы для ремонта</p>
                            </div>
                        </a>

                        <!-- Инструменты -->
                        <a href="{{ route('catalog', ['category' => 3]) }}" class="category-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-paint-brush"></i>
                                </div>
                                <h3>Лакокрасочные материалы</h3>
                                <p>Краски, лаки и эмали</p>
                            </div>
                        </a>

                        <!-- Сантехника -->
                        <a href="{{ route('catalog', ['category' => 4]) }}" class="category-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-shower"></i>
                                </div>
                                <h3>Герметики</h3>
                                <p>Для ванной и кухни</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Слайд 2 -->
                <div class="carousel-item">
                    <div class="categories-grid">
                        <!-- Электрика -->
                        <a href="{{ route('catalog', ['category' => 5]) }}" class="category-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-tint"></i>
                                </div>
                                <h3>Пропитки</h3>
                                <p>Защитные составы</p>
                            </div>
                        </a>

                        <!-- Калькулятор -->
                        <a href="#" onclick="handleCalculatorClick(event)" class="category-card service-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-calculator"></i>
                                </div>
                                <h3>Калькулятор</h3>
                                <p>Расчет материалов</p>
                            </div>
                        </a>

                        <!-- Обратная связь -->
                        <a href="{{ route('about') }}#call" class="category-card service-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-comments"></i>
                                </div>
                                <h3>Обратная связь</h3>
                                <p>Задайте вопрос</p>
                            </div>
                        </a>

                        <!-- Отзывы -->
                        <a href="#" class="category-card service-card" data-aos="fade-up">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <h3>Отзывы</h3>
                                <p>Мнения клиентов</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Навигация -->
            <button class="carousel-control-prev" type="button" data-bs-target="#categoriesCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
            <button class="carousel-control-next" type="button" data-bs-target="#categoriesCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>

            <!-- Индикаторы -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#categoriesCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#categoriesCarousel" data-bs-slide-to="1"></button>
            </div>
        </div>
    </div>
</div>



    </div>
    <section class="hits">
    <h1 class="zag_hits">Хиты продаж</h1>
        <div class="hits-container">
            <div class="hits-grid">
                @foreach($hits as $product)
                    <div class="hit-card">
                        <div class="hit-label">Хит</div>
                        <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" 
                             alt="{{ $product->title }}" 
                             class="hit-image">
                        <h3 class="hit-title">{{ $product->title }}</h3>
                        <div class="hit-info">
                            <div class="hit-property">
                                <span>Страна производителя:</span>
                                <span>{{ $product->country }}</span>
                            </div>
                            <div class="hit-property">
                                <span>Вес:</span>
                                <span>{{ $product->weight }}</span>
                </div>
                </div>
                        <div class="hit-price">{{ number_format($product->price, 0, ',', ' ') }} ₽</div>
                    </div>
                @endforeach
        </div>
        <div class="catalog-button">
            <a href="{{ route('catalog') }}" class="btn-to-catalog">Перейти в каталог</a>
            </div>
    </div>
</section>

<section class="otzovi">
    <div class="container">
        <h1 class="zag_hits">Отзывы</h1>
        <div class="row">
            <div class="otz">
                <img src="{{ Vite::asset('resources/media/images/otz.png')}}" width="290" alt="">
                <h1 class="otz__header">Черенев Роман</h1>
                <p class="otz__text">Отличное качество</p>
            </div>
            <div class="otz">
                <img src="{{ Vite::asset('resources/media/images/otz2.png')}}" width="290" alt="">
                <h1 class="otz__header">Одинова Надежда</h1>
                <p class="otz__text">Быстрая доставка</p>
            </div>
            <div class="otz">
                <img src="{{ Vite::asset('resources/media/images/otz3.png')}}" width="290" alt="">
                <h1 class="otz__header">Тупиков Никита</h1>
                <p class="otz__text">Хорошие материалы</p>
            </div>
        </div>
    </div>
</section>
<section class="partners">
    <div class="partners-container">
        <h2 class="zag_hits">Наши партнеры</h2>
        <p class="partners-subtitle">Мы сотрудничаем с ведущими производителями строительных материалов</p>
        
        <div class="partners-grid">
            <a href="{{ route('catalog', ['manufacturer' => 'Knauf']) }}" class="partner-card" data-aos="zoom-in">
                <img src="{{ Vite::asset('resources/media/images/knauf.jpg')}}" alt="Knauf" class="partner-logo">
                <div class="partner-overlay">
                    <h3>Knauf</h3>
                </div>
            </a>

            <a href="{{ route('catalog', ['manufacturer' => 'МОМЕНТ']) }}" class="partner-card" data-aos="zoom-in" data-aos-delay="100">
                <img src="{{ Vite::asset('resources/media/images/moment.webp')}}" alt="moment" class="partner-logo">
                <div class="partner-overlay">
                    <h3>МОМЕНТ</h3>
                </div>
            </a>

            <a href="{{ route('catalog', ['manufacturer' => 'Цементум']) }}" class="partner-card" data-aos="zoom-in" data-aos-delay="200">
                <img src="{{ Vite::asset('resources/media/images/цементум.webp')}}" alt="Цементум" class="partner-logo">
                <div class="partner-overlay">
                    <h3>Цементум</h3>
                </div>
            </a>

            <a href="{{ route('catalog', ['manufacturer' => 'KUDO']) }}" class="partner-card" data-aos="zoom-in" data-aos-delay="300">
                <img src="{{ Vite::asset('resources/media/images/KUDO5.jpg')}}" alt="KUDO" class="partner-logo">
                <div class="partner-overlay">
                    <h3>KUDO</h3>
                </div>
            </a>
        </div>
    </div>
</section>


    @endsection

    <script>
    function addToCart(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновляем количество товаров в корзине
                document.querySelector('.cart-count').textContent = data.cartCount;
                // Показываем уведомление
                alert('Товар добавлен в корзину');
            }
        });
    }

    function toggleFavorite(productId) {
        fetch('/favorites/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Показываем уведомление
                alert(data.message);
            }
        });
    }

    function handleCalculatorClick(event) {
        event.preventDefault();
        @auth
            window.location.href = "{{ route('calculator') }}";
        @else
            var authModal = new bootstrap.Modal(document.getElementById('authModal'));
            authModal.show();
        @endauth
    }
    </script>

    <!-- Модальное окно для авторизации -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Требуется авторизация</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Для использования калькулятора необходимо авторизоваться или зарегистрироваться.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('login') }}" class="btn btn-primary">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Зарегистрироваться</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
