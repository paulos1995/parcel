<?php

namespace App\Form;

use App\Entity\Parcel;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ParcelStatusRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ParcelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'ltr_title' // form-control
                ]
            ])
            ->add('barcodeNumber')
            ->add('weight')
            ->add('size')
            ->add('price')
            ->add('category', EntityType::class, [
                'class' => 'App:Category',
                'query_builder' => function (CategoryRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.name', 'ASC')
                        ->setMaxResults(5);
                },
                'choice_label' => function (Category $category) {
                    return $category->getName() . ' ' . ($category->getScan() ? '📷' : '🔒');
                },
                'multiple' => false,
                'expanded' => false,
                'required' => true,
                'placeholder' => '',
                'attr' => [
                    'class' => 'ltr_category category-ajax-select'
                ]
            ])
            ->add('status', EntityType::class, [
                'class' => 'App:ParcelStatus',
                'query_builder' => function (ParcelStatusRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.id', 'ASC');
                },
                'multiple' => false,
                'expanded' => false,
                'required' => true,
            ])
            ->add('file', FileType::class, [
                'label' => 'Scan (PDF file)',

                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',
                    ])
                ],
            ])
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) {
                    $form = $event->getForm();
                    /** @var @var Parcel $letter */
                    $letter = $event->getData();
                    if ($letter->getCategory()) {
                        $submittedCategoryId = $letter->getCategory()->getId();
                        $form->add('category', EntityType::class, [
                            'class' => 'App:Category',
                            'query_builder' => function (CategoryRepository $er) use ($submittedCategoryId) {
                                return $er->createQueryBuilder('o')
                                    ->andWhere('o.id = :submittedCategoryId')
                                    ->setParameter('submittedCategoryId', $submittedCategoryId);
                            },
                            'choice_label' => function (Category $category) {
                                return $category->getName() . ' ' . ($category->getScan() ? '📷' : '🔒');
                            },
                            'multiple' => false,
                            'expanded' => false,
                            'required' => true,
                            'placeholder' => '',
                            'attr' => array(
                                'class' => 'category-ajax-filter-select'
                            )
                        ]);
                    }

                }
            )
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
                                return $category->getName() . ' ' . ($category->getScan() ? '📷' : '🔒');
                            },
                            'multiple' => false,
                            'expanded' => false,
                            'required' => true,
                            'placeholder' => '',
                            'attr' => array(
                                'class' => 'category-ajax-filter-select'
                            )
                        ]);
                    }

                }
            );;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parcel::class,
        ]);
    }
}
