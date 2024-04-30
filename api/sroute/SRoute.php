<?php

declare(strict_types=1);

/**
 * @author Inacio Agostinho Uassire
 */

namespace sprint\sroute;

/**
 * This is sprint route class, for making easy and bring features you need to make your routes easy to 
 * manage
 * Class SRoute
 * @package sprint\sroute
 */

class SRoute
{
	/**
	 * This static array variable to hold all the routes
	 * @var array
	 */
	private static $routes 		= [];
	/**
	 * This static string variable to hold root path
	 * @var string
	 */
	public static $root 		= "/";

	/**
	 * This function runs the route according to the http verb request
	 * @param $uri
	 * @param $handler
	 * @return static
	 */
	public static function route($uri, $handler = null)
	{
		switch (strtolower($_SERVER["REQUEST_METHOD"])) {
			case "get":
				self::get($uri, $handler);
				break;
			case "head":
				self::head($uri, $handler);
				break;
			case "post":
				self::post($uri, $handler);
				break;
			case "put":
				self::put($uri, $handler);
				break;
			case "delete":
				self::delete($uri, $handler);
				break;
			case "patch":
				self::patch($uri, $handler);
				break;
			default:
				throw new \Exception("Invalid http method informed.");
		}

		return new static();
	}

	/**
	 * This function runs to route for the GET http verb request
	 * @param $uri
	 * @param $handler
	 * @return Route
	 */
	public static function get($uri, $handler = null)
	{
		return self::addRoute("get", $uri, $handler);
	}

	/**
	 * This function runs to route for the HEAD http verb request
	 * @param $uri
	 * @param $handler
	 * @return Route
	 */
	public static function head($uri, $handler = null)
	{
		return self::addRoute("head", $uri, $handler);
	}

	/**
	 * This function runs to route for the POST http verb request
	 * @param $uri
	 * @param $handler
	 * @return Route
	 */
	public static function post($uri, $handler = null)
	{
		return self::addRoute("post", $uri, $handler);
	}

	/**
	 * This function runs to route for the DELETE http verb request
	 * @param $uri
	 * @param $handler
	 * @return Route
	 */
	public static function delete($uri, $handler = null)
	{
		return self::addRoute("delete", $uri, $handler);
	}

	/**
	 * This function runs to route for the PUT http verb request
	 * @param $uri
	 * @param $handler
	 * @return Route
	 */
	public static function put($uri, $handler = null)
	{
		return self::addRoute("put", $uri, $handler);
	}

	/**
	 * This function runs to route for the PATCH http verb request
	 * @param $uri
	 * @param $handler
	 * @return Route
	 */
	public static function patch($uri, $handler = null)
	{
		return self::addRoute("patch", $uri, $handler);
	}

	/**
	 * This function is responsible to add the informed routes the $routes variable
	 * @param $http_verb
	 * @param $uri
	 * @param $handler
	 * @param null $prefix
	 * @param null $middleware
	 * @param null $where
	 * @param null $controller
	 * @param null $method
	 * @return static
	 */
	private static function addRoute($http_verb, $uri, $handler = null, $prefix = null, $where = null, $middlewares = array(), $name = null, $controller = null, $method = null)
	{
		self::$routes[] = array(
			"http_verb" 	=> $http_verb,
			"uri" 			=> self::addLeadingAndTrailingSlashesToUri($uri),
			"handler"		=> self::handler($handler),
			"params"		=> array(),
			"prefix"		=> $prefix,
			"where" 		=> $where,
			"middleware" 	=> array(
				"middlewares"	=> $middlewares
			),
			"name"			=> $name,
			"controller"	=> $controller,
			"method"		=> $method,
		);

		return new static();
	}

