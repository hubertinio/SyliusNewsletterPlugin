<?php

namespace Dotit\SyliusNewsletterPlugin\Controller;

use Dotit\SyliusNewsletterPlugin\Entity\NewsletterSubscription;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UpdateEmailFromNewsletterAction
{
    private EntityManagerInterface $entityManager;
    private UserContextInterface $userContext;

    public function __construct
    (
        EntityManagerInterface $entityManager,
        UserContextInterface $userContext,
    )
    {
        $this->entityManager = $entityManager;
        $this->userContext = $userContext;
    }

    public function __invoke(Request $request): JsonResponse
    {
        // Get the email from the request
        $email = json_decode($request->getContent(), true)['email'] ?? null;

        if (!$email) {
            throw new NotFoundHttpException('Email not provided.', null, Response::HTTP_NOT_FOUND);
        }

        // Find the newsletter subscription entity by email
        $newsletterSubscription = $this->entityManager
            ->getRepository(NewsletterSubscription::class)
            ->findOneBy(['email' => $email]);

        if ($newsletterSubscription) {
            throw new AccessDeniedHttpException('Email already in use.', null, Response::HTTP_UNAUTHORIZED);
        }

        /** @var ShopUserInterface|null $user */
        $user = $this->userContext->getUser();

        if (!$user) {
            throw new AccessDeniedHttpException('Authentification required.', null, Response::HTTP_UNAUTHORIZED);
        }

        // Find the newsletter subscription entity by email
        $newsletterSubscription = $this->entityManager
            ->getRepository(NewsletterSubscription::class)
            ->findOneBy(['shopUser' => $user]);

        if (!$newsletterSubscription) {
            throw new NotFoundHttpException('Newsletter subscription not found.', null, Response::HTTP_NOT_FOUND);
        }

        $newsletterSubscription->setEmail($email);

        // Persist the entity to the database
        $this->entityManager->persist($newsletterSubscription);
        $this->entityManager->flush();

        $data['id'] = $newsletterSubscription->getId();
        $data['email'] = $newsletterSubscription->getEmail();
        $data['shopUser']['id'] = $newsletterSubscription->getShopUser()->getId();
        $data['shopUser']['email'] = $newsletterSubscription->getShopUser()->getUsername();

        // Return JsonResponse with $data array
        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}
