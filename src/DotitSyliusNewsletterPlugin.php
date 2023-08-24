<?php

declare(strict_types=1);

namespace Dotit\SyliusNewsletterPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class DotitSyliusNewsletterPlugin extends Bundle
{
    use SyliusPluginTrait;
}
