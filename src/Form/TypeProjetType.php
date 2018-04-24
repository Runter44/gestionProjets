<?php

namespace App\Form;

use App\Entity\TypeProjet;
use App\Form\TacheProjetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class TypeProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, array(
          'label' => 'Nom du type de projet',
          'label_attr' => array(
            'class' => 'col-sm-2 col-form-label',
          ),
          'attr' => array(
            'class' => 'col-sm-10 form-control',
            'maxlength' => '100',
            'placeholder' => 'Entrez le nom du type de projet',
          ),
        ))
        ->add('tacheTypes', CollectionType::class, array(
          'label' => 'Tâches du type de projet',
          'label_attr' => array(
            'class' => 'form-label d-none',
          ),
          'entry_type' => TypeTacheType::class,
          'allow_add' => true,
          'allow_delete' => true,
          'by_reference' => false,
        ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeProjet::class,
        ]);
    }
}
