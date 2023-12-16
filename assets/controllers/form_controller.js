import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    connect() {
        let inputs = Array.from(this.element.querySelectorAll('input'))
        this.inputs = {required: {}, all: {}}

        inputs.forEach(input => {
            if(input.required) {this.inputs.required[input.name] = input}
            this.inputs.all[input.name] = input
        })

        inputs.forEach(input => input.addEventListener('input', e => this.handleInput(e)))

        this.button = document.querySelector('#order-button button')
    }

    handleInput(e) {
        let error = {status:false}
        // check que les inputs sont corrects

        // check les champs requis
        for (let i = 0; i < Object.values(this.inputs.required).length; i++) {
            if(Object.values(this.inputs.required)[i].value == "") error.status = true              
        }

        if(error.status === false) this.allowSubmit()
        else this.preventSubmit()
    }

    allowSubmit() {
        this.button.removeAttribute('disabled')
    }
    preventSubmit() {
        this.button.setAttribute('disabled', true)
    }

}
