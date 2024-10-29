@include('layouts.header')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Здесь добавьте CSRF-токен -->
    <title>Отчеты</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Основные стили */
        body {
            display: flex; /* Используем flexbox для основного контейнера */
        }

        #sidebar {
            width: 20%; /* Фиксированная ширина боковой панели */
            margin-right: 20px; /* Отступ между боковой панелью и областью отчета */
        }

        #report-area {
            border: 1px solid #ccc;
            height: 500px;
            flex-grow: 1; /* Занимает оставшееся пространство */
            position: relative;
            overflow: hidden;
        }

        .block {
            width: 300px;
            height: 300px;
            border: 1px solid #000;
            position: absolute;
            resize: both;
            overflow: auto;
            background: white;
        }

        .drag-handle {
            width: 100%;
            height: 20px;
            background-color: #ddd;
            cursor: move;
            position: absolute;
            top: 0;
            left: 0;
            text-align: center;
            line-height: 20px;
        }

        #sidebar .block {
            position: relative;
            margin-bottom: 10px;
            cursor: grab;
            width: 150px;
            height: 80px;
            text-align: center;
            line-height: 80px;
        }

        #controls {
            display: none;
            margin-bottom: 10px;
        }

        #controls input {
            margin-right: 10px;
        }
    </style>
</head>
<body>

    <div id="sidebar">
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="pie">Круговая диаграмма</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="bar">Столбчатая диаграмма</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="line">Линейная диаграмма</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="histogram">Гистограмма</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="radar">Радиальная диаграмма</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="polar">Полярная диаграмма</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="scatter">Облако точек</div>
        <div class="block" draggable="true" ondragstart="dragNewBlock(event)" data-type="text">Текстовый блок</div>
    </div>
    <div id="controls">
        <button id="delete-block">Удалить</button>
        <div id="chart-controls" style="display:none;">
            <div id="chart-values"></div>
            <button id="add-value">Добавить значение</button>
            <button id="update-chart">Обновить диаграмму</button>
        </div>
    </div>
    <div id="page-controls">
        <button onclick="addPage()">Добавить страницу</button>
        <select id="page-selector" onchange="switchPage()">
            <option value="page-1">Страница 1</option>
        </select>
    </div>
    <div id="report-area" ondrop="dropNewBlock(event)" ondragover="allowDrop(event)"></div>
    <button id="download-images">Скачать Изображения</button>
@auth
    <h3>Публикация отчета</h3>
<form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <label for="project_name">Название проекта:</label>
        <input type="text" id="project_name" name="project_name" required>
    </div>

    <div>
        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea>
    </div>

    <div>
        <label>Тип отчета:</label>
        <input type="radio" id="public" name="report_type" value="public" required>
        <label for="public">Публичный</label>
        <input type="radio" id="private" name="report_type" value="private" required>
        <label for="private">Частный</label>
    </div>

    <div>
        <label for="first_page_image">Первая страница (изображение):</label>
        <input type="file" id="first_page_image" name="first_page_image" accept="image/*" required>
    </div>

    <div>
        <label for="images">Остальные страницы (изображения):</label>
        <input type="file" id="images" name="images[]" accept="image/*" multiple>
    </div>

    <div>
        <button type="submit">Опубликовать отчет</button>
    </div>
