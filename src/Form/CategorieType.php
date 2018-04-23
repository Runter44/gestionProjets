<?php

namespace App\Form;

use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
              'attr' => array(
                'class' => 'form-control col-sm-9 mr-3',
                'placeholder' => 'Nom de la catégorie',
                'maxlength' => '100',
              ),
            ))
            ->add('submit', SubmitType::class, array(
              'label' => 'Ajouter',
              'attr' => array(
                'class' => 'btn btn-success col-sm-2 px-2',
              ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
