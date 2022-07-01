<?php

namespace App\Form;

use App\Entity\Property;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('url')
            ->add('adresse')
            ->add('code')
            ->add('ville')
            ->add('lieu')
            ->add('title')
            ->add('description')
            ->add('prix')
            ->add('id_categorie')
            ->add('image',FileType::class, ["mapped"=>false,'data_class'=>null,'label'=> 'ticket', 'required' => false])
            ->add('dateAchat')
            ->add('dateGarantie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            
        ]);
    }
}
