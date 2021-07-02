<?php

namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OperationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('operation_type', null, [
                "label" => "Type d'opération:",
            ])
            ->add('amount', null, [
                "label" => "Montant :",
            ])
            ->add('motif', null, [
                "label" => "Motif de l'opération :",
            ])
            ->add('enregistrer', SubmitType::class, [
                "attr" => ["class" => "bg-dark text-white"],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
