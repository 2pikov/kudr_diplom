@extends('layouts.app')

@section('content')
    <div class="shopping-cart">
        <h2 class="vash_korz">Корзина</h2>
        @if (count($cart) > 0)
            <div class="cart-info-summary">
                <span>{{ count($cart) }} товар</span>
            </div>

            <div class="cart-layout">
                <div class="cart-items-container">
                    @foreach ($cart as $item)
                        <div class="cart-item">
                            <div class="item-checkbox">
                                <input type="checkbox" class="select-item">
                            </div>

                            <div class="cart-item-info">
                                <div class="item-header">
                                    <div class="item-title-block">
                                        <h3 class="cart-item-title">{{ $item->title }}</h3>
                                    </div>
                                    <button class="delete-item" onclick="removeFromCart('{{ $item->id }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>

                                <div class="item-controls">
                                    <div class="quantity-controls">
                                        <button class="quantity-button quantity-button-minus {{ $item->qty == 1 ? 'quantity-button-disabled' : '' }}"
                                            id="decrease" cartid="{{ $item->id }}">-</button>
                                        <span class="cart-item-quantity">{{ $item->qty }}</span>
                                        <button class="quantity-button quantity-button-plus"
                                            id="increase" cartid="{{ $item->id }}">+</button>
                                    </div>
                                    <div class="price-info">
                                        <span class="current-price">{{ $item->price }} ₽</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="cart-sidebar">
                    <div class="order-summary">
                        <h3>Итого</h3>
                        <div class="summary-details">
                            <div class="summary-row">
                                <span>Товары ({{ count($cart) }})</span>
                                <span>{{ array_sum(array_map(function($item) { return $item->price * $item->qty; }, $cart)) }} ₽</span>
                            </div>
                        </div>
                        <a href="{{ route('quick-order') }}" class="checkout-button">Перейти к оформлению</a>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <p class="empty-cart-message">Корзина пуста</p>
                <a href="{{ route('catalog') }}" class="btn-to-catalog">Перейти в каталог</a>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    const cartItems = document.querySelectorAll('.cart-item');

    cartItems.forEach(item => {
        const increaseButton = item.querySelector('.quantity-button-plus');
        const decreaseButton = item.querySelector('.quantity-button-minus');
        const productId = Number(increaseButton.attributes.cartid.value);

        increaseButton.addEventListener('click', () => {
            fetch(`/changeqty/incr/${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при обновлении количества');
                });
        });

        decreaseButton.addEventListener('click', () => {
            fetch(`/changeqty/decr/${productId}`)
                 .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при обновлении количества');
                });
        });
    });

    function removeFromCart(id) {
        if (confirm('Вы уверены, что хотите удалить этот товар?')) {
            fetch(`/cart/remove/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Произошла ошибка при удалении товара');
            });
        }
    }
</script>
@endpush