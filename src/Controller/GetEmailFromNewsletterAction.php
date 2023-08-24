<?php

namespace Dotit\SyliusNewsletterPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Dotit\SyliusNewsletterPlugin\Entity\NewsletterSubscription;
use Dotit\SyliusNewsletterPlugin\Responder\GetEmailFromNewsletterResponder;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetEmailFromNewsletterAction
{
    private EntityManagerInterface $entityManager;
    private UserContextInterface $userContext;
    private GetEmailFromNewsletterResponder $responder;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserContextInterface $userContext,
        GetEmailFromNewsletterResponder $responder,
    )
    {
        $this->entityManager = $entityManager;
        $this->userContext = $userContext;
        $this->responder = $responder;
    }

    public function __invoke(Request $request): Response
    {
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

        $result['id'] = $newsletterSubscription->getId();
        $result['email'] = $newsletterSubscription->getEmail();
        $result['shopUser']['id'] = $newsletterSubscription->getShopUser()->getId();
        $result['shopUser']['email'] = $newsletterSubscription->getShopUser()->getUsername();

        // Return a response with the updated CompareProduct
        return $this->responder->createResponse($result);
    }
}
