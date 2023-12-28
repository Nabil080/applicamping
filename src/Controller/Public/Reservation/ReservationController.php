<?php

namespace App\Controller\Public\Reservation;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return $this->render('public/reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/reservation/{id}', name: 'reservation_show')]
    public function reservation($id = 1): Response
    {
        return $this->render('public/reservation/reservation.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
