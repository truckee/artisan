<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\SelectBlockType.php

namespace AppBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SelectBlockType
 *
 */
class SelectBlockType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $artist = $options['artist'];
        $show = $options['show'];
        $builder
            ->add('block', EntityType::class,
                [
                    'class' => 'AppBundle:Block',
                    'label' => 'Select block',
                    'choice_label' => function($block, $key, $index) {
                        return $block->getLower() . ' to ' . $block->getUpper();
                    },
                    'query_builder' => function (EntityRepository $er) use($artist, $show) {
                        return $er->createQueryBuilder('b')
                            ->where('b.artist = :artist')
                            ->andWhere('b.show = :show')
                            ->setParameter(':artist', $artist)
                            ->setParameter(':show', $show)
                            ->orderBy('b.lower', 'ASC');
                    },
                    'mapped' => false,
            ])
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Select',
                    'label_format' => ['class' => 'text-bold']
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Block',
            'required' => false,
            'artist' => null,
            'show' => null,
        ));
//        $resolver->setRequired('entity_manager');
    }
}
