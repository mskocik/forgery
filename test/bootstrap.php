<?php declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$cacheDir = __DIR__ . '/temp/cache/Nette.Configurator';
array_map('unlink', glob("$cacheDir/*.*"));
@rmdir($cacheDir);

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . '/temp');
$configurator->addConfig(__DIR__ . '/config.neon');

$container = $configurator->createContainer();

