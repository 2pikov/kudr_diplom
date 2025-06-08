<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
</head>

<body>
    @extends('layouts.app')
    @section('content')
    <section class="company">
<h1 class="zag_comp">О компании</h1>
    <div class="container5">
<div class="company-block">
    <div class="company-image">
        <img src="{{ Vite::asset('resources/media/images/mya.png')}}" alt="Company Image" width="500px" height="auto">
    </div>
    <div class="company-description">
        <p class="opis_comp">За всё время существования на рынке мы выросли из маленького розничного магазина до крупной сети продаж, собственными складами и автопарком, вырастили команду профессионалов, которые готовы ответить на любой ваш вопрос.</p>
        <div class="company-stats">
            <div class="stat">
                <span class="number">100+</br></span>
                <span class="description">клиентов</span>
            </div>
            <div class="stat">
                <span class="number">15 лет</br></span>
                <span class="description">на рынке</span>
            </div>
            <div class="stat">
                <span class="number">8 точек</br></span>
                <span class="description">продаж по области</span>
            </div>
        </div>
    </div>
</div>
</div>
</section>
<section class="why">
    <div class="pochemy">
    <h1 class="zag_comp">Почему именно мы</h1>
    <div class="why-block">
  <div class="why-item">
    <img src="{{ Vite::asset('resources/media/images/why.jpg')}}" alt="Картинка 1">
    <p class="why_number">01</p>
    <p class="why_opis">Гарантия лучшей цены для клиентов Ульяновской области</p>
  </div>
  <div class="why-item">
    <img src="{{ Vite::asset('resources/media/images/dostavka.png')}}" alt="Картинка 2">
    <p class="why_number">02</p>
    <p class="why_opis">Бесплатная доставка по городу от 10000 рублей</p>
  </div>
  <div class="why-item">
    <img src="{{ Vite::asset('resources/media/images/skidka.jpg')}}" alt="Картинка 3">
    <p class="why_number">03</p>
    <p class="why_opis">Гибкая система скидок</p>
  </div>
  <div class="why-item">
    <img src="{{ Vite::asset('resources/media/images/material.jpg')}}" alt="Картинка 4">
    <p class="why_number">04</p>
    <p class="why_opis">Большой ассортимент товаров</p>
  </div>
  <div class="why-item">
    <img src="{{ Vite::asset('resources/media/images/chel.png')}}" alt="Картинка 5">
    <p class="why_number">05</p>
    <p class="why_opis">Профессиональные консультации от нашей команды</p>
  </div>
  <div class="why-item">
    <img src="{{ Vite::asset('resources/media/images/kachvo.jpg')}}" alt="Картинка 6">
    <p class="why_number">06</p>
    <p class="why_opis">Высокое качество материалов</p>
  </div>
</div>
</section>
<section class="call" id="call">
    <div class="call_cont">
        <img src="{{ Vite::asset('resources/media/images/call.png')}}" class="call_img" >
        <form method="post" action="{{ route('contact.submit') }}" class="form">
            @csrf
            <h1 class="form_zag">Ждем именно Вас!</h1>
            <p class="form_opis">Оставьте заявку и мы ответим на все Ваши вопросы</p>
                <div class="input-group">
                    <input type="text" name="name_user" placeholder="Введите Имя" required >
                    <hr color ="darkgrey" width="650px" size="1px"/>
                </div>
                <div class="input-group">
                    <input type="email" name="email_user" placeholder="Введите email" required>
                    <hr color ="darkgrey" width="650px" size="1px"/>
                </div>
                <div class="input-group">
                    <input type="text" placeholder="Введите Сообщение" name="descr_user" required>
                    <hr color ="darkgrey" width="650px" size="1px"/>
                </div>
                <div class="input-group">
                    <button type="submit" class="zag_but">Отправить</button>
                </div>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
        </form>
    </div>
    </section>
    @endsection
</body>

</html>