
<?php
/**
 * This file is part of the eZ Publish package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 *
 * @version //autogentag//
 */
// Get global config.php settings
if (!file_exists(__DIR__.'/vendor/ezsystems/ezpublish-kernel/config.php')) {
    if (!symlink(__DIR__.'/vendor/ezsystems/ezpublish-kernel/config.php-DEVELOPMENT', __DIR__.'/vendor/ezsystems/ezpublish-kernel/config.php')) {
        throw new \RuntimeException('Could not symlink vendor/ezsystems/ezpublish-kernel/config.php-DEVELOPMENT to vendor/ezsystems/ezpublish-kernel/config.php, please copy vendor/ezsystems/ezpublish-kernel/config.php-DEVELOPMENT to vendor/ezsystems/ezpublish-kernel/config.php & customize to your needs!');
    }
}
if (!($settings = include(__DIR__.'/vendor/ezsystems/ezpublish-kernel/config.php'))) {
    throw new \RuntimeException('Could not read vendor/ezsystems/ezpublish-kernel/config.php, please copy vendor/ezsystems/ezpublish-kernel/config.php-DEVELOPMENT to vendor/ezsystems/ezpublish-kernel/config.php & customize to your needs!');
}
require_once __DIR__.'/vendor/autoload.php';
