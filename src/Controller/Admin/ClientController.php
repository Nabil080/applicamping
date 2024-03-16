<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\ClientType;
use App\Repository\UserRepository;
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

#[Route('/admin/clients', name: 'app_admin_clients')]
class ClientController extends AbstractController
{
    private string $title = "Clients";

    private function getPath($file): string
    {
        return sprintf('admin/clients/%s.html.twig', $file);
    }


    #[Route('', name: '')]
    public function clients(UserRepository $userRepository): Response
    {
        $clients = $userRepository->findByRole("ROLE_USER");
        foreach ($clients as $user) $user->getLogs()->getValues();

        return $this->render($this->getPath('index'), [
            'title' => $this->title,
            'clients' => $clients
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, UserRepository $userRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(ClientType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setRoles(['ROLE_USER']);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            $logService->write($user, "create");

            return $this->redirectToRoute('app_admin_clients');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(User $user, Request $request, UploadService $uploadService, UserRepository $userRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(ClientType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            $logService->write($user, "update");

            return $this->redirectToRoute('app_admin_clients');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            "create" => false
        ]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(User $user, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($user, "delete");

        $user->setStatut('Archivé');
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_clients');
    }
}
