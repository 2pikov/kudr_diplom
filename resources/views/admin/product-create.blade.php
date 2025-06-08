@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="auth-container">
            <form action="/product-create" method="POST">
                @method('post')
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Название</label>
                    <input type="text" class="form-control" id="title" name="title">
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Цена</label>
                    <input type="number" class="form-control" id="price" name="price">
                </div>
                <div class="mb-3">
                    <label for="qty" class="form-label">Количество</label>
                    <input type="number" class="form-control" id="qty" name="qty">
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Цвет</label>
                    <input type="text" class="form-control" id="color" name="color">
                </div>
                <div class="mb-3">
                    <label for="weight" class="form-label">Вес</label>
                    <input type="text" class="form-control" id="weight" name="weight">
                </div>
                <div class="mb-3">
                    <label for="obiem" class="form-label">Объем</label>
                    <input type="text" class="form-control" id="obiem" name="obiem">
                </div>
                <div class="mb-3">
                    <label for="rashod" class="form-label">Расход</label>
                    <input type="text" class="form-control" id="rashod" name="rashod" placeholder="Например: 0.2 кг/м²">
                </div>
                <div class="mb-3">
                    <label for="osnova" class="form-label">Основа</label>
                    <input type="text" class="form-control" id="osnova" name="osnova">
                </div>
                <div class="mb-3">
                    <label for="time" class="form-label">Время застывания</label>
                    <input type="number" class="form-control" id="time" name="time">
                </div>
                <div class="mb-3">
                    <label for="tempa" class="form-label">Температура эксплуатации</label>
                    <input type="text" class="form-control" id="tempa" name="tempa">
                </div>
                <div class="mb-3">
                    <label for="srok_godnosti" class="form-label">Срок годности</label>
                    <input type="number" class="form-control" id="srok_godnosti" name="srok_godnosti">
                </div>
                <div class="mb-3">
                    <label for="dop_info" class="form-label">Дополнительная информация</label>
                    <input type="text" class="form-control" id="dop_info" name="dop_info">
                </div>
                <div class="mb-3">
                    <label for="img" class="form-label">Изображение</label>
                    <input type="text" class="form-control" id="img" name="img"
                        placeholder="Введите название изображения с расширением файла из resources/media/images">
                </div>
                <div class="mb-3">
                    <label for="country" class="form-label">Страна-производитель</label>
                    <input type="text" class="form-control" id="country" name="country" placeholder="">
                </div>
                <div class="mb-3">
                    <label for="category" class="form-label">Категория</label>
                    <select name="category" id="category" class="form-control">
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->product_type }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="submit" value="Подтвердить" class="btn btn-primary">
            </form>
        </div>
    </div>
@endsection
