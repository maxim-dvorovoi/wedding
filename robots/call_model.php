<?php

require __DIR__ . '/_inc.php';

list($class, $method) = explode('.', $argv[1], 2);
$fullClass = '\\App\\Model\\' . $class;
$obj = new $fullClass;

$args = array_slice($argv, 2);
print_r(call_user_func_array([$obj, $method], $args));