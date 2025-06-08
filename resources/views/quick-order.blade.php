@extends('layouts.app')

@section('content')
<div class="quick-order">
    <h1>Оформление заказа</h1>

    <div class="quick-order__content">
        <div class="quick-order__products">
            @foreach($products as $product)
                <div class="quick-order__product">
                    <img src="{{ Vite::asset('resources/media/images/' . $product->img) }}" alt="{{ $product->title }}" onclick="openImageModal(this.src)">
                    <div class="quick-order__product-info">
                        <h2>{{ $product->title }}</h2>
                        <div class="product-details">
                            <span class="quantity">{{ $product->quantity }} шт.</span>
                            <span class="price">{{ number_format($product->price, 0, '.', ' ') }} ₽</span>
                        </div>
                    </div>
                </div>
            @endforeach
            
            <div class="quick-order__total">
                <span>Итого:</span>
                <span class="total-price">{{ number_format($total, 0, '.', ' ') }} ₽</span>
            </div>
        </div>

        <form action="{{ route('quick-order.store') }}" method="POST" class="quick-order__form" id="orderForm">
            @csrf
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

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
                           max="{{ min(auth()->user()->bonus_balance ?? 0, $total * 0.3) }}" 
                           step="1" 
                           value="0"
                           onchange="updateTotal()">
                    <span class="bonus-currency">бонусов</span>
                </div>
                <div class="final-price">
                    <span>Итоговая сумма:</span>
                    <span id="final-amount">{{ number_format($total, 0, '.', ' ') }} ₽</span>
                </div>
            </div>

            <div class="form-group">
                <label>Имя</label>
                <input type="text" name="name" required placeholder="Введите ваше имя" value="{{ old('name') }}">
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Телефон</label>
                <input type="tel" name="phone" required placeholder="Введите номер телефона" value="{{ old('phone') }}">
                @error('phone')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Введите email" value="{{ old('email') }}">
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Адрес доставки</label>
                <input type="text" name="address" required placeholder="Введите адрес доставки" value="{{ old('address') }}">
                @error('address')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit" id="submitOrder">Оформить заказ</button>
        </form>
    </div>
</div>

<!-- Модальное окно для изображений -->
<div class="image-modal" id="imageModal">
    <div class="modal-content">
        <button class="close-modal" onclick="closeImageModal()">&times;</button>
        <img id="modalImage" src="" alt="Увеличенное изображение">
    </div>
</div>

<style>
.quick-order {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.quick-order h1 {
    font-size: 32px;
    color: #001a34;
    margin-bottom: 40px;
}

.quick-order__content {
    display: grid;
    grid-template-columns: 1fr 1.5fr;
    gap: 40px;
}

.quick-order__products {
    background: #f8f8f8;
    padding: 30px;
    border-radius: 12px;
}

.quick-order__product {
    display: flex;
    gap: 20px;
    padding: 20px 0;
    border-bottom: 1px solid #e5e5e5;
}

.quick-order__product img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
}

.quick-order__product-info {
    flex: 1;
}

.quick-order__product-info h2 {
    font-size: 18px;
    color: #001a34;
    margin-bottom: 10px;
}

.product-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.quantity {
    color: #707f8d;
}

.price {
    font-weight: 500;
    color: #001a34;
}

.quick-order__total {
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

.quick-order__form {
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

.form-group input {
    padding: 12px;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    font-size: 16px;
}

.error {
    color: #f91155;
    font-size: 14px;
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

.btn-submit {
    background: #0054BE;
    color: white;
    border: none;
    padding: 15px;
    border-radius: 8px;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.3s;
}

.btn-submit:hover {
    background: #0046a0;
}

.image-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    z-index: 1000;
}

.image-modal.active {
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
}

.modal-content img {
    max-width: 100%;
    max-height: 90vh;
    object-fit: contain;
}

.close-modal {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 30px;
    cursor: pointer;
}

@media (max-width: 768px) {
    .quick-order__content {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const submitButton = document.getElementById('submitOrder');
    submitButton.disabled = true;
    submitButton.textContent = 'Оформление заказа...';
    this.submit();
});

function openImageModal(imageSrc) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    modalImage.src = imageSrc;
    modal.classList.add('active');
    
    // Закрытие по клику вне изображения
    modal.onclick = function(e) {
        if (e.target === modal) {
            closeImageModal();
        }
    };
    
    // Закрытие по клавише Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeImageModal();
        }
    });
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.remove('active');
}

function updateTotal() {
    const totalAmount = {{ $total }};
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