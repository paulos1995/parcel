<?php

namespace App\Controller;

use App\Form\ParcelHandoverSelectType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ParcelType;
use App\Form\Filter\ParcelFilterType;
use App\Form\Filter\ParcelHandoverFilterType;
use App\Entity\Parcel;
use App\Repository\CategoryRepository;
use App\Repository\ParcelStatusRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Service\FileParcelService;

/**
 * @Route("/kadmin/parcel")
 */
class ParcelAdminController extends AbstractController
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * @Route("/print_handover", name="parcel_admin_print_handover", methods={"GET", "POST"})
     */
    public function printHandover(PaginatorInterface $paginator, EntityManagerInterface $em, Request $request, CategoryRepository $categoryRepository, ParcelStatusRepository $parcelStatusRepository): Response
    {
        $parcels = [];
        $selectForm = $this->createForm(ParcelHandoverSelectType::class);
        $selectForm->handleRequest($request);
        if ($selectForm->isSubmitted() && $selectForm->isValid()) {
            $parcels = $selectForm->get('parcels')->getData();
        }

        return $this->render('parcel/print_handover.html.twig', [
            'parcels' => $parcels,
        ]);
    }

    /**
     * @Route("/handover", name="parcel_admin_handover", methods={"GET","POST"})
     */
    public function parcelsHandover(PaginatorInterface $paginator, EntityManagerInterface $em, Request $request, CategoryRepository $categoryRepository, ParcelStatusRepository $parcelStatusRepository): Response
    {
        $filter = ['statuses' => [$parcelStatusRepository->find(4), $parcelStatusRepository->find(6)]];
        $filterForm = $this->createForm(ParcelHandoverFilterType::class, $filter);

        $filterBuilder = $em->createQueryBuilder()
            ->select([
                'Parcel', 'Category', 'ParcelStatus'
            ])
            ->from('App\Entity\Parcel', 'Parcel')
            ->leftJoin('Parcel.category', 'Category')
            ->leftJoin('Parcel.status', 'ParcelStatus')
            ->indexBy('Parcel', 'Parcel.id')
        ;


        if ($request->query->has($filterForm->getName())) {

            $filter = $request->query->get($filterForm->getName());
            $filterForm->submit($filter);
            $category = $categoryRepository->find($filter['category']);
            $filterBuilder->andWhere('Parcel.category = :category')->setParameter('category', $category);
            if (isset($filter['statuses']) && $filter['statuses']) {
                $filterBuilder->andWhere('ParcelStatus.id IN (:statuses)')->setParameter('statuses', $filter['statuses']);
            }
            if (isset($filter['barcodes']) && $filter['barcodes'] && trim($filter['barcodes'])) {
                $barcodes = explode("\n", $filter['barcodes']);
                $barcodes = array_map('trim', $barcodes);
                if ($barcodes)
                    $filterBuilder->andWhere('Parcel.barcodeNumber IN (:barcodeNumbers)')->setParameter('barcodeNumbers', $barcodes);
            }

            $query = $filterBuilder->getQuery();

            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                500 /*limit per page*/
            );

            $parcels = $query->getResult();

            $selectForm = $this->createForm(ParcelHandoverSelectType::class, [], ['parcels'=>$parcels]);
            $selectForm->handleRequest($request);
            if ($selectForm->isSubmitted() && $selectForm->isValid()) {

                $selectedParcels = $selectForm->get('parcels')->getData();
                if ($selectForm->get('changeStatusBtn')->isClicked()) {
                    $givenStatus = $parcelStatusRepository->find(5);
                    foreach ($selectedParcels as $parcel) {
                        /** @var Parcel $parcel */
                        $parcel->setStatus($givenStatus);
                    }
                    $em->flush();
                }

                return $this->render('parcel/print_handover.html.twig', [
                    'parcels' => $selectedParcels,
                    'todaysDate' => new \DateTime()
                ]);
            }

            return $this->render('parcel/handover.html.twig', [
                'parcels' => $parcels,
                'filterForm' => $filterForm->createView(),
                'selectForm'=>$selectForm->createView(),
                'pagination' => $pagination,
            ]);

        } else {
            $parcels = [];
            $pagination = null;
        }


        return $this->render('parcel/handover.html.twig', [
            'parcels' => $parcels,
            'filterForm' => $filterForm->createView(),
            'pagination' => $pagination,
        ]);
    }


    /**
     * @Route("/", name="parcel_admin_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $filterForm = $this->createForm(ParcelFilterType::class);

        $filterBuilder = $em->createQueryBuilder()
            ->select([
                'Parcel', 'Category', 'ParcelStatus'
            ])
            ->from('App\Entity\Parcel', 'Parcel')
            ->leftJoin('Parcel.category', 'Category')
            ->leftJoin('Parcel.status', 'ParcelStatus');


        if ($request->query->has($filterForm->getName())) {
            $filter = $request->query->get($filterForm->getName());
            $filterForm->submit($filter);

            if (isset($filter['title']) && $filter['title']) {
                $filterBuilder->andWhere('Parcel.title LIKE :title')->setParameter('title', '%' . $filter['title'] . '%');
            }
            if (isset($filter['category']) && $filter['category']) {
                $filterBuilder->andWhere('Category.id = :category')->setParameter('category', $filter['category']);
            }
            if (isset($filter['status']) && $filter['status']) {
                $filterBuilder->andWhere('ParcelStatus.id = :status')->setParameter('status', $filter['status']);
            }
            if (isset($filter['barcodeNumber']) && $filter['barcodeNumber']) {
                $filterBuilder->andWhere('Parcel.barcodeNumber = :barcodeNumber')->setParameter('barcodeNumber', $filter['barcodeNumber']);
            }
            if (isset($filter['hasScanOrdered']) && $filter['hasScanOrdered']) {
                if (-1==$filter['hasScanOrdered']) $filterBuilder->andWhere('Parcel.scanOrdered IS NULL');
                if ( 1==$filter['hasScanOrdered']) $filterBuilder->andWhere('Parcel.scanOrdered IS NOT NULL');
            }
            if (isset($filter['hasOrderedScanInserted']) && $filter['hasOrderedScanInserted']) {
                if (-1 == $filter['hasOrderedScanInserted']) $filterBuilder->andWhere('Parcel.scanInserted IS NULL');
                if (1 == $filter['hasOrderedScanInserted']) $filterBuilder->andWhere('Parcel.scanInserted IS NOT NULL');
            }

        }

        $query = $filterBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $_ENV['PAGINATION_MAX_NUMBER_OF_ITEM_PER_PAGE'] /*limit per page*/
        );

        $parcels = $query->getResult();

        return $this->render('parcel/index.html.twig', [
            'parcels' => $parcels,
            'pagination' => $pagination,
            'form' => $filterForm->createView(),
        ]);
    }

    /**
     * @Route("/new", name="parcel_new", methods="GET|POST")
     */

    public function newAction(Request $request): Response
    {

        $rich = $request->get('rich');
        $parcel = new Parcel();
        $parcel->setCreatedByUser($this->getUser());
        $form = $this->createForm(ParcelType::class, $parcel);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('file')->getData();
            if ($uploadedFile) {
                $this->handleFileUpload($uploadedFile, $parcel);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($parcel);
            $entityManager->flush();

            $dummyFormNumber = 'L' . $parcel->getId();
            return $this->render('parcel/saved.html.twig', [
                'parcel' => $parcel,
                'dummyFormNumber' => $dummyFormNumber
            ]);
        }
        if ($rich) {
            return $this->render('parcel/new_rich.html.twig', [
                'parcel' => $parcel,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->render('parcel/new.html.twig', [
                'parcel' => $parcel,
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/show:{id}", name="parcel_admin_show", methods={"GET"})
     */
    public function show(Parcel $parcel): Response
    {
        return $this->render('parcel/show.html.twig', [
            'parcel' => $parcel,
        ]);
    }


    /**
     * @Route("/edit:{id}", name="parcel_admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Parcel $parcel, FileParcelService $parcelService): Response
    {
        $form = $this->createForm(ParcelType::class, $parcel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $uploadedFile = $form->get('file')->getData();
            if ($uploadedFile) {
                $parcelService->deleteFile($parcel);
                $this->handleFileUpload($uploadedFile, $parcel);
                if ($parcel->getScanOrdered() && $parcel->getFileName()) {
                    $parcel->setScanInserted(new \DateTime());
                }
            }

            $parcel->setModifiedByUser($this->getUser());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('parcel_admin_index');
        }

        return $this->render('parcel/edit.html.twig', [
            'parcel' => $parcel,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/multi", name="parcel_multi", methods="GET")
     */
    public function multiFormAction(Request $request): Response
    {

        $parcel = new Parcel();

        $form0 = $this->createForm(ParcelType::class, $parcel);
        $form = $this->createForm(ParcelType::class, $parcel);


        return $this->render('parcel/multi.html.twig', [
            'parcel' => $parcel,
            'form0' => $form0->createView(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete:{id}", name="parcel_admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Parcel $parcel): Response
    {
        if ($this->isCsrfTokenValid('delete' . $parcel->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($parcel);
            $entityManager->flush();
            $this->addFlash('danger', 'Deleted.');
        }

        return $this->redirectToRoute('parcel_admin_index');
    }

    private function handleFileUpload($uploadedFile, Parcel $parcel): void
    {
        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $parcel->setOriginalName($uploadedFile->getClientOriginalName());
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $orgDir = $parcel->getCategory()->getId();
        if (!is_dir($this->getParameter('upload_directory') . DIRECTORY_SEPARATOR . $orgDir)) {
            mkdir($this->getParameter('upload_directory') . DIRECTORY_SEPARATOR . $orgDir);
        }
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
        $filenameInOrgdir = $orgDir . DIRECTORY_SEPARATOR . $newFilename;

        // Move the file to the directory where files are stored
        try {
            $uploadedFile->move(
                $this->getParameter('upload_directory') . DIRECTORY_SEPARATOR . $orgDir,
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        $fileSize = filesize($this->getParameter('upload_directory') . DIRECTORY_SEPARATOR . $filenameInOrgdir);
        $parcel->setSize($fileSize);
        $parcel->setFilename($filenameInOrgdir);
    }

}