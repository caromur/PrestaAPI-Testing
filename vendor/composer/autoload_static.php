<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit627254a8a15f41ccdfd6a035d95fc51b
{
    public static $files = array (
        '9d0199ba51ea5554d22791b39f1a0709' => __DIR__ . '/..' . '/prestaShop/prestaShop-webservice-lib/PSWebServiceLibrary.php',
    );

    public static $classMap = array (
        'PrestaShopWebservice' => __DIR__ . '/..' . '/prestashop/prestashop-webservice-lib/PSWebServiceLibrary.php',
        'PrestaShopWebserviceException' => __DIR__ . '/..' . '/prestashop/prestashop-webservice-lib/PSWebServiceLibrary.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit627254a8a15f41ccdfd6a035d95fc51b::$classMap;

        }, null, ClassLoader::class);
    }
}
