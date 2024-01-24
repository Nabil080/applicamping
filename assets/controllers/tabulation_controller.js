import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        selector: String,
    }

    connect() {
        this.buttons = this.element.querySelectorAll('button')
        this.tabs = document.querySelectorAll(this.selectorValue)

        this.show(0)

        this.buttons.forEach((button, index) => button.addEventListener('click', e => this.show(index)))
    }

    show(index) {
        this.resetAll()

        this.tabs[index].classList.remove('hidden')
        this.buttons[index].classList.add('active')
    }

    resetAll() {
        this.tabs.forEach(tab => tab.classList.add('hidden'))
        this.buttons.forEach(button => button.classList.remove('active'))
    }



}
