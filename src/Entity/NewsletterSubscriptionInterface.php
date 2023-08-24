<?php

namespace Dotit\SyliusNewsletterPlugin\Entity;

use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Core\Model\ShopUserInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface NewsletterSubscriptionInterface extends ResourceInterface
{
    public function getId(): ?int;
    public function getEmail(): ?string;
    public function setEmail(string $email): self;
    public function getShopUser(): ?ShopUser;
    public function setShopUser(?ShopUserInterface $shopUser): self;
}
