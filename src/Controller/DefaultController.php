<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\ParcelRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Security as CoreSecurity;



/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{

    /**
     * @Route("/", name="home", methods="GET")
     * @Security("is_granted('ROLE_USER')")
     */

    public function homeAction(Request $request, SluggerInterface $slugger, ParcelRepository $parcelRepository, CategoryRepository $categoryRepository, CoreSecurity $security): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $numberOfUnseenPerCategory = [];
        $defaultCategoryId = null;
        if ($security->isGranted('ROLE_LOCATION_MODERATOR')) {
            // parcel_admin_index for ADMIN
            return $this->redirectToRoute('parcel_admin_index', ['sort' => 'Parcel.created', 'direction'=>'desc']);
        }
        else {
            $categories = $user->getCategories();
            if (count($categories) > 1) {
                foreach ($categories as $category) {
                    $numberOfUnseenPerCategory[$category->getId()] = $parcelRepository->getNumberOfUnseenPerCategory($category);
                }
                $maxUnseen = max($numberOfUnseenPerCategory);
                foreach ($numberOfUnseenPerCategory as $categoryId => $numberOfUnseen) {
                    if ($numberOfUnseen === $maxUnseen) $defaultCategoryId = $categoryId;
                }

            } else if (1 === count($categories)) {
                $defaultCategoryId = $categories[0]->getId();
            }
            if ($defaultCategoryId) {
                return $this->redirectToRoute('parcel_user_index', ['id' => $defaultCategoryId]);
            }
        }
        return $this->render('home.html.twig', []);
    }


}