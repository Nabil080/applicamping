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
        this.currentStep = 0;
        this.steps = []
        this.stepsProgress = []
        this.element.querySelectorAll('[data-step]').forEach((step, index) => {
            this.steps[index] = step
        })
        this.progressTarget.querySelectorAll('.progress-item').forEach((stepProgress, index) => {
            this.stepsProgress[index] = stepProgress
        })
        this.currentPrice = this.progressTarget.querySelector('span#current-price')

        this.reservation = {
            durée: {},
            nombre: {},
            type: {},
            emplacement: {},
            options: [],
            client: {},
            tarif: {
                sejour: {
                    adultes: 0,
                    enfants: 0,
                },
                emplacement: 0,
                options: 0,
                total: 0
            }
        }

        this.cards = this.element.querySelectorAll('.hebergement-card')
    }

    // Recupere les données par rapport au séjour
    fetchDatabase() {
        let data = {}
        // saison de la période
        data.saison = "basse saison 2023"
        // prix adulte
        data.adulte = 400
        // prix enfant
        data.enfant = 230
        // liste des hebergements avec emplacements libres
        return data
    }

    checkStep(number) {
        let step
        let progress
        switch (number) {
            case 0:
                step = this.steps[0]
                this.reservation.durée.début = {
                    date: strToDate(step.querySelector('input[name="start"]').value),
                    str: strToDate(step.querySelector('input[name="start"]').value).toLocaleString().split(' ')[0]
                }
                this.reservation.durée.fin = {
                    date: strToDate(step.querySelector('input[name="end"]').value),
                    str: strToDate(step.querySelector('input[name="end"]').value).toLocaleString().split(' ')[0]
                }
                this.reservation.durée.nuits = parseInt((this.reservation.durée.fin.date - this.reservation.durée.début.date) / (1000 * 60 * 60 * 24), 10) - 1;

                this.reservation.nombre.adultes = parseInt(step.querySelector('input#adult-counter').value)
                this.reservation.nombre.enfants = parseInt(step.querySelector('input#child-counter').value)

                if (this.reservation.durée.début.date instanceof Date
                    && this.reservation.durée.fin.date instanceof Date
                    && this.reservation.durée.nuits > 0
                    && Number.isInteger(this.reservation.nombre.adultes)
                    && Number.isInteger(this.reservation.nombre.enfants)) {
                    progress = this.stepsProgress[0]
                    step.classList.add('valid')

                    this.database = this.fetchDatabase()
                    console.log(this.database);

                    // prix
                    this.reservation.tarif.sejour.adultes = (this.database.adulte * this.reservation.nombre.adultes * this.reservation.durée.nuits)
                    this.reservation.tarif.sejour.enfants = (this.database.enfant * this.reservation.nombre.enfants * this.reservation.durée.nuits)

                    progress.querySelector('p.details').innerText = `
                        ${this.reservation.durée.nuits} nuits 
                        Du ${this.reservation.durée.début.str} 
                        Au ${this.reservation.durée.fin.str} 
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

                    // prix
                    this.reservation.tarif.emplacement = (this.reservation.type.prix * this.reservation.durée.nuits)

                    progress.querySelector('p.details').innerText = `
                        ${this.reservation.type.nom}
                        ${(this.reservation.type.prix / 100).toFixed(2)}€/nuit
                        ${this.reservation.type.taille.min} à ${this.reservation.type.taille.max} personnes
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

                // prix
                this.reservation.tarif.options = 0
                this.reservation.options.forEach(option => { this.reservation.tarif.options += option.total })

                // récap
                this.generateRecap(this.reservation)

                progress.querySelector('p.details').innerText = `
                        ${this.reservation.options.map(option => option.nom).join(', ')}
                    `
                break;
            case 4:
                step = this.steps[4]
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

        // ! update prix affiché
        this.reservation.tarif.total = (
            this.reservation.tarif.sejour.adultes
            + this.reservation.tarif.sejour.enfants
            + this.reservation.tarif.emplacement
            + this.reservation.tarif.options
        )
        this.currentPrice.innerText = (this.reservation.tarif.total / 100).toFixed(2)

        console.log(this.reservation);
    }



    setActiveCard(e) {
        this.cards.forEach(card => card.classList.remove('active'))
        e.target.classList.add('active')

        this.reservation.type.nom = e.target.dataset.name
        this.reservation.type.taille = {
            min: parseInt(e.target.dataset.minSize),
            max: parseInt(e.target.dataset.maxSize)
        }
        this.reservation.type.prix = parseInt(e.target.dataset.price)
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
        // console.log(fetch);

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
            if (input.type == "checkbox" && input.checked || input.type == "number" && input.value > 0) {
                let data = {
                    nom: input.name,
                    nombre: input.value.replace("on", 1),
                    prix: parseInt(option.dataset.price),
                    parJour: (option.dataset.perNight == "true"),
                    parPersonne: (option.dataset.perPerson == "true"),
                }

                data.total = (data.prix * data.nombre)
                if (data.parJour) data.total = data.total * (this.reservation.durée.nuits)
                if (data.parPersonne) data.total = data.total * (this.reservation.nombre.adultes + this.reservation.nombre.enfants)


                options.push(data)
            }
        })

        this.reservation.options = options
    }

    generateRecap(reservation) {
        let body = this.steps[4].querySelector('table tbody')
        let foot = this.steps[4].querySelector('table tfoot')
        let nuits = this.reservation.durée.nuits


        // ! Champs obligatoires
        // hébérgement
        body.innerHTML = `
            <tr class="bg-main-100">
					<td scope="row" class="px-12 font-semibold text-gray-900 whitespace-nowrap text-start ">
						Hébérgement
					</td>
					<td class="last:text-end"></td>
					<td class="last:text-end"></td>
				</tr>
        `
        // emplacement
        body.innerHTML += `
            <tr>
                <td scope="row" class="font-medium text-gray-900 whitespace-nowrap text-start ">
                    <li class="list-inside">${reservation.type.nom}, ${nuits} nuits du ${reservation.durée.début.str} au ${reservation.durée.fin.str}</li>
                </td>
                <td class="last:text-end">${(reservation.type.prix / 100).toFixed(2)}€ / ${nuits} nuits</td>
                <td class="last:text-end">${(reservation.tarif.emplacement / 100).toFixed(2)}€</td>
            </tr>
        `
        // adultes/enfants
        body.innerHTML += `
            <tr>
                <td scope="row" class="font-medium text-gray-900 whitespace-nowrap">
                    <li class="list-inside">${reservation.nombre.adultes} adultes </li>
                </td>
                <td class="last:text-end">${(this.database.adulte / 100).toFixed(2)}€ / ${reservation.nombre.adultes} adultes / ${nuits} nuits</td>
                <td class="last:text-end">${(reservation.tarif.sejour.adultes / 100).toFixed(2)}€</td>
            </tr>
            <tr>
                <td scope="row" class="font-medium text-gray-900 whitespace-nowrap">
                    <li class="list-inside">${reservation.nombre.enfants} enfants </li>
                </td>
                <td class="last:text-end">${(this.database.enfant / 100).toFixed(2)}€ / ${reservation.nombre.enfants} enfants / ${nuits} nuits</td>
                <td class="last:text-end">${(reservation.tarif.sejour.enfants / 100).toFixed(2)}€</td>
            </tr>
        `
        // total hebergement
        body.innerHTML += `
            <tr class=" border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Total hébérgement :
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end border-t border-black">
                    ${((reservation.tarif.emplacement + reservation.tarif.sejour.adultes + reservation.tarif.sejour.enfants) / 100).toFixed(2)}€
                </td>
            </tr>
        `
        // options
        body.innerHTML += `
            <tr class="bg-main-100">
                <td scope="row" class="font-semibold text-lg text-gray-900 whitespace-nowrap text-start ">
                    Options
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end"></td>
            </tr>
        `
        // ! Options facultatives
        this.reservation.options.forEach(option => {
            let calculString = `${(option.prix / 100).toFixed(2)}€`
            if (option.parJour || option.ParPersonne) {
                if (option.parJour) calculString += ` / ${nuits} nuits`
                if (option.parPersonne) calculString += ` / ${reservation.nombre.adultes + reservation.nombre.enfants} personnes`
            } else {
                if (option.nombre > 1) calculString += ` / ${option.nombre} ${option.nom}`
            }

            body.innerHTML += `
                <tr>
                    <td scope="row" class=" font-medium text-gray-900 whitespace-nowrap text-start ">
                        <li class="list-inside">${option.nom}</li>
                    </td>
                    <td class="last:text-end">
                        10€/5nuits
                    </td>
                    <td class="last:text-end">
                        ${(option.total / 100).toFixed(2)}€
                    </td>
                </tr>
            `
        })

        if (this.reservation.options.length == 0) {
            body.innerHTML += `
                <tr>
                <td scope="row" class=" font-medium text-gray-900 whitespace-nowrap text-start ">
                    <li class="list-inside">Aucune option</li>
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end">0€</td>
                </tr>
            `
        }

        // total options
        body.innerHTML += `
            <tr class=" border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Total options :
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end border-t border-black">
                    ${(reservation.tarif.options / 100).toFixed(2)}€
                </td>
            </tr>
        `
        // ! sous-total
        body.innerHTML += `
            <tr class=" bg-main-300 border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Sous total :
                </td>
                <td class="last:text-end">${((reservation.tarif.emplacement + reservation.tarif.sejour.adultes + reservation.tarif.sejour.enfants) / 100).toFixed(2)}€ + ${(reservation.tarif.options / 100).toFixed(2)}€</td>
                <td class="last:text-end border-t border-black">
                    ${((reservation.tarif.emplacement + reservation.tarif.sejour.adultes + reservation.tarif.sejour.enfants + reservation.tarif.options) / 100).toFixed(2)}€
                </td>
            </tr>
        `


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