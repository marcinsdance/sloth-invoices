<?php

namespace AppBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class InvoiceType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

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
            ->add('profile', null, array(
                'property' => 'name',
                'attr' => array('class'=>'form-control'),
                'label_attr' => array('class'=>'form-control-label'),
                'placeholder' => '- Choose Profile -'
            ))
            ->add('save', 'submit', array(
                'label' => 'Submit',
                'attr' => array('class' => 'btn btn-primary')
            ));

        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'This form can\'t be used without an authenticated user.'
            );
        }

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) use ($user) {
                $form = $event->getForm();
                $profilesOptions = array(
                    'class' => 'AppBundle\Entity\Profile',
                    'property' => 'name',
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        return $er->createQueryBuilder('profile')
                            ->where('profile.user = ' . $user->getId());
                    }
                );
                $clientsOptions = array(
                    'class' => 'AppBundle\Entity\Client',
                    'property' => 'company_name',
                    'query_builder' => function (EntityRepository $er) use ($user) {
                        return $er->createQueryBuilder('client')
                            ->where('client.user = ' . $user->getId());
                    }
                );
                $form->add('profile', 'entity', $profilesOptions);
                $form->add('client', 'entity', $clientsOptions);
        });
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