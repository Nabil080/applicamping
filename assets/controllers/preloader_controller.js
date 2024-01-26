import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    connect() {
        window.addEventListener("load", this.element.remove());
    }

    show() {
        this.element.innerHTML = this.html
        this.element.classList.remove('hidden')
    }

    hide() {
        this.element.innerHTML = ""
        this.element.classList.add('hidden')
    }

    get html() {
        return `
            <div {{ stimulus_controller('preloader') }} class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white">
                <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-primary border-t-transparent"></div>
            </div>
        `
    }

}
