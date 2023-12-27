import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['stepOne', 'stepTwo', 'stepThree', 'submit']
    static values = {
        currentStep: { type: Number, default: 0 }
    }

    // connect() { this.showStep(this.currentStepValue) }

    get steps() {
        return [this.stepOneTarget, this.stepTwoTarget, this.stepThreeTarget]
    }

    showStep(number) {
        this.steps.forEach((step, index) => {
            if (index === number) step.classList.remove('hidden')
            else step.classList.add('hidden')
        })

        this.submitTarget.innerText = checkIfLastStep([number, this.steps.length]) ? `S'inscrire` : `Continuer`
    }

    handleNextButton(e) {
        e.preventDefault()

        if (this.checkIfValid(this.currentStepValue)) this.goToNextStep()
    }

    goToNextStep() {
        this.currentStepValue++
        this.showStep(this.currentStepValue)
    }

    checkIfValid(stepNumber) {
        // Vérifie qu'il n'y a pas d'erreur utilisateur
        switch (stepNumber) {
            case 0:
                let emailInput = this.steps[0].querySelector('input')
                if (checkEmail(emailInput)) return this.sendValidationMail(emailInput.value)
            case 1:
                let codeInput = this.steps[1].querySelector('input#securityCode')
                return (checkCode(codeInput, this.randomCode))
            case 2:
                // todo verif front des input
                this.element.querySelector('form').submit()

                return false
            default:
                break;
        }
        return false
    }

    sendValidationMail(email) {
        // génère un token
        this.randomCode = generateRandomCode();
        this.steps[1].querySelector('input#securityCode').value = this.randomCode


        let todo = 'Envoie le mail de validation'
        this.steps[1].querySelector('input#validateEmail').value = email

        return todo ? true : false
    }
}

// validator
const validator = (validator, value) => validator(value)

const checkIfLastStep = value => value[0] === value[1] - 1

const checkEmail = input => {
    if ((/^[^\s@]+@[^\s@]+\.[^\s@]+$/).test(input.value)) {
        return true
    } else {
        input.setCustomValidity('Adresse email invalide');
        input.reportValidity();


        return false
    }
}

const checkCode = (input, code) => {
    if (input.value.toUpperCase() == code){
        return true
    }else{
        input.setCustomValidity('Code incorrect');
        input.reportValidity();
    }

}

function generateRandomCode() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    const codeLength = 5;
    let code = '';

    for (let i = 0; i < codeLength; i++) {
        const randomIndex = Math.floor(Math.random() * characters.length);
        code += characters.charAt(randomIndex);
    }

    return code;
}




