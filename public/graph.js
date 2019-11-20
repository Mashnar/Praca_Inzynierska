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
    createChart("Temperatura", "Wykres temperatury", time, "myChart", temp);

}


function createChart(label, title, time, id_div, data) {
    new Chart(document.getElementById(id_div), {
        type: 'line',
        data: {
            labels: time,
            datasets: [{
                data: data,
                label: label,
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
                text: title
            }
        }

    });
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
    }, true);

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
    createChart("Ciśnienie", "Wykres Ciśnienia", time, "myChart", hum);
}

function temperatureChart(temp, time) {
    createChart("Temperatura", "Wykres temperatury", time, "myChart", temp);
}

function humidityChart(hum, time) {
    createChart("Wilgotnosć", "Wykres wilgotności", time, "myChart", hum);
}

function pollutionChart(pm10, pm25, time) {
    console.log(pm10);
    console.log(pm25);
    console.log(time);
    new Chart(document.getElementById('myChart'), {
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




