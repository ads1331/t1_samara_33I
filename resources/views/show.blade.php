@php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;
@endphp
@include('layouts.header')
<head>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $report->project_name }}</div>

                <div class="card-body">
                    <div class="card-img-body">
                        <img src="{{ Storage::url($report->first_page_image) }}" class="img-fluid row" alt="Первая страница отчета" style="margin: 0 auto">
                    </div>
                    <p><strong>Описание:</strong> {{ $report->description }}</p>
                    
                    <p><strong>Тип отчета:</strong> {{ $report->report_type == 'public' ? 'Публичный' : 'Частный' }}</p>

                    <p><strong>Автор:</strong> {{ $report->user->name }}</p>
                    <div class="other-pages">
                    @if($report->images && $report->images->count() > 0)
                        <h5>Остальные страницы:</h5>
                        <div class="row">
                            @foreach($report->images as $image)
                                <div class="col-md-4">
                                    <img src="{{ Storage::url($image->image_path) }}" class="img-fluid" alt="Страница {{ $image->page_number }}">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Дополнительных страниц нет.</p>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>