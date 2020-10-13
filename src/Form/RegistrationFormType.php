<?php

namespace App\Form;

use App\Entity\Cities;
use App\Entity\Countries;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Users\UserService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    /**
     * @var UserService $userService
     */
    private $userService;

    /**
     * RegistrationFormType constructor.
     * @param $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('country', EntityType::class,
            [
                'required' => false,
                'class' => 'App\Entity\Countries',
                'placeholder' => 'Select country',
                'mapped' => true,
            ]
        );
        $builder->get('country')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                if ($form->getData() != null) {
                    $form = $event->getForm();
                    $form->getParent()->add('state', EntityType::class,
                        [
                            'required' => false,
                            'mapped' => true,
                            'class' => 'App\Entity\States',
                            'placeholder' => false,
                            'choices' => $form->getData()->getState()
                        ]);
                } else {
                    $form->getParent()->add('state', EntityType::class,
                        [
                            'required' => false,
                            'mapped' => true,
                            'class' => 'App\Entity\States',
                            'placeholder' => false,
                            'choices' => []
                        ]);
                }

            });
        $builder
            ->add('firstName', TextType::class,
                [
                    'required' => false,
                    'empty_data' => '',
                    'attr' => ['class' => 'not_number']
                ])
            ->add('lastName', TextType::class, ['required' => false, 'empty_data' => '', 'attr' => ['class' => 'not_number']])
            ->add('email', TextType::class, ['required' => false, 'empty_data' => '',])
            ->add('phone', TextType::class, ['required' => false,
                'attr' => ['class' => 'not_word'], 'empty_data' => '', 'mapped' => true])
            ->add('state', ChoiceType::class,
                [
                    'placeholder' => '------------------------',
                    'required' => false,
                    'mapped' => false,
                ])
            ->add('cityTown', TextType::class,
                [
                    'required' => false,
                    'empty_data' => '',
                    'attr' => ['class' => 'not_number']
                ]
            )
            ->add('address', TextType::class, ['required' => false, 'empty_data' => '',])
            ->add('postCode', TextType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'This ( Postcode ) should not be blank',
                        ]),
                    ],
                    'empty_data' =>  false,
                    'attr' => ['class' => 'not_word']
                ])
            ->add('plainPassword', RepeatedType::class,
                [
                    'required' => false,
                    'mapped' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            // max length allowed by Symfony for security reasons
                            'max' => 4096,
                        ]),
                    ],
                    'type' => PasswordType::class,
                    'invalid_message' => 'The ( Passwords ) do not match.',
                    'first_options' => array('label' => 'Password'),
                    'second_options' => array('label' => 'Confirm Password')
                ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'attr' => ['id' => 'agreeTerms'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('avatar', FileType::class, array(
                'data_class' => null,
                'required' => false,
                'mapped' => false
            ));
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
                $state = $data->getState();
                if ($state) {
                    $form->get('country')->setData($state->getCountry());
                    $form->add('state', EntityType::class,
                        [
                            'required' => false,
                            'placeholder' => false,
                            'mapped' => false,
                            'attr' => ['class' => 'demo'],
                            'class' => 'App\Entity\States',
                            'choices' => $state->getCountry()->getState()
                        ]);
                } else {
                    $form->add('state', EntityType::class,
                        [
                            'required' => false,
                            'placeholder' => false,
                            'mapped' => false,
                            'class' => 'App\Entity\States',
                            'choices' => []
                        ]);
                }
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'empty_data' => '',
        ]);
    }
}
