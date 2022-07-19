<?php

namespace App\Form;

use App\Entity\UserJob;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserJobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('created_at')
            ->add('updated_at')
            ->add('deleted_at')
            ->add('created_by')
            ->add('updated_by')
            ->add('deleted_by')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserJob::class,
        ]);
    }
}
