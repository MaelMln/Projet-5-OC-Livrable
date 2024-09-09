<?php

namespace App;

class Router
{
	private array $routes = [];

	public function add(string $route, string $controller, string $method): void
	{
		$this->routes[$route] = ['controller' => $controller, 'method' => $method];
	}

	public function dispatch(string $action): void
	{
		if (isset($this->routes[$action])) {
			$controllerName = $this->routes[$action]['controller'];
			$methodName = $this->routes[$action]['method'];
			$controller = new $controllerName();
			$controller->$methodName();
		} else {
			throw new \Exception("La page demandée n'existe pas.");
		}
	}
}
