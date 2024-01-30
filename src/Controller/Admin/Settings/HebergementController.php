<?php

namespace App\Controller\Admin\Settings;

use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/settings/hebergements', name: 'app_admin_settings')]
class HebergementController extends AbstractController
{
    private function getPath($file): string
    {
        return sprintf('admin/settings/hebergements/%s.html.twig', $file);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, SluggerInterface $slugger, HebergementRepository $hebergementRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(HebergementType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $hebergement = $form->getData();
            $image = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $image->move(
                        $this->getParameter('hebergements_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imagename' property to store the PDF file name
                // instead of its contents
                $hebergement->setImage($newFilename);
            }

            // ... persist the $product variable or any other work

            $entityManagerInterface->persist($hebergement);
            $entityManagerInterface->flush();

            $message = "Un hébérgement (ID ". $hebergement->getId() .") a été crée";
            $context = "hebergement";
            $type = "creation";
            $logService->write($message, $context, $type);

            // return $this->redirectToRoute('app_admin_settings');
        }

        return $this->render($this->getPath('create'), ["form" => $form]);
    }
}
