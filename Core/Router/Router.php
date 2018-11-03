<?php

namespace Core\Router;

class Router
{
  protected $routes = [];

  const NO_ROUTE = 1;

  // dans le contructeur j'instancie avec les routes de mon app
  public function __construct(string $module_name, $test)
  {
	$xml = new \DOMDocument;
	if ($test)
	  $xml->load($test);
	else
	  $xml->load(__DIR__ . '/../../' . '/App/' . $module_name .
		'/Config/routes.xml');


	$routes = $xml->getElementsByTagName('route');

	// On parcourt les routes du fichier XML.
	foreach ($routes as $route)
	{
	  $this->addRoute(new Route($route));
	}
  }

  protected function addRoute(Route $route)
  {
	if (!in_array($route, $this->routes))
	{
	  $this->routes[] = $route;
	}
  }

  public function getRoute($url): Route
  {
	foreach ($this->routes as $route)
	{
	  // Si la route correspond à l'URL
	  if ($route->match($url) === true)
	  {
		// je la retourne deja setup
		return $route;
	  }
	}

	throw new \RuntimeException('Aucune route ne correspond à l\'URL',
	  self::NO_ROUTE);
  }

  /**
   * @return array
   */
  public function getRoutes(): array
  {
	return $this->routes;
  }
}
