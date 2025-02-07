<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Topic;
use App\Entity\Label;
use App\Form\DataTransformer\LabelTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('headline', TextType::class, ['label' => 'Title'])
            ->add('summary', TextType::class, ['label' => 'Description', 'required' => false])
            ->add('content', TextareaType::class, ['label' => 'Text', 'required' => false])
            ->add('category', EntityType::class, [
                'class' => Topic::class,
                'choice_label' => 'label',
                'placeholder' => 'Select Category',
                'required' => false,
                'empty_data' => null,
            ])
            ->add('tags', EntityType::class, [
                'class' => Label::class,
                'choice_label' => 'title',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'label' => 'Tags',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
