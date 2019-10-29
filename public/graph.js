window.onload = function () {


    const temp = Routing.generate('chart', /* your params */);

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
    let pressure = [];
    Object.keys(data).forEach(function (k) {
        let dt = data[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time.push(dt.slice(0, dt.lastIndexOf(".")));
        temp.push(data[k].temperature);
        pressure.push(data[k].pressure);
    });
    // console.log(data);
    createChart("Ciśnienie", "Wykres ciśnienia", time, "myChartPressure", pressure);
    //createChart("Temperatura", "Wykres temperatury", time, "myChartTemp", temp);

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
                text: 'Wykres ciśnienia'
            }
        }

    });
}

