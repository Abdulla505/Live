<?php

/**
 * Attempts to load Composer'file.php'/../../../autoload.php'file.php'/../vendor/autoload.php', // stand-alone package
    ];
    foreach ($files as $file) {
        if (is_file($file)) {
            require_once $file;

            return true;
        }
    }

    return false;
};
