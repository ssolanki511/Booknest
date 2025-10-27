const options = {
    colors: ["#1A56DB", "#FDBA8C"],
    series: [
        {
            name: "Book Sales",
            color: "#1A56DB",
            data: bookSalesData, // Use the PHP-encoded data
        },
        {
            name: "Revenue",
            color: "#FDBA8C",
            data: revenueData, // Use the PHP-encoded data
        },
    ],
    chart: {
        type: "bar",
        height: "280px",
        fontFamily: "Inter, sans-serif",
        toolbar: {
            show: false,
        },
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "80%",
            borderRadiusApplication: "end",
            borderRadius: 2,
        },
    },
    tooltip: {
        shared: true,
        intersect: false,
        style: {
            fontFamily: "Inter, sans-serif",
        },
    },
    states: {
        hover: {
            filter: {
                type: "darken",
                value: 1,
            },
        },
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: false,
    },
    xaxis: {
        floating: false,
        labels: {
            show: true,
            style: {
                fontFamily: "Inter, sans-serif",
                cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400',
            },
        },
    },
    yaxis: {
        show: true,
    },
    fill: {
        opacity: 1,
    },
};

if (document.getElementById("column-chart")) {
    const chart = new ApexCharts(document.getElementById("column-chart"), options);
    chart.render();
}