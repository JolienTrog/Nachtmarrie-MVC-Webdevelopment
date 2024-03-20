<?php

namespace Nachtmerrie;

function autoloader(string $classPath): void
{
    //baseDir den Pfad anpassen wo lib Verzeichnes liegt
    $baseDir = '/var/www/nachtmerrie/lib';
    $parts = explode('\\', $classPath);

    if (isset($parts[0]) && $parts[0] === 'Nachtmerrie') {
        $parts[0] = $baseDir;
        $filePath = implode(DIRECTORY_SEPARATOR, $parts) . '.php';

        if (file_exists($filePath) && is_readable($filePath)) {
            require($filePath);
        }
    }
}