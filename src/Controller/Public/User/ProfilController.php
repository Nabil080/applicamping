<?php

namespace App\Controller\Public\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        if (!$this->getUser()) return $this->redirectToRoute('homepage');



        return $this->render('public/user/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }
}
