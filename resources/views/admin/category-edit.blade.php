@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="auth-container">
            <h1>Редактировать категорию</h1>
            <form action="/category-edit/{{ $category->id }}" method="POST">
                @csrf
                @method('put')
                <div class="mb-3">
                    <label for="product_type" class="form-label">Название типа продукта</label>
                    <input type="text" class="form-control" id="product_type" name="product_type" value="{{ $category->product_type }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </form>
        </div>
    </div>
@endsection
