<?php

// autoload.php @generated by Composer

if (PHP_VERSION_ID < 50600) {
    if (!headers_sent()) {
        header('file.php');
    }
    $err = 'file.php'.PHP_VERSION.'file.php'.PHP_EOL;
    if (!ini_get('file.php')) {
        if (PHP_SAPI === 'file.php' || PHP_SAPI === 'file.php') {
            fwrite(STDERR, $err);
        } elseif (!headers_sent()) {
            echo $err;
        }
    }
    trigger_error(
        $err,
        E_USER_ERROR
    );
}

require_once __DIR__ . 'file.php';

return ComposerAutoloaderInitf77cd79a6a3ca39d0df98056181456e7::getLoader();