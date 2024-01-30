import { Controller } from '@hotwired/stimulus';
import { Modal } from 'flowbite';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {

    connect() {

    }


    test() {
        console.log('hi')
    }
}
