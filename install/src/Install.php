<?php

define('SOURCE_PATH', realpath(__DIR__ . '/../..'));

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
    protected static $metadata = [];

    /**
     * @var array keyword => value
     */
    protected static $inputs = [];

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
     * Intro message.
     */
    public static function intro()
    {
        $metadata = wordwrap(implode(', ', array_keys(self::$metadata)), 80 ,"\n");
        print <<<EOD
PSR-4 Package initialization script. Version 2.0.0-dev

This script will replace all the metadata keywords with the input value.
Metadata keywords are:

$metadata

Press [ENTER] to continue or type "q" to quit:
EOD;
        $handle = fopen('php://stdin', 'r');
        $input = strtolower(trim(fgets($handle)));
        fclose($handle);

        if (false !== strpos($input, 'q') || false !== strpos($input, 'Q')) {
            exit(1);
        }
    }

    /**
     * Initializes the installation.
     */
    public static function init()
    {
        ini_set('memory_limit', '512M');
        self::$metadata = require __DIR__ . '/metadata.php';
        $files = require __DIR__ . '/files.php';
        self::$removedFiles = $files['remove'];
        self::$renamedFiles = $files['rename'];
    }

    /**
     * Gets metadata inputs from STDIN.
     */
    public static function getInputs()
    {
        $handle = fopen('php://stdin', 'r');
        foreach (self::$metadata as $keyword => $value) {

            $description = $value[0];
            $default = is_callable($value[1])
                ? call_user_func($value[1], self::$inputs)
                : $value[1];

            print PHP_EOL;
            print $description . " [$default]:\n";

            $input = trim(fgets($handle));
            if (empty($input) && false !== strpos($description, '[REQUIRED]')) {
                print " ERROR! Required input cannot be empty.\n";
                exit(1);
            }

            self::$inputs[$keyword] = $input? $input: $default;
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

            if (false !== strpos($iterator->getRealPath(), SOURCE_PATH . '/.git')) {
                continue;
            }

            $content = str_replace(
                array_keys(self::$inputs),
                array_values(self::$inputs),
                file_get_contents($file)
            );
            $modified[] = self::shortenPath($iterator->getFileName());
            file_put_contents($file, $content);
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
            if (is_dir($file)) {
                $removable = self::recursiveRemove($file);
                continue;
            }
            $removable = self::remove($file);
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
                    self::shortenPath($from),
                    self::shortenPath($to)
                ) . PHP_EOL;
            } else {
                print sprintf(
                    ' Unable to rename "%s" to "%s".',
                    self::shortenPath($from),
                    self::shortenPath($to)
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
        self::init();
        self::intro();
        self::getInputs();
        self::process();
        self::removeFiles();
        self::renameFiles();
        self::removeGit();

        return 0;
    }

    protected static function shortenPath($path)
    {
        return str_replace(SOURCE_PATH . DIRECTORY_SEPARATOR, '', $path);
    }

    protected static function remove($file)
    {
        if (is_dir($file)) {
            if (@rmdir($file)) {
                print sprintf(' Removed "%s" directory.', self::shortenPath($file)) . PHP_EOL;
                return true;
            }

            print sprintf(' Unable to remove "%s" directory.', self::shortenPath($file)) . PHP_EOL;
            return false;
        }

        if (@unlink($file)) {
            print sprintf(' Removed "%s" file.', self::shortenPath($file)) . PHP_EOL;
            return true;
        }

        print sprintf(' Unable to remove "%s" file.', self::shortenPath($file)) . PHP_EOL;
        return false;
    }

    protected static function recursiveRemove($directory)
    {
        $result = false;
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $directory,
                RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($files as $fileinfo) {
            $result = self::remove($fileinfo->getRealPath());
        }

        $result = self::remove($directory);

        return $result;
    }

    protected static function removeGit()
    {
        $git = SOURCE_PATH . '/.git';
        if (!file_exists($git)) {
            return;
        }

        print PHP_EOL;
        print 'Would you like to remove .git directory and initialize a new one? : ';

        $handle = fopen('php://stdin', 'r');
        $input = strtolower(trim(fgets($handle)));
        fclose($handle);

        if (false !== strpos($input, 'y') || false !== strpos($input, 'Y')) {
            self::recursiveRemove($git);
            print PHP_EOL . shell_exec('git init');
        }
    }
}
