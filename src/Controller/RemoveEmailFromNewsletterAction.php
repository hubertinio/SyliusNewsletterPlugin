<?php
namespace Dotit\SyliusNewsletterPlugin\Controller;

use Dotit\SyliusNewsletterPlugin\Entity\NewsletterSubscription;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RemoveEmailFromNewsletterAction
{
    private EntityManagerInterface $entityManager;
    private UserContextInterface $userContext;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserContextInterface $userContext,
    ) {
        $this->entityManager = $entityManager;
        $this->userContext = $userContext;
    }

    public function __invoke(Request $request): Response
    {
        // Get the email from the request
        $email = json_decode($request->getContent(), true)['email'] ?? null;

        if (!$email) {
            throw new \InvalidArgumentException('Email not provided.');
        }

        // Find the newsletter subscription entity by email
        $newsletterSubscription = $this->entityManager
            ->getRepository(NewsletterSubscription::class)
            ->findOneBy(['email' => $email]);

        if (!$newsletterSubscription) {
            throw new NotFoundHttpException('Newsletter subscription not found.', null, Response::HTTP_NOT_FOUND);
        }

        /** @var ShopUserInterface|null $user */
        $user = $this->userContext->getUser();

        if ($user instanceof ShopUserInterface && $newsletterSubscription->getShopUser()!==$user) {
            throw new AccessDeniedHttpException('You are not allowed to remove this mail.', null, Response::HTTP_UNAUTHORIZED);
        }

        if ($user===null && $newsletterSubscription->getShopUser()!==null) {
            throw new AccessDeniedHttpException('Authentification required.', null, Response::HTTP_UNAUTHORIZED);
        }

        // Remove the entity from the database
        $this->entityManager->remove($newsletterSubscription);
        $this->entityManager->flush();

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
