<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\AddExistingArtistsType.php

namespace AppBundle\Form;

use AppBundle\Entity\Artist;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * AddExistingArtistsType
 *
 */
class AddExistingArtistsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $show = $options['show'];
        $qbA = $options['query_bulider'];
        $builder->add(
            'artists',
            EntityType::class,
                [
                    'class' => Artist::class,
                    'choice_label' => function ($artist, $key, $index) {
                        return $artist->getLastName() . ', ' . $artist->getFirstName();
                    },
                    'query_builder' => $qbA,
                    'expanded' => true,
                    'multiple' => true,
            ]
        )
            ->add(
                'save',
                SubmitType::class,
                array(
                    'label' => 'Add artist(s)',
                    'label_format' => ['class' => 'text-bold']
        )
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Show',
            'required' => false,
            'show' => null,
            'query_bulider' => null,
        ));
    }

    public function getBlockPrefix()
    {
        return 'show_artists';
    }
}
