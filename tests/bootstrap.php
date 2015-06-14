<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file.
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright (c) {{YEAR}}, {{COPYRIGHT}}
 * @link {{HOMEPAGE}}
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 */

/**
 * Phpunit bootstrap file
 * 
 * @author {{AUTHOR}} <{{EMAIL}}>
 */

$basePath = realpath(__DIR__ . '/..');
$autoload = $basePath . "/vendor/autoload.php";

if (!file_exists($autoload)) {
    die(<<<MSG
 Please run "composer install" to install dependencies and create autoload file.
    
MSG
    );
}

$loader = require $autoload;
$loader->addPsr4('Fixture\\', 'tests/Fixture');
