import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["previous", "next", "progress"];

    previous() {
        if (this.currentStep == 0) return

        this.currentStep--
        this.checkProgress()
    }

    next() {
        if (this.checkStep(this.currentStep) == false) return

        this.currentStep++
        this.checkProgress()
    }

    goToNumber(event) {
        if (event.target.classList.contains('done')) this.currentStep = parseInt(event.target.dataset.number)
        this.checkProgress()
    }

    connect() {
        this.setupVariables()

        this.setupClickables()

        this.checkProgress()

    }

    setupVariables() {
        // général
        this.currentStep = 0;
        this.steps = []
        this.stepsProgress = []
        this.element.querySelectorAll('[data-step]').forEach((step, index) => {
            this.steps[index] = step
        })
        this.progressTarget.querySelectorAll('.progress-item').forEach((stepProgress, index) => {
            this.stepsProgress[index] = stepProgress
        })

        this.reservation = {
            durée: {
                début: "",
                fin: "",
                nuits: "",
            },
            nombre: {
                adultes: "",
                enfants: "",
            },
            type: {
                nom: "",
                prix: "",
                taille: "",
            },
            options: [
                {
                    nom: "",
                    montant: "",
                    parJour: "",
                    parPersonne: "",
                }
            ],
            client: {
                nom: "",
                prenom: "",
                email: "",
                telephone: "",
                adresse: "",
                ville: "",
                codePostal: "",
            },
            total: 0
        }

        this.cards = this.element.querySelectorAll('.hebergement-card')
    }

    checkStep(number) {
        switch (number) {
            case 0:
                this.reservation.durée.début = strToDate(this.steps[0].querySelector('input[name="start"]').value)
                this.reservation.durée.fin = strToDate(this.steps[0].querySelector('input[name="end"]').value)
                this.reservation.durée.nuits = parseInt((this.reservation.durée.fin - this.reservation.durée.début) / (1000 * 60 * 60 * 24), 10) - 1;

                if(this.reservation.durée.début instanceof Date && this.reservation.durée.fin instanceof Date && this.reservation.durée.nuits > 0) return true
                break;
            default:
                break;
        }

        return false
    }


    setupClickables() {
        this.previousTarget.dataset.action = "step#previous";
        this.nextTarget.dataset.action = "step#next";
        this.stepsProgress.forEach((progress, index) => {
            progress.dataset.action = "click->step#goToNumber"
            progress.dataset.number = index
        })
        this.cards.forEach(card => card.dataset.action = "click->step#setActiveCard")
    }

    checkProgress() {
        // ! update shown step
        this.steps.forEach(step => step.classList.add('hidden'))
        this.steps[this.currentStep].classList.remove('hidden')

        // ! update progress bar
        this.stepsProgress.forEach((progress, index) => {
            if (index <= this.currentStep) progress.classList.add('done')
        })
        // ! update boutons navigation
        if (this.currentStep === 0) this.previousTarget.setAttribute('disabled', true)
        else this.previousTarget.removeAttribute('disabled')

        if (this.currentStep === this.steps.length - 1) this.nextTarget.setAttribute('disabled', true)
        else this.nextTarget.removeAttribute('disabled')

        console.log(this.reservation);
    }



    setActiveCard(e) {
        console.log(e)
        this.cards.forEach(card => card.classList.remove('active'))
        e.target.classList.add('active')
    }

    // Remplir les infos preview
    // Stocker les infos et check la progreSion


}


function strToDate(str) {

    var dateComponents = str.match(/(\w+) (\d+) (\S+) (\d+)/);

    var jour = parseInt(dateComponents[2], 10);
    var moisStr = dateComponents[3];
    var annee = parseInt(dateComponents[4], 10);

    // Liste des mois en français
    var mois = {
        "janvier": 0,
        "février": 1,
        "mars": 2,
        "avril": 3,
        "mai": 4,
        "juin": 5,
        "juillet": 6,
        "août": 7,
        "septembre": 8,
        "octobre": 9,
        "novembre": 10,
        "décembre": 11
    };

    return new Date(annee, mois[moisStr], jour);

}