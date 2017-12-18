<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\SelectReceiptType.php

namespace AppBundle\Form;

use AppBundle\Entity\Receipt;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SelectReceiptType
 *
 */
class SelectReceiptType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $show = $options['show'];
        $builder
            ->add('receipt', EntityType::class,
                [
                    'class' => 'AppBundle:Receipt',
                    'label' => false,
                    'choice_label' => function($receipt, $key, $index) {
                        return $receipt->getReceiptNo();
                    },
                    'choice_value' => function (Receipt $receipt = null) {
                        return $receipt ? $receipt->getReceiptNo() : '';
                    }, 'mapped' => false,
                    'query_builder' => function (EntityRepository $er) use($show) {
                        return $er->createQueryBuilder('r')
                            ->where('r.show = :show')
                            ->setParameter(':show', $show)
                            ->orderBy('r.receiptNo', 'ASC');
                    }
            ])
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Edit',
                    'label_format' => ['class' => 'text-bold']
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Receipt',
            'required' => false,
            'show' => null,
        ));
    }
}
