<?php

namespace App\Components;

use Core\MainController;

class Router
{
	private array $routes;

	public function __construct()
	{
		$routePaths = include $_SERVER['DOCUMENT_ROOT'] . "/crud/app/config/routes.php";
		$this->routes = $routePaths;
	}

	/**
	 * @return string|null
	 */
	private function getURI(): ?string
	{
		return !empty($_SERVER['REQUEST_URI']) ? trim($_SERVER['REQUEST_URI'], '/') : null;
	}

	/**
	 * @return void
	 */
	public function run()
	{
		$uri = $this->getURI();
		$uri = preg_replace("/\?([a-z]+)=\w*/", "", $uri);

		if ($uri != null)
		{
			foreach ($this->routes as $route => $path)
			{
				if (preg_match("~$route~", $uri))
				{
					$internalRoute = preg_replace("~$route~", $path, $uri);

					if (mb_strlen($internalRoute) == mb_strlen($uri) || mb_strlen($route) == mb_strlen($uri))
					{
						$sections = explode('/', $internalRoute);

						$modelName = "\App\Tables\\" . ucfirst(array_shift($sections));
						$actionName = array_shift($sections);

						$parameters [] = $modelName;
						foreach ($sections as $section)
							$parameters [] = $section;

						$controllerObject = new MainController();

						if (method_exists($controllerObject, $actionName))
						{
							$result = call_user_func_array(array($controllerObject, $actionName), $parameters);

							die;
						} else
						{
							header("HTTP/1.0 404 Not Found");
							die();
						}
					} else
					{
						header("HTTP/1.0 404 Not Found");
						die();
					}
				}
			}

			header("HTTP/1.0 404 Not Found");
			die();
		} else
		{
			header("Location: /groups");
			die();
		}
	}

}