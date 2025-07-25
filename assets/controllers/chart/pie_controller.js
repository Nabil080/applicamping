import { Controller } from "@hotwired/stimulus";
import ApexCharts from "apexcharts";

export default class extends Controller {
    static targets = ["div"];
    static values = {
        series: { type: Array, default: [80, 34, 45, 12] },
        labels: { type: Array, default: ["Camping-car", "Caravane", "Port", "Emplacement libre"] }
    }

    connect() {
        // document.addEventListener('turbo:before-fetch-request', () => this.hide());
        document.addEventListener("turbo:load", this.render());  
    }

    render() {
        this.hide()
        new ApexCharts(this.divTarget, this.options).render()
    }

    hide() {
        this.divTarget.innerHTML = ""
    }

    disconnect() {
        console.log('bye to pie');
    }

    get options() {
        return {
            series: this.seriesValue,
            chart: {
                type: "donut",
                width: 450,
            },
            colors: ["#3C50E0", "#6577F3", "#8FD0EF", "#0FADCF"],
            labels: this.labelsValue.map(string => string.replace("_"," ")),
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
