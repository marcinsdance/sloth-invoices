<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('qty', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('value', null, array(
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'
                )))
            ->add('description', null, array(
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
            'data_class' => 'AppBundle\Entity\Item'
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
        return 'ItemType';
    }
}