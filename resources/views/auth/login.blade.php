@php
use Illuminate\Support\Facades\Route;
@endphp
@include('layouts.header')
<head>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<x-guest-layout class="login-title-wrapp">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 login-title-wrapp" :status="session('status')" />
    <h2 class="login-title">Вход</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-text-input id="email" class="login" type="email" name="email" placeholder="Логин" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-text-input id="password" class="password"
                            type="password"
                            name="password"
                            placeholder="Пароль"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 remember">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Запомнить меня') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Забыли пароль?') }}
                </a>
            @endif

            
        </div>
        <div class="mt-4 login-btn-wrapp">
        <x-primary-button class="ml-3 login-btn">
                {{ __('Вход') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
