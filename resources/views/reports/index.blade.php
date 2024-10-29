@include('layouts.header')
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Загрузка отчета</title>
    <script>
        function addAdditionalFile() {
            const additionalFilesContainer = document.getElementById('additionalFilesContainer');
            const newFileInput = document.createElement('div');
            newFileInput.innerHTML = `
                <h4>Дополнительный файл для анализа:</h4>
                <input type="file" name="additional_report_files[]" accept=".xls,.xlsx,.csv">
                <h4>Выберите диапазоны для анализа:</h4>
                <label>Начальная категория: <input type="number" name="additional_columns[start_category][]" required></label><br>
                <label>Конечная категория: <input type="number" name="additional_columns[end_category][]" required></label><br>
                <label>Начальные числовые данные: <input type="number" name="additional_columns[start_number][]" required></label><br>
                <label>Конечные числовые данные: <input type="number" name="additional_columns[end_number][]" required></label><br>
                <label>Формула: <input type="text" name="additional_columns[formula][]" placeholder="Например, A1+B1" required></label><br>
                <hr>
            `;
            additionalFilesContainer.appendChild(newFileInput);
        }
    </script>
</head>
<body>
<h1>Загрузить отчет</h1>

<form action="{{ route('reports.upload') }}" method="POST" enctype="multipart/form-data" class="generation">
    @csrf
    <input type="file" name="report_file" accept=".xls,.xlsx,.csv" required>

    <h4>Выберите диапазоны для анализа:</h4>
    <label>Начальная категория: <input type="number" name="columns[start_category][]" required></label><br>
    <label>Конечная категория: <input type="number" name="columns[end_category][]" required></label><br>
    <label>Начальные числовые данные: <input type="number" name="columns[start_number][]" required></label><br>
    <label>Конечные числовые данные: <input type="number" name="columns[end_number][]" required></label><br>
    <label>Формула: <input type="text" name="columns[formula][]" placeholder="Например, A1+B1" required></label><br>

    <div id="additionalFilesContainer"></div>

    <button type="button" onclick="addAdditionalFile()">Добавить еще файл</button>
    <button type="submit">Загрузить и проанализировать</button>
</form>

@if($errors->any())
    <div>
        <strong>Ошибка!</strong> {{ $errors->first() }}
    </div>
@endif
</body>
</html>
