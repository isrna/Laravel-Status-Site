/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

//window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

/*const app = new Vue({
    el: '#app',
});*/

var Chart = require('chart.js');

Chart.defaults.global.responsive = true;
Chart.defaults.global.maintainAspectRatio = false;
Chart.defaults.global.showAllTooltips = true;
Chart.defaults.global.tooltips.custom = function (tooltip) {
    if (!tooltip) return;
    // disable displaying the color box;
    tooltip.displayColors = false;
};
Chart.defaults.global.tooltips.callbacks.label = function (tooltipItem, data) {
    return tooltipItem.yLabel + data.datasets[tooltipItem.datasetIndex].label;
};
Chart.defaults.global.tooltips.callbacks.title = function (tooltipItem, data) {
    return;
};
Chart.defaults.global.legend.display = false;

window.globalCharjsOptions = {
    scales: {
        yAxes: [{
            position: 'right',
            gridLines: {
                drawBorder: false,
                color: '#000000',
                zeroLineColor: '#000000'
            },
            ticks: {
                fontColor: '#AAAAAA',
                precision: 0
            }
        }],
        xAxes: [{
            gridLines: {
                display: false,
            },
            ticks: {
                fontColor: '#AAAAAA'
            }
        }]
    }
};

var seconds = 60;
var element = document.getElementById('refresh_time');
var Tick = function() {
    if(--seconds <= 0)
        location.reload();
    else
        setTimeout(Tick, 1000);

    element.innerHTML = seconds;
}

$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

    if(element != null) {
        element.innerHTML = seconds;
        setTimeout(Tick, 1000);
    }
});

