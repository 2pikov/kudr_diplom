@extends('layouts.app')
@section('content')
<div class="contacts">
    <div class="contacts__container">
        <h1 class="contacts__title">Наши контакты</h1>
        
        <div class="contacts__grid">
            <div class="contacts__info">
                <div class="contact-card">
                    <div class="contact-card__icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-card__content">
                        <h3 class="contact-card__title">Адреса</h3>
                        <p class="contact-card__text">г. Ульяновск, Созидателей, 13А</p>
                        <p class="contact-card__text">г. Ульяновск, Созидателей, 13б</p>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card__icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-card__content">
                        <h3 class="contact-card__title">Телефоны</h3>
                        <p class="contact-card__text">+7 (8822) 33-44-55</p>
                        <p class="contact-card__text">+7 (8433) 66-77-88</p>
                    </div>
                </div>

                <div class="contact-card">
                    <div class="contact-card__icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-card__content">
                        <h3 class="contact-card__title">Почта</h3>
                        <p class="contact-card__text">masterstroi@inbox.ru</p>
                    </div>
                </div>
            </div>

            <div class="contacts__map">
                <img src="{{ Vite::asset('resources/media/images/call1.jpg')}}" alt="Карта" class="map-image">
            </div>
        </div>
    </div>
</div>
@endsection
