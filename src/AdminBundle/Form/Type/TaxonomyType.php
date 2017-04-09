<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;



class TaxonomyType extends AbstractType
{

    public function getName()
    {
        return 'taxonomy';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Tytuł'
            ))
            ->add('slug', TextType::class, array(
                'label' => 'Alias'
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Zapisz'
            ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\AbstractTaxonomy'
        ));
    }
}
