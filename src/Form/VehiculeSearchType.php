<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class VehiculeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, [
                'required' => false,
                'label' => 'Marque',
                'attr' => ['placeholder' => 'Rechercher par marque']
            ])
            ->add('prixMax', NumberType::class, [
                'required' => false,
                'label' => 'Prix maximum',
                'attr' => ['placeholder' => 'Exemple : 20000']
            ])
            ->add('disponible', ChoiceType::class, [
                'choices' => [
                    'Tous' => null,
                    'Disponibles' => true,
                    'Non disponibles' => false
                ],
                'required' => false,
                'label' => 'Disponibilité',
                'expanded' => false,
                'multiple' => false
            ])
            ->add('rechercher', SubmitType::class, [
                'label' => 'Rechercher',
                'attr' => ['class' => 'btn btn-primary']
            ]);
    }}