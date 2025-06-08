@extends('layouts.app')

@section('content')
<div class="checkout-container">
    <h1 class="section-title">Оформление заказа</h1>

    <div class="checkout-content">
        <div class="order-summary">
            <h2>Ваш заказ</h2>
            <div class="cart-items">
                @foreach($cartItems as $item)
                    <div class="cart-item">
                        <div class="item-info">
                            <span class="item-title">{{ $item->title }}</span>
                            <span class="item-qty">{{ $item->quantity }} шт.</span>
                        </div>
                        <span class="item-price">{{ number_format($item->price * $item->quantity, 0, '.', ' ') }} ₽</span>
                    </div>
                @endforeach
            </div>
            <div class="order-total">
                <span>Итого:</span>
                <span class="total-price">{{ number_format($totalAmount, 0, '.', ' ') }} ₽</span>
            </div>
        </div>

        <form method="POST" action="{{ route('orders.create') }}" class="checkout-form">
            @csrf
            
            <div class="bonus-section">
                <h3>Использовать бонусы</h3>
                <div class="bonus-info">
                    <p>Доступно бонусов: <strong>{{ number_format(auth()->user()->bonus_balance ?? 0, 0, '.', ' ') }}</strong></p>
                    <p>Можно использовать до 30% от суммы заказа</p>
                </div>
                <div class="bonus-input">
                    <input type="number" 
                           name="use_bonus" 
                           id="use_bonus" 
                           min="0" 
                           max="{{ min(auth()->user()->bonus_balance ?? 0, $totalAmount * 0.3) }}" 
                           step="1" 
                           value="0"
                           onchange="updateTotal()">
                    <span class="bonus-currency">бонусов</span>
                </div>
                <div class="final-price">
                    <span>Итоговая сумма:</span>
                    <span id="final-amount">{{ number_format($totalAmount, 0, '.', ' ') }} ₽</span>
                </div>
            </div>

            <div class="form-group">
                <label for="name">Имя</label>
                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" required>
            </div>

            <div class="form-group">
                <label for="phone">Телефон</label>
                <input type="tel" id="phone" name="phone" value="{{ auth()->user()->phone }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required>
            </div>

            <div class="form-group">
                <label for="address">Адрес доставки</label>
                <textarea id="address" name="address" required>{{ auth()->user()->address }}</textarea>
            </div>

            <button type="submit" class="submit-button">Оформить заказ</button>
        </form>
    </div>
</div>

<style>
.checkout-container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.section-title {
    font-size: 32px;
    color: #001a34;
    margin-bottom: 40px;
}

.checkout-content {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 40px;
}

.order-summary {
    background: #f8f8f8;
    padding: 30px;
    border-radius: 12px;
}

.order-summary h2 {
    font-size: 24px;
    color: #001a34;
    margin-bottom: 20px;
}

.cart-items {
    margin-bottom: 20px;
}

.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #e5e5e5;
}

.item-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.item-title {
    font-size: 18px;
    color: #001a34;
}

.item-qty {
    font-size: 16px;
    color: #707f8d;
}

.item-price {
    font-size: 18px;
    font-weight: 500;
    color: #001a34;
}

.order-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    font-size: 20px;
    font-weight: 500;
}

.total-price {
    color: #001a34;
}

.checkout-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-size: 16px;
    color: #001a34;
}

.form-group input,
.form-group textarea {
    padding: 12px;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    font-size: 16px;
}

.form-group textarea {
    height: 100px;
    resize: vertical;
}

.bonus-section {
    background: #f8f8f8;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.bonus-section h3 {
    font-size: 20px;
    color: #001a34;
    margin-bottom: 15px;
}

.bonus-info {
    margin-bottom: 15px;
}

.bonus-info p {
    color: #707f8d;
    margin-bottom: 5px;
}

.bonus-input {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.bonus-input input {
    width: 120px;
    padding: 10px;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    font-size: 16px;
}

.bonus-currency {
    color: #707f8d;
}

.final-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 18px;
    font-weight: 500;
    color: #001a34;
    padding-top: 15px;
    border-top: 1px solid #e5e5e5;
}

.submit-button {
    background: #0054BE;
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.3s;
}

.submit-button:hover {
    background: #0046a0;
}

@media (max-width: 768px) {
    .checkout-content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function updateTotal() {
    const totalAmount = {{ $totalAmount }};
    const bonusInput = document.getElementById('use_bonus');
    const finalAmount = document.getElementById('final-amount');
    
    let bonusValue = parseInt(bonusInput.value) || 0;
    const maxBonus = Math.min({{ auth()->user()->bonus_balance ?? 0 }}, totalAmount * 0.3);
    
    if (bonusValue > maxBonus) {
        bonusValue = maxBonus;
        bonusInput.value = maxBonus;
    }
    
    const finalSum = Math.max(0, totalAmount - bonusValue);
    finalAmount.textContent = new Intl.NumberFormat('ru-RU').format(finalSum) + ' ₽';
}
</script>
@endsection 