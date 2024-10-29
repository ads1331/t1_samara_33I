<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;

use App\Models\Report;
use App\Models\ReportImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage;

class NewReportController extends Controller
{
    public function index()
    {
        $reports = Report::where('report_type', 'public')->with('user')->get();
        return view('main', compact('reports'));
    }

    // Метод для отображения конкретного отчета
    public function show($id)
{

    $report = Report::with('images')->findOrFail($id);
    return view('show', compact('report'));
}

    // Метод для создания нового отчета
    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'description' => 'required|string',
            'report_type' => 'required|in:public,private',
            'first_page_image' => 'required|image', // Только первая страница
        ]);

        $report = Report::create([
            'user_id' => Auth::id(),
            'project_name' => $request->project_name,
            'description' => $request->description,
            'report_type' => $request->report_type,
            'first_page_image' => $request->file('first_page_image')->store('public/reports'), // Сохраняем первую страницу
        ]);

        // Если есть изображения остальных страниц
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                ReportImage::create([
                    'report_id' => $report->id,
                    'image_path' => $image->store('public/reports'),  // сохраняем в публичную папку
                    'page_number' => $key + 2, // Первая страница уже сохранена
                ]);
            }
        }
        

        return redirect()->route('report.index')->with('success', 'Отчет опубликован!');
    }
}
