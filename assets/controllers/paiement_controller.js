import { Controller } from '@hotwired/stimulus';



export default class extends Controller {
    connect() {

    }

    chooseType(e) {
        this.element.querySelectorAll('button[data-action="paiement#chooseType"]').forEach(button => button.classList.remove('button'))

        e.target.classList.add('button')
        this.type = e.target.classList.id

    }

}
