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
              'attr' => array(
                'class' => 'form-control',
                'maxlength' => '100',
                'placeholder' => 'Entrez le nom du projet',
              ),
            ))
            ->add('tacheProjets', CollectionType::class, array(
              'label' => 'Tâches du projet',
              'label_attr' => array(
                'class' => 'form-label d-none',
              ),
              'entry_type' => TacheProjetType::class,
              'allow_add' => true,
              'allow_delete' => true,
              'by_reference' => false,
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
