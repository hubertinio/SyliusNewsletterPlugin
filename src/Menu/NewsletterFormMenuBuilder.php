<?php

declare(strict_types=1);

namespace Dotit\SyliusNewsletterPlugin\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Dotit\SyliusNewsletterPlugin\Entity\NewsletterSubscriptionInterface;
use Dotit\SyliusNewsletterPlugin\Event\NewsletterFormMenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class NewsletterFormMenuBuilder
{
    public function __construct(
        private FactoryInterface $factory,
        private EventDispatcherInterface $eventDispatcher
    ) {
    }

    public function createMenu(array $options = []): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if (!array_key_exists('newsletter', $options) || !$options['newsletter'] instanceof NewsletterSubscriptionInterface) {
            return $menu;
        }

        /*$menu
            ->addChild('details')
            ->setAttribute('template', '@DotitSyliusNewsletterPlugin/Admin/Newsletter/Tab/_details.html.twig')
            ->setLabel('sylius.ui.details')
            ->setCurrent(true)
        ;

        $menu
            ->addChild('profile')
            ->setAttribute('template', '@DotitSyliusNewsletterPlugin/Admin/Newsletter/Tab/_profile.html.twig')
            ->setLabel('dotit_sylius_newsletter_plugin.ui.profile')
        ;

        $menu
            ->addChild('media')
            ->setAttribute('template', '@DotitSyliusNewsletterPlugin/Admin/Newsletter/Tab/_media.html.twig')
            ->setLabel('sylius.ui.media')
        ;*/

        $this->eventDispatcher->dispatch(
            new NewsletterFormMenuBuilderEvent($this->factory, $menu, $options['newsletter'])
        );

        return $menu;
    }
}
