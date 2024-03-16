import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {

    }

    select(e) {
        console.log(this.element.dataset.id)
        
        document.querySelector(`select`).value = this.element.dataset.id
    }

}
