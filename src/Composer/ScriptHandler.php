<?php

namespace App\Composer;

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\Event;

class ScriptHandler
{
    public static function clearCache(Event $event)
    {
        $options = static::getOptions($event);

        $warmup = '';
        if (!$options['symfony-cache-warmup']) {
            $warmup = ' --no-warmup';
        }

        static::executeCommand($event, 'cache:clear'.$warmup);
    }

    public static function installAssets(Event $event)
    {
        $options = static::getOptions($event);

        $webDir = $options['symfony-web-dir'];
        if (!is_dir($webDir)) {
            throw new \RuntimeException(sprintf('The %s (%s) specified in composer.json was not found', 'symfony-web-dir', $webDir));
        }

        $symlink = '';
        if ('symlink' == $options['symfony-assets-install']) {
            $symlink = '--symlink ';
        } elseif ('relative' == $options['symfony-assets-install']) {
            $symlink = '--relative ';
        }

        static::executeCommand($event, 'assets:install '.$symlink.escapeshellarg($webDir));
    }

    private static function executeCommand(Event $event, $cmd)
    {
        $consoleDir = static::getOptions($event)['symfony-bin-dir'];
        if (!is_dir($consoleDir)) {
            throw new \RuntimeException(sprintf('The %s (%s) specified in composer.json was not found', 'symfony-bin-dir', $consoleDir));
        }

        $timeout = $event->getComposer()->getConfig()->get('process-timeout');
        $php = escapeshellarg(static::getPhp());
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.($phpArgs ? ' '.$phpArgs : '').' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) { $event->getIO()->write($buffer, false); });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s", escapeshellarg($cmd), self::removeDecoration($process->getOutput()), self::removeDecoration($process->getErrorOutput())));
        }
    }

    private static function getOptions(Event $event)
    {
        $options = [
            'symfony-bin-dir' => 'bin',
            'symfony-web-dir' => 'public',
            'symfony-assets-install' => 'copy',
            'symfony-cache-warmup' => false,
        ];

        $options = array_merge($options, $event->getComposer()->getPackage()->getExtra());

        (new Dotenv())->load(getcwd() . DIRECTORY_SEPARATOR .'.env');

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];
        $options['symfony-cache-warmup'] = getenv('SYMFONY_CACHE_WARMUP') ?: $options['symfony-cache-warmup'];

        return $options;
    }

    private static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find(false)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }
        return $phpPath;
    }

    private static function getPhpArguments()
    {
        $ini = null;
        $arguments = [];

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if ($env = getenv('COMPOSER_ORIGINAL_INIS')) {
            $paths = explode(PATH_SEPARATOR, $env);
            $ini = array_shift($paths);
        } else {
            $ini = php_ini_loaded_file();
        }

        if ($ini) {
            $arguments[] = '--php-ini='.$ini;
        }

        return $arguments;
    }

    private static function removeDecoration($string)
    {
        return preg_replace("/\033\[[^m]*m/", '', $string);
    }
}
