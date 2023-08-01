<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit94614be95cf6300fed548458f3358b31
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Logger' => __DIR__ . '/../..' . '/app/Logger.php',
        'LoggerLevel' => __DIR__ . '/../..' . '/app/LoggerLevel.php',
        'LoggerType\\AbstractLogger' => __DIR__ . '/../..' . '/app/LoggerType/AbstractLogger.php',
        'LoggerType\\Api' => __DIR__ . '/../..' . '/app/LoggerType/Api.php',
        'LoggerType\\Console' => __DIR__ . '/../..' . '/app/LoggerType/Console.php',
        'LoggerType\\Email' => __DIR__ . '/../..' . '/app/LoggerType/Email.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit94614be95cf6300fed548458f3358b31::$classMap;

        }, null, ClassLoader::class);
    }
}