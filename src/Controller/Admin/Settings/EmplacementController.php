<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Emplacement;
use App\Form\EmplacementType;
use App\Repository\EmplacementRepository;
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

#[Route('/admin/settings/emplacements', name: 'app_admin_settings_emplacements')]
class EmplacementController extends AbstractController
{
    private string $title = "Emplacements";

    private function getPath($file): string
    {
        return sprintf('admin/settings/emplacements/%s.html.twig', $file);
    }

    #[Route('', name: '')]
    public function emplacements(EmplacementRepository $emplacementRepository): Response
    {
        $emplacements = $emplacementRepository->findBy([], ["id" => "desc"]);
        foreach($emplacements as $emplacement) $emplacement->getTags()->getValues();

        return $this->render($this->getPath('index'), [
            "title" => $this->title,
            'emplacements' => $emplacements
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, UploadService $uploadService, EmplacementRepository $hebergementRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(EmplacementType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $emplacement = $form->getData();

            $entityManagerInterface->persist($emplacement);
            $entityManagerInterface->flush();

            $logService->write($emplacement, "create");


            return $this->redirectToRoute('app_admin_settings_emplacements');
        }

        return $this->render("layout/form.html.twig", [
            "title" => "Emplacements",
            "form" => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(Emplacement $emplacement, Request $request, UploadService $uploadService, EmplacementRepository $hebergementRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(EmplacementType::class, $emplacement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $emplacement = $form->getData();

            $entityManagerInterface->persist($emplacement);
            $entityManagerInterface->flush();

            $logService->write($emplacement, "update&");


            return $this->redirectToRoute('app_admin_settings_emplacements');
        }

        return $this->render("layout/form.html.twig", [
            "title" => "Emplacements",
            "form" => $form,
            "create" => false
        ]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Emplacement $emplacement, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($emplacement, "delete");

        $entityManagerInterface->remove($emplacement);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_emplacements');
    }
}
