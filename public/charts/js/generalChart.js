window.onload = function () {


    const temp = Routing.generate('dataForGeneralChart');

    fetch(temp)
        .then((res) => {
            return res.json();
        })
        .then((data) => {


            console.log(data);
            let hum = [];
            let time_hum = [];

            let press = [];
            let time_press = [];


            let temp_inside = [];
            let time_inside_temp = [];

            let temp_outside = [];
            let time_outside_temp = [];

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
            Object.keys(data.temp_outside).forEach(function (k) {

                let dt = data.temp_outside[k].createdAt.date;
                /* Możliwe ze jakos ładniej to zrobie, TODO*/
                time_outside_temp.push(dt.slice(0, dt.lastIndexOf(".")));
                temp_outside.push(data.temp_outside[k].humidity);

            });
            Object.keys(data.pollution).forEach(function (k) {

                let dt = data.pollution[k].createdAt.date;
                /* Możliwe ze jakos ładniej to zrobie, TODO*/
                time_pollution.push(dt.slice(0, dt.lastIndexOf(".")));
                pm25.push(data.pollution[k].pm25);
                pm10.push(data.pollution[k].pm10);

            });


            lineChart("Wilgotność", "Wykres Wilgotności Wewnątrz", time_hum, "myChartHumidity", hum);
            lineChart("Ciśnienie", "Wykres Ciśnienia Wewnątrz", time_press, "myChartPressure", press);
            pollutionChart(pm10, pm25, time_pollution);
            lineChart('Temperatura', 'Wykres Temperatury', time_inside_temp, 'myChartTemp', temp_inside);

        });


};


function lineChart(label, title, time, id_div, data) {
    if (typeof chart !== "undefined") {
        chart.destroy();
    }


    new Chart(document.getElementById(id_div), {
        type: 'line',
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


function pollutionChart(pm10, pm25, time) {

    if (typeof chart !== "undefined") {
        chart.destroy();
    }

    new Chart(document.getElementById('myChartPollution'), {
        type: 'line',
        data: {
            labels: time,
            datasets: [{
                data: pm10,
                label: 'PM10',
                borderColor: "#ef7d00",
                fill: false
            }, {
                data: pm25,
                label: 'PM25',
                borderColor: "#ef7d00",
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








