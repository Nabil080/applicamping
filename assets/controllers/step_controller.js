import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["previous", "next", "progress"];

    previous() {
        if (this.currentStep == 0) return

        this.currentStep--
        this.checkProgress()
    }

    next() {
        this.checkStep(this.currentStep)
        if (!(this.steps[this.currentStep].classList.contains('valid'))) return

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
        this.currentStep = 3;
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
            emplacement: {
                numéro: "",
                tags: []
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
        let step
        let progress
        switch (number) {
            case 0:
                step = this.steps[0]
                this.reservation.durée.début = strToDate(step.querySelector('input[name="start"]').value)
                this.reservation.durée.fin = strToDate(step.querySelector('input[name="end"]').value)
                this.reservation.durée.nuits = parseInt((this.reservation.durée.fin - this.reservation.durée.début) / (1000 * 60 * 60 * 24), 10) - 1;

                this.reservation.nombre.adultes = parseInt(step.querySelector('input#adult-counter').value)
                this.reservation.nombre.enfants = parseInt(step.querySelector('input#child-counter').value)

                if (this.reservation.durée.début instanceof Date
                    && this.reservation.durée.fin instanceof Date
                    && this.reservation.durée.nuits > 0
                    && Number.isInteger(this.reservation.nombre.adultes)
                    && Number.isInteger(this.reservation.nombre.enfants)) {
                    progress = this.stepsProgress[0]
                    step.classList.add('valid')

                    progress.querySelector('p.details').innerText = `
                        ${this.reservation.durée.nuits} nuits 
                        Du ${this.reservation.durée.début.toLocaleString().split(' ')[0]} 
                        Au ${this.reservation.durée.fin.toLocaleString().split(' ')[0]} 
                        ${this.reservation.nombre.adultes} adultes 
                        ${this.reservation.nombre.enfants} enfants
                        `
                }
                break;
            case 1:
                step = this.steps[1]

                if (this.reservation.type.nom != ""
                    && this.reservation.type.prix != ""
                    && this.reservation.type.taille != "") {
                    progress = this.stepsProgress[1]
                    step.classList.add('valid')

                    progress.querySelector('p.details').innerText = `
                        ${this.reservation.type.nom}
                        ${this.reservation.type.prix}
                        ${this.reservation.type.taille}
                        `
                    this.chooseEmplacement()
                }

                break;
            case 2:
                step = this.steps[2]
                if (this.reservation.emplacement.numéro != "") {
                    step.classList.add('valid')
                    progress = this.stepsProgress[2]

                    progress.querySelector('p.details').innerText = `
                    Emplacement ${this.reservation.emplacement.numéro}
                    ${this.reservation.emplacement.tags.join(', ')}
                    `
                }
                break;
            case 3:
                step = this.steps[3]
                step.classList.add('valid')
                progress = this.stepsProgress[3]

                this.checkOptions()

                console.log(this.reservation.options);

                // progress.querySelector('p.details').innerText = `
                //         ${this.reservation.options.join(', ')}
                //     `
                break;
            default:
                return false
        }
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
        this.stepsProgress[this.currentStep].classList.add('done')
        // ! update boutons navigation
        if (this.currentStep === 0) {
            this.previousTarget.setAttribute('disabled', true)
        } else {
            this.previousTarget.removeAttribute('disabled')
        }
        if (this.steps[this.currentStep].classList.contains('valid') || this.currentStep == 3) {
            this.nextTarget.removeAttribute('disabled')
        } else {
            this.nextTarget.setAttribute('disabled', true)
        }

        console.log(this.reservation);
    }



    setActiveCard(e) {
        this.cards.forEach(card => card.classList.remove('active'))
        e.target.classList.add('active')

        this.reservation.type.nom = e.target.querySelector('h5').innerText
        this.reservation.type.taille = e.target.querySelector('span.size').innerText
        this.reservation.type.prix = e.target.querySelector('div.price').innerText
        this.nextTarget.removeAttribute('disabled')

        this.steps[2].querySelector('h2 span').innerText = this.reservation.type.nom
    }

    chooseEmplacement() {
        let step = this.steps[2]
        let tags = Array.from(step.querySelectorAll('.select2-selection__choice')).map(item => item.title);


        // fetch l'emplacement du type avec les tags spécifiés depuis la bdd
        let fetch = new Object
        fetch['numéro'] = Math.random()
        fetch['tags'] = tags


        // attribue et affiche l'emplacement recup
        this.reservation.emplacement.numéro = fetch['numéro']
        this.reservation.emplacement.tags = fetch['tags']
        console.log(fetch);

        step.querySelector('span.emplacement-number').innerText = this.reservation.emplacement.numéro
        step.querySelector('span.emplacement-tags').innerText = this.reservation.emplacement.tags.join(', ')

        // go next
        this.nextTarget.removeAttribute('disabled')
        step.classList.add('valid')
    }

    checkOptions() {
        let step = this.steps[3]
        let options = []

        step.querySelectorAll('article.option').forEach(option => {
            let input = option.querySelector('input');
            console.log(option.dataset);
            if (input.type == "checkbox" && input.checked || input.type == "number" && input.value > 0) {
                options.push({
                    nom: input.name,
                    montant: parseInt(option.dataset.price),
                    parJour: (option.dataset.perNight == "true"),
                    parPersonne: (option.dataset.perPerson  == "true")
                })
            }
        })

        this.reservation.options = options
    }

}


function strToDate(str) {

    var dateComponents = str.match(/(\w+) (\d+) (\S+) (\d+)/);

    var jour = parseInt(dateComponents[2], 10);
    var moisStr = dateComponents[3];
    var annee = parseInt(dateComponents[4], 10);

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