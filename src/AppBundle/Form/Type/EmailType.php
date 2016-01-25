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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class EmailType extends AbstractType
{
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $email = $user->getEmail();
        if (!$user) {
            throw new \LogicException(
                'This form can\'t be used without an authenticated user.'
            );
        }

        $builder
            ->add('emailfrom', 'email', array(
                'attr' => array('value' => $email),
                'label' => 'Email From',
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('emailto', 'email', array(
                'attr' => array('placeholder' => 'recipient@example.com'),
                'label' => 'Email To',
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('subject', 'text', array(
                'attr' => array('value' => 'Invoice ' . $options['invoice_id']),
                'label_attr' => array('class'=>'form-control-label')
            ))
            ->add('message', 'textarea', array(
                'attr' => array(
                    'cols' => 60,
                    'rows' => 5
                ),
                'label_attr' => array('class'=>'form-control-label'),
                'data' => 'Please see invoice ' . $options['invoice_id'] . ' attached.'
            ))
            ->add('send', 'submit', array(
                'label' => 'Send',
                'attr' => array('class' => 'btn btn-primary')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $collectionConstraint = new Collection(array(
            'email' => array(
                new NotBlank(array('message' => 'Email should not be blank.')),
                new Email(array('message' => 'Invalid email address.'))
            )
        ));

        $resolver->setDefaults( array(
            'invoice_id' => ''
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
        return 'EmailType';
    }
}