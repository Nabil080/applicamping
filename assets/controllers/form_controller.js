import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    connect() {
        let inputs = Array.from(this.element.querySelectorAll('input'))
        this.inputs = {}

        inputs.forEach(input => this.inputs[input.name] = input)
        
        console.log(this.inputs)
        this.element.querySelectorAll('input').forEach(input => input.addEventListener('input', e => this.handleInput(e)))

    }

    handleInput(e) {


    }
}
