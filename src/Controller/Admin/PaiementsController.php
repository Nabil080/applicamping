<?php

namespace App\Controller\Admin;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use App\Service\LogService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/paiements', name: 'app_admin_paiements')]
class PaiementsController extends AbstractController
{
    private string $title = "Paiements";
    private string $index = "app_admin_paiements";
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
    public function day(PaiementRepository $paiementRepository): Response
    {
        $now = new DateTime('now');
        $paiements = $paiementRepository->findByDay($now);

        foreach ($paiements as $paiement) {
            switch ($paiement->getMethode()) {
                case 'Carte bancaire':
                    $paiementsArray['Carte bancaire']['paiements'][] = $paiement;
                    $paiementsArray['Carte bancaire']['total'] = ($paiementsArray['Carte bancaire']['total'] ?? 0) + $paiement->getMontant();
                    break;
                case 'Virement bancaire':
                    $paiementsArray['Virement bancaire']['paiements'][] = $paiement;
                    $paiementsArray['Virement bancaire']['total'] = ($paiementsArray['Virement bancaire']['total'] ?? 0) + $paiement->getMontant();
                    break;
                case 'Espèce':
                    $paiementsArray['Espèce']['paiements'][] = $paiement;
                    $paiementsArray['Espèce']['total'] = ($paiementsArray['Espèce']['total'] ?? 0) + $paiement->getMontant();
                    break;
                case 'Chèque vacance':
                    $paiementsArray['Chèque vacance']['paiements'][] = $paiement;
                    $paiementsArray['Chèque vacance']['total'] = ($paiementsArray['Chèque vacance']['total'] ?? 0) + $paiement->getMontant();
                    break;
                case 'chèque':
                    $paiementsArray['chèque']['paiements'][] = $paiement;
                    $paiementsArray['chèque']['total'] = ($paiementsArray['chèque']['total'] ?? 0) + $paiement->getMontant();
                    break;
                default:
                    $paiementsArray['autre']['paiements'][] = $paiement;
                    $paiementsArray['autre']['total'] = ($paiementsArray['autre']['total'] ?? 0) + $paiement->getMontant();
                    break;
            }
        }

        return $this->render($this->getPath("day"), [
            'title' => 'Caisse du jour',
            'paiementsArray' => $paiementsArray
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
    public function create(Security $security, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(PaiementType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $paiement = $form->getData();
            $paiement->setUser($security->getUser());

            $entityManagerInterface->persist($paiement);
            $entityManagerInterface->flush();

            $logService->write($paiement, "create");

            return $this->redirectToRoute($this->index);
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            'redirectRoute' => $this->index
        ]);
    }

    #[Route('/update/{id}', name: '_update')]
    public function update(Paiement $paiement, Security $security, Request $request, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(PaiementType::class, $paiement);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $paiement = $form->getData();

            $entityManagerInterface->persist($paiement);
            $entityManagerInterface->flush();

            $logService->write($paiement, "update");

            return $this->redirectToRoute($this->index);
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            'redirectRoute' => $this->index,
            'create' => false,
        ]);
    }

    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Paiement $paiement, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($paiement, "archive");

        $paiement->setStatut('Archivé');
        $entityManagerInterface->flush();


        return $this->redirectToRoute($this->index);
    }
}
