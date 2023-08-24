<?php

namespace Dotit\SyliusNewsletterPlugin\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Dotit\SyliusNewsletterPlugin\Entity\NewsletterSubscription;

class NewsletterSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsletterSubscription::class);
    }

    public function findAllEmails(): array
    {
        return $this->createQueryBuilder('ns')
            ->select('ns.email')
            ->getQuery()
            ->getArrayResult();
    }
}
