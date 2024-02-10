<?php

namespace App\Controller\Admin;

use App\Entity\Paiement;
use App\Form\PaiementType;
use App\Repository\PaiementRepository;
use App\Repository\ReservationRepository;
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

    #[Route('/caisse', name: '_caisse')]
    public function caisse(Request $request, PaiementRepository $paiementRepository, ReservationRepository $reservationRepository): Response
    {
        $type = $request->get('type') ?? 'day';
        $date = new DateTime($request->get($type) ?? 'now');
        $paiements = $type === 'day' ? $paiementRepository->findByDay($date) : $paiementRepository->findByMonth($date);
        $count = count($paiements);
        $total = 0;

        $totalReservations = [];
        foreach ($paiements as $paiement) {
            $paiementsArray[$paiement->getMethode()]['paiements'][] = $paiement;
            $paiementsArray[$paiement->getMethode()]['total'] = ($paiementsArray[$paiement->getMethode()]['total'] ?? 0) + $paiement->getMontant();
            $paiementsArray[$paiement->getMethode()]['reservations'][$paiement->getReservation()->getId()] = ($paiement->getReservation());
            $totalReservations[$paiement->getReservation()->getId()] = true;
            $total += $paiement->getMontant();
        }

        return $this->render($this->getPath("caisse"), [
            'title' => 'Caisse du ' . ($type === 'day' ? 'jour' : 'mois'),
            'paiementsArray' => $paiementsArray ?? [],
            'total' => $total,
            'date' => $date,
            'count' => $count,
            'countReservations' => count($totalReservations)
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
