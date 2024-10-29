// Сохранение всех графиков
function saveCharts() {
    const charts = document.querySelectorAll('canvas');
    charts.forEach(chart => {
        const imageData = chart.toDataURL();
        // Отправляем данные на сервер
        $.ajax({
            url: '/save-chart',
            type: 'POST',
            data: { chart: imageData },
            success: function(response) {
                console.log('Chart saved successfully');
            },
            error: function(error) {
                console.log('Error saving chart', error);
            }
        });
    });
}
