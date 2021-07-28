<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\ParcelOrderScanType;
use App\Form\Filter\ParcelUserFilterType;
use App\Entity\Parcel;
use App\Entity\Category;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/org_")
 */
class ParcelUserController extends AbstractController
{
    /**
     * @Route("{id}/parcels", name="parcel_user_index", methods={"GET"})
     */
    public function index(Category $category, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {

        $user = $this->getUser();
        if ($category->getOwner() != $user) {
            $response = $this->render('security/access_denied.html.twig');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }

        $filterForm = $this->createForm(ParcelUserFilterType::class);

        $filterBuilder = $em->createQueryBuilder()
            ->select([
                'Parcel', 'ParcelStatus'
            ])
            ->from('App\Entity\Parcel', 'Parcel')
            ->leftJoin('Parcel.status', 'ParcelStatus')
            ->addOrderBy('Parcel.created', 'DESC');;
        $filterBuilder->andWhere('Parcel.category = :category')->setParameter('category', $category);

        if ($request->query->has($filterForm->getName())) {
            $filter = $request->query->get($filterForm->getName());
            $filterForm->submit($filter);

            if (isset($filter['title']) && $filter['title']) {
                $filterBuilder->andWhere('Parcel.title LIKE :title')->setParameter('title', '%' . $filter['title'] . '%');
            }
            if (isset($filter['status']) && $filter['status']) {
                $filterBuilder->andWhere('ParcelStatus.id = :status')->setParameter('status', $filter['status']);
            }
            if (isset($filter['barcodeNumber']) && $filter['barcodeNumber']) {
                $filterBuilder->andWhere('Parcel.barcodeNumber = :barcodeNumber')->setParameter('barcodeNumber', $filter['barcodeNumber']);
            }

        }

        $query = $filterBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $_ENV['PAGINATION_MAX_NUMBER_OF_ITEM_PER_PAGE'] /*limit per page*/
        );

        $parcels = $query->getResult();

        return $this->render('parcel_user/index.html.twig', [
            'parcels' => $parcels,
            'pagination' => $pagination,
            'form' => $filterForm->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("{category}/parcel_order_scan:{parcel}/", name="parcel_user_order_scan", methods={"GET","POST"}, requirements={
     * "category": "\d+"
     * })
     */
    public function orderScanAction(Request $request, Parcel $parcel, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ParcelOrderScanType::class, $parcel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($parcel->getScanOrdered()) {
                $this->addFlash('warning', $translator->trans('You cannot order a scan because it has already been ordered.').' ('.$parcel->getScanOrdered()->format('Y-m-d H:i').')');
            } else if ($parcel->getCategory()->getScan()) {
                $this->addFlash('warning', $translator->trans('You cannot order a scan because we scan all correspondence for your account.'));
            } else if ($parcel->getFileName()) {
                $this->addFlash('warning', $translator->trans('You cannot order a scan of this parcel as the scan is already in the system.'));
            } else {
                $parcel->setModifiedByUser($this->getUser());
                $parcel->setScanOrdered(new \DateTime());
                $parcel->setScanDue($_ENV['SINGLE_SCAN_PRICE']);
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash('success', $translator->trans('A scan of the parcel has been ordered.'));
                return $this->redirectToRoute('parcel_user_index', ['id' => $parcel->getCategory()->getId()]);
            }
        }

        return $this->render('parcel_user/order_scan.html.twig', [
            'parcel' => $parcel,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("{category}/parcel_show:{parcel}/", name="parcel_user_show", methods={"GET"}, requirements={
     * "category": "\d+"
     * })
     */
    public function show(Category $category, Parcel $parcel): Response
    {
        $user = $this->getUser();
        if ($category->getOwner() != $user) {
            $response = $this->render('security/access_denied.html.twig');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);
            return $response;
        }

        return $this->render('parcel_user/show.html.twig', [
            'parcel' => $parcel,
        ]);
    }


}