	/**
	 * Groups some routes property for a group of routes
	 * eg. Middleware, Controller, Prefix
	 * @param $group
	 * @param $callback
	 * @throws \Exception
	 */
	public static function group($group, $callback)
	{
		if (!is_array($group)) {
			throw new \Exception("The first parameter must be array, " . gettype($group) . " informed");
		}

		if (!is_callable($callback)) {
			throw new \Exception("The second parameter must be callable, " . gettype($callback) . " informed");
		}

		//We need to get all routes that are not in the current group of routes
		//we do this by passing all the routes currently added and assign to a variables
		$routesOutOfThisScope = self::$routes;

		//After we get all routes that are not in this group of routes now we reset the 
		//global self::$routes that holds the routes
		self::$routes = array();

		//We now run the callback function and this will assign the current routes in the group of
		//routes to the global variable self::$routes
		call_user_func($callback);

		//For group we consider the following: prefix, middleware and the controller as they
		//share the possibility to be grouped
		if (array_key_exists("prefix", $group)) {
			if (!empty($group["prefix"])) {
				$prefix = $group["prefix"];
				array_walk(self::$routes, function (&$route, $key) use ($prefix) {
					$route["prefix"] = "/" . trim($prefix, "/");
				});
			}
		}

		if (array_key_exists("middleware", $group)) {
			if (!empty($group["middleware"])) {
				$middleware = $group["middleware"];

				array_walk(self::$routes, function (&$route, $key) use ($middleware) {
					foreach ($middleware as $handler) {
						array_push($route["middleware"]["middlewares"], self::handler($handler));
					}
				});
			}
		}

		if (array_key_exists("controller", $group)) {
			if (!empty($group["controller"])) {
				$controller = $group["controller"];
				array_walk(self::$routes, function (&$route, $key) use ($controller) {
					$route["controller"] = self::handler($controller);
				});
			}
		}

		self::$routes = array_merge($routesOutOfThisScope, self::$routes);

		return true;
	}

	/**
	 * This function calls the prefix method as chaning to the route
	 * @param $condition
	 * @return Route
	 */
	public static function prefix($prefix, $callback)
	{
		//We need to get all routes that are not in the current group of routes
		//we do this by passing all the routes currently added and assign to a variables
		$routesOutOfThisScope = self::$routes;

		//After we get all routes that are not in this group of routes now we reset the 
		//global self::$routes that holds the routes
		self::$routes = array();

		//We now run the callback function and this will assign the current routes in the group of
		//routes to the global variable self::$routes
		call_user_func($callback);

		//For group we consider the following: prefix, middleware and the controller as they
		//share the possibility to be grouped
		array_walk(self::$routes, function (&$route, $key) use ($prefix) {
			$route["prefix"] = "/" . trim($prefix, "/");
		});

		self::$routes = array_merge($routesOutOfThisScope, self::$routes);

		if (empty(self::$routes)) return false;

		return true;
	}

	/**
	 * This function calls the where method as chaning to the route
	 * @param $condition
	 * @return Route
	 */
	public function where()
	{
		if (func_num_args() == 0) {
			throw new \Exception("The [where] function expects one parameter (as array) or two parameters as string, zero passed");
		}

		if (func_num_args() == 1) {
			if (!is_array(func_get_arg(0))) {
				throw new \Exception("You passed one argument, must be type of array or pass the second argument");
			}
			$condition = func_get_arg(0);
		}

		if (
			func_num_args() == 2 &&
			is_string(func_get_arg(0)) &&
			(is_string(func_get_arg(1)) || is_numeric(func_get_arg(1))
			)
		) {
			$condition = [
				strval(func_get_arg(0)) => func_get_arg(1)
			];
		}

		if (func_num_args() >= 3) {
			throw new \Exception("The where function expects one (as array) or two arguments (as string), more than three passed");
		}

		return self::modifiers("where", $condition);
	}

	/**
	 * This function calls the middleware method as chaning to the route
	 * @param $condition
	 * @return Route
	 */
	public function middleware($condition)
	{
		return self::modifiers("middleware", $condition);
	}

	/**
	 * This function calls the name method as chaning to the route
	 * @param $condition
	 * @return Route
	 */
	public function name($condition)
	{
		if (!is_string($condition)) {
			throw new \Exception("The parameter for named route must be string, " . gettype($condition) . " informed");
		}

		return self::modifiers("name", $condition);
	}

	/**
	 * This function calls the controller method as chaning to the route
	 * @param $controller
	 * @return Route
	 */
	public function controller($controller)
	{
		return self::modifiers("controller", $controller);
	}

	/**
	 * This function calls the method in the controller as chaning to the route
	 * @param $method
	 * @return Route
	 */
	public function method($method)
	{
		return self::modifiers("method", $method);
	}

	/**
	 * @param $modifier
	 * @param $condition
	 * @return $this
	 */
	private static function modifiers($modifier, $condition)
	{
		$appliedRoute = array_pop(self::$routes);

		if ($modifier != "middleware") {
			$appliedRoute[$modifier] = $condition;
		} else {
			array_push($appliedRoute[$modifier]["middlewares"], $condition);
		}

		array_push(self::$routes, $appliedRoute);

		return new static();
	}

