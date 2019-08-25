<?php

namespace AppBundle\Form;

use AppBundle\Entity\Address;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('')
            ->setMethod('POST')
            ->add('city', TextType::class, ['label' => 'City'])
            ->add('street', TextType::class, ['label' => 'Street'])
            ->add('number', TextType::class, ['label' => 'Number'])
            ->add('save', SubmitType::class, ['label' => 'Add address']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            ['data_class' => Address::class]
        );
    }
}
