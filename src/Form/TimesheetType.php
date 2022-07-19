<?php

namespace App\Form;

use App\Entity\Project;
use App\Entity\ProjectTimesheet;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TimesheetType extends AbstractType
{
    private TokenStorageInterface $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

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
                'class' => Project::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->leftJoin('p.users', 'pu')
                        ->where('pu.id IN (:users)')
                        ->orderBy('p.name', 'ASC')
                        ->setParameter('users', $this->tokenStorage->getToken()->getUser())
                        ;
                },
                'group_by' => 'client',
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
