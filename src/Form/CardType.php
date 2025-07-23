<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Lane;
use App\Entity\User;
use App\Entity\Board;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('position')
            ->add('lane', EntityType::class, [
                'class' => Lane::class,
                'choices' => $options['available_lanes'],
                'choice_label' => function (Lane $lane) {
                    return sprintf('%s (%s)', $lane->getTitle(), $lane->getBoard()->getName());
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
            'available_lanes' => null
        ]);
    }
}
