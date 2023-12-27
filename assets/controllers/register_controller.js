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

    goToNextStep(e) {
        e.preventDefault()

        let isNotLastStep = this.currentStepValue < this.steps.length - 1
        let hasNoError = this.checkIfValid(this.currentStepValue)

        console.log(isNotLastStep)
        if (isNotLastStep && hasNoError) {
            this.currentStepValue++
            this.showStep(this.currentStepValue)
        }

    }

    checkIfValid(stepNumber) {
        return true
    }
}
