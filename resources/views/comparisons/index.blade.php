@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ Vite::asset('resources/css/comparisons.css') }}">
@endsection

@section('content')
<div class="comparison-container">
    <h1>Сравнение товаров</h1>

    @if($comparisons->isEmpty())
        <div class="alert no-comparisons-message" style="background: #fff; border: 1px solid #e0e0e0; border-radius: 8px;">
            У вас нет товаров для сравнения
        </div>
    @else
        <div class="alert no-comparisons-message" style="display: none;">
            У вас нет товаров для сравнения
        </div>
        <div class="table-responsive comparison-table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Характеристика</th>
                        @foreach($comparisons as $comparison)
                            <th>
                                {{ $comparison->product->title }}
                                <button class="remove-comparison" 
                                        data-product-id="{{ $comparison->product->id }}"
                                        title="Удалить из сравнения">
                                    ×
                                </button>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Изображение</td>
                        @foreach($comparisons as $comparison)
                            <td>
                                <img src="{{ Vite::asset('resources/media/images/' . $comparison->product->img) }}" 
                                     alt="{{ $comparison->product->title }}">
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Цена</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ number_format($comparison->product->price, 0, ',', ' ') }} ₽</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Цвет</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ $comparison->product->color }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Вес</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ $comparison->product->weight }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Основа</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ $comparison->product->osnova }}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Температура применения</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ $comparison->product->tempa }}°C</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Время застывания</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ $comparison->product->time }}ч.</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Срок годности</td>
                        @foreach($comparisons as $comparison)
                            <td>{{ $comparison->product->srok_godnosti }}дн.</td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>
    @endif
</div>

<script>
document.querySelectorAll('.remove-comparison').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.productId;
        const headerCell = this.closest('th');
        const headerIndex = Array.from(headerCell.parentNode.children).indexOf(headerCell);

        fetch('/comparisons/toggle', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'removed') {
                headerCell.remove();

                document.querySelectorAll('.comparison-table-container tbody tr').forEach(row => {
                    if (row.children[headerIndex]) {
                        row.children[headerIndex].remove();
                    }
                });

                const remainingProducts = document.querySelectorAll('.comparison-table-container thead th').length - 1;
                if (remainingProducts === 0) {
                    document.querySelector('.comparison-table-container').style.display = 'none';
                    document.querySelector('.no-comparisons-message').style.display = 'block';
                }
            } else if (data.status === 'error') {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при удалении товара из сравнения.');
        });
    });
});
</script>
@endsection 