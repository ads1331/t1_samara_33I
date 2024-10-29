@php
use Illuminate\Support\Facades\Route;
@endphp
@include('layouts.header')
<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<x-guest-layout>
    <h2 class="login-title">Регистрация</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            
            <x-text-input id="name" class="login" type="text"  placeholder="Имя" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            
            <x-text-input id="email" class="login" type="email" placeholder="Почта" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            

            <x-text-input id="password" class="login"
                            type="password"
                            placeholder="Придумайте пароль"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">

            <x-text-input id="password_confirmation" class="login"
                            type="password"
                            placeholder="Повторите пароль"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Уже зарегистрированы?') }}
            </a>

            
        </div>
        <div class="mt-4 login-btn-wrapp">
        <x-primary-button class="ml-3 login-btn">
                {{ __('Регистрация') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
