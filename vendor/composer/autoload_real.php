<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit4ae9f5a128d60c7a0d1288f5d7ac3a66
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

        spl_autoload_register(array('ComposerAutoloaderInit4ae9f5a128d60c7a0d1288f5d7ac3a66', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit4ae9f5a128d60c7a0d1288f5d7ac3a66', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit4ae9f5a128d60c7a0d1288f5d7ac3a66::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
