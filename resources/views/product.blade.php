@extends('layouts.app')
@section('content')
    <div class="product">
        <nav class="product__breadcrumbs">
            <a href="/">Главная</a> / 
            <a href="/catalog">Каталог</a> / 
            <span>{{ $product->title }}</span>
        </nav>
        
        <div class="product__header">
            <h1 class="product__title">{{ $product->title }}</h1>
            <div class="product__meta">
                <div class="product__meta-left">
                    <div class="product__code">Код товара: {{ $product->id }}</div>
                    <div class="product__rating">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star">{{ $i <= (isset($product->average_rating) ? round($product->average_rating) : 0) ? '★' : '☆' }}</span>
                            @endfor
                        </div>
                        <a href="#reviews" class="reviews-link">{{ $product->reviews->count() }} отзывов</a>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="product__content-wrapper">
            <div class="product__left-column">
                <div class="product__gallery">
                    <div class="product__main-image">
                        <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" alt="{{ $product->title }}">
                    </div>
                </div>
            </div>
            <div class="product__right-column">
                <div class="product__top-info-section">
                    {{-- <div class="product__info-block">
                        <div class="info-row">
                            <span class="info-label">Цвет:</span>
                            <span class="info-value">{{ $product->color }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Вес нетто:</span>
                            <span class="info-value">{{ $product->weight }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Основа:</span>
                            <span class="info-value">{{ $product->osnova }}</span>
                        </div>
                        <a href="#" class="all-characteristics">Все характеристики</a>
                    </div> --}}
                    <div class="product__price-block">
                        <div class="price-amount">{{ $product->price }} ₽</div>
                        <div class="product__buttons-container">
                            <button class="btn-add-to-cart">В корзину</button>
                        </div>
                        <button class="btn-favorite">
                            <span class="favorite-icon">♡</span>
                            В избранное
                        </button>
                        <button class="btn-compare">Сравнить</button>
                    </div>
                </div>
                
                <div class="product__bottom-info-section">
                    <div class="product__bonuses">
                        <div class="bonus-row">
                            <span class="bonus-icon">💎</span>
                            <span>Получите 5% бонусами, при оформлении товара!</span>
                        </div>
                    </div>
                    <div class="product__availability">
                        <div class="availability-row">
                            <span class="check-icon">✓</span>
                            <span>В наличии {{ $product->qty }} шт.</span>
                        </div>
                    </div>
                    {{-- Здесь можно добавить блоки для самовывоза и курьера, если они есть --}}
                </div>
            </div>
        </div>

        <div class="product__tabs">
            <div class="tabs__header">
                <button class="tab-btn active" data-tab="characteristics">Характеристики</button>
                <button class="tab-btn" data-tab="reviews">Отзывы</button>
            </div>

            <div class="tab-content active" id="characteristics">
                <table class="characteristics-table">
                    <tr>
                        <td>О товаре</td>
                        <td>{{ $product->dop_info }}</td>
                    </tr>
                    <tr>
                        <td>Категория</td>
                        <td>{{ $product->product_type }}</td>
                    </tr>
                    <tr>
                        <td>Вес</td>
                        <td>{{ $product->weight }}</td>
                    </tr>
                    <tr>
                        <td>Цвет</td>
                        <td>{{ $product->color }}</td>
                    </tr>
                    <tr>
                        <td>Основа</td>
                        <td>{{ $product->osnova }}</td>
                    </tr>
                    <tr>
                        <td>Температура применения</td>
                        <td>{{ $product->tempa }}°C</td>
                    </tr>
                    <tr>
                        <td>Время застывания</td>
                        <td>{{ $product->time }}ч.</td>
                    </tr>
                    <tr>
                        <td>Срок годности</td>
                        <td>{{ $product->srok_godnosti }}дн.</td>
                    </tr>
                </table>
            </div>

            <div class="tab-content" id="reviews">
                @auth
                    <form id="reviewForm" class="review-form" action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="form-group">
                            <label>Ваша оценка</label>
                            <div class="rating-input">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" value="{{ $i }}" id="star{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}">★</label>
                                @endfor
                            </div>
                            <span class="error" id="ratingError"></span>
                        </div>
                        <div class="form-group">
                            <label>Ваш отзыв</label>
                            <textarea name="content" required>{{ old('content') }}</textarea>
                            <span class="error" id="contentError"></span>
                        </div>
                        <button type="submit">Отправить отзыв</button>
                    </form>
                @else
                    <p>Чтобы оставить отзыв, пожалуйста, <a href="{{ route('login') }}">войдите</a></p>
                @endauth

                <div class="reviews-list">
                    @forelse($product->reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <span class="review-author">{{ $review->user->name }}</span>
                                <span class="review-date">{{ $review->created_at->format('d.m.Y') }}</span>
                                <div class="review-rating">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="star">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                    @endfor
                                </div>
                            </div>
                            <div class="review-content">
                                {{ $review->content }}
                            </div>
                        </div>
                    @empty
                        <p>Пока нет отзывов</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>


    <style>
    /* Стили для тостов */
    .toast-container {
        z-index: 9999;
    }

    .toast {
        background: #fff;
        border: none;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        min-width: 300px;
    }

    .toast-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 0.75rem 1rem;
    }

    .toast-icon {
        font-size: 1.25rem;
        line-height: 1;
        margin-right: 0.5rem;
    }

    .toast-body {
        padding: 1rem;
        color: #333;
        font-size: 16px;
    }

    .btn-close {
        opacity: 0.5;
        transition: opacity 0.2s;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* Анимация появления */
    .toast.showing {
        opacity: 0;
        transform: translateY(100%);
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
        transition: all 0.3s ease-in-out;
    }

    /* Хлебные крошки */
    .product__breadcrumbs {
        font-size: 16px;
    }

    /* Заголовок продукта */
    .product__title {
        font-size: 34px;
    }

    /* Мета-информация */
    .product__code {
        font-size: 16px;
    }

    .reviews-link {
        font-size: 16px;
    }

    .product__guarantee {
        font-size: 16px;
    }

    /* Кнопки действий */
    .btn-favorite, .btn-compare {
        font-size: 16px;
    }

    /* Информация о продукте */
    .info-label {
        font-size: 16px;
    }

    .info-value {
        font-size: 18px;
    }

    .all-characteristics {
        font-size: 16px;
    }

    /* Цена и кнопки */
    .price-amount {
        font-size: 32px;
    }

    .btn-add-to-cart, .btn-quick-order {
        font-size: 18px;
    }

    /* Бонусы и наличие */
    .bonus-row, .availability-row {
        font-size: 16px;
    }

    /* Табы */
    .tab-btn {
        font-size: 18px;
    }

    /* Характеристики */
    .characteristics-table td {
        font-size: 16px;
        font-family: 'Exo 2', sans-serif;
    }

    /* Отзывы */
    .review-author {
        font-size: 18px;
        font-family: 'Exo 2', sans-serif;
    }

    .review-date {
        font-size: 16px;
        font-family: 'Exo 2', sans-serif;
    }

    .review-content {
        font-size: 16px;
        font-family: 'Exo 2', sans-serif;
    }

    /* Стили для формы отзыва */
    .review-form {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 30px;
        font-family: 'Exo 2', sans-serif;
    }

    .review-form label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #333;
        font-size: 15px;
        font-family: 'Exo 2', sans-serif;
    }

    .review-form textarea {
        width: 100%;
        min-height: 100px;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-top: 5px;
        font-size: 14px;
        resize: vertical;
        font-family: 'Exo 2', sans-serif;
    }

    .review-form textarea:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .review-form button {
        background-color: #d20d0d;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: all 0.3s ease;
        margin-top: 15px;
    }

    .review-form button:hover {
        background-color: #b00b0b;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .review-form button:active {
        background-color: #8a0909;
        transform: translateY(0);
        box-shadow: none;
    }

    .error {
        color: #dc3545;
        font-size: 14px;
        margin-top: 5px;
        display: block;
        font-family: 'Exo 2', sans-serif;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .rating-input {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 5px;
    }

    .rating-input input {
        display: none;
    }

    .rating-input label {
        cursor: pointer;
        font-size: 24px;
        color: #ddd;
        padding: 0 2px;
        transition: color 0.2s ease;
    }

    .rating-input input:checked ~ label,
    .rating-input label:hover,
    .rating-input label:hover ~ label {
        color: #f1c40f;
    }

    .product__content-wrapper {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }

    .product__left-column {
        flex: 1 1 350px;
        min-width: 320px;
    }

    .product__right-column {
        display: block;
        width: 370px;
        min-width: 320px;
        max-width: 100%;
        margin-left: auto;
    }

    .product__info-price-container {
        display: block;
        padding: 16px 12px 16px 12px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 20px;
        width: 100%;
    }

    .product__info-block {
        margin-bottom: 24px;
        width: 100%;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
        word-break: break-word;
    }
    .info-label {
        font-size: 16px;
        color: #888;
        flex-shrink: 0;
        margin-right: 8px;
        min-width: 80px;
        max-width: 120px;
        word-break: break-word;
    }
    .info-value {
        font-size: 18px;
        color: #222;
        word-break: break-word;
        white-space: normal;
        flex: 1 1 auto;
        text-align: right;
        min-width: 80px;
    }

    .product__price-block {
        margin-top: 0;
        margin-bottom: 0;
        width: 100%;
    }

    @media (max-width: 900px) {
        .product__content-wrapper {
            flex-direction: column;
            gap: 20px;
        }
        .product__right-column, .product__left-column {
            max-width: 100%;
            min-width: 0;
        }
    }

    /* Сообщение об отсутствии отзывов */
    .reviews-list p {
        text-align: center;
        color: #666;
        padding: 30px 0;
        font-size: 14px;
        font-family: 'Exo 2', sans-serif;
    }
    </style>

    <script>
    // Функция для показа тоста
    function showToast(message, type = 'success') {
        const toast = document.getElementById('cartToast');
        const toastBody = toast.querySelector('.toast-body');
        const bsToast = new bootstrap.Toast(toast, {
            animation: true,
            autohide: true,
            delay: 3000
        });

        toastBody.textContent = message;
        if (type === 'success') {
            toast.classList.add('border-success');
            toastBody.style.color = '#198754';
        } else {
            toast.classList.add('border-danger');
            toastBody.style.color = '#dc3545';
        }

        bsToast.show();

        toast.addEventListener('hidden.bs.toast', function () {
            toast.classList.remove('border-success', 'border-danger');
        });
    }

    // Функция для показа модального окна авторизации
    function showAuthModal() {
        const authModal = new bootstrap.Modal(document.getElementById('authModal'));
        authModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const addToCartBtn = document.querySelector('.btn-add-to-cart');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                fetch('/add-to-cart/{{ $product->id }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.status === 401) {
                        showAuthModal();
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        showToast('Товар успешно добавлен в корзину', 'success');
                    } else {
                        showToast(data.message || 'Произошла ошибка', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Произошла ошибка при добавлении товара', 'error');
                });
            });
        }

        const productId = '{{ $product->id }}';

        const favoriteBtn = document.querySelector('.btn-favorite');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
                fetch('/favorites/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (response.status === 401) {
                        showAuthModal();
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'added') {
                        this.classList.add('active');
                        this.querySelector('.favorite-icon').textContent = '♥';
                        showToast('Товар добавлен в избранное', 'success');
                    } else if (data && data.status === 'removed') {
                        this.classList.remove('active');
                        this.querySelector('.favorite-icon').textContent = '♡';
                        showToast('Товар удален из избранного', 'success');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Произошла ошибка', 'error');
                });
            });
        }

        const compareBtn = document.querySelector('.btn-compare');
        if (compareBtn) {
            compareBtn.addEventListener('click', function() {
                fetch('/comparisons/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    if (response.status === 401) {
                        showAuthModal();
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.status === 'added') {
                        this.classList.add('active');
                        showToast('Товар добавлен к сравнению', 'success');
                    } else if (data && data.status === 'removed') {
                        this.classList.remove('active');
                        showToast('Товар удален из сравнения', 'success');
                    } else if (data && data.status === 'error') {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Произошла ошибка', 'error');
                });
            });
        }

        const quickOrderBtn = document.querySelector('.btn-quick-order');
        if (quickOrderBtn) {
            quickOrderBtn.addEventListener('click', function(e) {
                e.preventDefault();
                fetch('/quick-order/{{ $product->id }}', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (response.status === 401) {
                        showAuthModal();
                        return;
                    }
                    window.location.href = this.href;
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Произошла ошибка', 'error');
                });
            });
        }
    });

    // Обработка табов
    const tabBtns = document.querySelectorAll('.tab-btn');
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const tabId = this.dataset.tab;
            
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            
            this.classList.add('active');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // Обработка отправки отзыва
    document.addEventListener('DOMContentLoaded', function() {
        const reviewForm = document.getElementById('reviewForm');
        if (reviewForm) {
            reviewForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                document.getElementById('ratingError').textContent = '';
                document.getElementById('contentError').textContent = '';

                const formData = new FormData(this);

                fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (response.status === 401) {
                        showAuthModal();
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                        reviewForm.reset();
                        
                        const reviewsList = document.querySelector('.reviews-list');
                        const newReview = document.createElement('div');
                        newReview.className = 'review-item';
                        newReview.innerHTML = `
                            <div class="review-header">
                                <span class="review-author">${data.review.user.name}</span>
                                <span class="review-date">${data.review.created_at}</span>
                                <div class="review-rating">
                                    ${Array(5).fill().map((_, i) => 
                                        `<span class="star">${i < data.review.rating ? '★' : '☆'}</span>`
                                    ).join('')}
                                </div>
                            </div>
                            <div class="review-content">
                                ${data.review.content}
                            </div>
                        `;
                        
                        const emptyMessage = reviewsList.querySelector('p');
                        if (emptyMessage) {
                            emptyMessage.remove();
                        }
                        
                        reviewsList.insertBefore(newReview, reviewsList.firstChild);
                        
                        const stars = document.querySelector('.product__rating .stars');
                        if (stars) {
                            stars.innerHTML = Array(5).fill().map((_, i) => 
                                `<span class="star">${i < Math.round(data.average_rating) ? '★' : '☆'}</span>`
                            ).join('');
                        }
                        
                        const reviewsLink = document.querySelector('.reviews-link');
                        if (reviewsLink) {
                            const currentCount = parseInt(reviewsLink.textContent);
                            reviewsLink.textContent = `${currentCount + 1} отзывов`;
                        }

                        showToast('Отзыв успешно добавлен', 'success');
                    } else {
                        if (data.errors) {
                            if (data.errors.rating) {
                                document.getElementById('ratingError').textContent = data.errors.rating[0];
                            }
                            if (data.errors.content) {
                                document.getElementById('contentError').textContent = data.errors.content[0];
                            }
                        }
                        showToast('Ошибка при добавлении отзыва', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Произошла ошибка при отправке отзыва', 'error');
                });
            });
        }
    });
    </script>

    <!-- Контейнер для тостов (уведомлений) -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Уведомление</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                <!-- Здесь будет текст уведомления -->
            </div>
        </div>
    </div>

    <!-- Модальное окно для авторизации (если еще не добавлено в layouts/app.blade.php) -->
    {{-- Проверьте, добавлено ли уже это модальное окно в главный шаблон layouts/app.blade.php --}}
    {{-- Если добавлено, этот блок можно удалить во избежание дублирования ID --}}
    <div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="authModalLabel">Требуется авторизация</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Для выполнения этого действия необходимо авторизоваться или зарегистрироваться.</p>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('login') }}" class="btn btn-primary">Войти</a>
                    <a href="{{ route('register') }}" class="btn btn-secondary">Зарегистрироваться</a>
                </div>
            </div>
        </div>
    </div>
@endsection