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

$loader = require __DIR__ . '/../vendor/autoload.php';
$loader->addPsr4('Fixture\\', 'test/Fixture');
