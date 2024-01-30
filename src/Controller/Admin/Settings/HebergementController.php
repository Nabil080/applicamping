<?php

namespace App\Controller\Admin\Settings;

use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(Request $request, HebergementRepository $hebergementRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(HebergementType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());


dd();
            $hebergement = $form->getData();

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
