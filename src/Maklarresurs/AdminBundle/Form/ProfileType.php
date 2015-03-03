<?php

namespace Maklarresurs\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('companyName', 'text', array(
                'label' => 'Company Name',
                'attr'  => array(
                    'placeholder' => 'Enter company Name...'
                )
            ))
            ->add('orgNr', 'text', array(
                'label' => 'Org Nr',
                'attr'  => array(
                    'placeholder' => 'Enter Org Nr...'
                )
            ))
            ->add('streetAddress', 'text', array(
                'label' => 'Street Address',
                'attr'  => array(
                    'placeholder' => 'Enter Street Address...'
                )
            ))
            ->add('billingAddress', 'text', array(
                'label' => 'Billing Address',
                'attr'  => array(
                    'placeholder' => 'Enter Billing Address...'
                )
            ))
            ->add('contactPerson', 'text', array(
                'label' => 'Contact Person',
                'attr'  => array(
                    'placeholder' => 'Enter Contact Person...'
                )
            ))
            ->add('telephoneNr', 'text', array(
                'label' => 'Telephone Nr',
                'attr'  => array(
                    'placeholder' => 'Enter Telephone Nr...'
                )
            ))
            ->add('website', 'text', array(
                'label' => 'Website',
                'attr'  => array(
                    'placeholder' => 'Enter Website Link..'
                )
            ))
            ->add('firstName', 'text', array(
                'label' => 'First Name',
                'attr'  => array(
                    'placeholder' => 'Enter first Name...'
                )
            ))
            ->add('lastName', 'text', array(
                'label' => 'Last Name',
                'attr'  => array(
                    'placeholder' => 'Enter Last Name...'
                )
            ))
            ->add('email', 'email', array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'attr'  => array(
                    'placeholder' => 'Enter your email address here...'
                )
            ))
            ->add('username', 'text', array(
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr'  => array(
                    'placeholder' => 'Enter a username...'
                )
            ))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array(
                    'label' => 'form.password',
                    'attr'  => array(
                        'placeholder' => 'Enter a Password...'
                    )
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation',
                    'attr'  => array(
                        'placeholder' => 'Confirm Password'
                    )
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))

        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Maklarresurs\UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'maklarresurs_adminbundle_user_profile';
    }
}
