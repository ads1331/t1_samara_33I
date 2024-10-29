@include('layouts.header')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анализ отчета</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Подключение PptxGenJS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pptxgenjs/3.8.0/pptxgen.min.js"></script>
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <!-- Подключение docx -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/docx/6.0.0/docx.min.js"></script>

    <!-- Подключение FileSaver -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

</head>
<body>

<h1 class="graphics_text">Графики по анализу данных</h1>

<!-- Форма для выбора графиков -->
<form id="chartSelectionForm" class="graphics">
    <h3>Выберите графики для отображения:</h3>
    <label><input type="checkbox" name="chartTypes[]" value="barChart" checked> Столбчатая диаграмма</label><br>
    <label><input type="checkbox" name="chartTypes[]" value="lineChart" checked> Линейный график</label><br>
    <label><input type="checkbox" name="chartTypes[]" value="pieChart" checked> Круговая диаграмма</label><br>
    <label><input type="checkbox" name="chartTypes[]" value="doughnutChart" checked> Кольцевая диаграмма</label><br>
    <label><input type="checkbox" name="chartTypes[]" value="radarChart" checked> Радарная диаграмма</label><br>
    <label><input type="checkbox" name="chartTypes[]" value="polarAreaChart" checked> Полярная диаграмма</label><br>
    <label><input type="checkbox" name="chartTypes[]" value="scatterChart" checked> График рассеяния</label><br>
    <button type="button" onclick="initializeCharts(firstAnalysis, additionalFilesResults)">Показать выбранные графики</button>
</form>

<!-- Канвасы для всех графиков -->
<div id="chartsContainer">
    <canvas id="barChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(barChartInstance)"></canvas>
    <canvas id="lineChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(lineChartInstance)"></canvas>
    <canvas id="pieChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(pieChartInstance)"></canvas>
    <canvas id="doughnutChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(doughnutChartInstance)"></canvas>
    <canvas id="radarChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(radarChartInstance)"></canvas>
    <canvas id="polarAreaChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(polarAreaChartInstance)"></canvas>
    <canvas id="scatterChart" width="400" height="200" style="display:none;" oncontextmenu="copyChart(scatterChartInstance)"></canvas>
</div>

<!-- Область для вставки диаграмм -->
<div id="chart-paste-area" style="border: 2px dashed #ccc; min-height: 200px; margin-top: 20px; padding: 10px;">
    <p style="text-align: center; margin: 0;">Вставьте диаграмму сюда (правый клик для вставки)</p>
</div>
<div style="margin-top: 20px;" class="download_file">
    <button onclick="downloadPDF()">Скачать PDF</button>
    <button id="downloadPPTXButton">Скачать PPTX</button>
    <button id="downloadDOCXButton">Скачать DOCX</button>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- Подключение PptxGenJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pptxgenjs/3.8.0/pptxgen.min.js"></script>

<!-- Подключение docx -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/docx/6.0.0/docx.min.js"></script>

<!-- Подключение FileSaver -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>





