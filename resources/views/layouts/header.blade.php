<!-- resources/views/components/header.blade.php -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>Документы</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light navigation">
    <div class="container-fluid">
        <a class="navbar-brand navigation-btn" href="/">Главная</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="{{ route('report.index') }}">Все отчеты</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="/upload">Изменение Данных</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="{{ route('reports.index') }}">Генерация отчетов</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="{{ route('report.create') }}">Создать отчет</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="{{route('upload.form')}}">Сравнение Двух</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="/gantt">Диаграмма Ганта</a>
                </li>
                @guest
                <li class="nav-item">
                    <a class="nav-link navigation-main" href="{{ route('login') }}">Вход</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link navigation-main" href="/register">Регистрация</a>
                </li>
                @else
                <li class="nav-item">
                    <a class="nav-link navigation-btn" href="{{ route('profile.index') }}">Личный кабинет</a>
                </li>
            </ul>
            <div class="end-item">
                <li class="nav-item navbar-nav">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    <a class="nav-link navigation-main" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        Выход
                    </a>
                </li>
</div>
                @endguest
        </div>
    </div>
</nav>

    <!-- Подключаем скрипты Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
