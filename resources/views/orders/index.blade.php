@extends('layouts.app')

@section('content')
<div class="orders-container">
    <h1 class="orders-title">Мои заказы</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="orders-list">
        @forelse($orders as $order)
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <h2 class="order-number">Заказ №{{ $order['number'] }}</h2>
                        <div class="order-meta">
                            <span class="order-date">{{ \Carbon\Carbon::parse($order['created_at'])->format('d.m.Y H:i') }}</span>
                            <span class="order-status status-{{ strtolower($order['status']) }}">{{ $order['status'] }}</span>
                        </div>
                    </div>

                    @if($order['status'] === 'Новый')
                        <div class="order-actions">
                            <form action="{{ route('order-delete', ['number' => $order['number']]) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить заказ?')">
                                    Удалить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="order-items">
                    @foreach($order['items'] as $item)
                        <div class="order-item">
                            <div class="item-info">
                                <span class="item-title">{{ $item['title'] }}</span>
                                <span class="item-quantity">{{ $item['quantity'] }} шт.</span>
                            </div>
                            <span class="item-price">{{ number_format($item['total'], 0, '.', ' ') }} ₽</span>
                        </div>
                    @endforeach
                </div>

                <div class="order-footer">
                    <div class="order-total">
                        <span>Всего товаров: {{ $order['total_items'] }} шт.</span>
                        <span>Итого: {{ number_format($order['total_sum'], 0, '.', ' ') }} ₽</span>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-orders">
                У вас пока нет заказов
            </div>
        @endforelse
    </div>
</div>

<style>
.orders-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
    font-family: var(--font-body);
}

.orders-title {
    font-family: var(--font-heading);
    font-size: 2em;
    color: #333;
    margin-bottom: 30px;
}

.order-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.order-number {
    font-family: var(--font-heading);
    font-size: 1.5em;
    color: #333;
    margin-bottom: 15px;
}

.order-meta {
    display: flex;
    gap: 20px;
    margin-bottom: 15px;
}

.order-date {
    color: #666;
}

.order-status {
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.9em;
}

.status-новый { 
    background: #e3f2fd;
    color: #0054BE;
}

.status-подтвержден { 
    background: #e8f5e9;
    color: #198754;
}

.status-отменен { 
    background: #ffebee;
    color: #dc3545;
}

.order-items {
    margin: 20px 0;
}

.order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
}

.item-info {
    display: flex;
    gap: 20px;
    align-items: center;
}

.item-title {
    font-weight: 500;
    color: #333;
}

.item-quantity {
    color: #666;
    font-size: 0.9em;
}

.item-price {
    font-weight: 600;
    color: #333;
}

.order-footer {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    color: #333;
}

.btn {
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover {
    background: #bb2d3b;
}

.no-orders {
    text-align: center;
    padding: 40px;
    background: #fff;
    border-radius: 12px;
    color: #666;
    font-size: 1.2em;
    border: 1px solid #e0e0e0;
}

.alert {
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

@media (max-width: 768px) {
    .order-header {
        flex-direction: column;
    }
    
    .order-actions {
        margin-top: 20px;
    }
    
    .item-info {
        flex-direction: column;
        gap: 5px;
        align-items: flex-start;
    }
    
    .order-total {
        flex-direction: column;
        gap: 10px;
        align-items: flex-start;
    }
}
</style>
@endsection 