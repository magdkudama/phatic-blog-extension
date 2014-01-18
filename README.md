Phatic Blog Extension
=====================

Blog extension for Phatic

Installing
----------

```bash
$> curl -s https://getcomposer.org/installer | php
$> php composer.phar install
```

Code quality
------------
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b37e4846-8895-40ae-877d-b4d349a787f0/mini.png)](https://insight.sensiolabs.com/projects/b37e4846-8895-40ae-877d-b4d349a787f0)
[![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/magdkudama/phatic-blog-extension/badges/quality-score.png?s=c2abd5604a7000a5cfa09631bd6738f032b26ea9)](https://scrutinizer-ci.com/g/magdkudama/phatic-blog-extension/)
[![Code Coverage](https://scrutinizer-ci.com/g/magdkudama/phatic-blog-extension/badges/coverage.png?s=02d2b795d5f609e4e702a8d9f416f390f96317ce)](https://scrutinizer-ci.com/g/magdkudama/phatic-blog-extension/)
[![Build Status](https://travis-ci.org/magdkudama/phatic-blog-extension.png?branch=master)](https://travis-ci.org/magdkudama/phatic-blog-extension)

Running
-------

```bash
$> cp phatic.yml.dist phatic.yml
$> bin/phatic
$> bin/phatic bootstrap-application
$> bin/phatic blog:generate-site
```

Configure phatic.yml:

    config:
      extensions:
        MagdKudama\PhaticBlogExtension\PhaticBlogExtension:
          permalink:
            type: 'date'
            param: 'Y/m'

You can choose the permalink type, or register your own permalink. The other core permalink type is "prefix".

More documentation is coming...

License
-------

Phatic Blog Extension is licensed under the [MIT license](LICENSE.md).

Contributors
------------

- Magd Kudama [magdkudama] [lead developer]