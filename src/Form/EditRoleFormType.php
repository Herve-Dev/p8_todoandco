<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EditRoleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('username')
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles :',
                'label_attr' => [
                    'class' => 'form-check-label',
                ],
                'multiple' => true,
                'expanded' => false, 
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ],
                'attr' => [
                    'class' => 'form-select' 
                ],
                
            ])
            //->add('email')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
