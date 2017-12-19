<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\ReceiptTicketType.php

namespace AppBundle\Form;

use AppBundle\Form\TicketType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ReceiptTicketType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salesDate', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'MM/dd/yyyy',
                ])
            ->add('receiptNo', HiddenType::class, [
                'data' => $options['next'],
                'label' => false,
                ])
            ->add('tickets', CollectionType::class, [
                'label' => false,
                'entry_type'   => TicketType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                ])
            ->add('save', SubmitType::class, array(
                'label' => 'Add receipt',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Receipt',
            'required' => false,
            'next' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'receipt';
    }
}
