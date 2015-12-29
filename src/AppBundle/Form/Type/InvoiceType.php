<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('date', null, array(
                'widget' => 'single_text',
                'attr' => array('class'=>'form-control datepicker'),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('client', null, array(
                'property' => 'company_name',
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'),
                'placeholder' => '- Choose Client -'
            ))
            ->add('save', 'submit', array(
                'label' => 'Submit',
                'attr' => array('class' => 'btn btn-primary')
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Invoice'
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
        return 'InvoiceType';
    }
}