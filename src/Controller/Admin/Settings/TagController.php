<?php

namespace App\Controller\Admin\Settings;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
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

#[Route('/admin/settings/tags', name: 'app_admin_settings_tags')]
class TagController extends AbstractController
{
    private string $title = "Tags";

    private function getPath($file): string
    {
        return sprintf('admin/settings/tags/%s.html.twig', $file);
    }


    #[Route('/', name: '')]
    public function tags(TagRepository $tagRepository): Response
    {
        $tags = $tagRepository->findBy([], ["id" => "desc"]);
        foreach ($tags as $tag) $tag->getEmplacements()->getValues();

        return $this->render($this->getPath('index'), [
            'title' => $this->title,
            'tags' => $tags
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, TagRepository $tagRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(TagType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $entityManagerInterface->persist($tag);
            $entityManagerInterface->flush();

            $logService->write($tag, "create");

            return $this->redirectToRoute('app_admin_settings_tags');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form
        ]);
    }


    #[Route('/update/{id}', name: '_update')]
    public function update(Tag $tag, Request $request, UploadService $uploadService, TagRepository $tagRepository, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $tag = $form->getData();

            $entityManagerInterface->persist($tag);
            $entityManagerInterface->flush();

            $logService->write($tag, "update");

            return $this->redirectToRoute('app_admin_settings_tags');
        }

        return $this->render("layout/form.html.twig", [
            "title" => $this->title,
            "form" => $form,
            "create" => false
        ]);
    }



    #[Route('/delete/{id}', name: '_delete')]
    public function delete(Tag $tag, LogService $logService, EntityManagerInterface $entityManagerInterface): Response
    {

        $logService->write($tag, "delete");

        $entityManagerInterface->remove($tag);
        $entityManagerInterface->flush();


        return $this->redirectToRoute('app_admin_settings_tags');
    }
}
