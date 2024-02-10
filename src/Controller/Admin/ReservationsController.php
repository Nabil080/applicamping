<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use App\Form\Flow\ReservationFlow;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use App\Service\LogService;
use App\Service\ReservationService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservations', name: 'app_admin_reservations')]
class ReservationsController extends AbstractController
{

    private string $title = "Réservations";

    private function getPath($file): string
    {
        return sprintf('admin/reservations/%s.html.twig', $file);
    }

    #[Route('', name: '')]
    public function reservations(ReservationRepository $reservationRepository): Response
    {
        $reservations = $reservationRepository->findBy([], ["id" => "desc"]);
        // foreach ($reservations as $reservation) $reservation->getEmplacements()->getValues();

        return $this->render($this->getPath('index'), [
            'title' => $this->title,
            'reservations' => $reservations,
        ]);
    }

    #[Route('/pointages', name: '_day')]
    public function index(Request $request, ReservationRepository $reservationRepository): Response
    {
        $day = new DateTime($request->get('day') ?? 'now');

        $checkIns = $reservationRepository->getCheckIns($day);
        $checkOuts = $reservationRepository->getCheckOuts($day);
        $current = $reservationRepository->getCurrent($day);

        $count = count($checkIns) + count($checkOuts) + count($current);

        // dd($checkIns, $checkOuts, $current);

        return $this->render($this->getPath("day"), [
            'title' => 'Pointages',
            'checkIns' => $checkIns,
            'checkOuts' => $checkOuts,
            'current' => $current,
            'count' => $count,
            'day' => $day,
        ]);
    }


    #[Route('/create', name: '_create')]
    public function create(Request $request, ReservationService $reservationService, LogService $logService, EntityManagerInterface $em, ReservationFlow $flow): Response
    {
        $reservation = new Reservation(); // Your form data class. Has to be an object, won't work properly with an array.

        $flow->bind($reservation);

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                if($flow->getCurrentStepNumber() > 1) $displayHebergements = $reservationService->getHebergementsByRequestOrReservation(null,$reservation);

                if($flow->getCurrentStepNumber() === 5){
                    // $recapitulatif = $reservationService->getRecap
                }





                $form = $flow->createForm();
            } else {
                // flow finished
                $em->persist($reservation);
                $em->flush();

                $flow->reset(); // remove step data from the session

                $logService->write($reservation, "create");
                return $this->redirectToRoute('app_admin_reservations'); // redirect when done
            }
        }


        return $this->render("layout/flow.html.twig", [
            "title" => $this->title,
            "form" => $form,
            'flow' => $flow,
            "redirectRoute" => 'app_admin_reservations',
            'turbo' => false,
            'displayHebergements' => $displayHebergements ?? [],
        ]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Reservation $reservation, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($reservation, "archive");

        $reservation->setStatut('Archivé');
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_reservations');
    }
}
