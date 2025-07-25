<?php

namespace App\Form;

use App\Entity\Board;
use App\Entity\Lane;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LaneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('position');
            if (!is_null($options['available_boards'])) {
            $builder
            ->add('board', EntityType::class, [
                'class' => Board::class,
                'choices' => $options['available_boards'],
                'choice_label' => 'name',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lane::class,
            'available_boards' => null
        ]);
    }
}
