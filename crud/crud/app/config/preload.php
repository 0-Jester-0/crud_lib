<?php

spl_autoload_register(function (string $className) {
	require_once $_SERVER['DOCUMENT_ROOT'] . "/crud/" . str_replace("\\", "/", mb_strtolower($className)) . ".php";
});

require_once $_SERVER['DOCUMENT_ROOT'] . "/crud/app/components/router.php";

$router = new \App\Components\Router();
$router->run();
