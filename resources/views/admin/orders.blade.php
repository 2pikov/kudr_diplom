@extends('layouts.app')

@section('content')
<div class="container admin-orders">
    <div class="admin-header">
        <h1>Управление заказами</h1>
        
        <div class="filters">
            <a href="{{ route('admin.orders') }}" class="filter-link {{ !request('filter') ? 'active' : '' }}">
                Все заказы
            </a>
            <a href="{{ route('admin.orders', ['filter' => 'new']) }}" class="filter-link {{ request('filter') === 'new' ? 'active' : '' }}">
                Новые
            </a>
            <a href="{{ route('admin.orders', ['filter' => 'confirmed']) }}" class="filter-link {{ request('filter') === 'confirmed' ? 'active' : '' }}">
                Подтвержденные
            </a>
            <a href="{{ route('admin.orders', ['filter' => 'canceled']) }}" class="filter-link {{ request('filter') === 'canceled' ? 'active' : '' }}">
                Отмененные
            </a>
        </div>
    </div>

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
            <div class="order-card card mb-3">
                <div class="card-header order-header">
                    <div class="order-info">
                        <h2 class="card-title">Заказ №{{ $order['number'] }}</h2>
                        <div class="order-meta">
                            <span class="order-date">{{ \Carbon\Carbon::parse($order['created_at'])->format('d.m.Y H:i') }}</span>
                            <span class="order-status status-{{ strtolower($order['status']) }} badge rounded-pill">
                                @if($order['status'] === 'Новый')
                                    Новый
                                @elseif($order['status'] === 'Подтвержден')
                                    Подтвержден
                                @elseif($order['status'] === 'Отменен')
                                    Отменен
                                @else
                                    {{ $order['status'] }}
                                @endif
                            </span>
                        </div>
                        <div class="customer-info card-text">
                            <p><strong>Заказчик:</strong> {{ $order['customer'] }}</p>
                            <p><strong>Телефон:</strong> {{ $order['phone'] }}</p>
                            <p><strong>Email:</strong> {{ $order['email'] }}</p>
                            <p><strong>Адрес:</strong> {{ $order['address'] }}</p>
                        </div>
                    </div>
                    
                    @if($order['status'] === 'Новый')
                        <div class="order-actions">
                            <form action="{{ route('admin.orders.status', ['number' => $order['number'], 'action' => 'confirm']) }}" method="POST" class="d-inline me-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm">Подтвердить</button>
                            </form>
                            
                            <form action="{{ route('admin.orders.status', ['number' => $order['number'], 'action' => 'cancel']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены, что хотите отменить заказ?')">
                                    Отменить
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <div class="card-body order-items">
                    <h5 class="card-subtitle mb-2 text-muted">Товары в заказе:</h5>
                    @foreach($order['items'] as $item)
                        <div class="order-item d-flex justify-content-between">
                            <div class="item-info">
                                <span class="item-title">{{ $item['title'] }}</span>
                                <span class="item-quantity text-muted">{{ $item['quantity'] }} шт.</span>
                            </div>
                            <span class="item-price font-weight-bold">{{ number_format($item['total'], 0, '.', ' ') }} ₽</span>
                        </div>
                    @endforeach
                </div>

                <div class="card-footer order-footer">
                    <div class="order-total d-flex justify-content-between">
                        <span>Всего товаров: {{ $order['total_items'] }} шт.</span>
                        <span>Итого: <strong class="text-primary">{{ number_format($order['total_sum'], 0, '.', ' ') }} ₽</strong></span>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-orders alert alert-info">
                Заказов не найдено
            </div>
        @endforelse
    </div>
</div>

<style>
.admin-orders {
    padding: 20px;
    font-family: 'Exo 2', sans-serif;
}

.admin-header {
    margin-bottom: 30px;
}

.admin-header h1 {
    font-family: 'Exo 2', sans-serif;
    font-size: 2em;
    color: #333;
    margin-bottom: 20px;
}

.filters {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.filter-link {
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    color: #666;
    background: #f5f5f5;
    transition: all 0.3s ease;
    font-weight: 500;
}

.filter-link:hover {
    background: #e0e0e0;
    color: #333;
}

.filter-link.active {
    background: #dc3545;
    color: white;
}

.order-card {
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
}

.order-header {
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.order-info h2 {
    font-family: 'Exo 2', sans-serif;
    font-size: 1.3em;
    color: #333;
    margin-bottom: 10px;
}

.order-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.order-date {
    color: #666;
    font-size: 0.9em;
}

.order-status {
    font-size: 0.8em;
}

.status-новый.badge {
    background-color: #ffebee;
    color: #dc3545;
}

.status-подтвержден.badge {
    background-color: #e8f5e9;
    color: #198754;
}

.status-отменен.badge {
    background-color: #ffebee;
    color: #dc3545;
}

.customer-info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.customer-info p {
    margin: 5px 0;
    color: #555;
    font-size: 0.9em;
}

.order-items {
    margin: 20px 0;
}

.order-item {
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 8px;
}

.item-info {
    display: flex;
    gap: 15px;
    align-items: center;
}

.item-title {
    font-weight: 500;
    color: #333;
    font-size: 0.95em;
}

.item-quantity {
    font-size: 0.85em;
}

.item-price {
    color: #333;
    font-size: 1em;
}

.order-footer {
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.order-total {
    font-size: 1.1em;
    font-weight: 500;
}

.order-total strong {
    color: #dc3545;
}

.no-orders {
    margin-top: 30px;
    font-size: 1.2em;
    color: #666;
}

@media (max-width: 768px) {
    .admin-orders {
        padding: 15px;
    }

    .admin-header {
        margin-bottom: 20px;
    }

    .admin-header h1 {
        font-size: 1.8em;
    }

    .filters {
        gap: 8px;
    }

    .filter-link {
        padding: 8px 15px;
        font-size: 0.9em;
    }
    
    .order-card {
        padding: 15px;
    }

    .order-header {
        flex-direction: column;
        align-items: stretch;
        gap: 15px;
    }

    .order-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }

    .order-actions form {
        flex-grow: 1;
    }

    .order-actions .btn {
        width: 100%;
        text-align: center;
    }

    .order-info h2 {
        font-size: 1.4em;
    }

    .order-meta {
        flex-direction: column;
        gap: 5px;
    }

    .customer-info {
        padding: 10px;
    }

    .customer-info p {
        font-size: 0.85em;
    }

    .order-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 5px;
        padding: 10px;
    }

    .item-info {
        gap: 10px;
    }

    .item-title {
        font-size: 0.9em;
    }

    .item-quantity {
        font-size: 0.8em;
    }

    .item-price {
        font-size: 0.95em;
    }

    .order-total {
        font-size: 1em;
    }
}

@media (max-width: 576px) {
    .admin-header h1 {
        font-size: 1.6em;
    }

    .filter-link {
        padding: 6px 10px;
        font-size: 0.85em;
    }

    .order-actions {
        flex-direction: column;
        gap: 8px;
    }

     .order-actions form {
        flex-grow: initial;
    }
}
</style>
@endsection
