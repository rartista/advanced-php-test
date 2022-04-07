<?php

define('PROJECT_ROOT', __DIR__);
define('ROOT_URL', 'http://192.168.56.101/devbox/php-test/');

require_once('vendor/autoload.php');
require_once('classes/BaseModel.php');
require_once('classes/BaseController.php');
require_once('classes/ViewRenderer.php');

$model_files = glob('models/*');

foreach ($model_files as $model_file) {
    require_once($model_file);
}

$controller_files = glob('controllers/*');

foreach ($controller_files as $controller_file) {
    require_once($controller_file);
}

use Dotenv\Dotenv;

$dotenv = Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();
