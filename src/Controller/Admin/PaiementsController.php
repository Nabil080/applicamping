<?php

namespace App\Controller\Admin;

use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use App\Service\LogService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/paiements', name: 'app_admin_paiements')]
class PaiementsController extends AbstractController
{
    private string $title = "Paiements";
    private function getPath($file): string
    {
        return sprintf('admin/paiements/%s.html.twig', $file);
    }

    #[Route('', name: '')]
    public function paiements(PaiementRepository $paiementRepository): Response
    {
        $paiements = $paiementRepository->findBy(["statut" => ["Validé"]]);
        foreach ($paiements as $paiement) $paiement->getReservation();

        return $this->render($this->getPath('index'), [
            'title' => $this->title,
            'paiements' => $paiements
        ]);
    }

    #[Route('/jour', name: '_day')]
    public function day(): Response
    {
        return $this->render($this->getPath("day"), [
            'controller_name' => 'paiementsController',
        ]);
    }

    #[Route('/mois', name: '_month')]
    public function month(): Response
    {
        return $this->render($this->getPath("month"), [
            'controller_name' => 'paiementsController',
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(PaiementType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $paiement = $form->getData();

            $entityManagerInterface->persist($paiement);
            $entityManagerInterface->flush();

            $logService->write($paiement, "create");

            return $this->redirectToRoute('app_admin_paiements');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            'redirectRoute' => 'app_admin_paiements'
        ]);
    }
}
