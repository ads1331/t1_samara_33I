@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка отчетов</title>
</head>
<body>
    <h1 class="upload-name">Загрузка отчетов для сравнения</h1>

    @if(session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    <form action="{{ route('report.upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
        @csrf
        <div class="upload">
            <span class="upload-label">Файлы для сравнения</span>
            <span class=upload-label-1>Статус</span>
            <span class="upload-label-2">Действия</span>
        </div>
        <div class="upload">
        <label for="report_file_1" class="upload-label">Отчет 1:</label>
            <div class="upload-title">
                <span class="file-name">Файл не выбран</span>
            </div>
            <div class="upload-input">
                <label for="report_file_1" class="custom-file-upload custom-upload">Загрузить файл</label>
                <input type="file" name="report_file_1" id="report_file_1" accept=".xls,.xlsx,.csv" required style="display:none;">
            </div>
        </div>
        <div class="upload">
        <label for="report_file_2" class="upload-label">Отчет 2:</label>
        <div class="upload-title">
                <span class="file-name">Файл не выбран</span>
            </div>
        <div class="upload-input">
                <label for="report_file_2" class="custom-file-upload custom-upload">Загрузить файл</label>
                <input type="file" name="report_file_2" id="report_file_2" accept=".xls,.xlsx,.csv" required style="display:none;">
            </div>
        </div>
        <div class="upload-btn">
        <button type="submit">Загрузить и сравнить</button>
    </div>
    </form>
    

    <script>
        // Находим все поля input[type="file"] и добавляем обработчик события
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', event => {
                // Ищем ближайший к input элемент с классом .file-name
                const fileNameDisplay = event.target.closest('.upload').querySelector('.file-name');
                
                // Если файл выбран, обновляем текст, иначе отображаем "Файл не выбран"
                if (event.target.files.length > 0) {
                    const fileNames = Array.from(event.target.files).map(file => file.name).join(', ');
                    fileNameDisplay.textContent = fileNames;
                } else {
                    fileNameDisplay.textContent = 'Файл не выбран';
                }
            });
        });
    </script>
</body>
</html>
