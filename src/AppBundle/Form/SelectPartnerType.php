<?php
namespace AppBundle\Form;

use AppBundle\Entity\Partner;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectPartnerType extends AbstractType
{
    //todo what is best practice for data here? need the two partners here
    protected $partners;

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $this->partners = $options['partners'];

        $builder->add('partner', ChoiceType::class, [
                'choices' => $this->partners,
                'choice_label' => function($partner, $key, $index) {
                    /** @var Partner $partner */
                    return $partner->getPartnername();
                },
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('select', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setRequired('partners');
    }
}