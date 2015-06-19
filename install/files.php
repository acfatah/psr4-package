<?php

/**
 * List of files to be removed and renamed.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */

define('SOURCE_PATH', realpath(__DIR__ . '/..'));

return [
    'remove' => [
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'install'   . DIRECTORY_SEPARATOR . 'bin'           . DIRECTORY_SEPARATOR . 'init',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'install'   . DIRECTORY_SEPARATOR . 'bin',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'install'   . DIRECTORY_SEPARATOR . 'Install.php',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'install'   . DIRECTORY_SEPARATOR . 'files.php',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'install',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'src'       . DIRECTORY_SEPARATOR . '.gitkeep',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'tests'     . DIRECTORY_SEPARATOR . 'Fixture'       . DIRECTORY_SEPARATOR . '.gitkeep',
        SOURCE_PATH . DIRECTORY_SEPARATOR . 'tests'     . DIRECTORY_SEPARATOR . 'unit'          . DIRECTORY_SEPARATOR . '.gitkeep'
    ],
    'rename' => [
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_CHANGELOG.md'     => SOURCE_PATH . DIRECTORY_SEPARATOR . '_CHANGELOG.md',
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_composer.json'    => SOURCE_PATH . DIRECTORY_SEPARATOR . 'composer.json',
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_LICENSE'          => SOURCE_PATH . DIRECTORY_SEPARATOR . 'LICENSE',
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_nbproject'        => SOURCE_PATH . DIRECTORY_SEPARATOR . 'nbproject',
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_phpunit.xml.dist' => SOURCE_PATH . DIRECTORY_SEPARATOR . 'phpunit.xml.dist',
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_README.md'        => SOURCE_PATH . DIRECTORY_SEPARATOR . 'README.md',
        SOURCE_PATH . DIRECTORY_SEPARATOR . '_travis.yml'       => SOURCE_PATH . DIRECTORY_SEPARATOR . '.travis.yml'
    ]
];
