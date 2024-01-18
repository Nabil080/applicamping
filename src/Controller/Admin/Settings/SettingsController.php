<?php

namespace App\Controller\Admin\Settings;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/settings', name: 'app_admin_settings')]
class SettingsController extends AbstractController
{
    private function getPath($file): string
    {
        return sprintf('admin/settings/%s.html.twig', $file);
    }

    #[Route('/', name: '')]
    public function index(): Response
    {
        return $this->render($this->getPath('index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/logs', name: '_logs')]
    public function logs(): Response
    {
        return $this->render($this->getPath('logs/index'), [
            'controller_name' => 'AdminController',
        ]);
    }
}
