// fontawesome
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.css";
import "./styles/date_picker.css";


// stimulus
import "./bootstrap.js";

// flowbite
import "flowbite";
import "flowbite-datepicker";

// tailamdin
import "jsvectormap/dist/css/jsvectormap.css"
import "flatpickr/dist/flatpickr.min.css"

import Alpine from "alpinejs";
import persist from '@alpinejs/persist'


Alpine.plugin(persist)
window.Alpine = Alpine;
Alpine.start();

