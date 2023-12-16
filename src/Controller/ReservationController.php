<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/', name: 'reservation_create')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/reservation/{id}', name: 'reservation_show')]
    public function reservation($id = 1): Response
    {
        return $this->render('reservation/reservation.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }
}
