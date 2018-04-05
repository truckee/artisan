<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\SelectArtistType.php

namespace AppBundle\Form;

use AppBundle\Entity\Artist;
use AppBundle\Entity\ArtistRepository;
use AppBundle\Services\Defaults;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * SelectArtist
 *
 */
class SelectArtistType extends AbstractType
{
    private $show;

    public function __construct(Defaults $defaults)
    {
        $this->show = $defaults->showDefault();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $target = $options['target'];
        $notId = $options['notId'];
        $blockId = $options['blockId'];
        $show = $this->show;
        $builder
            ->add(
                'artist', EntityType::class,
                [
                    'class' => Artist::class,
                    'label' => false,
                    'choice_label' => function ($artist, $key, $index) {
                        return $artist->getLastName() . ', ' . $artist->getFirstName();
                    },
                    'query_builder' => function (ArtistRepository $repo) use ($target, $notId, $show) {
                        if ('replacement' === $target) {
                            return $repo->getReplacableArtists($notId);
                        }
                        if ('block' === $target || 'tickets' === $target) {
                            return $repo->getBlockOrTickets($show);
                        }
                        if ('delete' === $target) {
                            return $repo->deleteableArtists();
                        }
                        return $repo->getAllSelectable();
                    },
                    'mapped' => false,
                ]
            )
            ->add(
                'blockId', HiddenType::class,
                [
                    'data' => $blockId,
                    'mapped' => false,
                ]
            )
            ->add(
                'save', SubmitType::class,
                array(
                    'label' => 'Select',
                    'label_format' => ['class' => 'text-bold']
                )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Artist',
            'required' => false,
            'target' => null,
            'notId' => null,
            'blockId' => null,
        ));
    }
}
