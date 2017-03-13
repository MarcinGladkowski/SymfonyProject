<?php

namespace UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePasswordType extends AbstractType
{   
 
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('currentPassword', PasswordType::class, array(
                     'label' => 'Aktualne hasło',
                     'mapped' => false,
                     'constraints' => array(
                         new UserPassword(array(
                                 'message' => 'Podano błędne aktualne hasło użytkownika'   
                     )
                 ))))
                 ->add('plainPassword', RepeatedType::class, array(
                     'type' => PasswordType::class,
                      'label' => 'password',
                      'first_options'  => array('label' => 'Nowe hasło'),
                      'second_options' => array('label' => 'Powtórz hasło'),
                 ))
                 ->add('save', SubmitType::class, array(
                        'label' => 'Zmień hasło'
                 ));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User',
            'validation_groups' => array('Default', 'ChangePassword')
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'changePassword';
    }

}
