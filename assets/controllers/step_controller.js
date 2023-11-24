import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["previous", "next", "progress"];

    connect() {
        this.currentStep = 0;
        this.steps = []
        this.stepsProgress = []

        this.element.querySelectorAll('[step]').forEach((step, index) => {
            this.steps[index] = step
        })

        this.progressTarget.querySelectorAll('.progress-item').forEach((stepProgress, index) => {
            this.stepsProgress[index] = stepProgress
        })

        this.checkProgress()

        // console.log(this.previousTarget.attributes.disabled)
    }

    checkProgress() {

        // ! update progress bar
        this.stepsProgress.forEach((progress, index) => {
            if(index <= this.currentStep) progress.classList.add('done')
            else progress.classList.remove('done')
        })
        // ! update boutons navigation
        if(this.currentStep === 0) this.previousTarget.setAttribute('disabled', true)
        else this.previousTarget.removeAttribute('disabled')

        if(this.currentStep === this.steps.length - 1) this.nextTarget.setAttribute('disabled', true)
        else this.nextTarget.removeAttribute('disabled')

        console.log(this.currentStep === this.steps.length - 1)
        





    }
}