<script>
    const firstAnalysis = @json($firstAnalysisResults ?? []);
    const additionalFilesResults = @json($additionalFilesResults ?? []);

    let copiedChart = null; // Переменная для хранения скопированной диаграммы

    function createChart(ctx, chartType, data, options = {}) {
        return new Chart(ctx, {
            type: chartType,
            data: data,
            options: options
        });
    }

    let barChartInstance, lineChartInstance, pieChartInstance, doughnutChartInstance, radarChartInstance, polarAreaChartInstance, scatterChartInstance;

    function initializeCharts(firstAnalysis, additionalFilesResults) {
        // Убираем все графики перед обновлением
        document.querySelectorAll("canvas").forEach(canvas => canvas.style.display = "none");

        const selectedCharts = Array.from(document.querySelectorAll("input[name='chartTypes[]']:checked"))
            .map(input => input.value);

        // Столбчатая диаграмма
        if (selectedCharts.includes('barChart') && firstAnalysis.numerical_data.values.length > 0) {
            const barChartCtx = document.getElementById('barChart').getContext('2d');
            document.getElementById('barChart').style.display = "block";
            barChartInstance = createChart(barChartCtx, 'bar', {
                labels: firstAnalysis.categorical_data.labels,
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values,
                        backgroundColor: `rgba(${index * 50 + 100}, 162, 235, 0.5)`
                    }))
                ]
            });
        }

        // Линейный график
        if (selectedCharts.includes('lineChart') && firstAnalysis.numerical_data.values.length > 0) {
            const lineChartCtx = document.getElementById('lineChart').getContext('2d');
            document.getElementById('lineChart').style.display = "block";
            lineChartInstance = createChart(lineChartCtx, 'line', {
                labels: firstAnalysis.categorical_data.labels,
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values,
                        fill: false,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        tension: 0.1
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values,
                        fill: false,
                        borderColor: `rgba(${index * 50 + 100}, 75, 192, 1)`,
                        tension: 0.1
                    }))
                ]
            });
        }

        // Круговая диаграмма
        if (selectedCharts.includes('pieChart') && firstAnalysis.numerical_data.values.length > 0) {
            const pieChartCtx = document.getElementById('pieChart').getContext('2d');
            document.getElementById('pieChart').style.display = "block";
            pieChartInstance = createChart(pieChartCtx, 'pie', {
                labels: firstAnalysis.categorical_data.labels,
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values,
                        backgroundColor: [
                            `rgba(${index * 50 + 100}, 99, 132, 0.5)`,
                            `rgba(${index * 50 + 150}, 162, 235, 0.5)`,
                            `rgba(${index * 50 + 200}, 206, 86, 0.5)`,
                        ],
                    }))
                ]
            });
        }

        // Кольцевая диаграмма
        if (selectedCharts.includes('doughnutChart') && firstAnalysis.numerical_data.values.length > 0) {
            const doughnutChartCtx = document.getElementById('doughnutChart').getContext('2d');
            document.getElementById('doughnutChart').style.display = "block";
            doughnutChartInstance = createChart(doughnutChartCtx, 'doughnut', {
                labels: firstAnalysis.categorical_data.labels,
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values,
                        backgroundColor: [
                            `rgba(${index * 50 + 100}, 99, 132, 0.5)`,
                            `rgba(${index * 50 + 150}, 162, 235, 0.5)`,
                            `rgba(${index * 50 + 200}, 206, 86, 0.5)`,
                        ],
                    }))
                ]
            });
        }

        // Радарная диаграмма
        if (selectedCharts.includes('radarChart') && firstAnalysis.numerical_data.values.length > 0) {
            const radarChartCtx = document.getElementById('radarChart').getContext('2d');
            document.getElementById('radarChart').style.display = "block";
            radarChartInstance = createChart(radarChartCtx, 'radar', {
                labels: firstAnalysis.categorical_data.labels,
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values,
                        backgroundColor: `rgba(${index * 50 + 100}, 99, 132, 0.5)`,
                        borderColor: `rgba(${index * 50 + 100}, 99, 132, 1)`,
                        borderWidth: 1
                    }))
                ]
            });
        }

        // Полярная диаграмма
        if (selectedCharts.includes('polarAreaChart') && firstAnalysis.numerical_data.values.length > 0) {
            const polarAreaChartCtx = document.getElementById('polarAreaChart').getContext('2d');
            document.getElementById('polarAreaChart').style.display = "block";
            polarAreaChartInstance = createChart(polarAreaChartCtx, 'polarArea', {
                labels: firstAnalysis.categorical_data.labels,
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values,
                        backgroundColor: [
                            `rgba(${index * 50 + 100}, 99, 132, 0.5)`,
                            `rgba(${index * 50 + 150}, 162, 235, 0.5)`,
                            `rgba(${index * 50 + 200}, 206, 86, 0.5)`,
                        ],
                    }))
                ]
            });
        }

        // График рассеяния
        if (selectedCharts.includes('scatterChart') && firstAnalysis.numerical_data.values.length > 0) {
            const scatterChartCtx = document.getElementById('scatterChart').getContext('2d');
            document.getElementById('scatterChart').style.display = "block";
            scatterChartInstance = createChart(scatterChartCtx, 'scatter', {
                datasets: [
                    {
                        label: 'Первый анализ',
                        data: firstAnalysis.numerical_data.values.map((value, index) => ({
                            x: firstAnalysis.categorical_data.labels[index],
                            y: value
                        })),
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    },
                    ...additionalFilesResults.map((result, index) => ({
                        label: `Дополнительный анализ ${index + 1}`,
                        data: result.numerical_data.values.map((value, idx) => ({
                            x: result.categorical_data.labels[idx],
                            y: value
                        })),
                        backgroundColor: `rgba(${index * 50 + 100}, 99, 132, 0.5)`,
                    }))
                ]
            });
        }
    }

    function copyChart(chartInstance) {
        // Сохранение копии диаграммы
        copiedChart = chartInstance;
        alert('Диаграмма скопирована. Вставьте ее в нужное место (CTRL + V).');
    }

    document.getElementById('chart-paste-area').addEventListener('paste', function (event) {
        if (copiedChart) {
            const newCanvas = document.createElement('canvas');
            newCanvas.width = 400;
            newCanvas.height = 200;
            const ctx = newCanvas.getContext('2d');

            // Копируем данные графика
            new Chart(ctx, {
                type: copiedChart.config.type,
                data: copiedChart.config.data,
                options: copiedChart.config.options
            });

            // Добавляем новый канвас в область вставки
            document.getElementById('chart-paste-area').appendChild(newCanvas);
        }
    });
    function downloadPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Проходим по всем канвасам и добавляем их в PDF
        document.querySelectorAll('#chart-paste-area canvas').forEach((canvas, index) => {
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 10 + index * 100, 180, 90);
            doc.addPage(); // Добавляем новую страницу для следующего графика
        });

        doc.save('report.pdf');
    }


    document.addEventListener('DOMContentLoaded', function () {
        const firstAnalysis = @json($firstAnalysisResults ?? []);
        const additionalFilesResults = @json($additionalFilesResults ?? []);

        // Функция для загрузки PPTX
        function downloadPPTX() {
            let pptx = new PptxGenJS();
            pptx.addSlide().addText("Привет, мир!", { x: 1, y: 1, fontSize: 18 });
            pptx.save("PresentationName");
        }

        // Функция для загрузки DOCX
        function downloadDOCX() {
            const doc = new docx.Document();
            doc.addSection({
                properties: {},
                children: [
                    new docx.Paragraph({
                        text: "Привет, мир!",
                        heading: docx.HeadingLevel.HEADING_1,
                    }),
                ],
            });
            docx.Packer.toBlob(doc).then(blob => {
                saveAs(blob, "DocumentName.docx");
            });
        }

        // Привязка функций к кнопкам
        document.getElementById('downloadPPTXButton').addEventListener('click', downloadPPTX);
        document.getElementById('downloadDOCXButton').addEventListener('click', downloadDOCX);
    });


</script>
</body>
</html>
