<?php

namespace App\Form\Flow;

use App\Form\ReservationType;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use MyCompany\MyBundle\Form\CreateVehicleForm;

class ReservationFlow extends FormFlow {
	protected $allowDynamicStepNavigation = true;

	protected function loadStepsConfig() {
		return [
			1 => [
				'label' => 'Séjour',
				'form_type' => ReservationType::class,
			],
			2 => [
				'label' => 'Hébérgement',
				'form_type' => ReservationType::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData();
				},
			],
			3 => [
				'label' => 'Emplacement',
				'form_type' => ReservationType::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData();
				},
			],
			4 => [
				'label' => 'Options supplémentaires',
				'form_type' => ReservationType::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData();
				},
			],
		];
	}

}