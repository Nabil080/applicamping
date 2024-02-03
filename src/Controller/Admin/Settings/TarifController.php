<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Tarif;
use App\Form\TarifType;
use App\Repository\EmplacementRepository;
use App\Repository\HebergementRepository;
use App\Repository\OffreRepository;
use App\Repository\OptionRepository;
use App\Repository\TarifRepository;
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

#[Route('/admin/settings/tarifs', name: 'app_admin_settings_tarifs')]
class TarifController extends AbstractController
{
    private string $title = "tarifs";

    private function getPath($file): string
    {
        return sprintf('admin/settings/tarifs/%s.html.twig', $file);
    }


    #[Route('', name: '')]
    public function tarifs(HebergementRepository $hebergementRepository, OptionRepository $optionRepository, TarifRepository $tarifRepository, OffreRepository $offreRepository): Response
    {
        $hebergements = $hebergementRepository->findBy([], ["id" => "asc"]);
        foreach ($hebergements as $hebergement) $hebergement->getTarifs()->getValues();

        $options = $optionRepository->findBy([], ["id" => "asc"]);
        foreach ($options as $option) $option->getTarifs()->getValues();

        $adultes = $tarifRepository->findBy(["adulte" => true], ["id" => "asc"]);
        $enfants = $tarifRepository->findBy(["enfant" => true], ["id" => "asc"]);

        $ageCategory = [
            "adulte" => ["id" => 0, "nom" => "Adulte", "tarifs" => $adultes],
            "enfant" => ["id" => 0, "nom" => "Enfant", "tarifs" => $enfants]
        ];

        $remises = $offreRepository->findBy(["type" => "remise"], ["id" => "asc"]);
        $coupons = $offreRepository->findBy(["type" => "coupon"], ["id" => "asc"]);

        $offres = [
            "remise" => ["id" => 0, "type" => "Remises", "offres" => $remises ],
            "coupon" => ["id" => 0, "type" => "Coupons", "offres" => $coupons ],
        ];

        return $this->render($this->getPath('index'), [
            'title' => $this->title,
            'hebergements' => $hebergements,
            'options' => $options,
            'ageCategory' => $ageCategory,
            'offres' => $offres,
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, TarifRepository $tarifRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(TarifType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $tarif = $form->getData();

            $entityManagerInterface->persist($tarif);
            $entityManagerInterface->flush();

            $logService->write($tarif, "create");

            return $this->redirectToRoute('app_admin_settings_tarifs');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(Tarif $tarif, Request $request, UploadService $uploadService, TarifRepository $tarifRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(TarifType::class, $tarif);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $tarif = $form->getData();

            $entityManagerInterface->persist($tarif);
            $entityManagerInterface->flush();

            $logService->write($tarif, "update");

            return $this->redirectToRoute('app_admin_settings_tarifs');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            "create" => false
        ]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Tarif $tarif, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($tarif, "delete");

        $entityManagerInterface->remove($tarif);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_tarifs');
    }
}
