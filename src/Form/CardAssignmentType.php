<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\CardAssignment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardAssignmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('card', EntityType::class, [
                'class' => Card::class,
                'choices' => $options['available_cards'],
                'choice_label' => 'title',
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CardAssignment::class,
            'available_cards' => null
        ]);
    }
}
