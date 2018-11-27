Getting Started With Chaplean Bundle
====================================

# Prerequisites

This version of the bundle requires Symfony 3.4+.

# Installation

## 1. Composer

```console
composer require chaplean/elasticsearch-bundle
```

## 2. AppKernel.php

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Chaplean\Bundle\ElasticsearchBundle\ChapleanElasticsearchBundle(),
        ];

        // ...
    }

    // ...
}
```

## 3. config.yml

##### A. Import

```yaml
    - { resource: '@ChapleanElasticsearchBundle/Resources/config/config.yml' }
```

##### B. Configuration

```
chaplean_elasticsearch:
```
