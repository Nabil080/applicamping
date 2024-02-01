<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Option;
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

        return $this->render($this->getPath('create'), ["form" => $form]);
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

        return $this->render($this->getPath('create'), ["form" => $form, "create" => false]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Option $option, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($option, "delete");

        $entityManagerInterface->remove($option);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_options');
    }
}
