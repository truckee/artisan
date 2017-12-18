<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\SelectShowType.php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SelectShow
 *
 */
class SelectShowType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('show', EntityType::class,
                [
                    'class' => 'AppBundle:Show',
                    'label' => false,
                    'choice_label' => function($show, $key, $index) {
                        return $show->getShow();
                    },
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('s')
                            ->orderBy('s.show', 'ASC');
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
            'data_class' => 'AppBundle\Entity\Show',
            'required' => false,
        ));
    }
}
