<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Saison;
use App\Entity\OptionMaximum;
use App\Form\OptionMaximumType;
use App\Form\SaisonType;
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

#[Route('/admin/settings/saisons', name: 'app_admin_settings_saisons')]
class SaisonController extends AbstractController
{
    private string $title = "Saisons";

    private function getPath($file): string
    {
        return sprintf('admin/settings/saisons/%s.html.twig', $file);
    }

    #[Route('', name: '')]
    public function saisons(SaisonRepository $saisonRepository): Response
    {
        $saisons = $saisonRepository->findBy([], ["id" => "desc"]);
        foreach ($saisons as $saison) $saison->getSaisonDates()->getValues();

        return $this->render($this->getPath('index'), [
            "title" => $this->title,
            "saisons" => $saisons
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, SaisonRepository $saisonRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(SaisonType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $saison = $form->getData();

            $entityManagerInterface->persist($saison);
            $entityManagerInterface->flush();

            $logService->write($saison, "create");

            return $this->redirectToRoute('app_admin_settings_saisons');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
        ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(Saison $saison, Request $request, UploadService $uploadService, SaisonRepository $saisonRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(SaisonType::class, $saison);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $saison = $form->getData();

            $entityManagerInterface->persist($saison);
            $entityManagerInterface->flush();

            $logService->write($saison, "update");

            return $this->redirectToRoute('app_admin_settings_saisons');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form, "create" => false
        ]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Saison $saison, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($saison, "delete");

        $entityManagerInterface->remove($saison);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_saisons');
    }

    #[Route('/{id}/create', name: '_maximum_create')]
    public function maximumCreate(Saison $saison, Request $request, SaisonRepository $saisonRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(OptionMaximumType::class);
        $form->get('saison')->setData($saison);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $saisonMaximum = $form->getData();

            $entityManagerInterface->persist($saisonMaximum);
            $entityManagerInterface->flush();

            $logService->write($saisonMaximum, "create");

            return $this->redirectToRoute('app_admin_settings_saisons');
        }

        return $this->render("layout/form.html.twig", [
            "title" => "Règle d'saison supplémentaire",
            "form" => $form,
        ]);
    }

    #[Route('/maximum/update/{id}', name: '_maximum_update')]
    public function maximumUpdate(OptionMaximum $saisonMaximum, Request $request, SaisonRepository $saisonRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(OptionMaximumType::class, $saisonMaximum);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $saisonMaximum = $form->getData();

            $entityManagerInterface->persist($saisonMaximum);
            $entityManagerInterface->flush();

            $logService->write($saisonMaximum, "update");

            return $this->redirectToRoute('app_admin_settings_saisons');
        }

        return $this->render("layout/form.html.twig", [
            "title" => "Règle d'saison supplémentaire",
            "form" => $form,
            "create" => false
        ]);
    }

    #[Route('/maximum/delete/{id}', name: '_maximum_delete')]
    public function maximumDelete(OptionMaximum $saisonMaximum, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($saisonMaximum, "delete");

        $entityManagerInterface->remove($saisonMaximum);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_saisons');
    }
}
