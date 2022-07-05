<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectTimesheet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TimesheetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('length', IntegerType::class, [
                'label' => 'Temps',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Temps', 'min' => 1, 'max' => 1440],
                'required' => true,
            ])
            ->add('project', EntityType::class, [
                'label' => 'Projet',
                'row_attr' => ['class' => 'form-floating'],
                'attr' => ['placeholder' => 'Projet'],
                'required' => true,
                'class' => Project::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProjectTimesheet::class,
        ]);
    }
}
