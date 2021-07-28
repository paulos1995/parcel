<?php

namespace App\Form\Filter;

use App\Entity\ParcelStatus;
use App\Repository\ParcelStatusRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParcelUserFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['required'=>false])
            ->add('barcodeNumber', TextType::class, ['required'=>false])
            ->add('weight', IntegerType::class, ['required'=>false])
            ->add('size', IntegerType::class, ['required'=>false])
            ->add('price', IntegerType::class, ['required'=>false])
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
