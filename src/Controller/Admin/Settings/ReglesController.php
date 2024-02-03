<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Saison;
use App\Entity\Periode;
use App\Form\PeriodeType;
use App\Form\RegleSejourType;
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

    #[Route('/create/{type}', name: '_create')]
    public function create(string $type, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RegleSejourType::class, null, [
            'type' => $type,
        ]);

        if ($form->isSubmitted() && $form->isValid()) {
            $regle = $form->getData();

            $entityManagerInterface->persist($regle);
            $entityManagerInterface->flush();

            $logService->write($regle, "create");

            return $this->redirectToRoute('app_admin_settings_regles');
        }

        dump('hello');

        return $this->render("layout/form.html.twig", [
            "title" => 'règle ' . ($type === 'checkin' ? 'd\'arrivé' : 'règle de départ'),
            "form" => $form
        ]);
    }
}
