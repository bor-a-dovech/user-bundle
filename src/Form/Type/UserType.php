<?php

namespace Glavnivc\UserBundle\Form\Type;

use Glavnivc\UserBundle\Entity\Role;
use Glavnivc\UserBundle\Entity\User;
use Glavnivc\UserBundle\Repository\PermissionRepository;
use Glavnivc\UserBundle\Repository\RoleRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{

    public function __construct(
        RoleRepository $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Логин',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Логин',
                ],
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'required' => false,
                'attr' => [
                    'placeholder' => 'E-mail',
                ],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Роли',
                'choice_label' => function ($choice, $key, $value) {
                    return $choice->getName();
                },
                'choices' => $this->roleRepository->findAll(),
                'required' => false,
                'multiple' => true,
                'expanded' => false,
                'data' => $options['data']->getRole()->getValues(),
                'attr' => [
                    'data-select2-width' => '100%',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}