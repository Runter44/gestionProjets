<?php

namespace App\Form;

use App\Entity\Projet;
use App\Form\TacheProjetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
              'label' => 'Nom du projet',
              'label_attr' => array(
                'class' => 'col-sm-2 col-form-label',
              ),
              'attr' => array(
                'class' => 'col-sm-10 form-control',
                'maxlength' => '100',
                'placeholder' => 'Entrez le nom du projet',
              ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Projet::class,
        ]);
    }
}
