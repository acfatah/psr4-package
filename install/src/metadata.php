<?php

/**
 * List of metadata.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */

return [
    // 'name' => [ 'description', 'default']

    '{{PROJECT_NAME}}' => [
        '[REQUIRED] Project name. E.g, "Foo Project".',
        ''
    ],

    '{{VENDOR_PACKAGE}}' => [
        '[REQUIRED] Composer package name string. E.g, "acfatah/package".',
        ''
    ],

    '{{DESCRIPTION}}' => [
        'Project short description.',
        ''
    ],

    '{{PACKAGE_TYPE}}' => [
        'Package type.',
        'library'
    ],

    '{{KEYWORDS}}' => [
        "Composer comma separated package keywords quoted with (\"). E.g, \n"
            . '"psr-4", "library", "composer package"',
        ''
    ],

    '{{AUTHOR}}' => [
        'Author.',
        'Achmad F. Ibrahim'
    ],

    '{{EMAIL}}' => [
        'Email.',
        'acfatah@gmail.com'
    ],

    '{{HOMEPAGE}}' => [
        'Project homepage.',
        function ($metadata) {
            return 'https://github.com/' . $metadata['{{VENDOR_PACKAGE}}'];
        }
    ],

    '{{NAMESPACE}}' => [
        'Project namespace. E.g "Acfatah\Package"',
        function ($metadata) {
            $temp = explode('/', $metadata['{{VENDOR_PACKAGE}}']);
            array_walk($temp, function (&$value) {
                $value = ucfirst($value);
            });
            return implode('\\', $temp);
        }
    ],

    '{{AUTOLOAD-PSR4}}' => [
        'PSR-4 composer autoload string. E.g "Acfatah\\\\Package\\\\"',
        function ($metadata) {
            return str_replace('\\','\\\\', $metadata['{{NAMESPACE}}']) . '\\\\';
        }
    ],

    '{{COPYRIGHT}}' => [
        'Copyright holder.',
        function ($metadata) {
            return $metadata['{{AUTHOR}}'];
        }
    ],

    '{{DATE}}' => [
        'Project date (Y-m-d).',
        date('Y-m-d')
    ],

    '{{YEAR}}' => [
        'Year.',
        date('Y')
    ]
];