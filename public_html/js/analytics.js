$(function () {
    'use strict'
    setup_analytic_sales();
})
var salesChart = null;
function setup_analytic_sales(type='week'){
    if(salesChart!=null){
        salesChart.destroy();
    }
    var ticksStyle = {
        fontColor: '#495057',
        fontStyle: 'bold'
    }
    var mode      = 'index';
    var intersect = true;
    var canvas = document.getElementById('sales-chart');
    var data_json = data_analytics[type];
    var labels = data_json.labels;
    var now = data_json.data_now;
    var last = data_json.data_last;
    var growth = data_json.growth;
    var total = data_json.total;
    console.log(data_json);
if(growth > 0){
    document.querySelector('#sales-analytics .growth').className = 'text-success growth';
    document.querySelector('#sales-analytics .growth').innerHTML = '<i class="fas fa-arrow-up"></i> '+growth+'%';
}else{
    document.querySelector('#sales-analytics .growth').className = 'text-danger growth';
    document.querySelector('#sales-analytics .growth').innerHTML = '<i class="fas fa-arrow-down"></i> '+growth+'%';
}
$('.analytic_type').text(type);
$('.analytics_total').text( format_currency(total) );
     salesChart  = new Chart(canvas, {
        type   : 'bar',
        data   : {
            labels  : labels,
            datasets: [
                {
                    backgroundColor: '#007bff',
                    borderColor    : '#007bff',
                    data           : now
                },
                {
                    backgroundColor: '#ced4da',
                    borderColor    : '#ced4da',
                    data           : last
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            tooltips           : {
                mode     : mode,
               // intersect: intersect,
                callbacks: {
                    label:function(tooltipItem, chart) {
                        return format_currency(tooltipItem.value);
                    }
                }
            },
            hover              : {
                mode     : mode,
                intersect: intersect
            },
            legend             : {
                display: false
            },
            scales             : {
                yAxes: [{
                    // display: false,
                    gridLines: {
                        display      : true,
                        lineWidth    : '4px',
                        color        : 'rgba(0, 0, 0, .2)',
                        zeroLineColor: 'transparent'
                    },
                    ticks    : $.extend({
                        beginAtZero: true,

                        // Include a dollar sign in the ticks
                        callback: function (value, index, values) {
                            return format_currency(value)
                        }
                    }, ticksStyle)
                }],
                xAxes: [{
                    display  : true,
                    gridLines: {
                        display: false
                    },
                    ticks    : ticksStyle
                }]
            }
        }
    });



}