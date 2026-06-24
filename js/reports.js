let reportRevenueChart = null;

function loadReport() {

    let startDate =
    document.getElementById(
        "startDate"
    ).value;

    let endDate =
    document.getElementById(
        "endDate"
    ).value;

    fetch(
        "fetch_report.php" +

        "?startDate=" +
        startDate +

        "&endDate=" +
        endDate
    )

    .then(res => res.text())

    .then(data => {

    document.getElementById(
        "reportResult"
    ).innerHTML = data;

    loadRevenueChart();

});

}


function exportReport() {

    let startDate =
    document.getElementById(
        "startDate"
    ).value;

    let endDate =
    document.getElementById(
        "endDate"
    ).value;

    window.location.href =

    "export_report_csv.php"

    +

    "?startDate="

    +

    startDate

    +

    "&endDate="

    +

    endDate;

}


function loadRevenueChart() {

    let startDate =
    document.getElementById(
        "startDate"
    ).value;

    let endDate =
    document.getElementById(
        "endDate"
    ).value;

    fetch(

        "fetch_report_chart.php"

        +

        "?startDate="

        +

        startDate

        +

        "&endDate="

        +

        endDate

    )

    .then(res => res.json())

    .then(data => {

        let labels = [];
        let revenues = [];

        data.forEach(item => {

            labels.push(
                item.sale_day
            );

            revenues.push(
                item.revenue
            );

        });

        const ctx =
        document
        .getElementById(
            "reportRevenueChart"
        )
        .getContext("2d");

        if (
            reportRevenueChart
        ) {

            reportRevenueChart
            .destroy();

        }

        reportRevenueChart =
        new Chart(ctx, {

            type: "line",

            data: {

                labels: labels,

                datasets: [

                {

                    label:
                    "Revenue",

                    data:
                    revenues,

                    tension:
                    0.3
                    

                }

                ]

            },

            options: {

    responsive: true,

    maintainAspectRatio: false,

    scales: {

        x: {

            ticks: {

                color: "#ffffff"

            },

            grid: {

                color:
                "rgba(255,255,255,0.2)"

            }

        },

        y: {

            ticks: {

                color: "#ffffff"

            },

            grid: {

                color:
                "rgba(255,255,255,0.2)"

            }

        }

    }

}

        });

    });

}


function exportPDF() {

    let startDate =
    document.getElementById(
        "startDate"
    ).value;

    let endDate =
    document.getElementById(
        "endDate"
    ).value;

    window.location.href =

    "export_report_pdf.php"

    +

    "?startDate="

    +

    startDate

    +

    "&endDate="

    +

    endDate;

}