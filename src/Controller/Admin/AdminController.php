<?php

namespace App\Controller\Admin;

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

    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render($this->getPath('index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/clients', name: '_clients')]
    public function clients(): Response
    {
        return $this->render($this->getPath('clients/index'), [
            'controller_name' => 'AdminController',
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
}
