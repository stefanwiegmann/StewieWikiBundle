Setup
=====

## Bundle Configuration

### Automatic Configuration

Open a command console, enter your project directory and execute the
following command to copy over bundle configuration:

```console
$ bin/console stewie:wiki:configure
```

Pick which part to auto-configure. This will create `config/packages/stewie-wiki.yaml`, `config/routes/stewie-wiki.yaml`, add lines to `config/services.yaml` and add folders under `var/stewie/wiki-bundle/...` for attachements. Make sure to review these files and locations. `

### Manual Configuration

#### Add minimum security configuration

```php
// config/security.yaml
security:
    # ...

    # add some sane inheritance for logged in users without any groups assigned
    role_hierarchy:
        ROLE_USER: [[...], ROLE_WIKI_VIEW]
        # ...

    # make sure to leave login accessible for anybody (at least)
    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        # ...
        - { path: ^/wiki, roles: ROLE_WIKI_VIEW }
        # ...
# ...
```

#### Register services for the bundle in the `config/services.yaml` file of your project

```php
// config/services.yaml

// ...
services:

// ...

###> stewie/wiki-bundle ###
    Stewie\WikiBundle\:
        resource: '@StewieWikiBundle/*/*'
        exclude: '@StewieWikiBundle/{Entity}'
        tags: ['controller.service_arguments']
        autowire: true
###< stewie/wiki-bundle ###

```

#### Register routes

```php
// config/routes/stewie-wiki.yaml
// ...
stewie_wiki:
    resource: "@StewieWikiBundle/Controller/"
    type:     annotation
    prefix:   "{_locale}/"
    defaults:
      _locale: en
    requirements:
      _locale: en|de
```

### Populate data

#### fill minimal user data

```console
$ php bin/console stewie:wiki:fill-data
```

This would include status for wiki articles and roles for StewieUserBundle.

#### fill test user data

```console
$ php bin/console stewie:wiki:fill-data --all
```

This would add more dummy articles.
