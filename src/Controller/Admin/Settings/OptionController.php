<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Option;
use App\Entity\OptionMaximum;
use App\Form\OptionMaximumType;
use App\Form\OptionType;
use App\Repository\OptionRepository;
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

#[Route('/admin/settings/options', name: 'app_admin_settings_options')]
class OptionController extends AbstractController
{
    private function getPath($file): string
    {
        return sprintf('admin/settings/options/%s.html.twig', $file);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, OptionRepository $optionRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(OptionType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $option = $form->getData();

            $entityManagerInterface->persist($option);
            $entityManagerInterface->flush();

            $logService->write($option, "create");

            return $this->redirectToRoute('app_admin_settings_options');
        }

        return $this->render("layout/form.html.twig", ["title" => "Options", "form" => $form, ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(Option $option, Request $request, UploadService $uploadService, OptionRepository $optionRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(OptionType::class, $option);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $option = $form->getData();

            $entityManagerInterface->persist($option);
            $entityManagerInterface->flush();

            $logService->write($option, "update");

            return $this->redirectToRoute('app_admin_settings_options');
        }

        return $this->render("layout/form.html.twig", ["title" => "Options", "form" => $form, "create" => false]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Option $option, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($option, "delete");

        $entityManagerInterface->remove($option);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_options');
    }

    #[Route('/{id}/create', name: '_maximum_create')]
    public function maximumCreate(Option $option, Request $request, OptionRepository $optionRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(OptionMaximumType::class);
        $form->get('option')->setData($option);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $optionMaximum = $form->getData();

            $entityManagerInterface->persist($optionMaximum);
            $entityManagerInterface->flush();

            $logService->write($optionMaximum, "create");

            return $this->redirectToRoute('app_admin_settings_options');
        }

        return $this->render("layout/form.html.twig", ["title" => "Règle d'option supplémentaire", "form" => $form,]);
    }

    #[Route('/maximum/update/{id}', name: '_maximum_update')]
    public function maximumUpdate(OptionMaximum $optionMaximum, Request $request, OptionRepository $optionRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(OptionMaximumType::class, $optionMaximum);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $optionMaximum = $form->getData();

            $entityManagerInterface->persist($optionMaximum);
            $entityManagerInterface->flush();

            $logService->write($optionMaximum, "update");

            return $this->redirectToRoute('app_admin_settings_options');
        }

        return $this->render("layout/form.html.twig", ["title" => "Règle d'option supplémentaire", "form" => $form, "create" => false]);
    }

    #[Route('/maximum/delete/{id}', name: '_maximum_delete')]
    public function maximumDelete(OptionMaximum $optionMaximum, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($optionMaximum, "delete");

        $entityManagerInterface->remove($optionMaximum);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_options');
    }
}
