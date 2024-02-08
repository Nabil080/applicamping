<?php

namespace App\Controller\Admin;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/reservations', name: 'app_admin_reservations')]
class ReservationsController extends AbstractController
{
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
            'title' => 'Réservations',
            'reservations' => $reservations,
        ]);
    }

    #[Route('/pointages', name: '_day')]
    public function index(ReservationRepository $reservationRepository): Response
    {
        $checkIns = $reservationRepository->getCheckIns();
        $checkOuts = $reservationRepository->getCheckOuts();
        $current = $reservationRepository->getCurrent();

        // dd($checkIns, $checkOuts, $current);

        return $this->render($this->getPath("day"), [
            'title' => 'Pointages',
            'checkIns' => $checkIns,
            'checkOuts' => $checkOuts,
            'current' => $current,
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(): Response
    {
        return $this->render('public/reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
