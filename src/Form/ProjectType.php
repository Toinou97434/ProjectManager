<?php

namespace App\Form;

use App\Entity\Client;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du projet',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => "Nom du projet", 'autocomplete' => 'off'],
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du projet',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => "Description du projet", 'autocomplete' => 'off', 'style' => 'min-height: 250px'],
                'required' => true,
            ])
            ->add('estimated_time', IntegerType::class, [
                'label' => 'Temps estimé',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Temps estimé', 'min' => 0],
                'required' => true,
            ])
            ->add('start_at', DateType::class, [
                'label' => 'Début le',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Début le'],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('forecast_at', DateType::class, [
                'label' => 'Fin prévue le',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Fin prévue le'],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('ended_at', DateType::class, [
                'label' => 'Fin le',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Fin le'],
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('client', EntityType::class, [
                'label' => 'Client',
                'attr' => ['placeholder' => 'Client'],
                'class' => Client::class,
                'autocomplete' => true,
            ])
            ->add('users', EntityType::class, [
                'label' => 'Membres',
                'class' => User::class,
                'multiple' => true,
                'required' => true,
                'autocomplete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
