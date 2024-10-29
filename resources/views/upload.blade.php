@include('layouts.header')
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка файла</title>
</head>
<body>
    <h1>Загрузите файл</h1>
    <form action="/upload" method="POST" enctype="multipart/form-data" class="upload_file">
        @csrf
        <input type="file" name="file" accept=".xls,.xlsx,.csv" required>
        <button type="submit">Загрузить</button>
    </form>
</body>
</html>

