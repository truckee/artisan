<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name:',
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name:',
            ])
            ->add('address', TextType::class, [
                'label' => 'Address:',
            ])
            ->add('city', TextType::class, [
                'label' => 'City: ',
            ])
            ->add('state', TextType::class, [
                'label' => 'State: ',
            ])
            ->add('zip', TextType::class, [
                'label' => 'Zip: ',
            ])
            ->add('email', TextType::class, [
                'label' => 'Email: ',
            ])
            ->add('phone', TextType::class, [
                'label' => 'Phone: ',
            ])
            ->add('dba', TextType::class, [
                'label' => 'DBA: ',
            ])
            ->add('tax_id', TextType::class, [
                'label' => 'Tax ID: ',
            ])
            ->add('vendor', ChoiceType::class, [
                'label' => 'Vendor?',
                'choices' => [
                    'Vendor?' => 1,
                    ],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
                ])
            ->add('confirmed', ChoiceType::class, [
                'label' => 'Confirmed?',
                'choices' => [
                    'Confirmed?' => 1,
                    ],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('tax_form', ChoiceType::class, [
                'label' => 'Tax form?',
                'choices' => [
                    'Tax form?' => 1
                    ],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Add artist',
                'label_format' => ['class' => 'text-bold']
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Artist',
            'required' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'artist';
    }
}
