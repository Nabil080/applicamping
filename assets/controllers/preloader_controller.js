import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        this.hide();
        // this.setupTurboEventListeners()
    }

    setupTurboEventListeners() {
        document.addEventListener('turbo:before-fetch-response', () => this.show());

        document.addEventListener('turbo:load', () => this.hide());
    }

    show() {
        this.element.classList.remove('hidden')
    }

    hide() {
        this.element.classList.add('hidden')
    }
    

}

