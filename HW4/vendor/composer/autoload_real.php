<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitb060a4cd22670a1361cd308ef3aade76
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        spl_autoload_register(array('ComposerAutoloaderInitb060a4cd22670a1361cd308ef3aade76', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitb060a4cd22670a1361cd308ef3aade76', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitb060a4cd22670a1361cd308ef3aade76::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
