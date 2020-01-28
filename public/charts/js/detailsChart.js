//zmienna na wykres
//

let chart;


window.onload = function () {


    const temp = Routing.generate('chart');

    fetch(temp)
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            return generateChart(JSON.parse(data));
        });


};


function generateChart(data) {

    let temp = [];
    let time = [];
    Object.keys(data).forEach(function (k) {
        let dt = data[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time.push(dt.slice(0, dt.lastIndexOf(".")));
        temp.push(data[k].temperature);
    });
    createChart("Temperatura", "Wykres temperatury", time, "myChart", temp, 'bar', '#008000', true);

}


function showOptional() {


    document.getElementsByClassName('optional')[0].style.display = 'block';
}

function hideOptional() {
    document.getElementsByClassName('optional')[0].style.display = 'none';
}


function newChart() {


    let placed = document.querySelector('input[name="placed"]:checked').id;
    let type = document.querySelector('input[name="type_chart"]:checked').id;

    generateUserChart(placed, type)
}


function generateUserChart(placed, type) {
    const temp = Routing.generate('chart', {
        id_device: placed
        , type: type
    });



    fetch(temp)
        .then((res) => {
            return res.json();
        })
        .then((data) => {
            return InputChart(JSON.parse(data), type);
        });

}

function InputChart(data, type) {
    switch (type) {
        case 'pressure':
            let pres = [];
            let time = [];

            Object.keys(data).forEach(function (k) {
                let dt = data[k].createdAt.date;
                /* Możliwe ze jakos ładniej to zrobie, TODO*/
                time.push(dt.slice(0, dt.lastIndexOf(".")));
                pres.push(data[k].pressure);
            });

            return pressureChart(pres, time);
        case 'temperature':
            let temp = [];
            let time_temp = [];
            Object.keys(data).forEach(function (k) {
                let dt = data[k].createdAt.date;
                /* Możliwe ze jakos ładniej to zrobie, TODO*/
                time_temp.push(dt.slice(0, dt.lastIndexOf(".")));
                temp.push(data[k].temperature);
            });

            return temperatureChart(temp, time_temp);
        case 'humidity':
            let hum = [];
            let time_hum = [];
            Object.keys(data).forEach(function (k) {
                let dt = data[k].createdAt.date;
                /* Możliwe ze jakos ładniej to zrobie, TODO*/
                time_hum.push(dt.slice(0, dt.lastIndexOf(".")));
                hum.push(data[k].humidity);
            });
            return humidityChart(hum, time_hum);
        case 'pollution':
            let pm10 = [];
            let pm25 = [];
            let time_pollution = [];
            Object.keys(data).forEach(function (k) {
                let dt = data[k].createdAt.date;
                /* Możliwe ze jakos ładniej to zrobie, TODO*/
                time_pollution.push(dt.slice(0, dt.lastIndexOf(".")));
                pm10.push(data[k].pm10);
                pm25.push(data[k].pm25);
            });

            return pollutionChart(pm10, pm25, time_pollution);


    }
}


function pressureChart(hum, time) {
    createChart("Ciśnienie", "Wykres Ciśnienia", time, "myChart", hum, 'line', '#008000');
}

function temperatureChart(temp, time) {
    createChart("Temperatura", "Wykres temperatury", time, "myChart", temp, 'bar', '#008000', true);
}

function humidityChart(hum, time) {
    createChart("Wilgotnosć", "Wykres wilgotności", time, "myChart", hum, 'line', "#ef7d00");
}

function pollutionChart(pm10, pm25, time) {

    if (typeof chart !== "undefined") {
        chart.destroy();
    }

    chart = new Chart(document.getElementById('myChart'), {
        type: 'bar',
        data: {
            labels: time,
            datasets: [{
                data: pm10,
                label: 'PM10',
                borderColor: "#A9A9A9",
                backgroundColor: "#A9A9A9",
                pointRadius: 6,
                pointHoverRadius: 12,

                fill: false,
            }, {
                data: pm25,
                label: 'PM25',
                borderColor: "#696969",
                backgroundColor: "#696969",
                pointRadius: 6,
                pointHoverRadius: 12,

                fill: false,

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


function createChart(label, title, time, id_div, data, type, color, temperature = false) {

    if (typeof chart !== "undefined") {
        chart.destroy();
    }
    if (temperature) {

        const colorBackground = [];
        colorBackground.fill('rgb(255, 0, 0)', 0, data.length);

        color = colorBackground;
        console.log(color);
    }

    chart = new Chart(document.getElementById(id_div), {
        type: type,
        data: {
            labels: time,
            datasets: [{

                data: data,
                label: label,
                borderColor: color,
                fill: false,
                backgroundColor: color,
                pointRadius: 6,
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
                        callback: function (value, index, values) {
                            switch (label) {
                                case 'Temperatura':
                                    return value + '°';
                                case 'Ciśnienie':
                                    return value + ' hPa';
                                case 'Wilgotnosć':
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
                text: title,
                fontColor: "#000000"
            }
        }

    });

    if (temperature) {
        console.log('elo');
        let colorChangeValue = 0; //set this to whatever is the deciding color change value
        let dataset = chart.data.datasets[0];
        for (let i = 0; i < dataset.data.length; i++) {
            if (dataset.data[i] < colorChangeValue) {
                dataset.backgroundColor[i] = '#0000FF';
                dataset.borderColor[i] = '#0000FF';
            } else {
                dataset.backgroundColor[i] = 'rgb(255, 0, 0)';
                dataset.borderColor[i] = 'rgb(255, 0, 0)';
            }
        }
        chart.update();
    }
}







