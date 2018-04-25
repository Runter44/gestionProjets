<?php

namespace App\Form;

use App\Entity\Tache;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TacheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
              'attr' => array(
                'class' => 'form-control col-sm-5 mr-3',
                'placeholder' => 'Nom de la tâche',
                'maxlength' => '100',
              ),
            ))
            ->add('categorie', EntityType::class, array(
              'class' => Categorie::class,
              'choice_label' => function ($category, $key, $index) {
                return $category->getName();
              },
              'attr' => array(
                'class' => 'custom-select col-sm-4',
              ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tache::class,
        ]);
    }
}
