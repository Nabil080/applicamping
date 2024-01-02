import { Controller } from "@hotwired/stimulus";
import ApexCharts from "apexcharts";

export default class extends Controller {
    static targets = ["div"];

    connect() {
        let options = this.options

        new ApexCharts(this.element,options).render()
    }

    get options() {
        return {
            series: [80, 34, 45, 12],
            chart: {
                type: "donut",
                width: 450,
            },
            colors: ["#3C50E0", "#6577F3", "#8FD0EF", "#0FADCF"],
            labels: ["Camping-car", "Caravane", "Port", "Emplacement libre"],
            legend: {
                show: true,
                position: "bottom",
                fontSize: "16px",
                horizontalAlign: 'center',
                formatter: function (seriesName, opts) {
                    return [seriesName, "(", opts.w.globals.series[opts.seriesIndex], ")"]
                }
            },

            plotOptions: {
                pie: {
                    // size: 450,
                    donut: {
                        size: "65%",
                        background: "transparent",
                    },
                },
            },

            dataLabels: {
                enabled: true,
            },
            responsive: [
                {
                    breakpoint: 640,
                    options: {
                        chart: {
                            width: 350,
                        },
                    },
                },
            ],
        };
    }
}
