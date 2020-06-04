<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetPath;

    public function __construct($uploadPath)
    {
        $this->targetPath = $uploadPath;
    }

    public function upload(UploadedFile $file, $path = null): string
    {
        if (null === $path) {
            $path = $this->targetPath;
        }

        $filename = $this->generateUniqueName($file);

        # Fix the missing image/svg mimetype in Symfony
        if ($file->getMimeType() === "image/svg") {
            $filename .= "svg";
        }

        $file->move($path, $filename);

        return $filename;
    }

    public function generateUniqueName(UploadedFile $file): string
    {
        return md5(uniqid()).".".$file->guessExtension();
    }
}