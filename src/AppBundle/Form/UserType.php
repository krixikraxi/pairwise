<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\PartnerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', TextType::class)
            ->add('description', TextType::class)
            ->add('partnerone', PartnerType::class)
            ->add('partnertwo', PartnerType::class)
            ->add('save', SubmitType::class, array('label' => 'User hinzufügen'));
    }
}