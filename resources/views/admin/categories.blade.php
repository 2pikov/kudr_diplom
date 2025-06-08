@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Категории</h1>
        <a href="/category-create" class="btn btn-primary mb-3">Создать новую категорию</a>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Название типа продукта</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->product_type }}</td>
                            <td>
                                <a href="/category-edit/{{ $category->id }}" class="btn btn-primary btn-sm">Ред.</a>
                                <form action="/category-delete/{{ $category->id }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm">Удал.</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection