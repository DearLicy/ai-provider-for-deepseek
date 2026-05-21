<?php
/**
 * PSR-4 autoloader for AI Provider for DeepSeek.
 *
 * @package WordPress\DeepSeekAiProvider
 */

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    $prefix = 'WordPress\\DeepSeekAiProvider\\';
    $baseDir = __DIR__ . '/';

    $len = strlen($prefix);
    if (strncmp($class, $prefix, $len) !== 0) {
        return;
    }

    $relativeClass = substr($class, $len);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});