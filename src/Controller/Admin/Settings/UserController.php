<?php

namespace App\Controller\Admin\Settings;

use App\Entity\User;
use App\Form\AdminType;
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

#[Route('/admin/settings/users', name: 'app_admin_settings_users')]
class UserController extends AbstractController
{
    private string $title = "Gestionnaires";

    private function getPath($file): string
    {
        return sprintf('admin/settings/users/%s.html.twig', $file);
    }


    #[Route('', name: '')]
    public function users(UserRepository $userRepository): Response
    {
        $users = $userRepository->findByRole("ROLE_ADMIN");
        foreach ($users as $user) $user->getLogs()->getValues();

        return $this->render($this->getPath('index'), [
            'title' => $this->title,
            'users' => $users
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, UserRepository $userRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(AdminType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setRoles(['ROLE_ADMIN']);

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            $logService->write($user, "create");

            return $this->redirectToRoute('app_admin_settings_users');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(User $user, Request $request, UploadService $uploadService, UserRepository $userRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(AdminType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManagerInterface->persist($user);
            $entityManagerInterface->flush();

            $logService->write($user, "update");

            return $this->redirectToRoute('app_admin_settings_users');
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

        $entityManagerInterface->remove($user);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_users');
    }
}
