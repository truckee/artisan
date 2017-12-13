<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\TicketType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ticket', TextType::class, [
            ])
            ->add('amount', NumberType::class, [
                'scale' => 2,
            ])
            ->add('artist', TextType::class,
                [
                'mapped' => false,
                'disabled' => true,
            ])
            ->add('save', SubmitType::class,
                [
                'label' => 'Save',
                'attr' => ['class' => 'btn-sm btn-info']
            ])
            ->add('delete', SubmitType::class,
                [
                'label' => 'Delete',
                'attr' => ['class' => 'btn-sm btn-warning']
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Ticket',
            'validation_groups' => [],
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ticket';
    }
}
