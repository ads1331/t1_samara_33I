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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
    <div class="canvas-wrapp">
        <div id="sidebar">
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="pie">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/elipse.png') }}" alt="">
                </div>
                <span>Круговая диаграмма</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="bar">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/column.png') }}" alt="">
                </div>
                <span>Столбчатая диаграмма</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="line">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/line.png') }}" alt="">
                </div>
                <span>Линейная диаграмма</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="histogram">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/gistogramm.png') }}" alt="">
                </div>
                <span>Гистограмма</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="radar">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/radial2.png') }}" alt="">
                </div>
                <span>Радиальная диаграмма</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="polar">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/polar.png') }}" alt="">
                </div>
                <span>Полярная диаграмма</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="scatter">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/dots.png') }}" alt="">
                </div>
                <span>Облако точек</span>
            </div>
            <div class="diagramms" draggable="true" ondragstart="dragNewBlock(event)" data-type="text">
                <div class="diagramm-ico">
                    <img src="{{ asset('img/text.png') }}" alt="">
                </div>
                <span>Текстовый блок</span>
            </div>
        </div>
        <div id="report-area" ondrop="dropNewBlock(event)" ondragover="allowDrop(event)"></div>
        <div id="controls">
        <div id="chart-controls" style="display:none;">

            <div id="chart-values">

            </div>
                <div class="chart-buttons">
                <button id="delete-block">Удалить</button>
                <button id="add-value">Добавить значение</button>
                <button id="update-chart">Обновить диаграмму</button>
            </div>
        </div>
    </div>
    </div>
    <div class="publish_container">
    <div class="publish-wrapp">
    <div id="page-controls">
        <button onclick="addPage()">Добавить страницу</button>
        <select id="page-selector" onchange="switchPage()">
            <option  id="option" value="page-1">Страница 1</option>
        </select>
        <button id="download-images">Скачать Изображения</button>
    </div>

@auth
    <div class="publish">
    <h3>Публикация отчета</h3>
<form action="{{ route('report.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div>
        <div class="label-wrapp">
            <label for="project_name">Название проекта:</label>
        </div>
        <div class="input-wrapp">
            <input type="text" id="project_name" name="project_name" required>
        </div>
    </div>

    <div>
        <div class="label-wrapp">
            <label for="description">Описание:</label>
        </div>
        <div class="input-wrapp">
            <textarea id="description" name="description" required></textarea>
        </div>
    </div>

    <div>
        <div class="label-wrapp">
            <label>Тип отчета:</label>
        </div>
        <div class="input-wrapp">
            <input type="radio" id="public" name="report_type" value="public" required>
            <label for="public">Публичный</label>
            <input type="radio" id="private" name="report_type" value="private" required>
            <label for="private">Частный</label>
        </div>
    </div>

    <div>
        <div class="label-wrapp">
            <label for="first_page_image">Первая страница (изображение):</label>
        </div>
        <div class="input-wrapp">
            <label for="first_page_image" class="custom-file-upload">Загрузить файл</label>
            <span class="file-name">Файл не выбран</span>
            <input type="file" id="first_page_image" name="first_page_image" accept="image/*" required>
        </div>
    </div>

    <div>
        <div class="label-wrapp">
            <label for="images">Остальные страницы (изображения):</label>
        </div>
        <div class="input-wrapp">
            <label for="images" class="custom-file-upload">Загрузить файл</label>
            <span class="file-name">Файл не выбран</span>
            <input type="file" id="images" name="images[]" accept="image/*" multiple>
        </div>
    </div>

    <div>
        <button type="submit">Опубликовать отчет</button>
    </div>
