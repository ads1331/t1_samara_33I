@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
@include('layouts.header')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Здесь добавьте CSRF-токен -->
    <title>Отчеты</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Основные стили */
        

        

        
    
    </style>
</head>
<div class="container">

<h1>Все публичные отчеты</h1>

<div class="row">

    @foreach($reports as $report)

        <div class="col-md-4">

            <div class="card">
            <div class="card-img-wrapp">
            <img src="{{ Storage::url($report->first_page_image) }}" alt="Первая страница отчета" class="img-fluid">
            </div>


                <div class="card-body">

                    <h5 class="card-title">{{ $report->project_name }}</h5>

                    <p class="card-text">Описание: {{ Str::limit($report->description, 100) }}</p>

                    <p class="card-text">Автор: {{ $report->user->name }}</p>

                    <a href="{{ route('report.show', $report->id) }}" class="btn btn-primary">Подробнее</a>


                </div>

            </div>

        </div>

    @endforeach

</div>

</div>
