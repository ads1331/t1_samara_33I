@include('layouts.header')
<head>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<body>
    <header class="main-page">
        <div class="main-wrapp">
            <div class="first"><h3>Планирование<br> и управление проектами</h3></div>
            <div class="second"><h2>Другой взгляд<h2></div>
            <div class="four"><h2>на свой бизнес<h2></div>
            <div class="third"><h3><strong>Гибкий и интерактивный</strong><br> подход к генерации отчетов</h3></div>
        </div>
    </header>
    <main>
        <div class="run-wrapp">
            <div class='run-text'>< 500 отчетов в шаговой доступности</div>
        </div>
        
        <section class="pluses">
            <div class="pluses-wrapp">
                <div class="pluses-card">
                    <img src="{{asset('img/3.jpg')}}" alt="">
                    <div class="overlay">Сравнение отчетов</div>
                </div>
                <div class="pluses-card">
                    <img src="{{asset('img/2.jpg')}}" alt="">
                    <div class="overlay">Диаграмма Ганта</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/1.jpg')}}" alt="">
                <div class="overlay">Многостраничность</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/4.jpg')}}" alt="">
                <div class="overlay">Автоматическая генерация отчета</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/5.jpg')}}" alt="">
                <div class="overlay">Публикация и сохранение</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/6.jpg')}}" alt="">
                <div class="overlay">Настраиваемые диаграммы</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/7.jpg')}}" alt="">
                <div class="overlay">Перетаскивание блоков</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/8.jpg')}}" alt="">
                <div class="overlay">Большое разнообразие</div>
                </div>
                <div class="pluses-card">
                <img src="{{asset('img/3.jpg')}}" alt="">
                <div class="overlay">Сравнение диаграмм</div>
                </div>
            </div>
        </section>
        <section class="about">
            <div class="about-title">
                <h2>В чем плюсы</h2>
            </div>
            <div class="tabs">
                <div data-target="content1" class="tab active">Диаграмма Ганта</div>
                <div data-target="content2" class="tab">Автоматическая генерация отчетов</div>
                <div data-target="content3" class="tab">Сравнение двух отчетов</div>
            </div>

            <div class="contents">
                <div id="content1" class="content-item active">
                    <div class="content-info">
                        <h3>Распределить задачи по времени</h3>
                    </div>
                    <div class="content-info">
                        <h3>Определить критические точки проекта</h3>
                    </div>
                    <div class="content-info">
                        <h3>Следить за соблюдением сроков</h3>
                    </div>
                    <div class="content-info">
                        <h3>Координировать командную работу</h3>
                    </div>
                    <div class="content-more">
                        <a href="/create-report">Узнать больше</a>
                    </div>
                </div>
                <div id="content2" class="content-item">
                <div class="content-info">
                        <h3>Загрузка файлов XLS, CSV или XLSX</h3>
                    </div>
                    <div class="content-info">
                        <h3>Автоматическая обработка загруженный файлов</h3>
                    </div>
                    <div class="content-info">
                        <h3>Отображение ключевых показателей</h3>
                    </div>
                    <div class="content-info">
                        <h3>Генерация нескольких диаграмм и графиков сразу </h3>
                    </div>
                    <div class="content-more">
                        <a href="/create-report">Узнать больше</a>
                    </div>
                </div>
                <div id="content3" class="content-item">
                <div class="content-info">
                        <h3>Анализ и визуализация данных из двух разных файлов</h3>
                    </div>
                    <div class="content-info">
                        <h3>Выявление различий по ключевым критериям</h3>
                    </div>
                    <div class="content-info">
                        <h3>Отображение ключевых показателей</h3>
                    </div>
                    <div class="content-info">
                        <h3>Генерация разных диаграмм и графиков сразу </h3>
                    </div>
                    <div class="content-more">
                        <a href="/create-report">Узнать больше</a>
                    </div>
                </div>
            </div>
        </section>
        <div class="article">
            <h2>Измени свой <span>подход</span><br>к отчетам с нашим сайтом</h2>
        </div>
        @guest
        <section class="auth">
            <div class="auth-wrapp">
                <div class="auth-login">
                <x-guest-layout class="login-title-wrapp auth-login-wrapp">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 login-title-wrapp auth-login-title" :status="session('status')" />
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
        <div class="mt-4 login-btn-wrapp btn-auth">
        <x-primary-button class="ml-3 login-btn auth-login-btn">
                {{ __('Вход') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
                </div>
                <div class="auth-register">
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
                </div>
            </div>
        </section>
        @endguest
    </main>
    <script>
        const tabs = document.querySelectorAll('.tab');
        const contents = document.querySelectorAll('.content-item');

        tabs.forEach(tab =>{
            tab.addEventListener('click', () =>{
                tabs.forEach(t =>
                t.classList.remove('active'));
                contents.forEach(content =>
                content.classList.remove('active'));

                tab.classList.add('active');
                const target = tab.getAttribute('data-target');
                document.getElementById(target).classList.add('active');
            })
        })
    </script>
    @include('layouts.footer')
</body>