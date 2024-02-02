import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    // ...
    connect() {
        const $element = window.$(this.element);
        const options = {
            closeOnSelect: false,
            language: 'fr',
            selectionCssClass: '',
            allowClear: true
        }

            $element.select2(options);
    }

    disconnect() {
        const $element = window.$(this.element);

        $element.select2('destroy');

        $element.removeData('select2');
    }
    
}
