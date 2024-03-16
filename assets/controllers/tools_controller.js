import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        input: String,
        selector: String,
    }

    showPassword(){
        let input = document.querySelector(this.inputValue)

        input.type = input.type === 'text' ? 'password' : 'text' ;
    }

    print() {
        console.log(this.selectorValue)
        print()

    }
}
