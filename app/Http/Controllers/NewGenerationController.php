<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReportImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NewGenerationController extends Controller
{
    public function showUploadForm()
    {
        return view('compair.upload');
    }

    // Загрузка и обработка отчетов
    public function upload(Request $request)
    {
        // Валидация загружаемых файлов
        $request->validate([
            'report_file_1' => 'required|mimes:xls,xlsx,csv',
            'report_file_2' => 'required|mimes:xls,xlsx,csv',
        ]);

        // Сохранение файлов
        $filePath1 = $request->file('report_file_1')->store('reports');
        $filePath2 = $request->file('report_file_2')->store('reports');

        // Анализ данных отчетов
        $analysisResults1 = $this->analyzeReport($filePath1);
        $analysisResults2 = $this->analyzeReport($filePath2);

        // Сравнение отчетов
        $comparison = $this->compareReports($analysisResults1, $analysisResults2);

        // Сохранение данных анализа и сравнения в сессии
        $request->session()->put('analysisResults1', $analysisResults1);
        $request->session()->put('analysisResults2', $analysisResults2);
        $request->session()->put('comparison', $comparison);

        // Перенаправление на страницу сравнения
        return redirect()->route('reports.compare');
    }

    // Страница сравнения отчетов
    public function compare(Request $request)
    {
        // Извлечение данных из сессии
        $analysisResults1 = $request->session()->get('analysisResults1');
        $analysisResults2 = $request->session()->get('analysisResults2');
        $comparison = $request->session()->get('comparison');

        if (!$analysisResults1 || !$analysisResults2 || !$comparison) {
            // Если данных нет, перенаправляем на форму загрузки
            return redirect()->route('upload.form')->with('error', 'Пожалуйста, загрузите отчеты для сравнения.');
        }

        return view('compair.analysis', compact('analysisResults1', 'analysisResults2', 'comparison'));
    }

    // Анализ загруженного отчета
    private function analyzeReport($filePath)
{
    // Чтение данных из файла
    $data = Excel::toArray(new ReportImport, storage_path('app/' . $filePath));

    if (empty($data) || !isset($data[0])) {
        throw new \Exception('Файл пуст или имеет некорректную структуру.');
    }

    $labels = [];
    $numericalValues = [];
    $categoricalValues = [];

    foreach ($data[0] as $row) {
        // Обрабатываем только строки с данными
        if (!is_null($row[0]) && $row[0] !== '' && !is_null($row[1]) && $row[1] !== 0) {
            $labels[] = $row[0];
            $numericalValues[] = (int) $row[1];
            $categoricalValues[] = (int) ($row[2] ?? 0);
        }
    }

    // Проверка на пустые данные
    if (empty($labels) || empty($numericalValues)) {
        throw new \Exception('Отсутствуют необходимые данные для анализа.');
    }

    return [
        'numerical_data' => [
            'type' => 'number',
            'values' => $numericalValues,
        ],
        'categorical_data' => [
            'type' => 'category',
            'labels' => $labels,
            'values' => $categoricalValues,
        ],
        'scatter_data' => [
            'type' => 'scatter',
            'points' => array_map(function($x, $y) {
                return ['x' => $x, 'y' => $y];
            }, $numericalValues, $categoricalValues),
        ]
    ];
}


    // Сравнение отчетов
    private function compareReports($report1, $report2)
    {
        $difference = [];

        $length = max(count($report1['numerical_data']['values']), count($report2['numerical_data']['values']));
        
        for ($i = 0; $i < $length; $i++) {
            $value1 = $report1['numerical_data']['values'][$i] ?? 0;
            $value2 = $report2['numerical_data']['values'][$i] ?? 0;
            $difference[] = $value1 - $value2;
        }

        return [
            'numerical_difference' => $difference,
        ];
    }
}
