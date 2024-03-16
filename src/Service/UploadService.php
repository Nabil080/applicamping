<?php

namespace App\Service;

use App\Entity\Log;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->slugger = $sluggerInterface;
    }

    public function upload($file, $directory): string
    {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $this->slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $directory,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'imagename' property to store the PDF file name
                // instead of its contents
                return ($newFilename);
    }

}
