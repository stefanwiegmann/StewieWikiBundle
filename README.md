# Skeleton bundle for symfony bundle development

## Installation
`git clone git@github.com:stefanwiegmann/skeleton-bundle.git lib/stefanwiegmann/skeleton-bundle/`
### enable
```php
// config/bundles.php
    // ...
    Stewie\WikiBundle\StefanwiegmannSkeletonBundle::class => ['all' => true],
```

```php
// composer.json
    // ...
    "autoload-dev": {
        "psr-4": {
            "App\\Tests\\": "tests/",
            // ...
            "Stefanwiegmann\\SkeletonBundle\\": "lib/stefanwiegmann/skeleton-bundle/"
        }
    },
    // ...
```

```php
// config/services.yaml
    // ...
        # same for classes from /lib
        Stewie\WikiBundle\:
            resource: '../lib/stefanwiegmann/skeleton-bundle/*'
            exclude: '../lib/stefanwiegmann/skeleton-bundle/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}'

        # controllers are imported separately to make sure services can be injected
        # as action arguments even if you don't extend any base controller class
        Stewie\WikiBundle\Controller\:
            resource: '../lib/stefanwiegmann/skeleton-bundle/Controller'
            tags: ['controller.service_arguments']

```

`composer dump-autoload`
