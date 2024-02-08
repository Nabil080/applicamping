<?php

namespace App\Form\Flow;

use App\Form\ReservationType;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use MyCompany\MyBundle\Form\CreateVehicleForm;

class ReservationFlow extends FormFlow {

	protected function loadStepsConfig() {
		return [
			[
				'label' => 'Séjour',
				'form_type' => ReservationType::class,
			],
			[
				'label' => 'Hébérgement',
				'form_type' => ReservationType::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData();
				},
			],
			[
				'label' => 'Paiement',
			],
		];
	}

}