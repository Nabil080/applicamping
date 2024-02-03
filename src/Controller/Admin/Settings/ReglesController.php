<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Saison;
use App\Entity\Periode;
use App\Entity\RegleDuree;
use App\Entity\RegleSejour;
use App\Form\PeriodeType;
use App\Form\RegleDureeType;
use App\Form\RegleReservationType;
use App\Form\RegleSejourType;
use App\Form\SaisonType;
use App\Repository\RegleDureeRepository;
use App\Repository\RegleReservationRepository;
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
    public function regles(RegleSejourRepository $regleSejourRepository, RegleDureeRepository $regleDureeRepository, RegleReservationRepository $regleReservationRepository): Response
    {
        $checkIn = $regleSejourRepository->getCheckIns();
        foreach ($checkIn as $rule) $rule->getHebergements()->getValues();
        foreach ($checkIn as $rule) $rule->getSaisons()->getValues();

        $checkOut = $regleSejourRepository->getCheckOuts();
        foreach ($checkOut as $rule) $rule->getHebergements()->getValues();
        foreach ($checkOut as $rule) $rule->getSaisons()->getValues();

        $minStay = $regleDureeRepository->getMinStay();
        foreach ($minStay as $rule) $rule->getHebergements()->getValues();
        foreach ($minStay as $rule) $rule->getSaisons()->getValues();

        $maxStay = $regleDureeRepository->getMaxStay();
        foreach ($maxStay as $rule) $rule->getHebergements()->getValues();
        foreach ($maxStay as $rule) $rule->getSaisons()->getValues();

        $reservationRule = $regleReservationRepository->findOneBy([],["id" => "desc"]);
        $form = $this->createForm(RegleReservationType::class, $reservationRule);

        return $this->render($this->getPath('index'), [
            'controller_name' => 'AdminController',
            'title' => $this->title,
            'checkIn' => $checkIn,
            'checkOut' => $checkOut,
            'minStay' => $minStay,
            'maxStay' => $maxStay,
            'reservation' => $reservationRule,
            'form' => $form,
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

    
    #[Route('/sejour/{type}/delete/{id}', name: '_sejour_delete')]
    public function sejourDelete(RegleSejour $regleSejour, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $logService->write($regleSejour, "delete");

        $entityManagerInterface->remove($regleSejour);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_regles');
    }

    // ! regle de durée

    
    #[Route('/duree/{type}/create', name: '_duree_create')]
    public function dureeCreate(string $type, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RegleDureeType::class, null, [
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
            "title" => 'règle de durée ' . ($type === 'minstay' ? 'minimum' : 'maximum'),
            "form" => $form
        ]);
    }

    
    #[Route('/duree/{type}/update/{id}', name: '_duree_update')]
    public function dureeUpdate(string $type, RegleDuree $regleDuree, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RegledureeType::class, $regleDuree, [
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
            "title" => 'règle de durée ' . ($type === 'minstay' ? 'minimum' : 'maximum'),
            "form" => $form,
            "create" => false
        ]);
    }

        
    #[Route('/duree/{type}/delete/{id}', name: '_duree_delete')]
    public function dureeDelete(RegleDuree $regleDuree, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $logService->write($regleDuree, "delete");

        $entityManagerInterface->remove($regleDuree);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_regles');
    }
}
