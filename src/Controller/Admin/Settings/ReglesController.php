<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Saison;
use App\Entity\Periode;
use App\Form\PeriodeType;
use App\Form\SaisonType;
use App\Repository\RegleSejourRepository;
use App\Repository\SaisonRepository;
use App\Service\LogService;
use App\Service\UploadService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/settings/regles', name: 'app_admin_settings_regles')]
class ReglesController extends AbstractController
{
    private string $title = "Règles de réservation";

    private function getPath($file): string
    {
        return sprintf('admin/settings/regles/%s.html.twig', $file);
    }


    #[Route('', name: '')]
    public function regles(RegleSejourRepository $regleSejourRepository): Response
    {
        $checkIn = $regleSejourRepository->getCheckIns();
        foreach ($checkIn as $rule) $rule->getHebergements()->getValues();
        foreach ($checkIn as $rule) $rule->getSaisons()->getValues();

        $checkOut = $regleSejourRepository->getCheckOuts();
        foreach ($checkOut as $rule) $rule->getHebergements()->getValues();
        foreach ($checkOut as $rule) $rule->getSaisons()->getValues();

        $minStay = "";

        $maxStay = "";

        $reservationRules = "";
        return $this->render($this->getPath('index'), [
            'controller_name' => 'AdminController',
            'title' => $this->title,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
        ]);
    }
}
