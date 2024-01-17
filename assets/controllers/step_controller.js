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
        this.currentStep = 4
            ;
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
                if (this.reservation.tarif.total > 0) step.classList.add('valid')
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
        let recap = this.steps[4].querySelector('#recap')
        let nuits = this.reservation.durée.nuits

        // ! Champs obligatoires
        recap.innerHTML = getHeader()
        // * hébérgement
        recap.innerHTML += getCategoryRow('Hébergement')
        // emplacement
        recap.innerHTML += getRow(`Nuits ${reservation.type.nom}`, nuits, formatMoney(reservation.type.prix) + '€', formatMoney(reservation.tarif.emplacement))
        // adultes/enfants
        if (reservation.nombre.adultes != 0)
            recap.innerHTML += getRow('Adultes', reservation.nombre.adultes, formatMoney(this.database.adulte) + '€', formatMoney(reservation.tarif.sejour.adultes))
        if (reservation.nombre.enfants != 0)
            recap.innerHTML += getRow('Enfants', reservation.nombre.enfants, formatMoney(this.database.enfant) + '€', formatMoney(reservation.tarif.sejour.enfants))
        // total hebergement
        let totalHebergement = (reservation.tarif.emplacement + reservation.tarif.sejour.adultes + reservation.tarif.sejour.enfants)
        recap.innerHTML += getTotalRow('Total hébérgement', formatMoney(totalHebergement))
        // * options
        recap.innerHTML += getCategoryRow('Options')

        // ! Options facultatives
        this.reservation.options.forEach(option => {
            let calculString = `${(option.prix / 100).toFixed(2)}€`
            if (option.parJour == true) calculString += ` x ${nuits} nuits`
            if (option.parPersonne == true) calculString += ` x ${reservation.nombre.adultes + reservation.nombre.enfants} personnes`
            if (option.parJour == false && option.parPersonne == false && option.nombre > 1) calculString += ` x ${option.nombre} ${option.nom}`

            recap.innerHTML += getRow(option.nom, option.nombre, calculString, formatMoney(option.total))
        })
        if (this.reservation.options.length == 0) {
            console.log('hi')
            recap.innerHTML += getRow('Aucune option', '', '', 0)
        }
        // total options
        let totalOptions = reservation.tarif.options
        recap.innerHTML += getTotalRow('Total options', formatMoney(totalOptions))

        // ! reductions
        recap.innerHTML += getCategoryRow('Réductions')

        // remises
        // * fetch pour récupérer la remise du moment
        let remise = { nom: 'test', euro: 0, pourcentage: 0, found: false }
        let remiseText = 'Aucune remise correspondante'
        if (remise.found == true) remiseText = `${remise.nom} (${remise.pourcentage > 0 ? remise.pourcentage + '%' : remise.euro + '€'})`
        if (remise.pourcentage > 0) 'remise.euro = sousTotal * pourcentage '
        recap.innerHTML += getRow('Remise du moment', '', remiseText, '- ' + remise.euro)

        // code coupon
        // * fetch pour récupérer et tester les coupons
        if (this.coupon?.found == true)`${remise.nom} (${remise.pourcentage > 0 ? remise.pourcentage + '%' : remise.euro + '€'})`
        recap.innerHTML += getRow('Coupon de réduction', '', this.coupon ? this.coupon.nom : '', this.coupon ? '- ' + this.coupon.euro : '- 0')

        // total reduction
        let reductionTotal = remise.euro + (this.coupon ? this.coupon.euro : 0)
        recap.innerHTML += getTotalRow('Total réductions', formatMoney(reductionTotal))

        // ! TOTAL TTC
        let totalTTC = (totalHebergement + totalOptions) - reductionTotal
        recap.innerHTML += getFooterRow(formatMoney(totalTTC))

        return

        // body.innerHTML += `
        //     <tr id="coupon">
        //         <td scope="row" class=" font-medium text-gray-900 whitespace-nowrap ">
        //             <li class="list-inside">
        //                 Coupon de réduction
        //                 <input type="text" class="border ml-2 px-2 py-[0.5rem] w-[12ch] h-full" placeholder="REDUC50">
        //                 <button class="button" data-action='step#checkCoupon'>Tester</button>
        //             </li>
        //         </td>
        //         <td id="coupon-div" class="last:text-end">${this.coupon ? this.coupon.nom : ''}</td>
        //         <td id="coupon-montant" class="last:text-end">${this.coupon ? this.coupon.euro : 0}€</td>
        //     </tr>
        // `
    }


    checkCoupon(e) {
        // * fetch pour vérifier la validité du coupon
        const code = this.steps[4].querySelector('#coupon input').value;
        this.coupon = { nom: code, euro: 0, pourcentage: 0, found: false };
        setTimeout(() => this.generateRecap(this.reservation), 0);
    }


    /* 
    Créer la réservation et redirige vers sa page
    */
    validateOrder() {
        window.document.location.href = '/reservation'

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

const getHeader = () => `
    <div class="grid grid-cols-3 rounded-sm bg-gray-2 dark:bg-meta-4 sm:grid-cols-4">
        <div class="p-2.5  xl:p-5">
            <h5 class="text-sm font-medium uppercase xsm:text-base">Description</h5>
        </div>
        <div class="p-2.5 text-center xl:p-5">
            <h5 class="text-sm font-medium uppercase xsm:text-base">Quantité</h5>
        </div>
        <div class="hidden p-2.5 text-center sm:block xl:p-5">
            <h5 class="text-sm font-medium uppercase xsm:text-base">Prix unitaire</h5>
        </div>
        <div class="hidden p-2.5 text-center sm:block xl:p-5">
            <h5 class="text-sm font-medium uppercase xsm:text-base">Prix total</h5>
        </div>
    </div>
`

const getCategoryRow = name => `<div class="p-2.5 xl:p-5 bg-gray-50 border-t">${name}</div>`

const getRow = (name, amount, price, total) => `
    <div class="grid grid-cols-3  border-stroke dark:border-strokedark sm:grid-cols-4">
        <div class="flex items-center gap-3 p-2.5 xl:p-5">
            <p class="hidden font-medium text-black dark:text-white sm:block">
                ${name}
            </p>
        </div>
        <div class="flex items-center justify-center p-2.5 xl:p-5">
            <p class="font-medium text-black dark:text-white">${amount}</p>
        </div>

        <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
            <p class="font-medium text-black dark:text-white">${price}</p>
        </div>

        <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
            <p class="font-medium text-black dark:text-white">${total}€</p>
        </div>
    </div>
`

const getTotalRow = (name, total) => `
    <div class="border-t grid grid-cols-3  border-stroke dark:border-strokedark sm:grid-cols-4 bg-gray-200">
        <div class="flex items-center gap-3 p-2.5 xl:p-5">
            <p class="hidden font-medium text-black dark:text-white sm:block">
                ${name}
            </p>
        </div>
        <div class="flex items-center justify-center p-2.5 xl:p-5">
            <p class="font-medium text-black dark:text-white"></p>
        </div>

        <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
            <p class="font-medium text-black dark:text-white"></p>
        </div>

        <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5 border-t border-black">
            <p class="font-medium text-black dark:text-white">${total}€</p>
        </div>
    </div>
`

const getFooterRow = (total) => `
    <div class="grid grid-cols-3 border-t border-black  dark:border-strokedark sm:grid-cols-4 text-xl">
        <div class="flex items-center p-2.5 xl:p-5">
            <p class="font-bold dark:text-white">Total TTC</p>
        </div>
        <div class="flex items-center justify-center p-2.5 xl:p-5">
            <p class="font-bold dark:text-white"></p>
        </div>
        <div class="flex items-center justify-center p-2.5 xl:p-5">
            <p class="font-bold dark:text-white"></p>
        </div>
        <div class="flex items-center justify-center p-2.5 xl:p-5">
            <p class="font-bold dark:text-white">${total}€</p>
        </div>
    </div>
`

const formatMoney = money => (money / 100).toFixed(2)
