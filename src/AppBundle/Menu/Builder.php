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
        $menu['Receipt']->addChild('Add receipt', [
            'route' => 'receipt_add'
        ]);
        $menu['Receipt']->addChild('Edit receipt', [
            'route' => 'receipt_edit'
        ]);

        $menu->addChild('Artist');
        $menu['Artist']->addChild('Add artist', [
            'route' => 'artist_add'
        ]);
        $menu['Artist']->addChild('Edit artist', [
            'route' => 'artist_edit'
        ]);
        $menu['Artist']->addChild('Add existing to show', [
            'route' => 'existing_artists'
        ]);

        $menu->addChild('Tickets');
        $menu['Tickets']->addChild('Add block', [
            'route' => 'block_add'
        ]);
        $menu['Tickets']->addChild('Edit block', [
            'route' => 'block_edit'
        ]);


        $menu->addChild('Show');
        $menu['Show']->addChild('Add show', [
            'route' => 'show_add'
        ]);
        $menu['Show']->addChild('Edit show', [
            'route' => 'show_edit'
        ]);

        $menu->addChild('Reports', [
            'route' => 'reports'
        ]);

        if ($checker->isGranted('ROLE_ADMIN')) {
            $menu->addChild('Admin');
            $menu['Admin']->addChild('Ticket block reassign', [
                'route' => 'block_reassign'
            ]);
            $menu['Admin']->addChild('Users', [
                'route' => 'easyadmin'
            ]);
            $menu['Admin']->addChild('Delete artist', [
                'route' => 'artist_delete'
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
