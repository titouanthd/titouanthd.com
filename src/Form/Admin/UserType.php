<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('email', EmailType::class)
      ->add('pseudo', TextType::class)
      ->add('roles', ChoiceType::class, [
        'choices' => [
          'Admin' => 'ROLE_ADMIN',
          'User' => 'ROLE_USER',
        ],
        'autocomplete' => true,
      ]);

    // if we have an existing user, we don't want to change the password
    if ($options['data'] && $options['data']->getId() === null) {
      $builder->add('password', PasswordType::class, [
        'mapped' => true,
        'required' => true,
      ]);
    } else {
      $builder
        ->add('createdAt', DateTimeType::class, [
          'widget' => 'single_text',
          'disabled' => true,
        ])
        ->add('updatedAt', DateTimeType::class, [
          'widget' => 'single_text',
          'disabled' => true,
        ]);
    }

    // data transformer to convert between the string and array
    $builder->get('roles')
      ->addModelTransformer(new CallbackTransformer(
        fn (array $roles) => implode(', ', $roles),
        fn (string $roles) => explode(', ', $roles)
      ));
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => User::class,
    ]);
  }
}
