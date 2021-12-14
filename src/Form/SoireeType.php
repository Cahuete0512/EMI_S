<?php

namespace App\Form;

use App\Entity\Soiree;
use App\Form\ParticipantType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoireeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class , ['attr' => ['placeholder' => "nom de la soirée"]])
            ->add('lieu', TextType::class , ['attr' => ['placeholder' => "lieu de la soirée"]])
            ->add('date', DateType::class)
            ->add("ok", SubmitType::class, ["label"=>"OK"])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Soiree::class,
        ]);
    }

}