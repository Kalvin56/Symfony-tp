<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Titre de l\'article'
                ]
            ])
            ->add('author', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Auteur de l\'article'
                ]
            ])
            ->add('content', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Contenu de l\'article'
                ]
            ])
            ->add('category', null, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Catégorie de l\'article'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
