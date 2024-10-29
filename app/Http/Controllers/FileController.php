<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\YourDataImport;
use App\Exports\YourDataExport;

class FileController extends Controller
{
    protected $data = [];

    public function showUploadForm()
    {
        return view('upload');
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv,json|max:2048',
        ]);

        $file = $request->file('file');

        if ($file->getClientOriginalExtension() === 'xlsx') {
            $this->data = Excel::toArray(new YourDataImport, $file)[0];
        } elseif ($file->getClientOriginalExtension() === 'csv') {
            $this->data = Excel::toArray(new YourDataImport, $file)[0]; // Обработка CSV
        } elseif ($file->getClientOriginalExtension() === 'json') {
            $this->data = json_decode(file_get_contents($file), true); // Обработка JSON
        }

        return view('edit', ['data' => $this->data]);
    }

    public function processData(Request $request)
    {
        $this->data = $request->input('data');

        // Удаление строк
        if ($request->has('delete_rows')) {
            foreach ($request->input('delete_rows') as $rowIndex) {
                unset($this->data[$rowIndex]);
            }
            $this->data = array_values($this->data); // Сброс индексов
        }

        // Добавление новой строки
        if ($request->input('new_row')) {
            $newRow = array_fill(0, count($this->data[0]), ''); // Создаем пустую строку
            $this->data[] = $newRow; // Добавляем ее в данные
        }

        // Применение математической операции
        if ($request->has('column_index') && $request->has('math_operation')) {
            $columnIndex = $request->input('column_index');
            $value = $request->input('math_value');

            foreach ($this->data as &$row) {
                if (isset($row[$columnIndex])) {
                    if ($request->input('math_operation') === 'add') {
                        $row[$columnIndex] += $value; // Сложение
                    } elseif ($request->input('math_operation') === 'subtract') {
                        $row[$columnIndex] -= $value; // Вычитание
                    }
                }
            }
        }

        // Пример фильтрации данных
        if ($request->has('filter_value')) {
            $filterValue = $request->input('filter_value');
            $this->data = array_filter($this->data, function ($row) use ($filterValue) {
                return in_array($filterValue, $row); // Фильтруем строки, содержащие значение
            });
        }

        // Сохранение обработанных данных в временный файл
        if (empty($this->data)) {
            return redirect()->back()->withErrors(['message' => 'Нет данных для сохранения.']);
        }

        $outputFile = storage_path('app/public/modified_file.xlsx');
        Excel::store(new YourDataExport($this->data), 'modified_file.xlsx', 'public');

        return response()->download($outputFile)->deleteFileAfterSend(true);
    }

}
