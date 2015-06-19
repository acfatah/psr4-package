<?php

/**
 * PSR-4 Package installer class.
 *
 * @author Achmad F. Ibrahim <acfatah@gmail.com>
 */
class Install
{
    /**
     * @var int Program exit code
     */
    protected static $exitCode = 0;

    /**
     * @var array `['keywords' => [ 'description', 'default'], ...]`
     */
    protected static $metadata;

    /**
     * @var array Files to be removed
     */
    protected static $removedFiles;

    /**
     * @var array Files to be renamed
     */
    protected static $renamedFiles;

    /**
     * @var array Replacement values
     */
    protected static $replacement;

    /**
     * @var array Metadata keys
     */
    protected static $search;

    /**
     * Initializes the installation.
     */
    public static function init()
    {
        ini_set('memory_limit', '512M');
        self::$metadata = [
            // 'keywords' => [ 'description', 'default']
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
                'https://github.com/acfatah'
            ],
            '{{NAMESPACE}}' => [
                'Project namespace. E.g "Acfatah\Package"',
                ''
            ],
            '{{AUTOLOAD-PSR4}}' => [
                'PSR-4 composer autoload string. E.g "Acfatah\\\\Package\\\\"',
                ''
            ],
            '{{COPYRIGHT}}' => [
                'Copyright holder.',
                'Achmad F. Ibrahim'
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
        $files = require __DIR__ . '/files.php';
        self::$removedFiles = $files['remove'];
        self::$renamedFiles = $files['rename'];
    }

    /**
     * Gets metadata inputs from STDIN.
     */
    public static function getInput()
    {
        $handle = fopen('php://stdin', 'r');
        foreach (self::$metadata as $meta => $value) {
            print PHP_EOL;
            print $value[0] . " [{$value[1]}]:\n";
            self::$search[] = $meta;
            $input = trim(fgets($handle));
            if (empty($input) && false !== strpos($value[0], '[REQUIRED]')) {
                print " ERROR! Required input cannot be empty.\n";
                exit(1);
            }
            self::$replacement[] = $input? $input: $value[1];
        }
        fclose($handle);
    }

    /**
     * Intro message.
     */
    public static function intro()
    {
        print <<<EOD
PSR-4 Package initialization script. Version 1.0.0-dev\n

This script will replace all the metadata keywords with the input value.
Metadata keywords are:

 {{PROJECT_NAME}}, {{VENDOR_PACKAGE}}, {{DESCRIPTION}}, {{PACKAGE_TYPE}},
 {{KEYWORDS}}, {{AUTHOR}}, {{EMAIL}}, {{HOMEPAGE}}, {{AUTOLOAD-PSR4}},
 {{NAMESPACE}}, {{COPYRIGHT}}, {{DATE}}, {{YEAR}}

Press [ENTER] to continue or type "q" to quit: 
EOD;
        $handle = fopen('php://stdin', 'r');
        $input = strtolower(trim(fgets($handle)));
        if (false !== strpos($input, 'q')) {
            exit(1);
        }
        fclose($handle);
    }

    /**
     * Runs the replacement process.
     */
    public static function process()
    {
        // iterate all folders and replace metadata
        $recursive = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                SOURCE_PATH
            )
        );
        $modified = [];
        foreach ($recursive as $iterator) {
            if ($iterator->isDir()) {
                continue;
            }
            $file = $iterator->getRealPath();
            if ($file == __FILE__) {
                continue;
            }
            $content = file_get_contents($file);
            foreach (self::$metadata as $meta => $value) {
                if (false !== strpos($content, $meta)) {
                    $content = str_replace(self::$search, self::$replacement, $content);
                    $modified[] = self::simpifyPath($iterator->getFileName());
                    file_put_contents($file, $content);
                    break;
                }
            }
        }

        print PHP_EOL;
        print ' Modified ' . count($modified) . ' file(s):' . PHP_EOL;
        foreach ($modified as $value) {
            print '  * ' . $value . PHP_EOL;
        }
    }

    /**
     * Removes files.
     */
    public static function removeFiles()
    {
        print PHP_EOL;
        $removable = true;
        foreach (self::$removedFiles as $file) {
            if (is_file($file)) {
                if (@unlink($file)) {
                    print sprintf(' Removed "%s" file.', self::simpifyPath($file)) . PHP_EOL;
                } else {
                    print sprintf(' Unable to remove "%s" file.', self::simpifyPath($file)) . PHP_EOL;
                    $removable = false;
                }
            }
            if (is_dir($file)) {
                if (@rmdir($file)) {
                    print sprintf(' Removed "%s" directory.', self::simpifyPath($file)) . PHP_EOL;
                } else {
                    print sprintf(' Unable to remove "%s" directory.', self::simpifyPath($file)) . PHP_EOL;
                    $removable = false;
                }
            }
        }
        if (!$removable) {
            print " Please remove the file(s) or director(y|ies) manually." . PHP_EOL;
            self::$exitCode = 1;
        }
    }

    /**
     * Rename files.
     */
    public static function renameFiles()
    {
        print PHP_EOL;
        $renameable = true;
        foreach (self::$renamedFiles as $from => $to) {
            if (@rename($from, $to)) {
                print sprintf(
                    ' Renamed "%s" to "%s".',
                    self::simpifyPath($from),
                    self::simpifyPath($to)
                ) . PHP_EOL;
            } else {
                print sprintf(
                    ' Unable to rename "%s" to "%s".',
                    self::simpifyPath($from),
                    self::simpifyPath($to)
                ) . PHP_EOL;
                $renameable = false;
            }
        }
        if (!$renameable) {
            print " Please rename the file(s) manually." . PHP_EOL;
            self::$exitCode = 1;
        }
    }

    /**
     * Runs the installation.
     */
    public static function run()
    {
        self::intro();
        self::init();
        self::getInput();
        self::process();
        self::removeFiles();
        self::renameFiles();
        return self::$exitCode;
    }

    protected static function simpifyPath($path)
    {
        return str_replace(SOURCE_PATH . DIRECTORY_SEPARATOR, '', $path);
    }
}
