import { Controller } from "@hotwired/stimulus";
import DateRangePicker from "flowbite-datepicker/DateRangePicker";
import { locales } from "../../node_modules/flowbite-datepicker/js/i18n/base-locales.js";
import fr from "../../node_modules/flowbite-datepicker/js/i18n/locales/fr.js";

export default class extends Controller {
    connect() {
        this.setDates();
        this.initializeDateRangePicker();
        // this.setDefaultValues();
    }

    setDates() {
        locales.fr = fr.fr;

        let date = new Date();
        this.today = date
        this.nextWeek = new Date(
            date.getFullYear(),
            date.getMonth(),
            date.getDate() + 7
        ).toLocaleDateString("fr-FR");
        this.nextNextWeek = new Date(
            date.getFullYear(),
            date.getMonth(),
            date.getDate() + 14
        ).toLocaleDateString("fr-FR");
    }

    initializeDateRangePicker() {
        const options = {
            language: "fr",
            weekStart: 1,
            clearBtn: true,
            minDate: this.today,
        };

        const d = new DateRangePicker(this.element, options);

        d.inputs.forEach((input) => {
            input.addEventListener("changeDate", (event) => {
                this.synchronizeValues();
            });
        });
    }

    setDefaultValues() {
        this.start = this.element.querySelector('[name="start"]');
        this.end = this.element.querySelector('[name="end"]');

        if(this.start.value === "") this.start.value = this.nextWeek
        if(this.end.value === "") this.end.value = this.nextNextWeek
    }

    synchronizeValues() {
        let newStart = this.start.value;
        let newEnd = this.end.value;
        let startInputs = document.querySelectorAll('[name="start"]');
        let endInputs = document.querySelectorAll('[name="end"]');

        startInputs.forEach((input) => (input.value = newStart));
        endInputs.forEach((input) => (input.value = newEnd));
    }
}
