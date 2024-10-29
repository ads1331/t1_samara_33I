@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сравнение отчетов</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/charts.js') }}"></script>
</head>
<body>
    <h1>Сравнение отчетов</h1>

    <div style="display: flex; justify-content: space-between;">
        <!-- Отчет 1 -->
        <div id="report1" style="width: 45%;">
            <h2>Отчет 1</h2>
            <canvas id="report1barChart" width="400" height="200"></canvas>
            <canvas id="report1lineChart" width="400" height="200"></canvas>
            <canvas id="report1pieChart" width="400" height="200"></canvas>
            <canvas id="report1doughnutChart" width="400" height="200"></canvas>
            <canvas id="report1radarChart" width="400" height="200"></canvas>
            <canvas id="report1polarAreaChart" width="400" height="200"></canvas>
            <canvas id="report1scatterChart" width="400" height="200"></canvas>
            <canvas id="report1bubbleChart" width="400" height="200"></canvas>
        </div>

        <!-- Сравнение -->
        <div id="comparison" style="width: 10%;">
            <h2>Различия</h2>
            <ul id="differenceList"></ul>
        </div>

        <!-- Отчет 2 -->
        <div id="report2" style="width: 45%;">
            <h2>Отчет 2</h2>
            <canvas id="report2barChart" width="400" height="200"></canvas>
            <canvas id="report2lineChart" width="400" height="200"></canvas>
            <canvas id="report2pieChart" width="400" height="200"></canvas>
            <canvas id="report2doughnutChart" width="400" height="200"></canvas>
            <canvas id="report2radarChart" width="400" height="200"></canvas>
            <canvas id="report2polarAreaChart" width="400" height="200"></canvas>
            <canvas id="report2scatterChart" width="400" height="200"></canvas>
            <canvas id="report2bubbleChart" width="400" height="200"></canvas>
        </div>
    </div>

    <div id="errorMessage" style="color: red;"></div>
    <script>
        const analysis1 = @json($analysisResults1);
        const analysis2 = @json($analysisResults2);
        const comparison = @json($comparison);

        function createChart(ctx, chartType, data, options = {}) {
            new Chart(ctx, {
                type: chartType,
                data: data,
                options: options
            });
        }

        function initializeCharts(analysis, prefix) {
            if (!analysis || !analysis.categorical_data || !analysis.numerical_data) {
                console.error('Недостаточно данных для инициализации графиков');
                return;
            }

            const barChartCanvas = document.getElementById(prefix + 'barChart');
            const lineChartCanvas = document.getElementById(prefix + 'lineChart');
            const pieChartCanvas = document.getElementById(prefix + 'pieChart');
            const doughnutChartCanvas = document.getElementById(prefix + 'doughnutChart');
            const radarChartCanvas = document.getElementById(prefix + 'radarChart');
            const polarAreaChartCanvas = document.getElementById(prefix + 'polarAreaChart');
            const scatterChartCanvas = document.getElementById(prefix + 'scatterChart');
            const bubbleChartCanvas = document.getElementById(prefix + 'bubbleChart');

            const barChartCtx = barChartCanvas.getContext('2d');
            const lineChartCtx = lineChartCanvas.getContext('2d');
            const pieChartCtx = pieChartCanvas.getContext('2d');
            const doughnutChartCtx = doughnutChartCanvas.getContext('2d');
            const radarChartCtx = radarChartCanvas.getContext('2d');
            const polarAreaChartCtx = polarAreaChartCanvas.getContext('2d');
            const scatterChartCtx = scatterChartCanvas.getContext('2d');
            const bubbleChartCtx = bubbleChartCanvas.getContext('2d');

            // Destroy existing charts if they exist
            if (barChartCtx.chart) barChartCtx.chart.destroy();
            if (lineChartCtx.chart) lineChartCtx.chart.destroy();
            if (pieChartCtx.chart) pieChartCtx.chart.destroy();
            if (doughnutChartCtx.chart) doughnutChartCtx.chart.destroy();
            if (radarChartCtx.chart) radarChartCtx.chart.destroy();
            if (polarAreaChartCtx.chart) polarAreaChartCtx.chart.destroy();
            if (scatterChartCtx.chart) scatterChartCtx.chart.destroy();
            if (bubbleChartCtx.chart) bubbleChartCtx.chart.destroy();

            createChart(barChartCtx, 'bar', {
                labels: analysis.categorical_data.labels,
                datasets: [{
                    label: 'Числовые данные',
                    data: analysis.numerical_data.values,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            });

            createChart(lineChartCtx, 'line', {
                labels: analysis.categorical_data.labels,
                datasets: [{
                    label: 'Числовые данные',
                    data: analysis.numerical_data.values,
                    fill: false,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    tension: 0.1
                }]
            });

            createChart(pieChartCtx, 'pie', {
                labels: analysis.categorical_data.labels,
                datasets: [{
                    label: 'Категории',
                    data: analysis.categorical_data.values,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            });

            createChart(doughnutChartCtx, 'doughnut', {
                labels: analysis.categorical_data.labels,
                datasets: [{
                    label: 'Категории',
                    data: analysis.categorical_data.values,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            });

            createChart(radarChartCtx, 'radar', {
                labels: analysis.categorical_data.labels,
                datasets: [{
                    label: 'Категории',
                    data: analysis.categorical_data.values,
                    backgroundColor: 'rgba(179,181,198,0.2)',
                    borderColor: 'rgba(179,181,198,1)',
                    pointBackgroundColor: 'rgba(179,181,198,1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(179,181,198,1)'
                }]
            });

            createChart(polarAreaChartCtx, 'polarArea', {
                labels: analysis.categorical_data.labels,
                datasets: [{
                    label: 'Категории',
                    data: analysis.categorical_data.values,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            });

            if (analysis.scatter_data && analysis.scatter_data.points.length > 0) {
                createChart(scatterChartCtx, 'scatter', {
                    datasets: [{
                        label: 'Точки рассеяния',
                        data: analysis.scatter_data.points,
                        backgroundColor: 'rgba(75, 192, 192, 1)',
                    }]
                }, {
                    scales: {
                        x: { type: 'linear', position: 'bottom' }
                    }
                });
            }

            if (analysis.scatter_data && analysis.scatter_data.points.length > 0) {
                createChart(bubbleChartCtx, 'bubble', {
                    datasets: [{
                        label: 'Bubble Chart',
                        data: analysis.scatter_data.points,
                        backgroundColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                });
            }
        }

        // Инициализация графиков для отчетов
        initializeCharts(analysis1, 'report1'); // Prefix for Report 1
        initializeCharts(analysis2, 'report2'); // Prefix for Report 2

        // Вывод различий
        const differenceList = document.getElementById('differenceList');
        comparison.numerical_difference.forEach((diff, index) => {
            const li = document.createElement('li');
            li.textContent = `Разница в категории ${analysis1.categorical_data.labels[index]}: ${diff}`;
            differenceList.appendChild(li);
        });
    </script>
</body>
</html>
