import { Controller } from '@hotwired/stimulus';
import { Modal, initFlowbite } from 'flowbite';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {

	show(e) {
		initFlowbite()

		this.createModal(e)
		this.modal.show()
	}

	hide() {
		initFlowbite()

		FlowbiteInstances.getInstance('Modal', 'delete-modal').hide()
		this.destroyModal()
	}

	createModal(e) {
		let modal = document.createElement("article")
		modal.classList.add('modal', 'hidden')
		modal.id = "delete-modal"
		modal.innerHTML = this.getHTML(e)

		document.body.append(modal);

		this.modal = new Modal(modal, { onHide: () => this.destroyModal() }, { id: "delete-modal", override: true })
	}

	destroyModal() {
		document.querySelector('#delete-modal')?.remove()
	}

	getHTML(event) {
		let data = event.currentTarget.dataset

		return `
		<div class="w-full max-w-142.5 rounded-lg bg-white px-8 py-12 text-center dark:bg-boxdark md:px-17.5 md:py-15">
		<span class="mx-auto inline-block">
		  <svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
			<rect opacity="0.1" width="60" height="60" rx="30" fill="#DC2626"></rect>
			<path d="M30 27.2498V29.9998V27.2498ZM30 35.4999H30.0134H30ZM20.6914 41H39.3086C41.3778 41 42.6704 38.7078 41.6358 36.8749L32.3272 20.3747C31.2926 18.5418 28.7074 18.5418 27.6728 20.3747L18.3642 36.8749C17.3296 38.7078 18.6222 41 20.6914 41Z" stroke="#DC2626" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"></path>
		  </svg>
		</span>
		<h3 class="mt-5.5 pb-2 text-xl font-bold text-black dark:text-white sm:text-2xl">
		  Suppression ${data.type} : ${data.name} ID°${data.id}
		</h3>
		<p class="mb-10 font-medium">
		  Vous êtes sur le point de supprimer un(e) ${data.type}, cette suppression est irréversible soyez sûr de vous.
		</p>
		<div class="-mx-3 flex flex-wrap gap-y-4">
		  <div class="w-full px-3 2xsm:w-1/2">
			<button data-controller="delete" data-action="delete#hide" class="block w-full rounded border border-stroke bg-gray p-3 text-center font-medium text-black transition hover:border-meta-1 hover:bg-meta-1 hover:text-white dark:border-strokedark dark:bg-meta-4 dark:text-white dark:hover:border-meta-1 dark:hover:bg-meta-1">
			  Cancel
			</button>
		  </div>
		  <div class="w-full px-3 2xsm:w-1/2">
			<a data-controller="delete" data-action="click->delete#hide" href=${data.path} class="block w-full rounded border border-meta-1 bg-meta-1 p-3 text-center font-medium text-white transition hover:bg-opacity-90">
			  Supprimer
			</a>
		  </div>
		</div>
	  </div>
		`
	}

}
