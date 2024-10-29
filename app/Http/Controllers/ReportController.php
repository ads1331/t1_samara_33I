<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Log;
class ReportController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function glavniy(){
        return view('glavniy');
    }
    public function analyzeReport($filePath, $columnsToAnalyze)
    {
        // Загрузка файла с использованием библиотеки Excel
        $data = \Excel::toArray([], storage_path("app/{$filePath}"))[0];

        // Извлечение данных по заданным диапазонам категорий и числовых данных
        $startCategory = $columnsToAnalyze['start_category'];
        $endCategory = $columnsToAnalyze['end_category'];
        $startNumber = $columnsToAnalyze['start_number'];
        $endNumber = $columnsToAnalyze['end_number'];

        $categoricalData = [];
        $numericalData = [];
        $scatterData = [];

        // Примерная логика для извлечения данных по указанным диапазонам
        foreach ($data as $row) {
            $category = $row[$startCategory] ?? null;
            $number = $row[$startNumber] ?? null;

            if ($category && $number) {
                $categoricalData[] = $category;
                $numericalData[] = $number;
                $scatterData[] = ['x' => $category, 'y' => $number];
            }
        }

        // Формирование структурированных данных для анализа
        return [
            'categorical_data' => ['labels' => $categoricalData],
            'numerical_data' => ['values' => $numericalData],
            'scatter_data' => ['points' => $scatterData],
        ];
    }

    public function uploadAdditional(Request $request)
    {
        $request->validate([
            'additional_report_file' => 'required|mimes:xls,xlsx,csv',
            'columns.start_category' => 'required|integer',
            'columns.end_category' => 'required|integer',
            'columns.start_number' => 'required|integer',
            'columns.end_number' => 'required|integer',
        ]);

        // Загружаем файл
        $filePath = $request->file('additional_report_file')->store('reports');
        $columnsToAnalyze = $request->input('columns');

        // Анализ второго файла
        $secondAnalysisResults = $this->analyzeReport($filePath, $columnsToAnalyze);

        // Получаем результаты первого анализа для отображения на странице
        $firstAnalysisResults = session('firstAnalysisResults');

        return view('reports.analysis', compact('firstAnalysisResults', 'secondAnalysisResults'));
    }

    public function saveReport(Request $request)
    {

        return response()->json(['message' => 'Отчет сохранен!', 'report' => $request->report]);
    }
    public function generatePDF(Request $request)
{
    // Логируем входные данные
    Log::info('Generating PDF with data: ', $request->input('pages'));

    try {
        $pages = $request->input('pages');

        // Инициализация mPDF
        $mpdf = new \Mpdf\Mpdf();

        foreach ($pages as $pageId => $blocks) {
            $html = "<h1>{$pageId}</h1><div style='page-break-after: always;'>";

            foreach ($blocks as $block) {
                $html .= "<div style='position:absolute; left:{$block['left']}; top:{$block['top']};'>";

                // Проверяем наличие изображения графика
                if (!empty($block['chartImage'])) {
                    $chartImage = $block['chartImage'];

                    // Удаляем префикс data:image/png;base64,
                    $chartImage = str_replace('data:image/png;base64,', '', $chartImage);
                    $chartImage = base64_decode($chartImage); // Декодируем изображение из Base64

                    // Создаем временный файл для изображения
                    $tempFile = tempnam(sys_get_temp_dir(), 'chart_') . '.png';
                    if (file_put_contents($tempFile, $chartImage) === false) {
                        Log::error('Failed to write temporary chart image to file: ' . $tempFile);
                        return response()->json(['error' => 'Failed to create chart image'], 500);
                    }

                    // Вставляем изображение в PDF
                    $html .= "<img src='{$tempFile}' style='max-width: 100%; max-height: 300px;'/>";
                } else {
                    $html .= $block['html']; // Добавляем HTML контент
                }

                $html .= "</div>";
            }
            $html .= "</div>"; // Закрытие div для страницы
            $mpdf->WriteHTML($html);
        }

        // Возвращаем PDF на клиент
        return $mpdf->Output('report.pdf', 'D'); // 'D' - скачать файл
    } catch (\Mpdf\MpdfException $e) {
        Log::error('mPDF Exception: ' . $e->getMessage());
        return response()->json(['error' => 'PDF generation failed'], 500);
    } catch (\Exception $e) {
        Log::error('General Exception: ' . $e->getMessage());
        return response()->json(['error' => 'An error occurred while generating PDF'], 500);
    }
}









}
