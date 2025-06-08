@extends('layouts.app')
@section('content')
    <div class="cart">
        <table class="cart__table container">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Итого</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cart as $item)
                    <tr class="cart__raw">
                        <td>{{ $item->title }}</td>
                        <td class="cart__qty">
                            <span class="cart__qty-value">
                                {{ $item->qty }}
                            </span>
                        </td>
                        <td class="cart__price">{{ $item->price }}</td>
                        <td class="cart__price-total">{{ $item->price * $item->qty }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="">К оплате <span> {{ $total }} </span>
            <form action="/create-order" method="POST">
                @csrf
                <input type="password" class="password form-control" value="{{ old('password') }}"
                    autocomplete="current-password" name="password" placeholder="Введите пароль" required>
                <input type="submit" value="Сформировать заказ" class="order__confirm btn btn-success">
            </form>
        </div>
    </div>
    <script>
        const btn = document.querySelector('.order__confirm')
        const password = document.querySelector('.password')
        btn.addEventListener('click', (e) => {
            e.preventDefault()
            let response = undefined;
            fetch(`/create-order`, {
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        'password': password.value
                    }),
                    method: "POST"
                })
                .then(res => response = res.json())
                .finally(async () => {
                    let message = await response
                    if (message.message === 'err') {
                        password.classList.add('is-invalid')
                        setTimeout(() => {
                            password.classList.remove('is-invalid')
                        }, 10000);
                    } else {
                        password.classList.remove('is-invalid')
                        password.classList.add('is-valid')
                        setTimeout(() => {
                            window.location.replace('/user');
                        }, 500);
                    }
                })
        })
    </script>
@endsection
