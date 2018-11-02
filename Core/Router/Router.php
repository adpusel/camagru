<?php

namespace Core\Router;

class Router
{
  protected $routes = [];

  const NO_ROUTE = 1;

  // dans le contructeur j'instancie avec les routes de mon app
  public function __construct($module_name)
  {
	$xml = new \DOMDocument;
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

  public function getRoute($url) : Route
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
}

//new Router("Auth");
preg_match('#/admin/news-update-([0-9]+)-([0-9]+)\.html#',
  "/admin/news-update-99-88.html",
  $tab);
var_dump($tab);
































