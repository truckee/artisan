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
                    'class' => 'AppBundle:Artist',
                    'label' => false,
                    'choice_label' => function ($artist, $key, $index) {
                        return $artist->getLastName() . ', ' . $artist->getFirstName();
                    },
                    'query_builder' => function (EntityRepository $er) use ($target, $notId, $show) {
                        if ('replacement' === $target) {
                            return $er->createQueryBuilder('a')
                                ->where('a <> :notId')
                                ->setParameter('notId', $notId)
                                ->orderBy('a.firstName', 'ASC')
                                ->orderBy('a.lastName', 'ASC');
                        }
                        if ('block' === $target || 'tickets' === $target) {
                            return $er->createQueryBuilder('a')
                            ->join('a.shows', 's')
                            ->where('s.show = ?1')
                            ->orderBy('a.firstName')
                            ->orderBy('a.lastName')
                            ->setParameter(1, $show->getShow());
                        }
                        return $er->createQueryBuilder('a')
                            ->orderBy('a.firstName', 'ASC')
                            ->orderBy('a.lastName', 'ASC');
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
