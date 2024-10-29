@include('layouts.header')
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактирование данных</title>
</head>
<body>
    <h1>Редактирование данных</h1>
    <form action="/process" method="POST">
        @csrf
        <table border ="1">
            <thead>
                <tr>
                    @foreach ($data[0] as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $index => $row)
                    <tr>
                        @foreach ($row as $key => $value)
                            <td>
                                <input type="text" name="data[{{ $index }}][{{ $key }}]" value="{{ $value }}">
                            </td>
                        @endforeach
                        <td>
                            <input type="checkbox" name="delete_rows[]" value="{{ $index }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="operations-wrapp">
        <div class="add_operations">
            <button type="button" onclick="addRow()">Добавить строку</button>
            <button type="submit">Сохранить изменения</button>
        </div>
        <h2>Математические операции</h2>
        <label for="math_operation">Операция:</label>
        <select name="math_operation">
            <option value="add">Сложение</option>
            <option value="subtract">Вычитание</option>
        </select>
        <div class="add">
        <label for="column_index">Индекс колонки:</label>
        <input type="number" name="column_index" min="0" required>
</div>
<div class="sub">
        <label for="math_value">Значение:</label>
        <input type="number" name="math_value" required>
</div>  
        <h2>Фильтрация данных</h2>
        <label for="filter_value">Значение для фильтрации:</label>
        <input type="text" name="filter_value">
</div>
    </form>

    <script>
        function addRow() {
            const tableBody = document.querySelector('tbody');
            const newRow = document.createElement('tr');
            const columnsCount = document.querySelector('thead tr').children.length - 1; // Учитываем колонку для удаления
            
            for (let i = 0; i < columnsCount; i++) {
                const cell = document.createElement('td');
                cell.innerHTML = `<input type="text" name="data[new_row][${i}]" value="">`;
                newRow.appendChild(cell);
            }
            const deleteCell = document.createElement('td');
            deleteCell.innerHTML = `<input type="checkbox" name="delete_rows[]" style="display:none;">`;
            newRow.appendChild(deleteCell);
            tableBody.appendChild(newRow);
        }
    </script>
</body>
</html>
