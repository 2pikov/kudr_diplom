@extends('layouts.app')

@section('content')
<div class="favorites-page">
    <div class="container">
        <h1>Избранные товары</h1>
        
        <div class="sorting-block">
            <form action="{{ route('favorites') }}" method="GET" class="sort-form">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>По дате добавления (сначала новые)</option>
                    <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>По дате добавления (сначала старые)</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>По цене (сначала дешевые)</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>По цене (сначала дорогие)</option>
                    <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>По рейтингу</option>
                </select>
            </form>
        </div>

        <div class="products-grid">
            @forelse($favorites as $favorite)
                <div class="card1">
                    @if($favorite->product->is_new)
                        <div class="product-badge badge-new">Новинка</div>
                    @endif
                    @if($favorite->product->discount > 0)
                        <div class="product-badge badge-sale">-{{ $favorite->product->discount }}%</div>
                    @endif
                    
                    <a href="/product/{{ $favorite->product->id }}" style="text-decoration: none">
                        <img src="{{ Vite::asset('resources/media/images/') . $favorite->product->img }}" 
                             alt="{{ $favorite->product->title }}" 
                             class="hit-image">
                        <h3 class="hit-title">{{ $favorite->product->title }}</h3>
                        <div class="hit-info">
                            <div class="hit-property">
                                <span>Страна производителя:</span>
                                <span>{{ $favorite->product->country }}</span>
                            </div>
                            @if ($favorite->product->weight)
                            <div class="hit-property">
                                <span>Вес:</span>
                                <span>{{ $favorite->product->weight }}</span>
                            </div>
                            @elseif ($favorite->product->obiem)
                            <div class="hit-property">
                                <span>Объем:</span>
                                <span>{{ $favorite->product->obiem }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="product-rating">
                            <div class="rating-stars">
                                @php
                                    $rating = $favorite->product->reviews->avg('rating') ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = $rating - $fullStars >= 0.5;
                                @endphp
                                
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $fullStars)
                                        <i class="fas fa-star"></i>
                                    @elseif ($i == $fullStars + 1 && $hasHalfStar)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                            </div>
                            <span class="rating-count">({{ $favorite->product->reviews->count() }})</span>
                        </div>
                        
                        <div class="hit-price">{{ number_format($favorite->product->price, 0, ',', ' ') }} ₽</div>
                    </a>

                    <div class="hit-actions">
                        <button class="btn-add-cart" onclick="addToCart(event, {{ $favorite->product->id }})">
                            В корзину
                        </button>
                        <button class="btn-favorite2 active" onclick="toggleFavorite(event, {{ $favorite->product->id }})">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>
                </div>
            @empty
                <div class="no-favorites">
                    <p>В избранном пока нет товаров</p>
                    <a href="{{ route('catalog') }}" class="btn-to-catalog">Перейти в каталог</a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.favorites-page {
    padding: 40px 0;
    background: #fff;
}

.favorites-page h1 {
    margin-bottom: 30px;
    font-size: 32px;
    color: #333;
}

.sorting-block {
    margin-bottom: 30px;
}

.sort-form {
    max-width: 300px;
}

.form-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 40px;
}

.no-favorites {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.no-favorites p {
    font-size: 18px;
    color: #666;
    margin-bottom: 20px;
}

.btn-to-catalog {
    display: inline-block;
    padding: 12px 24px;
    background: #cc0000;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 600;
    transition: background 0.3s;
}

.btn-to-catalog:hover {
    background: #b30000;
    color: white;
}

@media (max-width: 992px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function showNotification() {
    const notification = document.getElementById('cartNotification');
    notification.classList.add('show');
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

function addToCart(event, productId) {
    event.preventDefault();
    fetch('/add-to-cart/' + productId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification();
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
    });
}

function toggleFavorite(event, productId) {
    event.preventDefault();
    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => {
        if (response.status === 401) {
            window.location.href = '/login';
            return;
        }
        return response.json();
    })
    .then(data => {
        if (data && data.status === 'removed') {
            // Удаляем карточку товара из DOM
            event.target.closest('.card1').remove();
            
            // Проверяем, остались ли еще товары
            const remainingProducts = document.querySelectorAll('.card1');
            if (remainingProducts.length === 0) {
                location.reload(); // Перезагружаем страницу для отображения сообщения о пустом избранном
            }
        }
    });
}
</script>
@endsection 