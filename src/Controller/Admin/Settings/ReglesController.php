<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Saison;
use App\Entity\Periode;
use App\Entity\RegleSejour;
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

    #[Route('/sejour/{type}/create', name: '_sejour_create')]
    public function sejourCreate(string $type, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RegleSejourType::class, null, [
            'type' => $type,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regle = $form->getData();

            $entityManagerInterface->persist($regle);
            $entityManagerInterface->flush();

            $logService->write($regle, "create");

            return $this->redirectToRoute('app_admin_settings_regles');
        }


        return $this->render("layout/form.html.twig", [
            "title" => 'règle ' . ($type === 'checkin' ? 'd\'arrivé' : 'de départ'),
            "form" => $form
        ]);
    }

    #[Route('/sejour/{type}/update/{id}', name: '_sejour_update')]
    public function sejourUpdate(string $type, RegleSejour $regleSejour, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RegleSejourType::class, $regleSejour, [
            'type' => $type,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regle = $form->getData();

            $entityManagerInterface->persist($regle);
            $entityManagerInterface->flush();

            $logService->write($regle, "update");

            return $this->redirectToRoute('app_admin_settings_regles');
        }


        return $this->render("layout/form.html.twig", [
            "title" => 'règle ' . ($type === 'checkin' ? 'd\'arrivé' : 'de départ'),
            "form" => $form,
            "create" => false
        ]);
    }
}
