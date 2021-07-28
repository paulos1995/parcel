<?php

namespace App\Form\Filter;

use App\Entity\Category;
use App\Entity\ParcelStatus;
use App\Repository\CategoryRepository;
use App\Repository\ParcelStatusRepository;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ParcelHandoverFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('barcodes', TextareaType::class, [
                'required'=>false,
                'attr'=> [
                    'rows'=>3
                ]
            ])
            ->add('weight', TextareaType::class, [
                'required'=>false
            ])
            ->add('size', TextareaType::class, [
                'required'=>false
            ])
            ->add('price', TextareaType::class, [
                'required'=>false,
            ])
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
                'required' => true,
                'placeholder' => '',
                'attr' => array(
                    'class' => 'category-ajax-filter-select'
                )
            ])
            ->add('statuses', EntityType::class, [
                'class' => 'App:ParcelStatus',
                'label' => 'Statuses',
                'query_builder' => function (ParcelStatusRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->orderBy('o.name', 'ASC')
                        ;
                },
                'choice_label' => function (ParcelStatus $letterStatus) {
                    return $letterStatus->getName();
                },
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'placeholder' => '',
                'attr' => array(
                    'class'=>''
                )
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
