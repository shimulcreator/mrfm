<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shimul
 * Date: 11/8/14
 * Time: 3:39 AM
 * To change this template use File | Settings | File Templates.
 */
namespace Maklarresurs\AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')

        ;
    }

    public function getName()
    {
        return 'maklarresursdocumenttype';
    }
}