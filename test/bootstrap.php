<?php

/**
 * Phpunit bootstrap file.
 * 
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @link {{HOMEPAGE}}
 * @author {{AUTHOR}} <{{EMAIL}}>
 */

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Fixture\\', 'test/Fixture');
