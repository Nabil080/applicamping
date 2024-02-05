<?php

namespace App\Controller\Admin;

use App\Repository\EmplacementRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'app_admin')]
class AdminController extends AbstractController
{
    private function getPath($file): string
    {
        return sprintf('admin/%s.html.twig', $file);
    }

    #[Route('', name: '')]
    public function index(ReservationRepository $reservationRepository, UserRepository $userRepository, EmplacementRepository $emplacementRepository): Response
    {

        // TODO
        $cardInfo = [
            // $paiements = "paiementRepository->getTotal('date' = 'ajourdhui')",
            // $reservations = $reservationRepository->count([]),
            // $utilisateurs = $userRepository->count([]),
        ];

        $emplacements = [
            // $libres = $emplacementRepository->getAvailable(),
            // $occupes = $emplacementRepository->getOccupied(),
        ];

        $check = [
            // "in" => $reservationRepository->getCheckIns(),
            // "out" => $reservationRepository->getCheckOuts(),
        ];

        return $this->render($this->getPath('index'), [
            'cards' => $cardInfo,
            'emplacements' => $emplacements,
            'check' => $check,
        ]);
    }

    #[Route('/reservations', name: '_reservations')]
    public function reservations(): Response
    {
        return $this->render($this->getPath('reservations/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/paiements', name: '_paiements')]
    public function paiements(): Response
    {
        return $this->render($this->getPath('paiements/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/create', name: '_create_redirect')]
    public function createRedirect(Request $rq): Response
    {
        return $this->redirect($rq->headers->get('referer') . "/create");
    }

    #[Route('/update/{id}', name: '_update_redirect')]
    public function updateRedirect(int $id, Request $rq): Response
    {
        return $this->redirect($rq->headers->get('referer') . "/update/" . $id);
    }

    #[Route('/delete/{id}', name: '_delete_redirect')]
    public function deleteRedirect(int $id, Request $rq): Response
    {
        return $this->redirect($rq->headers->get('referer') . "/delete/" . $id);
    }
}
