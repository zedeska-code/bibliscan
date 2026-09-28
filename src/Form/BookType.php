<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('isbn', IntegerType::class)
            ->add('title', TextType::class)
            ->add('author', TextType::class)
            ->add('coverUrl', TextType::class)
            ->add('publisher', TextType::class)
            ->add('publicationDate', DateType::class)
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    'Romance'             => 'romance',
                    'Fantasy'             => 'fantasy',
                    'Science-fiction'     => 'science-fiction',
                    'Policier/Thriller'   => 'policier/thriller',
                    'Historique'          => 'historique',
                    'Littérature'         => 'littérature',
                    'Jeunesse'            => 'jeunesse',
                    'BD/Manga'            => 'bd/manga',
                    'Biographie'          => 'biographie',
                    'Essai'               => 'essai',
                    'Autre'               => 'autre',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'À lire'              => 'à lire',
                    'En cours de lecture' => 'en cours',
                    'Lu'                  => 'lu',
                ],
            ])
            ->add(
                'rating',
                ChoiceType::class,
                [
                    'choices'
                    => [
                        '1'  => 1,
                        '2'  => 2,
                        '3'  => 3,
                        '4'  => 4,
                        '5'  => 5,
                    ],
                ]
            )
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
