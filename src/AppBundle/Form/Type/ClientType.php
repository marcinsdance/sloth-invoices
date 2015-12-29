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
            ->add('company_name', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('contact_name', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('address','textarea', array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('telephone1', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('telephone2', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('email1', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('email2', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('language','locale', array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('currency','currency', array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('save', 'submit', array(
                'label' => 'Submit',
                'attr' => array('class' => 'btn btn-primary')
            ))
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