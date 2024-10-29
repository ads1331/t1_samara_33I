<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Отчет</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .page {
            page-break-after: always; /* Перенос на новую страницу */
        }
        .block {
            border: 1px solid #000;
            margin-bottom: 10px;
            padding: 10px;
        }
        /* Добавь другие стили по необходимости */
    </style>
</head>
<body>
    @foreach($pages as $pageId => $blocks)
        <div class="page">
            <h1>{{ $pageId }}</h1>
            @foreach($blocks as $block)
                <div class="block">
                    {!! $block['html'] !!} <!-- Вставляем HTML блока -->
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
