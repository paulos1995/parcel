<?php

namespace App\Controller;

use App\Entity\Parcel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Security as CoreSecurity;
use App\Service\FileParcelService;

/**
 * @Route("/download")
 * @Security("is_granted('ROLE_USER')")
 */
class DownloadController extends AbstractController
{

    private $fileParcelService;

    public function __construct(FileParcelService $fileParcelService)
    {
        $this->fileParcelService = $fileParcelService;
    }


    /**
     * @Route("/parcel:{id}", name="parcel_file_download", methods={"GET"})
     */
    public function download(Parcel $parcel, CoreSecurity $security): Response
    {

        if (
            ($security->isGranted('ROLE_ADMIN'))
            || ($security->isGranted('ROLE_LOCATION_MODERATOR') && $parcel->getCategory()->getLocation() == $this->getUser()->getLocation())
            || ($this->getUser()->getId() && $parcel->getCategory()->getOwner() == $this->getUser())
        ) {

            $entityManager = $this->getDoctrine()->getManager();
            $file = $this->fileParcelService->getFilePath($parcel);
            if (file_exists($file)) {
                $response = new BinaryFileResponse($file);
                $response->setContentDisposition(
                // ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                    ResponseHeaderBag::DISPOSITION_INLINE,
                    $parcel->getOriginalName()
                );
                if ( $this->getUser()->getId() && $parcel->getCategory()->getOwner() == $this->getUser() ) {
                    // downloaded by owner
                    $parcel->setSeen(true);
                    $parcel->setDownloadedByUser($this->getUser());
                    $entityManager->flush();
                }
                return $response;
            }
            throw $this->createNotFoundException('The file has already been deleted.');
        } else {
            $response = $this->render('security/access_denied.html.twig');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }

    }



    /**
     * @Route("/kadmin/delfile:{id}", name="parcel_file_delete", methods={"DELETE"})
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function delete(Request $request, Parcel $parcel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parcel->getId(), $request->request->get('_token'))) {

            $entityManager = $this->getDoctrine()->getManager();
            $this->fileParcelService->deleteFile($parcel);
            $parcel->setFileName(null);
            $entityManager->remove($parcel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
