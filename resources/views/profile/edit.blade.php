@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ Vite::asset('resources/css/profile-edit.css') }}">
@endsection

@section('content')
<div class="profile-edit">
    <div class="card">
        <div class="card-header">Редактирование профиля</div>

        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('patch')

                <div class="form-group">
                    <label for="name">Имя</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" 
                           name="name" value="{{ old('name', $user->name) }}" required autocomplete="name">
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                           name="email" value="{{ old('email', $user->email) }}" required autocomplete="email">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
