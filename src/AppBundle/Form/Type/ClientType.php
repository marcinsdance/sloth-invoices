<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company_name')
            ->add('contact_name', 'text', array('required' => false))
            ->add('address','textarea', array('required' => false))
            ->add('telephone1', 'text', array('required' => false))
            ->add('telephone2', 'text', array('required' => false))
            ->add('email1', 'text', array('required' => false))
            ->add('email2', 'text', array('required' => false))
            ->add('language','locale')
            ->add('currency','currency')
            ->add('save', 'submit', array('label' => 'Submit New Client'))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        // TODO: Implement getName() method.
        return 'ClientType';
    }
}