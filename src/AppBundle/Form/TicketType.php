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

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'amount', TextType::class,
                [
                    'label' => 'Amount',
                    'label_attr' => ['style' => 'color: red;'],
                ]
            )
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($options) {
            $ticket = $event->getData();
            $form = $event->getForm();
            if (null !== $ticket) {
//                $form->add('rcptTotal', HiddenType::class, [
//                'data' => $options['rcptTotal'],
//                ]);
            }
        });


        $builder->addEventListener(FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
            $ticket = $event->getData();
            $form = $event->getForm();
            if (!$ticket || null === $ticket->getId()) {
                $form->add(
                    'ticket', TextType::class,
                    [
                        'label' => 'Ticket',
                        'label_attr' => ['style' => 'color: red;'],
                    ]
                );
                $form->add(
                    'artist', TextType::class,
                    [
                        'mapped' => false,
                        'disabled' => true,
                    ]
                );
            } else {
                $form->add('ticketDisplay', TextType::class,
                    [
                        'label' => 'Ticket',
                        'data' => $ticket->getTicket(),
                        'disabled' => true,
                        'mapped' => false,
                ]);
                $form->add(
                    'ticket', HiddenType::class,
                    [
                        'label' => 'Ticket',
                        'label_attr' => ['style' => 'color: red;'],
                    ]
                );
                $form->add(
                    'artist', TextType::class,
                    [
                        'data' => $ticket->getArtist()->getLastName() . ', ' . $ticket->getArtist()->getFirstName(),
                        'mapped' => false,
                        'disabled' => true,
                    ]
                );
                $form->add('rcptTotal', HiddenType::class, [
                'data' => $options['rcptTotal'],
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ticket::class,
            'validation_groups' => [],
            'required' => false,
            'rcptTotal' => null,
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
