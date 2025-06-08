@extends('layouts.app')

@section('content')
<div class="order-details">
    <h1>Заказ #{{ $order->number }}</h1>
    
    <div class="order-info">
        <div class="order-status">
            <h2>Статус заказа</h2>
            <p class="status {{ strtolower($order->status) }}">{{ $order->status }}</p>
        </div>

        <div class="order-dates">
            <h2>Даты</h2>
            <p>Создан: {{ $order->created_at->format('d.m.Y H:i') }}</p>
            <p>Обновлен: {{ $order->updated_at->format('d.m.Y H:i') }}</p>
        </div>

        <div class="order-bonuses">
            <h2>Бонусы</h2>
            <p>Использовано бонусов: {{ number_format($order->bonus_used, 0, '.', ' ') }}</p>
            <p>Начислено бонусов: {{ number_format($order->bonus_earned, 0, '.', ' ') }}</p>
        </div>

        <div class="order-contact">
            <h2>Контактная информация</h2>
            <p>Имя: {{ $order->name }}</p>
            <p>Телефон: {{ $order->phone }}</p>
            <p>Email: {{ $order->email }}</p>
            <p>Адрес: {{ $order->address }}</p>
        </div>
    </div>

    <div class="order-items">
        <h2>Товары</h2>
        <div class="items-list">
            @foreach($order->items as $item)
                <div class="order-item">
                    <img src="{{ Vite::asset('resources/media/images/' . $item->product->img) }}" alt="{{ $item->product->title }}">
                    <div class="item-info">
                        <h3>{{ $item->product->title }}</h3>
                        <p class="quantity">Количество: {{ $item->quantity }}</p>
                        <p class="price">{{ number_format($item->price, 0, '.', ' ') }} ₽</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
.order-details {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.order-details h1 {
    font-size: 32px;
    color: #001a34;
    margin-bottom: 40px;
}

.order-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
}

.order-info h2 {
    font-size: 20px;
    color: #001a34;
    margin-bottom: 15px;
}

.order-status .status {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 500;
}

.status.новый {
    background: #e3f2fd;
    color: #1976d2;
}

.status.в_обработке {
    background: #fff3e0;
    color: #f57c00;
}

.status.выполнен {
    background: #e8f5e9;
    color: #388e3c;
}

.status.отменен {
    background: #ffebee;
    color: #d32f2f;
}

.order-dates p,
.order-contact p,
.order-bonuses p {
    color: #707f8d;
    margin-bottom: 8px;
}

.order-items {
    background: #f8f8f8;
    padding: 30px;
    border-radius: 12px;
}

.order-items h2 {
    font-size: 24px;
    color: #001a34;
    margin-bottom: 20px;
}

.items-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-item {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: white;
    border-radius: 8px;
}

.order-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

.item-info {
    flex: 1;
}

.item-info h3 {
    font-size: 18px;
    color: #001a34;
    margin-bottom: 10px;
}

.quantity {
    color: #707f8d;
    margin-bottom: 5px;
}

.price {
    font-weight: 500;
    color: #001a34;
}

@media (max-width: 768px) {
    .order-info {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        flex-direction: column;
    }
    
    .order-item img {
        width: 100%;
        height: 200px;
    }
}
</style>
@endsection 