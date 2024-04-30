<?php
require_once __DIR__ . "/../vendor/autoload.php";

$root = $_ENV["APP_URI"];

function route($route)
{
	global $root;
	$route = trim($route, "/");
	return $root . "/" . $route;
}

function asset($asset)
{
	global $root;
	$asset = trim($asset, "/");
	return "{$root}/views/assets/{$asset}";
}

function flushMsgn($msg)
{
	$_SESSION["msg"] = $msg;

	echo $_SESSION["msg"];

	unset($_SESSION["msg"]);
}
