<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArtistType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $show = $options['show'];
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First name:',
                'label_attr' => ['style' => 'color: red;']
                ])
            ->add('lastName', TextType::class, [
                'label' => 'Last name:',
                'label_attr' => ['style' => 'color: red;']
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
                'label' => false,
            ])
            ->add('confirmed', CheckboxType::class, [
                'label' => false,
            ])
            ->add('tax_form', CheckboxType::class, [
                'label' => false,
            ])
            ->add(
                'save',
                SubmitType::class,
                array(
                    'label' => false,
                    'label_format' => ['class' => 'text-bold']
        )
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($show, $options) {
                $artist = $event->getData();
                $form = $event->getForm();
                $em = $options['entity_manager'];
                $isInShow = (null !== $artist->getId()) ? $em->getRepository('AppBundle:Artist')->isArtistInShow($show, $artist) : false;
                if (null !== $show) {
                    $form->add(
                    'inShow',
                    CheckboxType::class,
                    [
                        'label' => false,
                        'data' => $isInShow,
                        'mapped' => false
                ]
                );
                }
            }
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Artist',
            'required' => false,
            'show' => null,
            'validation_groups' => null,
        ));
        $resolver->setRequired('entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'artist';
    }
}
