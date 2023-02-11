<?php declare(strict_types=1);

use Tester\Environment;

require_once __DIR__ . '/../vendor/autoload.php';

define('TEMP_DIR', __DIR__ . '/temp');
define('TMP_DIR', TEMP_DIR);

Environment::setup();