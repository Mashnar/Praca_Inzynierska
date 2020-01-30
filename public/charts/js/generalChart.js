let pressure;
let humidity;
let temperature;
/***
 *
 *
 * NAPISAC O TYM W PRACY ZE MUSIALEM POTROIC FUNKCJE BO MI STARE DANE ZACHOWYWALY SIE
 */
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

    let dt;
    dt = data.press_inside[0].createdAt.date;
    let date_press = dt.slice(0, dt.lastIndexOf(" "));
    dt = data.hum_inside[0].createdAt.date;
    let date_hum = dt.slice(0, dt.lastIndexOf(" "));
    dt = data.pollution[0].createdAt.date;
    let date_pm = dt.slice(0, dt.lastIndexOf(" "));
    dt = data.temp_inside[0].createdAt.date;
    let date_temp = dt.slice(0, dt.lastIndexOf(" "));


    Object.keys(data.hum_inside).forEach(function (k) {

        let dt = data.hum_inside[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        dt = dt.slice(0, dt.lastIndexOf("."));
        time_hum.push(dt.substring(dt.lastIndexOf(" ") + 1));
        hum.push(data.hum_inside[k].humidity);

    });
    Object.keys(data.press_inside).forEach(function (k) {

        let dt = data.press_inside[k].createdAt.date;
        dt = dt.slice(0, dt.lastIndexOf("."));
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_press.push(dt.substring(dt.lastIndexOf(" ") + 1));
        press.push(data.press_inside[k].pressure);

    });
    Object.keys(data.temp_inside).forEach(function (k) {

        let dt = data.temp_inside[k].createdAt.date;
        dt = dt.slice(0, dt.lastIndexOf("."));
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_inside_temp.push(dt.substring(dt.lastIndexOf(" ") + 1));
        temp_inside.push(data.temp_inside[k].temperature);

    });

    Object.keys(data.pollution).forEach(function (k) {

        let dt = data.pollution[k].createdAt.date;
        dt = dt.slice(0, dt.lastIndexOf("."));
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_pollution.push(dt.substring(dt.lastIndexOf(" ") + 1));
        pm25.push(data.pollution[k].pm25);
        pm10.push(data.pollution[k].pm10);

    });


    chartHumidity("Wilgotność", "Wykres Wilgotności Wewnętrzny", time_hum, "myChartHumidity", hum, 'line', date_hum);
    chartPressure("Ciśnienie", "Wykres Ciśnienia Wewnętrzny", time_press, "myChartPressure", press, 'bar', date_press);
    pollutionChart(pm10, pm25, time_pollution, date_pm);
    chartTemp('Temperatura', 'Wykres Temperatury Wewnętrzny', time_inside_temp, 'myChartTemp', temp_inside, 'line', date_temp);


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
    let dt;
    dt = data.press_outside[0].createdAt.date;
    let date_press = dt.slice(0, dt.lastIndexOf(" "));
    dt = data.hum_outside[0].createdAt.date;
    let date_hum = dt.slice(0, dt.lastIndexOf(" "));
    dt = data.pollution[0].createdAt.date;
    let date_pm = dt.slice(0, dt.lastIndexOf(" "));
    dt = data.temp_outside[0].createdAt.date;
    let date_temp = dt.slice(0, dt.lastIndexOf(" "));

    Object.keys(data.hum_outside).forEach(function (k) {
        let dt = data.hum_outside[k].createdAt.date;
        dt = dt.slice(0, dt.lastIndexOf("."));

        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_hum.push(dt.substring(dt.lastIndexOf(" ") + 1));
        hum.push(data.hum_outside[k].humidity);

    });
    Object.keys(data.press_outside).forEach(function (k) {

        let dt = data.press_outside[k].createdAt.date;

        dt = dt.slice(0, dt.lastIndexOf("."));
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_press.push(dt.substring(dt.lastIndexOf(" ") + 1));
        press.push(data.press_outside[k].pressure);

    });
    Object.keys(data.temp_outside).forEach(function (k) {

        let dt = data.temp_outside[k].createdAt.date;

        dt = dt.slice(0, dt.lastIndexOf("."));
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_inside_temp.push(dt.substring(dt.lastIndexOf(" ") + 1));
        temp_inside.push(data.temp_outside[k].temperature);

    });

    Object.keys(data.pollution).forEach(function (k) {

        let dt = data.pollution[k].createdAt.date;
        dt = dt.slice(0, dt.lastIndexOf("."));
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time_pollution.push(dt.substring(dt.lastIndexOf(" ") + 1));

        pm25.push(data.pollution[k].pm25);
        pm10.push(data.pollution[k].pm10);

    });

    chartHumidity("Wilgotność", "Wykres Wilgotności Zewnętrzny", time_hum, "myChartHumidity", hum, 'line', date_hum);
    chartPressure("Ciśnienie", "Wykres Ciśnienia Zewnętrzny", time_press, "myChartPressure", press, 'bar', date_press);
    pollutionChart(pm10, pm25, time_pollution, date_pm);
    chartTemp('Temperatura', 'Wykres Temperatury Zewnętrzny', time_inside_temp, 'myChartTemp', temp_inside, 'line', date_temp);
}


