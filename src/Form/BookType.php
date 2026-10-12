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
            ->add(
                'isbn',
                IntegerType::class,
                [
                    'label' => 'ISBN',
                ]
            )
            ->add(
                'title',
                TextType::class,
                [
                    'label' => 'Titre',
                ]
            )
            ->add(
                'author',
                TextType::class,
                [
                    'label' => 'Auteur.ice.s']
            )
            ->add(
                'coverUrl',
                TextType::class,
                [
                    'label' => 'Couverture']
            )
            ->add(
                'publisher',
                TextType::class,
                [
                    'label' => 'Éditeur']
            )
            ->add(
                'publicationDate',
                DateType::class,
                [
                    'label' => 'Date de publication']
            )
            ->add(
                'genre',
                ChoiceType::class,
                [
                    'label' => 'Genre',
                    'choices' => [
                        'Romance'             => 'Romance',
                        'Fantasy'             => 'Fantasy',
                        'Science-fiction'     => 'Science-Fiction',
                        'Policier/Thriller'   => 'Policier/Thriller',
                        'Historique'          => 'Historique',
                        'Littérature'         => 'Littérature',
                        'Jeunesse'            => 'Jeunesse',
                        'BD/Manga'            => 'Bd/Manga',
                        'Biographie'          => 'Biographie',
                        'Essai'               => 'Essai',
                        'Autre'               => 'Autre',
                    ],
                ]
            )
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'À lire'    => 'À lire',
                    'En cours'  => 'En cours',
                    'Lu'        => 'Lu',
                    'Abandonné' => 'Abandonné',
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
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Enregistrer',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
