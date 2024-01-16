<?php

namespace App\Controller\Admin;

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

    #[Route('/pointages', name: '_day')]
    public function index(): Response
    {
        return $this->render($this->getPath("day"), [
            'controller_name' => 'ReservationsController',
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
