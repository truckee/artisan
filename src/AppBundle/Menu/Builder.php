<?php
/*
 * This file is part of the UUFNN Artisan package.
 * 
 * (c) UUFNN
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//src\AppBundle\Menu\Builder.php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));

        $menu->addChild('Receipts');
            $menu['Receipts']->addChild('Add', [
                'route' => 'receipt_add'
                ]);
            $menu['Receipts']->addChild('Edit', [
                'route' => 'receipt_edit'
                ]);
            $menu['Receipts']->addChild('Single artist', [
                'route' => 'single_artist_tickets'
                ]);
            $menu['Receipts']->addChild('View', [
                'route' => 'receipts_view'
                ]);

        $menu->addChild('Artist');
            $menu['Artist']->addChild('Add', [
                'route' => 'artist_add'
                ]);
            $menu['Artist']->addChild('Edit', [
                'route' => 'artist_edit'
                ]);
            $menu['Artist']->addChild('Add existing', [
                'route' => 'existing_artists'
                ]);
            $menu['Artist']->addChild('In show', [
                'route' => 'show_view'
                ]);

        $menu->addChild('Ticket');
            $menu['Ticket']->addChild('Add block', [
                'route' => 'block_add'
                ]);
            $menu['Ticket']->addChild('Edit block', [
                'route' => 'block_edit'
                ]);
            $menu['Ticket']->addChild('View blocks by artist', [
                'route' => 'blocks_by_artist'
                ]);
            $menu['Ticket']->addChild('View blocks by block', [
                'route' => 'blocks_by_block'
                ]);

        $menu->addChild('Show');
            $menu['Show']->addChild('Add', [
                'route' => 'show_add'
            ]);
            $menu['Show']->addChild('Edit', [
                'route' => 'show_edit'
            ]);
            $menu['Show']->addChild('Summary', [
                'route' => 'all_artists_show_tickets'
            ]);

        return $menu;
    }
}