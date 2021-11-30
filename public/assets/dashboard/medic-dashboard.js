
let colors = ['#d76d35', '#c81d25', '#01b0a4', '#4062d8']

// Create chart function
function createChart(id) {
    const ctx = document.getElementById(id);
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: '',
                data: [],
                backgroundColor: colors,
                borderColor: colors,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    title: {
                        display: true,
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Număr'
                    },
                    min: 0,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,
                    position: 'right'
                }
            }
        }
    });
}

// Update chart with ajax
function chartUpdateAjax(chart, url, chartContainer) {
    $.ajax({
        type: 'GET',
        url: url,
        dataType: "json",
        beforeSend: function () {
            $('body').append('<div class="loader"></div>')
        },
        success: function (result)
        {
            $('.loader').remove()
            // Get data
            let labels = $.map(result, function(element,index) {return index})
            let data = $.map(result, function(element) {return element})

            // Update chart
            chart.data.datasets[0].data = data;
            chart.data.labels = labels;
            chart.update();

            // Update general data
            if(url === '/medic/dashboard/distributie') {
                $('#total-medici-number').html(`<span>${result['Medici']}</span>`)
                $('#total-consultatii-number').html(`<span>${result['Consultații']}</span>`)
                $('#total-pacienti-number').html(`<span>${result['Pacienți']}</span>`)
            }
        }
    });
}

// When document ready
$(document).ready(function() {
    let distributionChart = createChart('distribution-chart', );
    let topChart = createChart('top-chart', );
    chartUpdateAjax(distributionChart, '/medic/dashboard/distributie');
    chartUpdateAjax(topChart, '/medic/dashboard/top-medici');
});