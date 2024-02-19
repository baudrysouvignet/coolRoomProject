<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Review;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('note', IntegerType::class, [
                'label' => 'La note',
                'attr' => [
                    'min' => 0,
                    'max' => 5
                ]
            ])
            ->add('description')
            ->add('moderated', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Suspendu' => 'suspended',
                    'Publique' => 'public'
                ],
                'multiple' => false,
                'expanded' => true,
                'data' => 'suspended'
            ])
            ->add('userReview', EntityType::class, [
                'label' => 'Utilisateurs',
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('priductReview', EntityType::class, [
                'label' => 'Le produit',
                'class' => Product::class,
                'choice_label' => 'title',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
