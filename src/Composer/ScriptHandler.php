<?php

namespace Slashworks\ContaoSimpleSvgIconsBundle\Composer;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Class ScriptHandler
 *
 * Run additional logics on composer events.
 *
 * @package Slashworks\ContaoSimpleSvgIconsBundle\Composer
 */
class ScriptHandler
{

    /**
     * Run tasks for the composer install event.
     *
     * @param Event $event
     */
    public static function install(Event $event)
    {
        static::copyExampleSvgIconSprite();

        static::executeCommand('contao:symlinks', $event);
    }

    /**
     * Copy an example SVG icon sprite file to the files folder to make it usable in the theme.
     */
    public static function copyExampleSvgIconSprite()
    {
        $filesystem = new Filesystem();

        if (!$filesystem->exists(getcwd() . '/files')) {
            return;
        }

        // Create target dummy folder and make it public
        if (!$filesystem->exists(getcwd() . '/files/example-svg-icon-sprite')) {
            $filesystem->mkdir(getcwd() . '/files/example-svg-icon-sprite');

            // Make the folder and its contents publicly available.
            file_put_contents(getcwd() . '/files/example-svg-icon-sprite/.public', '');
        }

        // Copy demo svg icon sprite to target dummy folder.
        $filesystem->copy(__DIR__ . '/../../example-sprite.svg', getcwd() . '/files/example-svg-icon-sprite/example-sprite.svg');
    }

    /**
     * Executes a command.
     *
     * @param string $cmd
     * @param Event  $event
     *
     * @throws \RuntimeException
     */
    private static function executeCommand($cmd, Event $event)
    {
        $phpFinder = new PhpExecutableFinder();

        if (false === ($phpPath = $phpFinder->find())) {
            throw new \RuntimeException('The php executable could not be found.');
        }

        $process = new Process(
            sprintf(
                '%s %s%s %s%s --env=%s',
                escapeshellarg($phpPath),
                escapeshellarg(__DIR__.'/../../../../bin/contao-console'),
                $event->getIO()->isDecorated() ? ' --ansi' : '',
                $cmd,
                self::getVerbosityFlag($event),
                getenv('SYMFONY_ENV') ?: 'prod'
            )
        );

        // Increase the timeout according to terminal42/background-process (see #54)
        $process->setTimeout(500);

        $process->run(
            function ($type, $buffer) use ($event) {
                $event->getIO()->write($buffer, false);
            }
        );

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf('An error occurred while executing the "%s" command: %s', $cmd, $process->getErrorOutput())
            );
        }
    }

    /**
     * Returns the verbosity flag depending on the console IO verbosity.
     *
     * @param Event $event
     *
     * @return string
     */
    private static function getVerbosityFlag(Event $event)
    {
        $io = $event->getIO();

        switch (true) {
            case $io->isDebug():
                return ' -vvv';

            case $io->isVeryVerbose():
                return ' -vv';

            case $io->isVerbose():
                return ' -v';

            default:
                return '';
        }
    }

}
