<?php

namespace Dotit\SyliusNewsletterPlugin\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Dotit\SyliusNewsletterPlugin\Repository\NewsletterSubscriptionRepository;
use League\Csv\Writer;

class NewsletterSubscriptionController extends ResourceController
{
    public function exportAction(NewsletterSubscriptionRepository $newsletterSubscriptionRepository): StreamedResponse
    {
        $emails = $newsletterSubscriptionRepository->findAllEmails();

        $response = new StreamedResponse(function() use ($emails) {
            $handle = fopen('php://output', 'w+');
            $csv = Writer::createFromStream($handle);

            $csv->insertOne(['Email']); // CSV header

            foreach ($emails as $email) {
                $csv->insertOne([$email['email']]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'newsletter_subscriptions.csv'
        ));

        return $response;
    }
}
