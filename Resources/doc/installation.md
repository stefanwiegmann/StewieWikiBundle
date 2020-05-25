Installation
============

## Install and enable the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require stewie/wiki-bundle
```

The command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Then, if not using Flex, enable the bundle by adding it to the list of registered bundles
in the `config/bundles.yaml` file of your project:

```php
// config/bundles.yaml

// ...
return [
            // ...
            Stewie\UserBundle\StewieWikiBundle::class => ['all' => true],
];
```

Make sure to update your database:

```console
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

## And next?
[Setup](setup.md)
