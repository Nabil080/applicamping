import { Controller } from '@hotwired/stimulus';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['stepOne', 'stepTwo', 'stepThree']
    static values = {
        currentStep: { type: Number, default: 0 }
    }

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
        let hasNoError = this.checkIfValid(this.currentStepValue)

        if (hasNoError) this.goToNextStep()
    }

    goToNextStep() {
        this.currentStepValue++
        this.showStep(this.currentStepValue)
    }

    checkIfValid(stepNumber) {
        // Vérifie que c'est pas la dernière étape
        let isLastStep = validator(checkIfLastStep, [this.currentStepValue, this.steps.length])
        if (isLastStep) return false

        // Vérifie qu'il n'y a pas d'erreur utilisateur
        switch (stepNumber) {
            case 0:
                let email = this.steps[0].querySelector('input').value
                let isEmailValid = validator(checkEmail, email)

                if (isEmailValid) return this.sendValidationMail(email)


                return false
            default:
                break;
        }
        return true
    }

    sendValidationMail(email) {
        let todo = 'Envoie le mail de validation'


        return todo ?? false
    }
}

// validator
const validator = (validator, value) => validator(value)

const checkIfLastStep = value => value[0] === value[1] - 1
const checkEmail = value => (/^[^\s@]+@[^\s@]+\.[^\s@]+$/).test(value)


