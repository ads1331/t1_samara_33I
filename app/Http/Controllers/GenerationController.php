<?php

namespace App\Http\Controllers;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ReportImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class GenerationController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    // Загрузка и обработка первого файла отчета
    public function upload(Request $request)
    {
        $request->validate([
            'report_file' => 'required|mimes:xls,xlsx,csv',
            'columns' => 'array|required',
            'columns.start_category' => 'required|array',
            'columns.end_category' => 'required|array',
            'columns.start_number' => 'required|array',
            'columns.end_number' => 'required|array',
            'additional_report_files.*' => 'nullable|mimes:xls,xlsx,csv',
        ]);

        $filePath = $request->file('report_file')->store('reports');
        $columnsToAnalyze = $request->input('columns');

        // Анализ первого файла
        $firstAnalysisResults = $this->analyzeReport($filePath, $columnsToAnalyze);

        // Обработка дополнительных файлов
        $additionalFilesResults = [];
        if ($request->hasFile('additional_report_files')) {
            foreach ($request->file('additional_report_files') as $key => $additionalFile) {
                if ($additionalFile) {
                    $additionalFilePath = $additionalFile->store('reports');
                    $columnsToAnalyzeForAdditional = [
                        'start_category' => $columnsToAnalyze['start_category'][$key] ?? null,
                        'end_category' => $columnsToAnalyze['end_category'][$key] ?? null,
                        'start_number' => $columnsToAnalyze['start_number'][$key] ?? null,
                        'end_number' => $columnsToAnalyze['end_number'][$key] ?? null,
                    ];
                    $additionalFilesResults[] = $this->analyzeReport($additionalFilePath, $columnsToAnalyzeForAdditional);
                }
            }
        }

        // Сохраняем результаты в сессии
        session(['firstAnalysisResults' => $firstAnalysisResults, 'additionalFilesResults' => $additionalFilesResults]);

        return view('reports.analysis', [
            'firstAnalysisResults' => $firstAnalysisResults,
            'additionalFilesResults' => $additionalFilesResults,
        ]);
    }

    // Загрузка и обработка второго файла отчета
    public function uploadAdditional(Request $request)
    {
        $request->validate([
            'additional_report_file' => 'required|mimes:xls,xlsx,csv',
            'columns' => 'array|required'
        ]);

        $filePath = $request->file('additional_report_file')->store('reports');
        $columnsToAnalyze = $request->input('columns');

        // Анализ второго файла
        $secondAnalysisResults = $this->analyzeReport($filePath, $columnsToAnalyze);

        // Получаем результаты первого анализа из сессии
        $firstAnalysisResults = session('firstAnalysisResults');

        return view('reports.analysis', [
            'firstAnalysisResults' => $firstAnalysisResults,
            'secondAnalysisResults' => $secondAnalysisResults,
        ]);
    }

    // Метод для анализа загруженного отчета
    private function analyzeReport($filePath, $columnsToAnalyze)
    {
        $data = Excel::toArray(new ReportImport, storage_path('app/' . $filePath));

        $labels = [];
        $numericalValues = [];
        $categoricalValues = [];

        foreach ($data[0] as $row) {
            $categoryColumn = $columnsToAnalyze['category'] ?? 0;
            $numberColumn = $columnsToAnalyze['number'] ?? 1;
            $optionalColumn = $columnsToAnalyze['optional'] ?? 2;

            if (!is_null($row[$categoryColumn]) && $row[$categoryColumn] !== '' && !is_null($row[$numberColumn]) && $row[$numberColumn] !== 0) {
                $labels[] = $row[$categoryColumn];
                $numericalValues[] = (int) $row[$numberColumn];
                $categoricalValues[] = (int) ($row[$optionalColumn] ?? 0);
            }
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

    public function generatePdf()
    {
        $chartPaths = Storage::disk('public')->files('charts');

        // Генерация PDF с использованием Blade-шаблона
        $pdf = PDF::loadView('reports.pdf', ['chartPaths' => $chartPaths]);

        return $pdf->download('report.pdf');
    }
}
