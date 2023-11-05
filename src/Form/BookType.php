<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Config\Monolog\HandlerConfig\PublisherConfig;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('category',ChoiceType::class,[
                'choices' => [
                    'Science-Fiction'=>'Science-Fiction',
                    'Mystery'=>'Mystery',
                    'Autobiography'=>'Autobiography',
                    'Romance'=>'Romance'
                ]
            ])
            ->add('publicationdate')
            ->add('publisher')
            ->add('author',EntityType::class, [
                'class'=>Author::class,
                'choice_label'=>'username',
                'multiple'=>false,
                'expanded'=>false
                ])
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
