<?php

// $root = "https://sprayer.agritechmoz.com";
$root = "http://localhost:8001";

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
