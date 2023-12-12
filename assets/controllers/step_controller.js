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
        this.currentStep = 5;
        this.steps = Array.from(this.element.querySelectorAll('[data-step]'));
        this.stepsProgress = Array.from(this.progressTarget.querySelectorAll('.progress-item'))
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
                [step, progress] = [this.steps[0], this.stepsProgress[0]]
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
                    step.classList.add('valid')

                    this.database = this.fetchDatabase()
                    // console.log(this.database);

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
                [step, progress] = [this.steps[1], this.stepsProgress[1]]

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
                [step, progress] = [this.steps[2], this.stepsProgress[2]]
                if (this.reservation.emplacement.numéro != "") {
                    step.classList.add('valid')

                    progress.querySelector('p.details').innerText = `
                    Emplacement ${this.reservation.emplacement.numéro}
                    ${this.reservation.emplacement.tags.join(', ')}
                    `
                }
                break;
            case 3:
                [step, progress] = [this.steps[3], this.stepsProgress[3]]
                step.classList.add('valid')

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
                [step, progress] = [this.steps[4], this.stepsProgress[4]]
                if(this.reservation.tarif.total > 0) step.classList.add('valid')
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
        this.previousTarget.disabled = this.currentStep === 0;
        if (this.steps[this.currentStep].classList.contains('valid') || this.currentStep == 3 || this.currentStep == 4) {
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
                <td class="last:text-end">${(reservation.type.prix / 100).toFixed(2)}€ x ${nuits} nuits</td>
                <td class="last:text-end">${(reservation.tarif.emplacement / 100).toFixed(2)}€</td>
            </tr>
        `
        // adultes/enfants
        body.innerHTML += `
            <tr>
                <td scope="row" class="font-medium text-gray-900 whitespace-nowrap">
                    <li class="list-inside">${reservation.nombre.adultes} adultes </li>
                </td>
                <td class="last:text-end">${(this.database.adulte / 100).toFixed(2)}€ x ${reservation.nombre.adultes} adultes x ${nuits} nuits</td>
                <td class="last:text-end">${(reservation.tarif.sejour.adultes / 100).toFixed(2)}€</td>
            </tr>
            <tr>
                <td scope="row" class="font-medium text-gray-900 whitespace-nowrap">
                    <li class="list-inside">${reservation.nombre.enfants} enfants </li>
                </td>
                <td class="last:text-end">${(this.database.enfant / 100).toFixed(2)}€ x ${reservation.nombre.enfants} enfants x ${nuits} nuits</td>
                <td class="last:text-end">${(reservation.tarif.sejour.enfants / 100).toFixed(2)}€</td>
            </tr>
        `
        // total hebergement
        let totalHebergement = (reservation.tarif.emplacement + reservation.tarif.sejour.adultes + reservation.tarif.sejour.enfants)
        body.innerHTML += `
            <tr class=" border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Total hébérgement :
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end border-t border-black">
                    ${(totalHebergement / 100).toFixed(2)}€
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
            // console.log(option);
            let calculString = `${(option.prix / 100).toFixed(2)}€`

            if (option.parJour == true) calculString += ` x ${nuits} nuits`
            if (option.parPersonne == true) calculString += ` x ${reservation.nombre.adultes + reservation.nombre.enfants} personnes`
            if (option.parJour == false && option.parPersonne == false && option.nombre > 1) calculString += ` x ${option.nombre} ${option.nom}`

            body.innerHTML += `
                <tr>
                    <td scope="row" class=" font-medium text-gray-900 whitespace-nowrap text-start ">
                        <li class="list-inside capitalize">${option.nom}</li>
                    </td>
                    <td class="last:text-end">
                        ${calculString}
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
        let totalOptions = reservation.tarif.options
        body.innerHTML += `
            <tr class=" border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Total options :
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end border-t border-black">
                    ${(totalOptions / 100).toFixed(2)}€
                </td>
            </tr>
        `
        // ! sous-total
        let sousTotal = totalHebergement + totalOptions
        body.innerHTML += `
            <tr class=" bg-main-300 border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Sous total :
                </td>
                <td class="last:text-end">${((reservation.tarif.emplacement + reservation.tarif.sejour.adultes + reservation.tarif.sejour.enfants) / 100).toFixed(2)}€ + ${(reservation.tarif.options / 100).toFixed(2)}€</td>
                <td class="last:text-end border-t border-black">
                    ${(sousTotal / 100).toFixed(2)}€
                </td>
            </tr>
        `
        // ! reductions
        body.innerHTML += `
            <tr class="bg-main-100">
                <td scope="row" class="font-semibold text-lg text-gray-900 whitespace-nowrap text-start ">
                    Réductions
                </td>
                <td></td>
                <td></td>
            </tr>
        `
        // remises
        // * fetch pour récupérer la remise du moment
        let remise = { nom: 'test', euro: 0, pourcentage: 0, found: false }
        let remiseText = 'Aucune remise correspondante'
        if (remise.found == true) remiseText = `${remise.nom} (${remise.pourcentage > 0 ? remise.pourcentage + '%' : remise.euro + '€'})`

        if (remise.pourcentage > 0) 'remise.euro = sousTotal * pourcentage '
        body.innerHTML += `
            <tr>
                <td scope="row" class=" font-medium text-gray-900 whitespace-nowrap ">
                    <li class="list-inside">Remise du moment</li>
                </td>
                <td class="last:text-end">${remiseText}</td>
                <td class="last:text-end">${remise.euro}€</td>
            </tr>
        `
        // code coupon
        // * fetch pour récupérer et tester les coupons
        if (this.coupon?.found == true)`${remise.nom} (${remise.pourcentage > 0 ? remise.pourcentage + '%' : remise.euro + '€'})`
        body.innerHTML += `
            <tr id="coupon">
                <td scope="row" class=" font-medium text-gray-900 whitespace-nowrap ">
                    <li class="list-inside">
                        Coupon de réduction
                        <input type="text" class="border ml-2 px-2 py-[0.5rem] w-[12ch] h-full" placeholder="REDUC50">
                        <button class="button" data-action='step#checkCoupon'>Tester</button>
                    </li>
                </td>
                <td id="coupon-div" class="last:text-end">${this.coupon ? this.coupon.nom : ''}</td>
                <td id="coupon-montant" class="last:text-end">${this.coupon ? this.coupon.euro : 0}€</td>
            </tr>
        `
        // total reduction
        let reductionTotal = remise.euro + (this.coupon ? this.coupon.euro : 0)
        body.innerHTML += `
            <tr class=" border-b border-black font-bold">
                <td scope="row" class=" font-bold text-gray-900 whitespace-nowrap ">
                    Total réductions :
                </td>
                <td class="last:text-end"></td>
                <td class="last:text-end border-t border-black">
                    ${(reductionTotal / 100).toFixed(2)}€
                </td>
            </tr>
        `
        // ! TOTAL TCC
        let totalTCC = sousTotal - reductionTotal
        foot.innerHTML = `
            <tr class="[&>td]:px-4 [&>td]:py-2 bg-main-300 border-b border-black font-bold">
                <td scope="row" class=" font-bold text-lg text-gray-900 whitespace-nowrap ">
                    Total TTC :
                </td>
                <td class="last:text-end">${(sousTotal / 100).toFixed(2)}€ - ${(reductionTotal / 100).toFixed(2)}€</td>
                <td class="last:text-end border-t border-black">
                    ${(totalTCC / 100).toFixed(2)}
                </td>
            </tr>
        `

    }


    checkCoupon(e) {
        // * fetch pour vérifier la validité du coupon
        const code = this.steps[4].querySelector('#coupon input').value;
        this.coupon = { nom: code, euro: 0, pourcentage: 0, found: false };
        setTimeout(() => this.generateRecap(this.reservation), 0);
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