<?php

/**
 * List of files to be removed and renamed.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */

return [

    'remove' => [
        SOURCE_PATH . '/install/bin/init',
        SOURCE_PATH . '/install/bin',
        SOURCE_PATH . '/install/src/Install.php',
        SOURCE_PATH . '/install/src/files.php',
        SOURCE_PATH . '/install/src/metadata.php',
        SOURCE_PATH . '/install/src',
        SOURCE_PATH . '/install',
        SOURCE_PATH . '/src/.gitkeep',
        SOURCE_PATH . '/tests/Fixture/.gitkeep',
        SOURCE_PATH . '/tests/unit/.gitkeep'
    ],

    'rename' => [
        SOURCE_PATH . '/_CHANGELOG.md'     => SOURCE_PATH . '/CHANGELOG.md',
        SOURCE_PATH . '/_composer.json'    => SOURCE_PATH . '/composer.json',
        SOURCE_PATH . '/_LICENSE'          => SOURCE_PATH . '/LICENSE',
        SOURCE_PATH . '/_nbproject'        => SOURCE_PATH . '/nbproject',
        SOURCE_PATH . '/_phpunit.xml.dist' => SOURCE_PATH . '/phpunit.xml.dist',
        SOURCE_PATH . '/_README.md'        => SOURCE_PATH . '/README.md',
        SOURCE_PATH . '/_travis.yml'       => SOURCE_PATH . '/.travis.yml'
    ]
];
