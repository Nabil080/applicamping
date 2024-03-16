import { Controller } from '@hotwired/stimulus';


export default class extends Controller {
    static targets = ["previous", "next", "progress"];

    connect() {
        this.setupVariables()

        this.setupClickables()

        this.checkProgress()

    }

    previous() {
        if (this.currentStep == 0) return

        this.currentStep--
        this.checkProgress()
    }

    async next() {
        await this.checkStep(this.currentStep)
        if (!(this.steps[this.currentStep].classList.contains('valid'))) return

        this.currentStep++
        this.checkProgress()
    }


    async checkStep(number) {
        switch (number) {
            case 0:
                this.handleFirstStep()
                break;

            default:
                break;
        }
    }


    async handleFirstStep() {
        let step
        let progress
        [step, progress] = [this.steps[0], this.stepsProgress[0]]
        let cardsString = ''

        // ? Ajoute les informations à la réservation
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

        // ? Récupère les données par rapport au séjour et affiche les cards hébergement
        await fetch(`reservation/hebergements?start=${this.reservation.durée.début.str}&end=${this.reservation.durée.fin.str}&adult=${this.reservation.nombre.adultes}&child=${this.reservation.nombre.enfants}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(displayHebergement => {
                    cardsString += this.getHebergementCardHtml(displayHebergement)
                });
            })

        document.querySelector('#displayHebergements').innerHTML = cardsString
    }



    getHebergementCardHtml(displayHebergement) {
        let h = displayHebergement.hebergement
        let e = displayHebergement.emplacements
        console.log(displayHebergement)
        return `
        <article data-name="Camping-car" data-price="{{9 + i}}00" data-min-size="2" data-max-size="3" class="swiper-slide hebergement-card group max-w-sm border-stroke border rounded-lg shadow cursor-pointer relative [&_*]:pointer-events-none hover:active">
		<div class="overlay bg-main-500 opacity-0"></div>
		<img class="rounded-t-lg" src="uploads/hebergements/${h.image}" alt=""/>
		<div class="p-5 relative">
			<h5 class="mb-2 text-2xl font-bold tracking-tight text-black">${h.nom}</h5>
			<p class="mb-3">
				<span class="size">${h.minimum}/${h.maximum} personnes</span>
				<br>
				<span class="amount">${e.Libres?.length}/${e.Total} emplacements</span>
			</p>
			<div class="price absolute  right-4 bottom-4 ">${displayHebergement}€/nuit</div>
		</div>
	</article>
        `

        
    }


























    goToNumber(event) {
        // if (event.target.classList.contains('done')) this.currentStep = parseInt(event.target.dataset.number)
        this.checkProgress()
    }

    setupVariables() {
        // général
        this.currentStep = 0
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
    }

    setupClickables() {
        this.previousTarget.dataset.action = "step#previous";
        this.nextTarget.dataset.action = "step#next";
        this.stepsProgress.forEach((progress, index) => {
            progress.dataset.action = "click->step#goToNumber"
            progress.dataset.number = index
        })
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

const getCouponRow = (nom, total) => `
    <div id="coupon" class="grid grid-cols-3  border-stroke dark:border-strokedark sm:grid-cols-4">
        <div class="flex items-center gap-3 p-2.5 xl:p-5">
            <p class="hidden font-medium text-black dark:text-white sm:block">
                Coupon de réduction
            </p>
        </div>
        <div class="flex items-center justify-center p-2.5 xl:p-5">
            <p class="font-medium text-black dark:text-white">
                <input type="text" class="border ml-2 px-2 py-[0.5rem] w-[12ch] h-full" placeholder="REDUC50">
                <button class="button" data-action='step#checkCoupon'>Tester</button>
            </p>

        </div>

        <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
            <p id="coupon-div" class="font-medium text-black dark:text-white">${nom}</p>
        </div>

        <div class="hidden items-center justify-center p-2.5 sm:flex xl:p-5">
            <p id="id=" coupon-montant"" class="font-medium text-black dark:text-white">${total}€</p>
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
