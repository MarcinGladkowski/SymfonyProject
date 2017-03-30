<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostType extends AbstractType
{

    public function getName()
    {
        return 'post';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, array(
                'label' => 'Tytuł',
                'attr' => array(
                    'placeholder' => 'Tytuł'
                )
            ))
            ->add('slug', TextType::class, array(
                'label' => 'Alias',
                'attr' => array(
                    'placeholder' => 'Alias'
                )
            ))
//            ->add('content', 'ckeditor', array(
//                'label' => 'Treść'
//            ))
            ->add('content', TextType::class, array(
                'label' => 'Treść'
            ))
            ->add('thumbnail', FileType::class, array(
                'label' => 'Miniaturka'
            ))
            ->add('publishedDate', DateTimeType::class, array(
                'label' => 'Data publikacji',
                'date_widget' => 'single_text', //pokazać też bez
                'time_widget' => 'single_text'
            ))
            ->add('category', EntityType::class, array(
                'label' => 'Kategoria',
                'class' => 'BlogBundle\Entity\Category',
                'choice_label' => 'name',
//                'empty_value' => 'Wybierz kategorię'
            ))
            ->add('tags', EntityType::class, array(
                'label' => 'Tagi',
                'multiple' => true,
                'class' => 'BlogBundle\Entity\Tag',
                'choice_label' => 'name',
                'attr' => array(
                    'placeholder' => 'Dodaj tagi'
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Zapisz'
            ));
    }
    
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Post'
        ));
    }

}
