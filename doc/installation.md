## Installation
1. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
    Dotit\SyliusNewsletterPlugin\DotitSyliusNewsletterPlugin::class => ['all' => true],
];
```
2. Add route to the routes.yaml

```yaml
## config/routes.yaml

dotit_sylius_newsletter_plugin_admin:
    resource: "@DotitSyliusNewsletterPlugin/Resources/config/routing/admin.yaml"
    prefix: /admin
```
3. Add the config to the _sylius.yaml

```yaml
## config/packages/_sylius.yaml

- { resource: "@DotitSyliusNewsletterPlugin/Resources/config/config.yaml" }
```
