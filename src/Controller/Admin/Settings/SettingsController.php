<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Hebergement;
use App\Form\CampingType;
use App\Repository\CampingRepository;
use App\Repository\EmplacementRepository;
use App\Repository\HebergementRepository;
use App\Repository\LogRepository;
use App\Repository\UserRepository;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(HebergementRepository $hebergementRepository, Request $request, EntityManagerInterface $entityManagerInterface, LogService $logService, CampingRepository $campingRepository, LogRepository $logRepository): Response
    {
        $camping = $campingRepository->findOneBy([]);

        $campingForm = $this->createForm(CampingType::class, $camping);
        $campingForm->handleRequest($request);


        if ($campingForm->isSubmitted() && $campingForm->isValid()) {
            $camping = $campingForm->getData();

            $entityManagerInterface->persist($camping);

            $message = "Les informations du camping ont été modifiés";
            $context = "camping";
            $type = "modification";
            $logService->write($message, $context, $type);

            // return $this->redirectToRoute('app_admin_settings');
        }

        $logs = $logRepository->findBy([], ["id" => "DESC"], 10);

        $hebergements = $hebergementRepository->findAll();
        $totalEmplacements = count(array_merge(...array_map(fn ($hebergement) => $hebergement->getEmplacements()->getValues(), $hebergements)));


        $total = ["emplacements" => $totalEmplacements, "hebergements" => count($hebergements)];

        return $this->render($this->getPath('index'), [
            'camping' => $camping,
            'campingForm' => $campingForm,
            'logs' => $logs,
            'hebergements' => $hebergements,
            'total' => $total,
        ]);
    }

    #[Route('/logs', name: '_logs')]
    public function logs(LogRepository $logRepository): Response
    {
        $logs = $logRepository->findBy([], ["id" => "DESC"], 10);

        return $this->render($this->getPath('logs/index'), [
            'logs' => $logs,
        ]);
    }

    #[Route('/users', name: '_users')]
    public function users(): Response
    {
        return $this->render($this->getPath('users/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/saisons', name: '_saisons')]
    public function saisons(): Response
    {
        return $this->render($this->getPath('saisons/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/options', name: '_options')]
    public function options(): Response
    {
        return $this->render($this->getPath('options/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/emplacements', name: '_emplacements')]
    public function emplacements(EmplacementRepository $emplacementRepository): Response
    {
        $emplacements = $emplacementRepository->findBy([], ["id" => "desc"]);
        // foreach($emplacements as $emplacement) $emplacement->getEmplacements()->getValues();

        return $this->render($this->getPath('emplacements/index'), [
            'emplacements' => $emplacements
        ]);
    }

    #[Route('/tarifs', name: '_tarifs')]
    public function tarifs(): Response
    {
        return $this->render($this->getPath('tarifs/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/rules', name: '_regles')]
    public function regles(): Response
    {
        return $this->render($this->getPath('regles/index'), [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/hebergements', name: '_hebergements')]
    public function hebergements(HebergementRepository $hebergementRepository): Response
    {
        $hebergements = $hebergementRepository->findBy([], ["id" => "desc"]);
        foreach ($hebergements as $hebergement) $hebergement->getEmplacements()->getValues();

        return $this->render($this->getPath('hebergements/index'), [
            'hebergements' => $hebergements,
        ]);
    }
}
