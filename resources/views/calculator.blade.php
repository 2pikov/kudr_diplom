@extends('layouts.app')

@section('content')
<div class="container calc-section">
    <h1 class="text-center mb-3 calc-title">Онлайн калькулятор расчета количества материалов</h1>
    
    <div class="calc-container">
        <!-- Форма -->
        <div class="calc-form">
            <div class="calc-form-group mb-2">
                <label for="product" class="calc-form-label">Вид материала</label>
                <div class="calc-input-group">
                    <span class="calc-input-group-text">
                        <i class="fas fa-box"></i>
                    </span>
                    <select class="calc-form-select" id="product" name="product_id">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="calc-form-group mb-2">
                <label for="area" class="calc-form-label">Размер работ</label>
                <div class="calc-input-group">
                    <span class="calc-input-group-text">
                        <i class="fas fa-ruler-combined"></i>
                    </span>
                    <input type="number" class="calc-form-control" id="area" name="area" min="0.1" step="0.1" placeholder="Введите значение">
                    <select class="calc-form-select" id="unit_type" style="max-width: 110px;">
                        <option value="m2">м²</option>
                        <option value="m">м</option>
                        <option value="cm">см</option>
                    </select>
                </div>
                <small class="calc-form-text text-muted">
                    Для герметиков введите длину шва, для остальных материалов - площадь поверхности
                </small>
            </div>

            <button class="btn btn-primary btn-lg w-100 calc-calculate-btn mb-2" onclick="calculateMaterials()">
                <i class="fas fa-calculator me-2"></i>Рассчитать
            </button>

            <div id="result" style="display: none;">
                <h3 class="calc-result-title h5 mb-2">Результат расчета:</h3>
                <div class="calc-result-box">
                    <div class="calc-result-item">
                        <i class="fas fa-box-open"></i>
                        <div>
                            <label>Материал:</label>
                            <span id="product_name"></span>
                        </div>
                    </div>
                    <div class="calc-result-item">
                        <i class="fas fa-weight"></i>
                        <div>
                            <label>Необходимое количество:</label>
                            <span><span id="quantity"></span> <span id="unit"></span></span>
                        </div>
                    </div>
                    <div class="calc-result-item">
                        <i class="fas fa-ruble-sign"></i>
                        <div>
                            <label>Стоимость:</label>
                            <span><span id="total_price"></span> руб.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.calc-section {
    max-width: 700px;
    margin: 40px auto;
    padding: 30px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    font-family: 'Exo 2', sans-serif;
}

.calc-title {
    font-size: 28px;
    font-weight: 600;
    color: #333;
    text-align: center;
    margin-bottom: 30px;
    position: relative;
}

.calc-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 70px;
    height: 3px;
    background: #D20D0D;
}

.calc-form-group {
    margin-bottom: 20px;
}

.calc-form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
    font-size: 15px;
}

.calc-input-group {
    display: flex;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 5px;
    overflow: hidden;
    transition: border-color 0.2s ease;
}

.calc-input-group:focus-within {
    border-color: #D20D0D;
    box-shadow: 0 0 0 0.2rem rgba(210, 13, 13, 0.1);
}

.calc-input-group-text {
    padding: 10px 15px;
    background-color: #eee;
    border-right: 1px solid #ccc;
    color: #555;
}

.calc-input-group-text i {
    font-size: 18px;
}

.calc-form-control, .calc-form-select {
    flex-grow: 1;
    padding: 10px 15px;
    border: none;
    outline: none;
    font-size: 16px;
    width: 100%;
    height: 100%; /* Добавлено */
}

.calc-form-control::placeholder {
    color: #999;
}

#unit_type.calc-form-select {
    flex-grow: 0;
    flex-shrink: 0;
    width: 120px;
    border-left: 1px solid #ccc;
}

.calc-form-text {
    display: block;
    margin-top: 5px;
    font-size: 13px;
    color: #777;
}

.calc-calculate-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    background: #D20D0D;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 12px 25px;
    font-size: 18px;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
    width: 100%;
    margin-top: 20px;
}

.calc-calculate-btn:hover {
    background: #b00b0b;
}

#result {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px dashed #ccc;
}

.calc-result-title {
    font-size: 20px;
    font-weight: 600;
    color: #333;
    margin-bottom: 15px;
}

.calc-result-box {
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 8px;
}

.calc-result-item {
    display: flex;
    align-items: center;
    margin-bottom: 12px;
    padding: 10px;
    background-color: #fff;
    border-radius: 5px;
    border: 1px solid #eee;
}

.calc-result-item:last-child {
    margin-bottom: 0;
}

.calc-result-item i {
    color: #D20D0D;
    font-size: 20px;
    margin-right: 15px;
    width: 25px;
    text-align: center;
}

.calc-result-item label {
    font-weight: 500;
    color: #555;
    margin-right: 10px;
}

.calc-result-item span {
    color: #333;
    font-weight: 600;
}

/* Адаптивность */
@media (max-width: 768px) {
    .calc-section {
        padding: 20px;
        margin: 20px;
    }

    .calc-title {
        font-size: 24px;
    }

    .calc-calculate-btn {
        font-size: 16px;
        padding: 10px 20px;
    }

    .calc-result-title {
        font-size: 18px;
    }

    .calc-result-box {
        padding: 10px;
    }

    .calc-result-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 8px;
    }

    .calc-result-item i {
        margin-right: 0;
        margin-bottom: 5px;
        font-size: 18px;
    }

    .calc-result-item label,
    .calc-result-item span {
        font-size: 15px;
    }

    .calc-result-item > div {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .calc-section {
        padding: 15px;
        margin: 15px;
    }

    .calc-title {
        font-size: 20px;
    }

    .calc-form-label {
        font-size: 14px;
    }

    .calc-input-group-text, .calc-form-control, .calc-form-select {
        padding: 8px 10px;
        font-size: 14px;
    }

    #unit_type.calc-form-select {
        width: 100px;
    }

    .calc-calculate-btn {
        font-size: 14px;
        padding: 10px 15px;
    }

    .calc-result-title {
        font-size: 16px;
    }

    .calc-result-item i {
        font-size: 16px;
    }

    .calc-result-item label,
    .calc-result-item span {
        font-size: 14px;
    }
}
</style>

<script id="products-data" type="application/json">@json($products)</script>
<script>
const products = JSON.parse(document.getElementById('products-data').textContent);

function calculateMaterials() {
    const area = document.getElementById('area').value;
    const productId = document.getElementById('product').value;
    const unitType = document.getElementById('unit_type').value;

    if (!area || !productId || area <= 0) {
        alert('Пожалуйста, введите корректные данные');
        return;
    }

    // Конвертируем значение в м² перед отправкой
    let convertedArea = area;
    switch(unitType) {
        case 'm':
            convertedArea = area; // Для погонных метров оставляем как есть
            break;
        case 'cm':
            convertedArea = area / 100; // Переводим сантиметры в метры
            break;
        case 'm2':
        default:
            convertedArea = area; // Для м² оставляем как есть
            break;
    }

    fetch(`/api/calculate?product_id=${productId}&area=${convertedArea}&unit_type=${unitType}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            document.getElementById('product_name').textContent = data.product_name;
            document.getElementById('quantity').textContent = data.quantity;
            document.getElementById('unit').textContent = data.unit;
            document.getElementById('total_price').textContent = data.total_price;
            document.getElementById('result').style.display = 'block';
        })
        .catch(error => {
            console.error('Ошибка:', error);
            alert('Произошла ошибка при расчете');
        });
}
</script>
@endsection 