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
use Symfony\Contracts\Translation\TranslatorInterface;
use Exception;

class AddEmailToNewsletterAction
{
    private EntityManagerInterface $entityManager;
    private UserContextInterface $userContext;
    private TranslatorInterface $translator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserContextInterface $userContext,
        TranslatorInterface $translator,
    ) {
        $this->entityManager = $entityManager;
        $this->userContext = $userContext;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $locale = 'en';

        try {
            $data = json_decode($request->getContent(), true);
            $email = $data['email'] ?? null;
            $locale = $data['locale'] ?? $locale;
            $locale = $data['locale'] = substr($locale, 0, 2);

            if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new NotFoundHttpException('dotit_sylius_newsletter_plugin.exception.email_not_found', null, Response::HTTP_NOT_FOUND);
            }

            $newsletterSubscription = $this->entityManager
                ->getRepository(NewsletterSubscription::class)
                ->findOneBy(['email' => $email]);

            if ($newsletterSubscription) {
                throw new AccessDeniedHttpException('dotit_sylius_newsletter_plugin.message.email_already_in_use', null, Response::HTTP_UNAUTHORIZED);
            }

            /** @var ShopUserInterface|null $user */
            $user = $this->userContext->getUser();

            if (null !== $user) {
                $newsletterSubscription = $this->entityManager
                    ->getRepository(NewsletterSubscription::class)
                    ->findOneBy(['shopUser' => $user]);

                if ($newsletterSubscription) {
                    throw new AccessDeniedHttpException('dotit_sylius_newsletter_plugin.message.email_already_in_use', null, Response::HTTP_UNAUTHORIZED);
                }
            }

            $newsletterSubscription = new NewsletterSubscription();
            $newsletterSubscription->setEmail($email);

            if ($user instanceof ShopUserInterface) {
                $newsletterSubscription->setShopUser($user);
            }

            $this->entityManager->persist($newsletterSubscription);
            $this->entityManager->flush();

            $data['id'] = $newsletterSubscription->getId();
            $data['email'] = $newsletterSubscription->getEmail();
            $data['message'] = $this->translator->trans('dotit_sylius_newsletter_plugin.message.subscribed', [], null, $locale);
            $data['data'] = $data; // debug;

            if (null !== $user) {
                $data['shopUser']['id'] = $newsletterSubscription->getShopUser()->getId();
                $data['shopUser']['email'] = $newsletterSubscription->getShopUser()->getUsername();
            }

            return new JsonResponse($data, Response::HTTP_CREATED);
        } catch (Exception $exception) {
            $message = $this->translator->trans($exception->getMessage(), [], null, $locale);

            return new JsonResponse(['message' => $message], $exception->getStatusCode());
        }
    }
}
