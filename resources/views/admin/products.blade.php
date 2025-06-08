@extends('layouts.app')
@section('content')
<style>
.table-responsive {
    width: 100%;
    overflow-x: auto;
    margin-top: 20px;
}
.cart_table.container {
    width: 100%;
    max-width: 1400px;
    margin: 20px auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    font-size: 0.9rem;
    font-family: 'Exo 2', sans-serif;
    border-collapse: collapse;
}
.cart_table.container th, .cart_table.container td {
    padding: 10px 8px;
    vertical-align: middle;
    border: 1px solid #eee;
    text-align: center;
}
.cart_table.container th {
    background-color: #f8f8f8;
    font-weight: 600;
}
.cart_table.container tr {
    vertical-align: middle;
    border-bottom: none !important;
}
.cart_table.container td:last-child {
    display: flex;
    gap: 5px;
    align-items: center !important;
    justify-content: center;
    height: 100%;
    padding: 10px 8px;
}
.cart_table.container .btn {
    min-width: auto;
    font-size: 0.8rem;
    padding: 5px 10px;
    white-space: nowrap;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}
.cart_table.container .btn-primary {
    background-color: #d20d0d;
    border-color: #d20d0d;
    color: white;
}
.cart_table.container .btn-primary:hover {
    background-color: #b30000;
    border-color: #b30000;
}
.cart_table.container .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}
.cart_table.container .btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
}
.cart_table.container form {
    display: inline;
    margin: 0;
}
.cart_table.container img {
    display: block;
    margin: 0 auto;
    max-height: 60px;
    width: auto;
    border-radius: 8px;
}
@media (max-width: 900px) {
    .cart_table.container {
        font-size: 0.8rem;
    }
    .cart_table.container th, .cart_table.container td {
        padding: 8px 5px;
    }
    .cart_table.container .btn {
        font-size: 0.75rem;
        padding: 4px 8px;
    }
    .cart_table.container img {
        max-height: 50px;
    }
}
@media (max-width: 600px) {
    .cart_table.container {
        font-size: 0.75rem;
    }
    .cart_table.container th, .cart_table.container td {
        padding: 6px 3px;
    }
    .cart_table.container .btn {
        font-size: 0.7rem;
        padding: 3px 6px;
    }
    .cart_table.container img {
        max-height: 40px;
    }
}
</style>
<div class="container">
    <div class="table-responsive">
        <table class="cart_table container">
            <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="cart_raw">
                        <td><img src="{{ Vite::asset('resources/media/images/') . $product->img }}" alt="" srcset=""
                                width="100px"></td>
                        <td>{{ $product->title }}</td>
                        <td>{{ $product->product_type }}</td>
                        <td>{{ $product->qty }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            <a href="/product-edit/{{ $product->id }}" class="btn btn-primary">Ред.</a>
                            <form action="/product-delete/{{ $product->id }}" method="POST" style="display:inline; margin:0;">
                                @method('delete')
                                @csrf
                                <input type="submit" class="btn btn-danger" value="Удал." style="min-width:auto;">
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection