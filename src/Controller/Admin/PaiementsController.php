<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/paiements', name: 'app_admin_paiements')]
class PaiementsController extends AbstractController
{
    private function getPath($file): string
    {
        return sprintf('admin/paiements/%s.html.twig', $file);
    }

    #[Route('/jour', name: '_day')]
    public function day(): Response
    {
        return $this->render($this->getPath("day"), [
            'controller_name' => 'paiementsController',
        ]);
    }

    #[Route('/mois', name: '_month')]
    public function month(): Response
    {
        return $this->render($this->getPath("month"), [
            'controller_name' => 'paiementsController',
        ]);
    }
}
