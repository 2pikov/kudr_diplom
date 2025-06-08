@extends('layouts.app')

@section('content')
<div class="quick-order-confirm">
    <h1>Подтверждение заказа</h1>
    
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('quick-order.verify') }}" method="POST" class="confirm-form">
        @csrf
        <div class="form-group">
            <label>Код подтверждения</label>
            <input type="text" name="code" required placeholder="Введите код из письма" maxlength="6">
            @error('code')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn-submit">Подтвердить заказ</button>
    </form>
</div>
@endsection 