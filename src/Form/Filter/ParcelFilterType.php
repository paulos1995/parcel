<?php

namespace App\Form\Filter;

use App\Entity\Category;
use App\Entity\ParcelStatus;
use App\Repository\CategoryRepository;
use App\Repository\ParcelStatusRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ParcelFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required'=>false])
            ->add('barcodeNumber', TextType::class, ['required'=>false])
            ->add('weight', TextType::class, ['required'=>false])
            ->add('size', TextType::class, ['required'=>false])
            ->add('price', TextType::class, ['required'=>false])
            ->add('category', EntityType::class, [
                'class' => 'App:Category',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.name', 'ASC')
                        ->setMaxResults(5);
                },
                'choice_label' => function (Category $category) {
                    return $category->getName().' '.($category->getScan()?'📷':'🔒');
                },
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => '',
                'attr' => array(
                    'class' => 'category-ajax-filter-select'
                )
            ])
            ->add('status', EntityType::class, [
                'class' => 'App:ParcelStatus',
                'query_builder' => function (ParcelStatusRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.name', 'ASC')
                        ;
                },
                'choice_label' => function (ParcelStatus $letterStatus) {
                    return $letterStatus->getName();
                },
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => '',
                'attr' => array(

                )
            ])
            ->add('hasScanOrdered', ChoiceType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'choices' => ['No'=>-1, 'Yes'=>1],

            ])
            ->add('hasOrderedScanInserted', ChoiceType::class, [
                'required' => false,
                'multiple' => false,
                'expanded' => false,
                'choices' => ['No'=>-1, 'Yes'=>1],

            ])

            ->addEventListener(
                FormEvents::PRE_SUBMIT,
                function (FormEvent $event) {
                    $form = $event->getForm();

                    $data = $event->getData();
                    $submittedCategoryId = $data['category'];
                    if ($submittedCategoryId) {
                        $form->add('category', EntityType::class, [
                            'class' => 'App:Category',
                            'query_builder' => function (CategoryRepository $er) use ($submittedCategoryId) {
                                return $er->createQueryBuilder('o')
                                    ->andWhere('o.id = :submittedCategoryId')
                                    ->setParameter('submittedCategoryId', $submittedCategoryId);
                            },
                            'choice_label' => function (Category $category) {
                                return $category->getName().' '.($category->getScan()?'📷':'🔒');
                            },
                            'multiple' => false,
                            'expanded' => false,
                            'required' => false,
                            'placeholder' => '',
                            'attr' => array(
                                'class' => 'category-ajax-filter-select'
                            )
                        ]);
                    }

                }
            );
        ;
    }

    public function getBlockPrefix()
    {
        return 'fi';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
