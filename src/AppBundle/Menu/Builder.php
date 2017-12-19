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
        $checker = $this->container->get('security.authorization_checker');

        $menu = $factory->createItem('root');

        $menu->addChild('Home', array('route' => 'homepage'));

        $menu->addChild('Receipt');
        $menu['Receipt']->addChild('Add', [
            'route' => 'receipt_add'
        ]);
        $menu['Receipt']->addChild('Edit', [
            'route' => 'receipt_edit'
        ]);
        $menu['Receipt']->addChild('Single artist', [
                'route' => 'single_artist_tickets'
        ]);
        $menu['Receipt']->addChild('View', [
            'route' => 'receipt_view'
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

        $menu->addChild('Block');
        $menu['Block']->addChild('Add block', [
            'route' => 'block_add'
        ]);
        $menu['Block']->addChild('Edit block', [
            'route' => 'block_edit'
        ]);
        $menu['Block']->addChild('View blocks by artist', [
                'route' => 'blocks_by_artist'
        ]);
        $menu['Block']->addChild('View blocks by block', [
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
            'route' => 'show_summary'
        ]);

        if ($checker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Admin', [
                'route' => 'easyadmin'
            ]);
        }

        return $menu;
    }

    public function logoutMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('Log out', [
            'route' => 'fos_user_security_logout'
        ]);

        return $menu;
    }
}
