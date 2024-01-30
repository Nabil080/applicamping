import { Controller } from '@hotwired/stimulus';
import { Modal } from 'flowbite';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {

    connect() {
        const options = {
            backdrop: 'static',
        };

        this.modal = new Modal(this.element, options)

        let openButtons = document.querySelectorAll(`[data-modal-open='${this.element.attributes.id.value}']`)
        openButtons.forEach(button => button.addEventListener('click', () => this.show()))

        let hideButtons = document.querySelectorAll(`[data-modal-hide='${this.element.attributes.id.value}']`)
        hideButtons.forEach(button => button.addEventListener('click', () => this.hide()))
    }

    show() { this.modal.show() }
    hide() { this.modal.hide() }

}
