@extends('layouts.app')

@section('content')
<div class="profile-container">
    <div class="profile-sidebar">
        <h2 class="section-title">Личная информация</h2>
        
        <nav class="sidebar-menu">
            <a href="{{ route('favorites') }}" class="menu-link">
                <span>Избранное</span>
                <span class="counter">{{ auth()->user()->favorites()->count() }}</span>
            </a>
            <a href="{{ route('orders.index') }}" class="menu-link">
                <span>Мои заказы</span>
                <span class="counter">{{ count($orders) }}</span>
            </a>
            <a href="{{ route('comparisons.index') }}" class="menu-link">
                <span>Сравнения</span>
                <span class="counter">{{ auth()->user()->comparisons()->count() }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="menu-link">
                <span>Редактировать профиль</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-link logout">
                    <span>Выйти</span>
                </button>
            </form>
        </nav>
    </div>

    <div class="profile-content">
        <h2 class="section-title">Мои данные</h2>
        <div class="data-cards">
            <div class="data-card">
                <div class="data-title">Бонусный баланс</div>
                <div class="data-value">
                    {{ number_format($user->bonus_balance ?? 0, 0, '.', ' ') }} ₽
                </div>
            </div>
            <div class="data-card">
                <div class="data-title">Всего заказов</div>
                <div class="data-value">{{ count($orders) }}</div>
            </div>
            <div class="data-card">
                <div class="data-title">Избранных товаров</div>
                <div class="data-value">{{ auth()->user()->favorites()->count() }}</div>
            </div>
        </div>

        <h2 class="section-title orders-title">Мои заказы</h2>
        @if(count($orders) > 0)
            <div class="orders-list">
                @foreach($orders as $order)
                    <div class="order-card">
                        <div class="order-header">
                            <div class="order-info">
                                <div class="order-number">Заказ №{{ $order['number'] }}</div>
                                <div class="order-date">от {{ \Carbon\Carbon::parse($order['created_at'])->format('d.m.Y') }}</div>
                                <div class="order-status">Статус: {{ $order['status'] }}</div>
                            </div>
                        </div>

                        <div class="order-items">
                            @foreach($order['items'] as $item)
                                <div class="order-item">
                                    <span class="item-title">{{ $item->title }}</span>
                                    <span class="item-qty">{{ $item->qty }} шт.</span>
                                    <span class="item-price">{{ number_format($item->price * $item->qty, 0, '.', ' ') }} ₽</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="order-total">
                            <span>Итого:</span>
                            <span class="total-price">{{ number_format($order['items']->sum(function($item) { return $item->price * $item->qty; }), 0, '.', ' ') }} ₽</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-orders">
                У вас пока нет заказов
            </div>
        @endif
    </div>
</div>

<style>
.profile-container {
    display: flex;
    gap: 80px;
    max-width: 1600px;
    margin: 40px auto;
    padding: 0 60px;
}

.profile-sidebar {
    flex: 0 0 350px;
}

.section-title {
    font-size: 30px;
    font-weight: 500;
    color: #001a34;
    margin: 0 0 40px;
}

.orders-title {
    margin-top: 60px;
}

.sidebar-menu {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.menu-link {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0;
    color: #001a34;
    text-decoration: none;
    font-size: 20px;
    border-bottom: 1px solid #e5e5e5;
}

.menu-link:hover {
    color: #0054BE;
}

.counter {
    font-size: 16px;
    color: #707f8d;
}

.logout {
    border: none;
    background: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    color: #f91155;
    padding: 15px 0;
    font-size: 18px;
}

.profile-content {
    flex: 1;
}

.data-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 60px;
}

.data-card {
    background: #f8f8f8;
    padding: 30px;
    border-radius: 12px;
}

.data-title {
    font-size: 18px;
    color: #707f8d;
    margin-bottom: 15px;
}

.data-value {
    font-size: 30px;
    font-weight: 500;
    color: #001a34;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: #f8f8f8;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}

.order-header {
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.order-number {
    font-size: 20px;
    font-weight: 500;
    color: #333;
    margin-bottom: 5px;
}

.order-date {
    font-size: 16px;
    color: #666;
}

.order-status {
    font-size: 16px;
    margin-top: 5px;
    font-weight: 500;
    color: #0054BE;
}

.order-items {
    margin-bottom: 15px;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.item-title {
    font-size: 18px;
    flex: 1;
    margin-right: 20px;
}

.item-qty {
    font-size: 16px;
    color: #666;
    margin-right: 20px;
}

.item-price {
    font-size: 18px;
    font-weight: 500;
    min-width: 100px;
    text-align: right;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 15px;
    font-weight: 500;
    font-size: 18px;
}

.total-price {
    font-size: 20px;
    color: #333;
}

.no-orders {
    font-size: 18px;
    text-align: center;
    padding: 40px;
    background: #f8f8f8;
    border-radius: 12px;
    color: #666;
}

@media (max-width: 1400px) {
    .profile-container {
        max-width: 1200px;
        padding: 0 40px;
        gap: 60px;
    }
}

@media (max-width: 1024px) {
    .profile-container {
        gap: 40px;
        padding: 0 30px;
    }

    .data-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
        padding: 0 20px;
    }
    
    .profile-sidebar {
        flex: none;
        width: 100%;
    }
    
    .data-cards {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection