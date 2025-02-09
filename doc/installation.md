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
    prefix: '/%sylius_admin.path_name%'
```
3. Add the config to the _sylius.yaml

```yaml
## config/packages/_sylius.yaml

- { resource: "@DotitSyliusNewsletterPlugin/Resources/config/config.yaml" }
```

4. Add new entries to your `webpack.config.js`
```js
// ./webpack.config.js

// Shop config
    .addEntry('dotit-newsletter-shop', 'vendor/dotit/sylius-newsletter-plugin/src/Resources/assets/shop/entry.js')

```

5. Add scripts functions to your templates

```yaml
sylius_ui:
    events:
        sylius.shop.layout.javascripts:
            blocks:
                sylius_shop:
                    template: "@DotitSyliusNewsletterPlugin/Shop/_scripts.html.twig"
                    priority: 10
        
```