<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Hebergement;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
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

#[Route('/admin/settings/hebergements', name: 'app_admin_settings_hebergements')]
class HebergementController extends AbstractController
{
    private string $title = "Hébergements";

    private function getPath($file): string
    {
        return sprintf('admin/settings/hebergements/%s.html.twig', $file);
    }

    #[Route('', name: '')]
    public function hebergements(HebergementRepository $hebergementRepository): Response
    {
        $hebergements = $hebergementRepository->findBy([], ["id" => "desc"]);
        foreach ($hebergements as $hebergement) $hebergement->getEmplacements()->getValues();

        return $this->render($this->getPath('index'), [
            "title" => $this->title,
            'hebergements' => $hebergements,
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, UploadService $uploadService, HebergementRepository $hebergementRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(HebergementType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $hebergement = $form->getData();
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $hebergement->setImage($uploadService->upload($image, $this->getParameter('hebergements_directory')));
            }

            // ... persist the $product variable or any other work

            $entityManagerInterface->persist($hebergement);
            $entityManagerInterface->flush();

            $logService->write($hebergement, "create");

            return $this->redirectToRoute('app_admin_settings_hebergements');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form
        ]);
    }

    #[Route('/update/{id}', name: '_update')]
    public function update(Hebergement $hebergement, Request $request, UploadService $uploadService, HebergementRepository $hebergementRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $hebergement = $form->getData();
            $image = $form->get('image')->getData();

            if ($image) {
                $hebergement->setImage($uploadService->upload($image, $this->getParameter('hebergements_directory')));
            }

            $entityManagerInterface->persist($hebergement);

            $logService->write($hebergement, "update");

            return $this->redirectToRoute('app_admin_settings_hebergements');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            "create" => false
        ]);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Hebergement $hebergement, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($hebergement, "create");

        $entityManagerInterface->remove($hebergement);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_hebergements');
    }
}
