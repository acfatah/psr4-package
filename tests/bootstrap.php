<?php

/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE file.
 * Redistributions of files must retain the above copyright notice.
 * 
 * @copyright (c) 2014, Achmad F. Ibrahim
 * @link https://github.com/acfatah
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 */

/**
 * Phpunit bootstrap file
 * 
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
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
$loader->addPsr4('Test\\', 'tests/');
