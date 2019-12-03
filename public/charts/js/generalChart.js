let humidityChart;
let pressureChart;
let temperaturaChart;
let polluChart;
window.onload = function () {


    const temp = Routing.generate('dataForGeneralChartInside');

    fetch(temp)
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            insideChart(data);

        });


};

function insideChart(data) {

    let hum = [];
    let time_hum = [];

    let press = [];
    let time_press = [];


    let temp_inside = [];
    let time_inside_temp = [];

    let pm25 = [];
    let pm10 = [];
    let time_pollution = [];


    Object.keys(data.hum_inside).forEach(function (k) {

        let dt = data.hum_inside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_hum.push(dt.slice(0, dt.lastIndexOf(".")));
        hum.push(data.hum_inside[k].humidity);

    });
    Object.keys(data.press_inside).forEach(function (k) {

        let dt = data.press_inside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_press.push(dt.slice(0, dt.lastIndexOf(".")));
        press.push(data.press_inside[k].pressure);

    });
    Object.keys(data.temp_inside).forEach(function (k) {

        let dt = data.temp_inside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_inside_temp.push(dt.slice(0, dt.lastIndexOf(".")));
        temp_inside.push(data.temp_inside[k].temperature);

    });

    Object.keys(data.pollution).forEach(function (k) {

        let dt = data.pollution[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_pollution.push(dt.slice(0, dt.lastIndexOf(".")));
        pm25.push(data.pollution[k].pm25);
        pm10.push(data.pollution[k].pm10);

    });


    chart("Wilgotność", "Wykres Wilgotności Wewnętrzny", time_hum, "myChartHumidity", hum, humidityChart, 'line');
    chart("Ciśnienie", "Wykres Ciśnienia Wewnętrzny", time_press, "myChartPressure", press, pressureChart, 'line');
    pollutionChart(pm10, pm25, time_pollution, polluChart);
    chart('Temperatura', 'Wykres Temperatury Wewnętrzny', time_inside_temp, 'myChartTemp', temp_inside, temperaturaChart, 'bar');


}

/**
 * jesli true, to wewnatrz, false na zewnatrz
 * @param typeChart
 */
function changeTypeOfChart(typeChart) {
    if (typeChart) {


        const temp = Routing.generate('dataForGeneralChartInside');

        fetch(temp)
            .then((res) => {
                return res.json();
            })
            .then((data) => {
                insideChart(data);


            });
        return true;
    }


    const temp = Routing.generate('dataForGeneralChartOutside');

    fetch(temp)
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            outsideChart(data);

        });


}

function outsideChart(data) {

    let hum = [];
    let time_hum = [];

    let press = [];
    let time_press = [];


    let temp_inside = [];
    let time_inside_temp = [];

    let pm25 = [];
    let pm10 = [];
    let time_pollution = [];


    Object.keys(data.hum_outside).forEach(function (k) {

        let dt = data.hum_outside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_hum.push(dt.slice(0, dt.lastIndexOf(".")));
        hum.push(data.hum_outside[k].humidity);

    });
    Object.keys(data.press_outside).forEach(function (k) {

        let dt = data.press_outside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_press.push(dt.slice(0, dt.lastIndexOf(".")));
        press.push(data.press_outside[k].pressure);

    });
    Object.keys(data.temp_outside).forEach(function (k) {

        let dt = data.temp_outside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_inside_temp.push(dt.slice(0, dt.lastIndexOf(".")));
        temp_inside.push(data.temp_outside[k].temperature);

    });

    Object.keys(data.pollution).forEach(function (k) {

        let dt = data.pollution[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_pollution.push(dt.slice(0, dt.lastIndexOf(".")));
        pm25.push(data.pollution[k].pm25);
        pm10.push(data.pollution[k].pm10);

    });


    chart("Wilgotność", "Wykres Wilgotności Zewnętrzne", time_hum, "myChartHumidity", hum, humidityChart, 'line');
    chart("Ciśnienie", "Wykres Ciśnienia Zewnętrzne", time_press, "myChartPressure", press, pressureChart, 'line');
    pollutionChart(pm10, pm25, time_pollution, polluChart);
    chart('Temperatura', 'Wykres Temperatury Zewnętrzny', time_inside_temp, 'myChartTemp', temp_inside, temperaturaChart, 'bar');
}


function chart(label, title, time, id_div, data, obj, type) {
    if (typeof obj !== "undefined") {
        obj.destroy();
    }


    obj = new Chart(document.getElementById(id_div), {
        type: type,
        data: {
            labels: time,
            datasets: [{

                data: data,
                label: label,
                borderColor: "#ef7d00",
                fill: false,
                backgroundColor: '#ef7d00',
                lineTension: 0,
                pointRadius: 4,
                pointHoverRadius: 12

            }
            ]
        },
        options: {
            legend: {
                //https://stackoverflow.com/a/49444741
                onClick: null,
                labels: {
                    // This more specific font property overrides the global property
                    fontColor: '#ef7d00',

                }
            },
            //https://stackoverflow.com/a/37293215
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "black",
                        callback: function (value, index, values) {
                            switch (label) {
                                case 'Temperatura':
                                    return value + '°';
                                case 'Ciśnienie':
                                    return value + ' hPa';
                                case 'Wilgotność':
                                    return value + ' %';
                            }

                        }
                    }
                }],
                xAxes: [{
                    ticks: {
                        //https://stackoverflow.com/a/39326127
                        fontColor: "black",
                        autoSkip: true,
                        maxTicksLimit: 8,


                    }
                }]
            },
            responsive: true,
            //https://stackoverflow.com/a/32460154
            maintainAspectRatio: false,
            title: {
                display: true,
                text: title
            }
        }

    });
}


function pollutionChart(pm10, pm25, time, chart) {

    if (typeof chart !== "undefined") {
        chart.destroy();
    }

    new Chart(document.getElementById('myChartPollution'), {
        type: 'bar',
        data: {
            labels: time,
            datasets: [{
                data: pm10,
                label: 'PM10',
                borderColor: "#ef7d00",
                backgroundColor: "#ef7d00",
                fill: false
            }, {
                data: pm25,
                label: 'PM25',
                borderColor: "#ef7d00",
                backgroundColor: "#ef7d00",
                fill: false

            }
            ]
        },
        options: {
            legend: {
                //https://stackoverflow.com/a/49444741
                onClick: null,
                labels: {
                    // This more specific font property overrides the global property
                    fontColor: '#ef7d00',

                }
            },
            //https://stackoverflow.com/a/37293215
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "black",

                    }
                }],
                xAxes: [{
                    ticks: {
                        fontColor: "black",


                    }
                }]
            },
            responsive: true,
            //https://stackoverflow.com/a/32460154
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Wykres zanieczyszczenia'
            }
        }

    });
}








