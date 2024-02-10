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

    #[Route('/jour', name: '_day')]
    public function day(Request $request, PaiementRepository $paiementRepository, ReservationRepository $reservationRepository): Response
    {
        $day = new DateTime($request->get('day') ?? 'now');
        $paiements = $paiementRepository->findByDay($day);
        $total = 0;
        $reservationTotal = count($reservationRepository->getCurrent($day));


        foreach ($paiements as $paiement) {
            $paiementsArray[$paiement->getMethode()]['paiements'][] = $paiement;
            $paiementsArray[$paiement->getMethode()]['total'] = ($paiementsArray[$paiement->getMethode()]['total'] ?? 0) + $paiement->getMontant();
            $total += $paiement->getMontant();
        }

        return $this->render($this->getPath("day"), [
            'title' => 'Caisse du jour',
            'paiementsArray' => $paiementsArray ?? [],
            'total' => $total,
            'day' => $day,
            'reservationTotal' => $reservationTotal,
        ]);
    }

    #[Route('/mois', name: '_month')]
    public function month(Request $request, PaiementRepository $paiementRepository,  ReservationRepository $reservationRepository): Response
    {
        $month = new DateTime($request->get('month') ?? 'now');
        $paiements = $paiementRepository->findByMonth($month);
        $total = 0;
        $reservationTotal = count($reservationRepository->findByMonth($month));

        foreach ($paiements as $paiement) {
            $paiementsArray[$paiement->getMethode()]['paiements'][] = $paiement;
            $paiementsArray[$paiement->getMethode()]['total'] = ($paiementsArray[$paiement->getMethode()]['total'] ?? 0) + $paiement->getMontant();
            $total += $paiement->getMontant();
        }

        return $this->render($this->getPath("month"), [
            'title' => 'Caisse du mois',
            'paiementsArray' => $paiementsArray ?? [],
            'month' => $month,
            'total' => $total,
            'reservationTotal' => $reservationTotal,
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
