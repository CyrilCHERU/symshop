<?php

namespace App\Form;

use App\Entity\OrderInfo;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ShippingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => "Nom Complet"
            ])
            ->add('address1', TextType::class, [
                'label' => "Adresse principale"
            ])
            ->add('adress2', TextType::class, [
                'label' => "Complément d'adresse",
                'required' => false
            ])
            ->add('city', TextType::class, [
                'label' => "Ville"
            ])
            ->add('zipCode', TextType::class, [
                'label' => "Code Postal"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderInfo::class,
        ]);
    }
}
