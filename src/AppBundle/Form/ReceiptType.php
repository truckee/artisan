<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\ReceiptType.php

namespace AppBundle\Form;

use AppBundle\Entity\Receipt;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReceiptType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salesDate', DateType::class,
                [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => ['class' => 'js-datepicker'],
                'format' => 'MM/dd/yyyy',
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Close',
            ))
            ->add('view', SubmitType::class, array(
                'label' => 'Close & view',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Receipt::class,
            'required' => false,
            'save_label' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'receipt';
    }
}
