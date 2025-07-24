<?php

namespace App\Form;

use App\Entity\Board;
use App\Entity\Label;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ColorType;

class LabelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('color', ColorType::class, [
                'label' => 'Farbe auswählen',
            ])
            ->add('board', EntityType::class, [
                'class' => Board::class,
                'choices' => $options['available_boards'],
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Label::class,
            'available_boards' => null
        ]);
    }
}
