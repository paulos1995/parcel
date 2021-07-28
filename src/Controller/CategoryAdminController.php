<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\Filter\CategoryFilterType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/kadmin/category")
 */
class CategoryAdminController extends AbstractController
{
    /**
     * @Route("/find.{_format}",
     *      requirements = { "_format" = "html|json" },
     *      name="category_admin_find", methods={"GET"})
     */
    public function findByFragmentAction(Request $request, $_format, CategoryRepository $categoryRepository): Response
    {
        $fragment = $request->query->get('q');
        if ($fragment) {
            $categories = $categoryRepository->findByFragment($fragment);
        } else {
            $categories = [];
        }

        if ('html' == $_format) {
            return $this->render('category_admin/index.html.twig', [
                'categories' => $categories,
            ]);
        } else {
            $orgArrs = [];
            if (count($categories)) {
                foreach ($categories as $category) {
                    $orgArrs[]=$category->toArray();
                }
            }
            $reaponseArr = [
                'results'=>$orgArrs
            ];
            $response = new JsonResponse();
            $response->setData($reaponseArr);
            return $response;
        }
    }

    /**
     * @Route("/", name="category_admin_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request): Response
    {
        $filterForm = $this->createForm(CategoryFilterType::class);

        $filterBuilder = $em->createQueryBuilder()
            ->select([
                'Category', 'Owner', 'Location', 'ScanPlan'
            ])
            ->from('App\Entity\Category', 'Category')
            ->leftJoin('Category.owner', 'Owner')
            ->leftJoin('Category.location', 'Location')
            ->leftJoin('Category.scanPlan', 'ScanPlan')
            ;


        if ($request->query->has($filterForm->getName())) {
            $filter = $request->query->get($filterForm->getName());
            $filterForm->submit($filter);

            if (isset($filter['name']) && $filter['name']) {
                $filterBuilder->andWhere('Category.name LIKE :name')->setParameter('name', '%'.$filter['name'].'%');
            }
            if (isset($filter['location']) && $filter['location']) {
                $filterBuilder->andWhere('Location.id = :location')->setParameter('location', $filter['location']);
            }


        }

        $query = $filterBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            $_ENV['PAGINATION_MAX_NUMBER_OF_ITEM_PER_PAGE'] /*limit per page*/
        );

        $categories = $query->getResult();

        return $this->render('category_admin/index.html.twig', [
            'categories' => $categories,
            'pagination' => $pagination,
            'form' => $filterForm->createView(),
        ]);

    }

    /**
     * @Route("/new", name="category_admin_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_admin_index');
        }

        return $this->render('category_admin/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_admin_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category_admin/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_admin_index');
        }

        return $this->render('category_admin/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_admin_index');
    }
}