</form>
@endauth

    <script>
        let selectedBlock = null;
        let valueCount = 2; // Начальное количество значений
        let chartInstances = {}; // Хранение созданных диаграмм
        let chartData = {}; // Хранение данных для каждой диаграммы

        function allowDrop(ev) {
            ev.preventDefault();
        }

        function dragNewBlock(ev) {
            ev.dataTransfer.setData("blockType", ev.target.getAttribute("data-type"));
        }

        function dropNewBlock(ev) {
            ev.preventDefault();
            const blockType = ev.dataTransfer.getData("blockType");
            createBlock(blockType, ev.clientX, ev.clientY);
        }

        function createBlock(blockType, x, y) {
            const newElement = document.createElement("div");
            newElement.className = "block";
            newElement.contentEditable = "true";

            const dragHandle = document.createElement("div");
            dragHandle.className = "drag-handle";
            dragHandle.innerHTML = "Переместить";
            newElement.appendChild(dragHandle);

            // Создание элемента canvas для графиков
            const canvas = document.createElement("canvas");
            canvas.width = 300;
            canvas.height = 300;
            newElement.appendChild(canvas);
            newElement.dataset.type = blockType;
            newElement.dataset.chartId = Date.now(); // Уникальный ID для диаграммы

            const rect = document.getElementById('report-area').getBoundingClientRect();
            newElement.style.left = x - rect.left + 'px';
            newElement.style.top = y - rect.top + 'px';

            makeDraggable(newElement);

            newElement.addEventListener('click', function() {
                selectBlock(newElement);
            });

            document.getElementById('report-area').appendChild(newElement);
        }

        function selectBlock(block) {
            selectedBlock = block;
            document.getElementById('controls').style.display = 'block';

            if (['pie', 'bar', 'line', 'histogram', 'radar', 'polar', 'scatter'].includes(block.dataset.type)) {
                document.getElementById('chart-controls').style.display = 'block';
                loadChartData(block.dataset.chartId);
            } else {
                document.getElementById('chart-controls').style.display = 'none';
            }
        }

        function loadChartData(chartId) {
            const data = chartData[chartId] || { labels: [], values: [] };
            valueCount = data.labels.length || 2; // Устанавливаем количество значений

            document.getElementById('chart-values').innerHTML = ''; // Очищаем старые поля
            for (let i = 0; i < valueCount; i++) {
                addInput(i + 1, data.labels[i] || '', data.values[i] || 0);
            }
        }

        function addInput(index, label = '', value = '') {
            const chartValuesDiv = document.getElementById('chart-values');
            const newLabel = document.createElement('input');
            newLabel.type = 'text';
            newLabel.placeholder = `Название ${index}`;
            newLabel.id = `label${index}`;
            newLabel.value = label;

            const newValue = document.createElement('input');
            newValue.type = 'number';
            newValue.placeholder = `Значение ${index}`;
            newValue.id = `value${index}`;
            newValue.value = value;

            chartValuesDiv.appendChild(newLabel);
            chartValuesDiv.appendChild(newValue);
        }

        document.getElementById('delete-block').addEventListener('click', function() {
            if (selectedBlock) {
                const chartId = selectedBlock.dataset.chartId;
                if (chartInstances[chartId]) {
                    chartInstances[chartId].destroy(); // Удаление диаграммы
                    delete chartInstances[chartId];
                    delete chartData[chartId]; // Удаление данных диаграммы
                }
                selectedBlock.remove();
                selectedBlock = null;
                document.getElementById('controls').style.display = 'none';
            }
        });

        document.getElementById('add-value').addEventListener('click', function() {
            valueCount++;
            addInput(valueCount); // Добавление нового поля ввода
        });

        document.getElementById('update-chart').addEventListener('click', function() {
            if (selectedBlock) {
                const labels = [];
                const data = [];
                const chartId = selectedBlock.dataset.chartId;

                // Собираем данные из полей ввода
                for (let i = 1; i <= valueCount; i++) {
                    const label = document.getElementById(`label${i}`).value || `Название ${i}`;
                    const value = document.getElementById(`value${i}`).value || 0;
                    labels.push(label);
                    data.push(Number(value));
                }

                // Получаем контекст canvas для отрисовки диаграммы
                const ctx = selectedBlock.querySelector("canvas").getContext("2d");

                // Если диаграмма уже существует, уничтожаем ее перед созданием новой
                if (chartInstances[chartId]) {
                    chartInstances[chartId].destroy();
                }

                let chartType = selectedBlock.dataset.type; // Определяем тип диаграммы

                // Создаем новую диаграмму
                const newChart = new Chart(ctx, {
                    type: chartType,
                    data: {
                        labels: labels,
                        datasets: [{
                            label: chartType.charAt(0).toUpperCase() + chartType.slice(1) + ' диаграмма',
                            data: data,
                            backgroundColor: getChartBackgroundColor(chartType),
                            borderColor: getChartBorderColor(chartType),
                            borderWidth: chartType === 'scatter' ? 1 : 0,
                            pointBackgroundColor: chartType === 'scatter' ? '#36a2eb' : null
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });

                chartInstances[chartId] = newChart; // Сохраняем экземпляр диаграммы
                chartData[chartId] = { labels, values: data }; // Сохраняем данные диаграммы
            }
        });

        function getChartBackgroundColor(type) {
            switch (type) {
                case 'pie':
                    return ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0'];
                case 'bar':
                case 'line':
                case 'histogram':
                    return '#36a2eb';
                case 'radar':
                    return 'rgba(54, 162, 235, 0.2)';
                case 'polar':
                    return ['#ff6384', '#36a2eb', '#cc65fe', '#ffce56', '#4bc0c0'];
                case 'scatter':
                    return 'rgba(54, 162, 235, 1)';
                default:
                    return '#36a2eb';
            }
        }

        function getChartBorderColor(type) {
            switch (type) {
                case 'line':
                case 'scatter':
                    return '#36a2eb';
                case 'radar':
                    return '#ff6384';
                default:
                    return 'transparent';
            }
        }

        function makeDraggable(element) {
            let isMouseDown = false;
            let offset = [0, 0];

            element.querySelector('.drag-handle').addEventListener('mousedown', function(e) {
                isMouseDown = true;
                offset = [
                    element.offsetLeft - e.clientX,
                    element.offsetTop - e.clientY
                ];
            });

            document.addEventListener('mouseup', function() {
                isMouseDown = false;
            });

            document.addEventListener('mousemove', function(e) {
                if (isMouseDown) {
                    element.style.left = (e.clientX + offset[0]) + 'px';
                    element.style.top = (e.clientY + offset[1]) + 'px';
                }
            });
        }

        let currentPage = "page-1";  // Текущая страница
    let pages = {
        "page-1": []  // Храним блоки для каждой страницы
    };

    function addPage() {
        const pageId = `page-${Object.keys(pages).length + 1}`;
        const reportArea = document.getElementById("report-area");

        // Сохранение текущего содержимого перед переключением
        pages[currentPage] = Array.from(reportArea.children).map(block => {
            return {
                html: block.outerHTML,
                left: block.style.left,
                top: block.style.top,
                chartData: block.dataset.chartId ? chartData[block.dataset.chartId] : null
            };
        });

        pages[pageId] = []; // Создаем новую пустую страницу
        const option = document.createElement("option");
        option.value = pageId;
        option.text = `Страница ${Object.keys(pages).length}`;
        document.getElementById("page-selector").appendChild(option);
        document.getElementById("page-selector").value = pageId;
        switchPage();
    }
    function switchPage() {
        const pageId = document.getElementById("page-selector").value;

        const reportArea = document.getElementById("report-area");
        // Сохранение текущего содержимого
        pages[currentPage] = Array.from(reportArea.children).map(block => {
            return {
                html: block.outerHTML,
                left: block.style.left,
                top: block.style.top,
                chartData: block.dataset.chartId ? chartData[block.dataset.chartId] : null
            };
        });

        // Очищаем область отчета
        reportArea.innerHTML = "";

        // Восстанавливаем содержимое новой страницы
        const pageContent = pages[pageId] || [];
        pageContent.forEach(blockData => {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = blockData.html;
            const block = tempDiv.firstChild;
            block.style.left = blockData.left;
            block.style.top = blockData.top;

            makeDraggable(block);

            // Восстанавливаем данные диаграмм, если это диаграмма
            if (block.dataset.chartId) {
                const ctx = block.querySelector("canvas").getContext("2d");
                const chartId = block.dataset.chartId;

                if (blockData.chartData) {
                    chartData[chartId] = blockData.chartData;

                    const newChart = new Chart(ctx, {
                        type: block.dataset.type,
                        data: {
                            labels: chartData[chartId].labels,
                            datasets: [{
                                label: block.dataset.type.charAt(0).toUpperCase() + block.dataset.type.slice(1) + ' диаграмма',
                                data: chartData[chartId].values,
                                backgroundColor: getChartBackgroundColor(block.dataset.type),
                                borderColor: getChartBorderColor(block.dataset.type),
                                borderWidth: 1,
                                fill: block.dataset.type === 'line'
                            }]
                        }
                    });

                    chartInstances[chartId] = newChart;
                }
            }

            block.addEventListener('click', function() {
                selectBlock(block);
            });

            reportArea.appendChild(block);
        });

        currentPage = pageId;
    }

function loadPage(pageId) {
    // Очищаем область отчета
    document.getElementById('report-area').innerHTML = '';

    // Загружаем блоки с данной страницы
    const blocks = pages[pageId];
    blocks.forEach(blockData => {
        const { type, left, top, chartId, chartData } = blockData;
        createBlock(type, parseInt(left), parseInt(top));

        // Восстанавливаем диаграмму, если она была
        if (chartId && chartData) {
            const block = document.querySelector(`#report-area .block[data-chart-id="${chartId}"]`);
            chartData[chartId] = chartData;
            loadSavedChart(block, chartData); // Функция для загрузки диаграммы
        }
    });
}

function loadSavedChart(block, data) {
    const ctx = block.querySelector("canvas").getContext("2d");
    const chartType = block.dataset.type;

    const newChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: data.labels,
            datasets: [{
                label: chartType.charAt(0).toUpperCase() + chartType.slice(1) + ' диаграмма',
                data: data.values,
                backgroundColor: getChartBackgroundColor(chartType),
                borderColor: getChartBorderColor(chartType),
                borderWidth: chartType === 'scatter' ? 1 : 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    chartInstances[block.dataset.chartId] = newChart; // Восстанавливаем диаграмму
}


   

    // Загрузка сохраненного отчета при открытии
    window.addEventListener('load', function() {
        const savedPages = localStorage.getItem('report-pages');
        if (savedPages) {
            pages = JSON.parse(savedPages);
            loadPage(currentPage);
        }
    });

    document.getElementById('download-images').addEventListener('click', function() {
        const reportArea = document.getElementById('report-area');

        html2canvas(reportArea).then(function(canvas) {
            // Создаем ссылку для скачивания изображения
            const link = document.createElement('a');
            link.href = canvas.toDataURL('image/png'); // Конвертируем canvas в PNG
            link.download = 'report-image.png'; // Имя файла для сохранения
            link.click(); // Инициируем скачивание
        }).catch(function(error) {
            console.error('Ошибка при создании изображения:', error);
        });
    });
        
  

document.getElementById('download-all-images').addEventListener('click', function() {
    downloadAllPagesAsImage();
});


function downloadAllPagesAsImage() {
    const pages = document.querySelectorAll('.page'); // Измените селектор на ваш
    console.log(`Найдено страниц: ${pages.length}`); // Логируем количество страниц

    if (pages.length === 0) {
        console.error("Нет страниц для захвата");
        return; // Выходим, если нет страниц
    }

    const container = document.createElement('div'); // Создаем временный контейнер

    pages.forEach((page) => {
        const clone = page.cloneNode(true); // Клонируем каждую страницу
        container.appendChild(clone); // Добавляем в контейнер
    });

    document.body.appendChild(container); // Добавляем контейнер в body

    setTimeout(() => {
        html2canvas(container, { scale: 2 }).then((canvas) => {
            const link = document.createElement('a');
            link.download = 'report.png'; // Имя файла
            link.href = canvas.toDataURL('image/png'); // Данные изображения
            link.click(); // Запускаем скачивание

            // Удаляем временный контейнер
            document.body.removeChild(container);
        }).catch(err => {
            console.error("Ошибка при захвате канваса:", err);
        });
    }, 1000); // Задержка в 1 секунду для рендеринга
}





document.getElementById('publish-report').addEventListener('click', function() {
    // Собираем данные для отправки
    const formData = new FormData();
    formData.append('project_name', document.getElementById('project_name').value);
    formData.append('description', document.getElementById('description').value);
    formData.append('report_type', document.querySelector('input[name="report_type"]:checked').value);

    const firstPageImage = document.getElementById('first_page_image').files[0];
    formData.append('first_page_image', firstPageImage);

    // Добавление остальных страниц
    const images = document.getElementById('images').files;
    for (let i = 0; i < images.length; i++) {
        formData.append('images[]', images[i]);
    }

    // Отправляем запрос на сервер
    fetch('/reports', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    }).then(response => {
        if (response.ok) {
            window.location.href = '/reports';
        } else {
            alert('Ошибка при публикации отчета');
        }
    });
});




        function saveCurrentPageState() {
            const currentPageData = [];
            const blocks = document.querySelectorAll('.block'); // Замените на ваш селектор

            blocks.forEach(block => {
                currentPageData.push({
                    html: block.innerHTML,
                    left: block.style.left,
                    top: block.style.top
                });
            });

            pages.push(currentPageData); // Сохраняем текущую страницу в массив
        }



    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>



</body>
</html>
