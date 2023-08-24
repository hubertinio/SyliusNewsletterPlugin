<?php

namespace Dotit\SyliusNewsletterPlugin\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Dotit\SyliusNewsletterPlugin\Controller\AddEmailToNewsletterAction;
use Dotit\SyliusNewsletterPlugin\Controller\GetEmailFromNewsletterAction;
use Dotit\SyliusNewsletterPlugin\Controller\RemoveEmailFromNewsletterAction;
use Dotit\SyliusNewsletterPlugin\Controller\UpdateEmailFromNewsletterAction;
use Dotit\SyliusNewsletterPlugin\Responder\GetEmailFromNewsletterResponder;
use Sylius\Component\Core\Model\ShopUser;
use Sylius\Component\Core\Model\ShopUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "dotit_newsletter_subscriptions")]
#[ApiResource(
    collectionOperations: [
        'add_email_to_newsletter' => [
            'method' => 'POST',
            'path' => '/shop/newsletter-subscriptions/add-email-to-newsletter/',
            'controller' => AddEmailToNewsletterAction::class,
            'openapi_context' => [
                'summary' => 'Add email to newsletter',
                'parameters' => [],
                'responses' => [
                    201 => [
                        'description' => 'Email added successfully to newsletter',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'email' => ['type' => 'string'],
                                        'shopUser' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'email' => ['type' => 'string'],
                                            ],
                                        ],
                                    ],
                                ],
                                'example' => [
                                    'id' => 35,
                                    'email' => 'newsletter@dotit.com',
                                    'shopUser' => [
                                        'id' => 3,
                                        'email' => 'sylius@dotit.com',
                                    ],
                                ],
                            ]
                        ]
                    ],
                    404 => [
                        'description' => 'Email not provided'
                    ],
                    403 => [
                        'description' => 'Email already in use'
                    ],
                ],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'email' => 'email@dotit.com'
                            ]
                        ]
                    ]
                ],
            ]
        ],
        'update_email_from_newsletter' => [
            'method' => 'PATCH',
            'path' => '/shop/newsletter-subscriptions/update-email-from-newsletter/',
            'controller' => UpdateEmailFromNewsletterAction::class,
            'openapi_context' => [
                'summary' => 'Update email from newsletter',
                'parameters' => [],
                'responses' => [
                    201 => [
                        'description' => 'Email updated successfully from newsletter',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'email' => ['type' => 'string'],
                                        'shopUser' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'email' => ['type' => 'string'],
                                            ],
                                        ],
                                    ],
                                ],
                                'example' => [
                                    'id' => 35,
                                    'email' => 'newsletter@dotit.com',
                                    'shopUser' => [
                                        'id' => 3,
                                        'email' => 'sylius@dotit.com',
                                    ],
                                ],
                            ]
                        ]
                    ],
                    404 => [
                        'description' => 'Email not provided'
                    ],
                    403 => [
                        'description' => 'Email already in use'
                    ],
                ],
                'requestBody' => [
                    'content' => [
                        'application/merge-patch+json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'email' => 'email@dotit.com'
                            ]
                        ]
                    ]
                ],
            ]
        ],
        'get_email_from_newsletter' => [
            'method' => 'GET',
            'path' => '/shop/compare-products/get-email-from-newsletter/',
            'controller' => GetEmailFromNewsletterAction::class,
            'output' => GetEmailFromNewsletterResponder::class,
            'openapi_context' => [
                'summary' => 'Retrieve the email for user',
                'parameters' => [],
                'responses' => [
                    200 => [
                        'description' => 'Retrieve the email for user',
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'id' => ['type' => 'integer'],
                                        'email' => ['type' => 'string'],
                                        'shopUser' => [
                                            'type' => 'object',
                                            'properties' => [
                                                'id' => ['type' => 'integer'],
                                                'email' => ['type' => 'string'],
                                            ],
                                        ],
                                    ],
                                ],
                                'example' => [
                                    'id' => 35,
                                    'email' => 'newsletter@dotit.com',
                                    'shopUser' => [
                                        'id' => 3,
                                        'email' => 'sylius@dotit.com',
                                    ],
                                ],
                            ]
                        ]
                    ],
                    404 => [
                        'description' => 'Newsletter not found'
                    ],
                    403 => [
                        'description' => 'Authentification required'
                    ],
                ]
            ]
        ],
        'remove_email_from_newsletter' => [
            'method' => 'DELETE',
            'path' => '/shop/newsletter-subscriptions/remove-email-from-newsletter/',
            'controller' => RemoveEmailFromNewsletterAction::class,
            'openapi_context' => [
                'summary' => 'Remove email from newsletter',
                'parameters' => [],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => ['type' => 'string']
                                ]
                            ],
                            'example' => [
                                'email' => 'email@dotit.com'
                            ]
                        ]
                    ]
                ],
            ]
        ]
    ],
    itemOperations:[],
    denormalizationContext: [
        'groups'=>['newsletter_subscriptions_write']
    ],
    normalizationContext: [
        'groups' => ['newsletter_subscriptions_read']
    ],
    paginationEnabled: false,
)]
#[UniqueEntity("email")]
class NewsletterSubscription implements NewsletterSubscriptionInterface
{
    #[ApiProperty(writable: false, identifier: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[Groups(['newsletter_subscriptions_write','newsletter_subscriptions_read'])]
    #[ORM\Column(type: "string", length: 255)]
    #[Assert\Email]
    private ?string $email = null;

    #[Groups(['newsletter_subscriptions_read'])]
    #[ORM\OneToOne(targetEntity: ShopUserInterface::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?ShopUser $shopUser;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getShopUser(): ?ShopUser
    {
        return $this->shopUser;
    }

    public function setShopUser(?ShopUserInterface $shopUser): self
    {
        $this->shopUser = $shopUser;

        return $this;
    }
}
