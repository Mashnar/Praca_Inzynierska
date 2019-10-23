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
    console.log(data);
    let temp = [];
    let time = [];
    Object.keys(data).forEach(function (k) {
        let dt = data[k].createdAt.date;
        /* Możliwe ze jakos ładniej to zrobie, TODO*/
        time.push(dt.slice(0, dt.lastIndexOf(".")));
        temp.push(data[k].temperature);
    });


    new Chart(document.getElementById("myChart"), {
        type: 'line',
        data: {
            labels: time,
            datasets: [{
                data: temp,
                label: "Temperatura",
                borderColor: "#3e95cd",
                fill: false
            }
            ]
        },
        options: {
            responsive: true,
            title: {
                display: true,
                text: 'Wykres temperatury'
            }
        }
    });
}

