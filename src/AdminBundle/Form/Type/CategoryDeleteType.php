<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use BlogBundle\Entity\Category;


class CategoryDeleteType extends AbstractType {
    
   
    private $category;
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        
        $this->category = $options['$Category'];
        
        $builder
            ->add('setNull', CheckboxType::class, array(
                'label' => 'Ustaw wszystkie posty bez kategorii',
                
                'mapped' => false,
             
            ))
            ->add('newCategory', EntityType::class, array(
                'label' => 'Wybierz nową kategorię dla postów',
                'empty_data' => 'Wybierz kategorię',
                'class' => 'BlogBundle:Category',
                'choice_label' => 'name',
                'mapped' => false,
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                              ->where('c.id != :categoryId')
                              ->setParameter('categoryId', $this->category->getId());
                }
            ))
            ->add('submit', SubmitType::class , array(
                    'label' => 'Usuń kategorię'
            ));
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'BlogBundle\Entity\Category',
            '$Category' => null
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'blogbundle_delete_category';
    }
}
