<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\TicketSearchType.php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TicketSearchType
 *
 */
class TicketSearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ticket', TextType::class)
            ->add(
                'save', SubmitType::class,
                array(
                    'label' => 'Search',
                    'label_format' => ['class' => 'text-bold']
                )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ticket::class,
            'required' => false,
        ));
    }
}
