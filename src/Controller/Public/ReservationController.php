<?php

namespace App\Controller\Public;

use App\Service\ReservationService;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// #[Route('/reservation', name: 'app_reservation')]
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
    
    #[Route('/reservation/hebergements', name: 'reservation_show')]
    public function getHebergements(Request $request, ReservationService $reservationService)
    {
        $displayHebergements = $reservationService->getHebergementsByRequest($request);
        
        dd($displayHebergements);

        return $this->json("Ca marche", 200);
    }
    
}
