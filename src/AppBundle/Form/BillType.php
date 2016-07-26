<?php

namespace AppBundle\Form;

use AppBundle\Entity\Partner;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options) {
        //array('partners'=> [new Partner(),new Partner()])
        $builder->add('partner', SelectPartnerType::class, $options)
            ->add('billname', TextType::class)
            ->add('billdescription', TextType::class)
            ->add('amount', MoneyType::class)
            ->add('billdate', DateTimeType::class)
            ->add('save', SubmitType::class, array('label' => 'Add the Bill'));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired('partners');
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Bill',
        ));
    }

}