</form>
</div>
</div>
</div>
@endauth
    <button id="download-pdf">Скачать PDF</button>
    <script>
        document.getElementById('download-pdf').addEventListener('click', function() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            // Заголовок отчета
            doc.text("Отчет", 10, 10);

            // Получаем блоки отчета
            const reportArea = document.getElementById('report-area');
            const blocks = reportArea.children;

            // Обрабатываем каждый блок
            Array.from(blocks).forEach((block, index) => {
                // Добавляем текст или информацию о блоке в PDF
                doc.text(`Блок ${index + 1}: ${block.dataset.type}`, 10, 20 + (index * 10));

                // Добавляем изображения, если есть
                const canvas = block.querySelector('canvas');
                if (canvas) {
                    const imgData = canvas.toDataURL('image/png');
                    doc.addImage(imgData, 'PNG', 10, 30 + (index * 40), 180, 90); // Вы можете настроить позицию и размеры
                }
            });

            // Скачиваем PDF
            doc.save("report.pdf");
        });

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
            newElement.contentEditable = "false";

            // Создание инпута над текстовым блоком
            if (blockType === 'text') {
                const input = document.createElement('input');
                input.type = 'text';
                input.placeholder = 'Введите текст здесь';
                newElement.appendChild(input);
            }

            const dragHandle = document.createElement("div");
            dragHandle.className = "drag-handle";
            newElement.appendChild(dragHandle);

            // Создание элемента canvas для графиков (если это график)
            if (['pie', 'bar', 'line', 'histogram', 'radar', 'polar', 'scatter'].includes(blockType)) {
                const canvas = document.createElement("canvas");
                canvas.width = 300;
                canvas.height = 300;
                newElement.appendChild(canvas);
            }

            newElement.dataset.type = blockType;
            newElement.dataset.chartId = Date.now(); // Уникальный ID для диаграммы

            const rect = document.getElementById('report-area').getBoundingClientRect();
            newElement.style.left = x - rect.left + 'px';
            newElement.style.top = y - rect.top + 'px';

            makeDraggable(newElement);

            newElement.addEventListener('click', function() {
                selectBlock(newElement);
            });

            dragHandle.addEventListener('mousedown', function(event) {
                event.preventDefault();
            });

            document.getElementById('report-area').appendChild(newElement);
        }

        const fileInput = document.getElementById('images');
const fileNameDisplay = document.querySelector('.file-name');

fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        const fileNames = Array.from(fileInput.files).map(file => file.name).join(', ');
        fileNameDisplay.textContent = fileNames;
    } else {
        fileNameDisplay.textContent = 'Файл не выбран';
    }
});




        function selectBlock(block) {
            selectedBlock = block;
            document.getElementById('controls').style.display = 'flex';

            if (['pie', 'bar', 'line', 'histogram', 'radar', 'polar', 'scatter'].includes(block.dataset.type)) {
                document.getElementById('chart-controls').style.display = 'flex';
                //document.getElementById('chart-controls').style.flex-wrap = 'wrap';
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
            labels.push(`${label} - ${value}`); // Меняем формат для каждого элемента
            data.push(Number(value));
        }

        // Получаем контекст canvas для отрисовки диаграммы
        const ctx = selectedBlock.querySelector("canvas").getContext("2d");

        // Если диаграмма уже существует, уничтожаем её перед созданием новой
        if (chartInstances[chartId]) {
            chartInstances[chartId].destroy();
        }

        let chartType = selectedBlock.dataset.type; // Определяем тип диаграммы

        // Специальные опции для линейной диаграммы
        let chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
        };

        // Проверка, если тип диаграммы 'line', то добавляем специфические настройки
        if (chartType === 'line') {
            chartOptions = {
                responsive: true,
                maintainAspectRatio: false,
                elements: {
                    line: {
                        tension: 0.4, // Сглаживание линий
                    },
                    point: {
                        radius: 5, // Размер точек
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Названия',
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Значения',
                        }
                    }
                }
            };
        }

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
                    borderWidth: chartType === 'line' ? 2 : 1,  // Толщина линии
                    fill: chartType === 'line' ? false : true // Отключаем заливку для линии
                }]
            },
            options: chartOptions
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
                borderWidth: chartType === 'scatter' ? 1 : 0,
                fill: chartType === 'line'
            }]
        },
        options: getChartOptions(chartType)
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