	/**
	 * @param $uri
	 * @return string
	 */
	private static function addLeadingAndTrailingSlashesToUri($uri)
	{
		$uri = trim($uri, "/");
		$uri = (!empty($uri) && strlen($uri)) ? str_pad($uri, strlen($uri) + 2, "/", STR_PAD_BOTH) : "/";
		return urldecode($uri);
	}

	/**
	 * @param $handler
	 * @return array|string
	 */
	private static function handler($handler)
	{
		if (is_string($handler) && !is_null($handler)) {
			if (strpos($handler, ".")) {
				$callback = explode(".", $handler);
			}
		}

		return (isset($callback) && !is_null($callback)) ? $callback : $handler;
	}
	/**
	 * @param $handler
	 * @param $parameters
	 * @throws \Exception
	 */
	private static function execute($handlers, $parameters)
	{
		array_walk($handlers, function ($handler, $k, $parameters) {
			if (is_array($handler)) {
				if (empty($handler[0]) || is_null($handler[0])) {
					throw new \Exception("The route expects a closure or an array with callable controller and method, null given");
				}

				if (!class_exists($handler[0])) {
					throw new \Exception("Controller {$handler[0]} not found");
				}

				if (empty($handler[1]) || is_null($handler[1])) {
					throw new \Exception("No method informed in the route, for the controller {$handler[0]}");
				}

				if (!method_exists($handler[0], $handler[1])) {
					throw new \Exception("Method {$handler[1]} not found in the controller {$handler[0]}");
				}

				$checkStatic = new \ReflectionMethod($handler[0], $handler[1]);

				$handler = $checkStatic->isStatic() === true ? $handler : array(
					new $handler[0],
					$handler[1]
				);

				return call_user_func_array($handler, $parameters);
			}

			if (is_callable($handler)) {
				return call_user_func_array($handler, $parameters);
			}
		}, $parameters);
	}

	/**
	 * @throws \Exception
	 */
	public static function run()
	{
		$uri = isset($_SERVER["REQUEST_URI"]) ? parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) : "/";

		$uri = str_replace(self::$root, "", $uri);

		$uri = self::addLeadingAndTrailingSlashesToUri($uri);

		$http_verb = strtolower($_SERVER["REQUEST_METHOD"]);

		$validRoute = [];

		foreach (self::$routes as &$route) {
			$fullRoute = (!is_null($route["prefix"]) || !empty($route["prefix"])) ? implode(
				"/",
				array(
					$route["prefix"],
					$route["uri"]
				)
			) : $route["uri"];

			$fullRoute = (!is_null($route["name"]) || !empty($route["name"])) ?
				self::addLeadingAndTrailingSlashesToUri($route["name"]) : $fullRoute;

			$pattern = str_replace('/', ':?', preg_replace('~{([\s\w-]+)}~iu', '(?<$1>[\s\w-]+)', $fullRoute));

			$matchRouteWithUri = preg_match("~^" . $pattern . "$~iu", str_replace('/', ':', $uri), $match);

			if ($matchRouteWithUri === 1) {
				$route["params"] = array_filter($match, function ($k) {
					return (is_string($k) && !empty($k));
				}, ARRAY_FILTER_USE_KEY);

				if ($http_verb == $route["http_verb"]) {
					$validRoute = $route;

					break;
				}
			}
		}

		if (empty($validRoute)) {
			throw new \Exception("The informed route {$uri} was not found");
		}

		if ($validRoute["http_verb"] != $http_verb) {
			throw new \Exception("The {$http_verb} http method is not allowed for the informed route");
		}

		$parameters = array_filter($validRoute["params"]) ?? [];

		if (!is_null($validRoute["where"])) {
			foreach ($validRoute["where"] as $key => $pattern) {
				if (array_key_exists($key, $parameters)) {
					if (!preg_match("~^" . $pattern . "$~iu", $parameters[$key])) {
						throw new \Exception("The value " . $parameters[$key] . " of the parameter {$key} does not match the pattern {$pattern} given in the route");
					}
				}
				unset($key);
			}
		}

		$handler = (!is_null($validRoute["handler"])) ? $validRoute["handler"] : array(
			$validRoute["controller"],
			$validRoute["method"]
		);

		$middlewares = $validRoute["middleware"]["middlewares"] ?? [];

		self::execute($middlewares, $parameters);
		return self::execute(array($handler), $parameters);
	}
}
