<?php
/*
 * This file is part of the UUFNN Artisan package.
 *
 * (c) UUFNN
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Form\ShowArtistsType.php

namespace AppBundle\Form;

use AppBundle\Entity\Artist;
use AppBundle\Services\Defaults;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ShowArtistsType
 *
 */
class ShowArtistsType extends AbstractType
{
    private $show;

    public function __construct(Defaults $defaults)
    {
        $this->show = $defaults->showDefault();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $show = $this->show;
        $builder->add(
            'artists',
            EntityType::class,
                [
                    'class' => Artist::class,
                    'choice_label' => function ($artist, $key, $index) {
                        return $artist->getLastName() . ', ' . $artist->getFirstName();
                    },
                    'query_builder' => function (EntityRepository $er) use ($show) {
                        $qb = $er->createQueryBuilder('a');
                        $ids = $qb
                            ->select('a.id')
                            ->leftJoin('a.shows', 's')
                            ->where('s.show = ?1')
                            ->setParameter(1, $show->getShow())
                            ->getQuery()
                            ->getResult();
                        if (empty($id)) {
                            return $er->createQueryBuilder('a');
                        } else {
                            return $er->createQueryBuilder('a')
                                ->where($er->createQueryBuilder('a')->expr()->notIn('a.id', ':ids'))
                                ->setParameter(':ids', $ids);
                        }
                    },
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
        ));
    }

    public function getBlockPrefix()
    {
        return 'show_artists';
    }
}