function chartPressure(label, title, time, id_div, data, type, date) {

    let canvas = document.getElementById(id_div);

    let context = canvas.getContext('2d');

    context.clearRect(0, 0, canvas.width, canvas.height);

    if (pressure !== undefined) {
        pressure.destroy();
    }
    // begin custom shape
    // begin custom shape
    pressure = new Chart(document.getElementById(id_div), {
        type: type,
        data: {
            labels: time,
            datasets: [{
                data: data,
                label: label,
                borderColor: "#008000",
                fill: false,
                backgroundColor: '#008000',
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
                    fontColor: '#000000',

                }
            },
            //https://stackoverflow.com/a/37293215
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "black",
                        callback: function (value) {
                            switch (label) {
                                case 'Temperatura':
                                    return value + '°C';
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


                    },
                    scaleLabel: {
                        display: true,
                        labelString: date + '                                                                    ',
                        fontSize: 19,


                    }
                }]
            },
            responsive: true,
            //https://stackoverflow.com/a/32460154
            maintainAspectRatio: false,
            title: {
                display: true,
                text: title,
                fontColor: "#000000"

            }
        }

    });
}


function chartHumidity(label, title, time, id_div, data, type, date) {


    if (humidity !== undefined) {
        humidity.destroy();
    }

    // begin custom shape
    // begin custom shape
    humidity = new Chart(document.getElementById(id_div), {
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
                    fontColor: '#000000',

                }
            },
            //https://stackoverflow.com/a/37293215
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "black",
                        callback: function (value) {
                            switch (label) {
                                case 'Temperatura':
                                    return value + '°C';
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


                    }, scaleLabel: {
                        display: true,
                        labelString: date + '                                                                    ',
                        fontSize: 19,


                    }
                }]
            },
            responsive: true,
            //https://stackoverflow.com/a/32460154
            maintainAspectRatio: false,
            title: {
                display: true,
                text: title,
                fontColor: "#000000"

            }
        }

    });
}


function chartTemp(label, title, time, id_div, data, type, date) {


    if (temperature !== undefined) {
        temperature.destroy();
    }


    // begin custom shape
    // begin custom shape
    temperature = new Chart(document.getElementById(id_div), {
        type: type,
        data: {
            labels: time,
            datasets: [{
                data: data,
                label: label,
                borderColor: '#FF0000',
                fill: false,
                backgroundColor: '#FF0000',
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
                    fontColor: '#000000',

                },
            },
            //https://stackoverflow.com/a/37293215
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "black",
                        callback: function (value) {
                            switch (label) {
                                case 'Temperatura':
                                    return value + '°C';
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


                    },
                    scaleLabel: {
                        display: true,
                        labelString: date + '                                                                    ',
                        fontSize: 19,


                    }
                }]
            },
            responsive: true,
            //https://stackoverflow.com/a/32460154
            maintainAspectRatio: false,
            title: {
                display: true,
                text: title,
                fontColor: "#000000"

            }
        },

    });


}


function pollutionChart(pm10, pm25, time, date) {
    let canvas = document.getElementById('myChartPollution');

    let context = canvas.getContext('2d');

    context.clearRect(0, 0, canvas.width, canvas.height);


    new Chart(document.getElementById('myChartPollution'), {
        type: 'bar',
        data: {
            labels: time,
            datasets: [{
                data: pm10,
                label: 'PM10',
                borderColor: "#A9A9A9",
                backgroundColor: "#A9A9A9",
                fill: false
            }, {
                data: pm25,
                label: 'PM25',
                borderColor: "#696969",
                backgroundColor: "#696969   ",
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
                    fontColor: '#000000',

                }
            },
            //https://stackoverflow.com/a/37293215
            scales: {
                yAxes: [{
                    ticks: {
                        fontColor: "black",
                        callback: function (value, index, values) {

                            return value + ' µg/m3';


                        }

                    }
                }],
                xAxes: [{
                    ticks: {
                        fontColor: "black",


                    },
                    scaleLabel: {
                        display: true,
                        labelString: date + '                                                                    ',
                        fontSize: 19,


                    }
                }]
            },
            responsive: true,
            //https://stackoverflow.com/a/32460154
            maintainAspectRatio: false,
            title: {
                display: true,
                text: 'Wykres zanieczyszczenia',
                fontColor: "#000000"
            }
        }

    });
}