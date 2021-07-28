<?php

namespace App\Service;

use App\Entity\Parcel;

use App\Repository\ParcelRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileParcelService
{
    private $uploadPernDirectory;
    private $output;

    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function __construct(ManagerRegistry $doctrine, ParameterBagInterface $parameterBag, ParcelRepository $parcelRepository)
    {
        $this->uploadPernDirectory = $parameterBag->get('upload_directory');
    }



    /**
     * Path to stored file (on server)
     * @param Parcel $parcel
     * @return string
     */
    public function getFilePath(Parcel $parcel): string
    {

        $filePath = $this->uploadPernDirectory .DIRECTORY_SEPARATOR. $parcel->getFileName();

        return $filePath;
    }


    public function deleteFile(Parcel $parcel): bool
    {
        $filePath = $this->getFilePath($letter);
        if ($letter->getFileName() && file_exists($filePath)) {
            $unlinkResult = unlink($filePath);
            if ($this->output) $this->output->writeln("<value>" . $filePath . "</value>\t file deleted");
            $letter->setFileName(null);
            return $unlinkResult;
        }

        return false;
    }


}