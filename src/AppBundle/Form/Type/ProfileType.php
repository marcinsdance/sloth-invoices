<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('company_name', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('file', null, array(
                'attr' => array('class'=>'form-control'),
                'label' => 'Logo',
            ))
            ->add('company_registration_number', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('slogan', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('contact_name', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('footer_note', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('address','textarea', array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('telephone', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('email', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('bank_account', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('sort_code', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('bank_name', null, array(
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
            'data_class' => 'AppBundle\Entity\Profile'
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
        return 'ProfileType';
    }
}