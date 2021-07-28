<?php

namespace App\Form;

use App\Entity\Parcel;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\ParcelStatusRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ParcelOrderScanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parcel::class,
        ]);
    }
}
