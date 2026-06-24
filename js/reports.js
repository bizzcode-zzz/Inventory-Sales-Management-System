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