<?php

namespace App\Form;

use App\Entity\Partie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class InitPartieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('passe')
            ->add('present')
            ->add('futur')
            ->add('je')
            ->add('tu')
            ->add('il')
            ->add('nous')
            ->add('vous')
            ->add('ils')
            ->add('affirmation')
            ->add('question')
            ->add('negation')
            ->add('Suivant', SubmitType::class)        ;


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Partie::class,
        ]);
    }
}
