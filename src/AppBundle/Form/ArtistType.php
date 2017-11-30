<?php

namespace AppBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
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
            ->add('vendor', CheckboxType::class, [
                'label' => 'Vendor? ',
            ])
            ->add('confirmed', CheckboxType::class, [
                'label' => 'Confirmed? ',
                'label_format' => ['class' => 'text-bold']
            ])
            ->add('tax_form', CheckboxType::class, [
                'label' => 'Tax form? ',
                'label_format' => ['class' => 'text-bold']
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
