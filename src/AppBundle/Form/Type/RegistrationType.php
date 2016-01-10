<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Util\LegacyFormHelper;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', null, array(
            'label' => 'form.username',
            'translation_domain' => 'FOSUserBundle',
            'attr' => array('class' => 'form-control'),
            'label_attr' => array('class' => 'form-control-label')
        ))->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'), array(
            'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array(
                'label' => 'form.password',
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'form-control-label')
            ),
            'second_options' => array(
                'label' => 'form.password_confirmation',
                'attr' => array('class' => 'form-control'),
                'label_attr' => array('class' => 'form-control-label')
            ),
            'invalid_message' => 'fos_user.password.mismatch',
        ))->add('email', null, array(
            'label' => 'form.email',
            'translation_domain' => 'FOSUserBundle',
            'attr' => array('class' => 'form-control'),
            'label_attr' => array('class' => 'form-control-label')
        ));
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}