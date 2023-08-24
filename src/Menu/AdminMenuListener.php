<?php

declare(strict_types=1);

namespace Dotit\SyliusNewsletterPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        /** @var ItemInterface $item */
        $item = $menu->getChild('customers');
        if (null == $item) {
            $item = $menu;
        }

        $item->addChild('Newsletter', ['route' => 'dotit_sylius_newsletter_plugin_admin_newsletter_index'])
            ->setLabel('dotit_sylius_newsletter_plugin.menu.admin.newsletter')
            ->setLabelAttribute('icon', 'envelope')
        ;
    }
}
