<?php

namespace Dotit\SyliusNewsletterPlugin\Responder;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class GetEmailFromNewsletterResponder
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createResponse(array $email): Response
    {
        // Serialize the data to JSON
        $json = $this->serializer->serialize($email, 'json');

        // Return a custom JSON response
        return new Response($json, Response::HTTP_OK);
    }
}
