<?php

namespace App\Controller\Admin\Settings;

use App\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/settings/hebergements', name: 'app_admin_settings')]
class HebergementController extends AbstractController
{
    private function getPath($file): string
    {
        return sprintf('admin/settings/hebergements/%s.html.twig', $file);
    }

    #[Route('/create', name: '_create')]
    public function create(LogRepository $logRepository): Response
    {

        return $this->render($this->getPath('create'), []);
    }
}
