<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\BlockType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

//use Symfony\Component\Form\FormEvent;
//use Symfony\Component\Form\FormEvents;

/**
 * BlockType
 *
 */
class BlockType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lower', TextType::class,
                [
                    'label' => 'Lower end',
            ])
            ->add('upper', TextType::class,
                [
                'label' => 'Upper end'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Add block',
            ])
        ;
//
//        $builder->addEventListener(FormEvents::POST_SUBMIT , function(FormEvent $event) {
//            $form = $event->getForm();
//            $first = $form->get('first')->getData();
//            $last = $form->get('last')->getData();
//            $block = [$first, $last];
//            print_r($block);
//        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Block',
            'required' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'block';
    }
}
