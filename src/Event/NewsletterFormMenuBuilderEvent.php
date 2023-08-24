<?php

declare(strict_types=1);

namespace Dotit\SyliusNewsletterPlugin\Event;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Dotit\SyliusNewsletterPlugin\Entity\NewsletterSubscriptionInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class NewsletterFormMenuBuilderEvent extends MenuBuilderEvent
{
    public function __construct(
        FactoryInterface $factory,
        ItemInterface $menu,
        private NewsletterSubscriptionInterface $newsletter
    ) {
        parent::__construct($factory, $menu);
    }

    public function getNewsletter(): NewsletterSubscriptionInterface
    {
        return $this->newsletter;
    }
}
