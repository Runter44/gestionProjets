<?php

namespace App\Form;

use App\Entity\TacheProjet;
use App\Entity\Tache;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class TacheProjetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tache', EntityType::class, array(
              'class' => Tache::class,
              'label' => 'Tâche',
              'label_attr' => array(
                'class' => 'd-none',
              ),
              'choice_label' => function ($tache, $key, $index) {
                return $tache->getName();
              },
              'group_by' => function ($tache, $key, $index) {
                return $tache->getCategorie()->getName();
              },
              'attr' => array(
                'class' => 'custom-select col-10 col-sm-4 mr-3',
              ),
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TacheProjet::class,
        ]);
    }
}
