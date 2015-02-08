<?php

namespace Ekyna\Bundle\CoreBundle\Composer;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;
use Composer\Script\CommandEvent;

/**
 * Class ScriptHandler
 * @package Stfalcon\Bundle\TinymceBundle\Composer
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ScriptHandler
{
    /**
     * @param CommandEvent $event
     */
    public static function install(CommandEvent $event)
    {
        $options = self::getOptions($event);
        $consolePathOptionsKey = array_key_exists('symfony-bin-dir', $options) ? 'symfony-bin-dir' : 'symfony-app-dir';
        $consolePath = $options[$consolePathOptionsKey];

        if (!is_dir($consolePath)) {
            printf(
                'The %s (%s) specified in composer.json was not found in %s, can not dump exposed routes.%s',
                $consolePathOptionsKey,
                $consolePath,
                getcwd(),
                PHP_EOL
            );

            return;
        }

        static::executeCommand($event, $consolePath, 'fos:js-routing:dump', $options['process-timeout']);
    }

    protected static function executeCommand(CommandEvent $event, $consolePath, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(self::getPhp());
        $console = escapeshellarg($consolePath.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf('An error occurred when executing the "%s" command.', escapeshellarg($cmd))
            );
        }
    }

    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(array(
            'symfony-app-dir' => 'app',
            'symfony-web-dir' => 'web',
            'symfony-assets-install' => 'hard'
        ), $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }

    protected static function getPhp()
    {
        $phpFinder = new PhpExecutableFinder;
        if (!$phpPath = $phpFinder->find()) {
            throw new \RuntimeException(
                'The php executable could not be found, add it to your PATH environment variable and try again'
            );
        }

        return $phpPath;
    }
}
