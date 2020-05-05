<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('UTC');

define('ROOT', dirname(__DIR__));
define('DOMAIN', $_SERVER['HTTP_HOST']);

$allowOrigin = DOMAIN === 'localhost' ? 'http://localhost:8080' : 'https://seo-admin.herokuapp.com';

header('Access-Control-Allow-Origin: ' . $allowOrigin);
header('Access-Control-Allow-Methods: GET,HEAD,OPTIONS,POST,PUT');
header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization');

require_once ROOT . '/include/fn.php';
require_once ROOT . '/vendor/autoload.php';

try {
	App\Core\App::i()->run();
} catch (\App\HttpException $err) {
	$code = $err->getCode();
	http_response_code($code);
	echo $err->getMessage();
} catch (\Throwable $err) {
	dumpException($err);
}