<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Property;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            
           
            ->add('lieu', ChoiceType::class,[
               'choices'=>[
                'en ligne'=>1,
                'en magasin' =>2]
                  ]) 
                
            ->add('url')
            ->add('adresse')    
            ->add('code',TextType::class,[
                'label' => 'code postal',
                'required' => false
            ])
            ->add('ville')        
            ->add('title')
            ->add('description')
            ->add('prix')
            ->add('category',EntityType::class,[
                'class' => Category::class,
                'label' => 'catégorie',
               /* 'choices' => [
                    'embalage' => 1,
                    'ensasage' => 2,
                    'remplissage' => 3,
                    'scellage' => 4
                ]*/
            ])
            ->add('image',FileType::class, ["mapped"=>false,'data_class'=>null,'label'=> 'ticket', 'required' => false])
            ->add('dateAchat')
            ->add('dateGarantie');
        
       
                   
                 
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            
        ]);
    }
}
