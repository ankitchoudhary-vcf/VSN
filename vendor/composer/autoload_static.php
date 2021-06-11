<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7d6883f8d8f6f70d40c312adc606765c
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7d6883f8d8f6f70d40c312adc606765c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7d6883f8d8f6f70d40c312adc606765c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7d6883f8d8f6f70d40c312adc606765c::$classMap;

        }, null, ClassLoader::class);
    }
}