import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['stepOne', 'stepTwo', 'stepThree']
    static values = {
        currentStep: { type: Number, default: 1 }
    }

    connect() { this.showStep(this.currentStepValue) }

    get steps() {
        return [this.stepOneTarget, this.stepTwoTarget, this.stepThreeTarget]
    }

    showStep(number) {
        this.steps.forEach((step, index) => {
            if (index === number) step.classList.remove('hidden')
            else step.classList.add('hidden')
        })
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
        // Vérifie que c'est pas la dernière étape
        if (checkIfLastStep([this.currentStepValue, this.steps.length])) return false

        // Vérifie qu'il n'y a pas d'erreur utilisateur
        switch (stepNumber) {
            case 0:
                let emailInput = this.steps[0].querySelector('input')
                if (checkEmail(emailInput)) return this.sendValidationMail(emailInput.value)

                return false
            default:
                break;
        }
        return true
    }

    sendValidationMail(email) {
        let todo = 'Envoie le mail de validation'


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


