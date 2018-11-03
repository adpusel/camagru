<?php
/**
 * User: adpusel
 * Date: 11/3/18
 * Time: 08:57
 */


namespace Core\Router;


use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
  public function testConstruct()
  {
	$path = __DIR__ . '/../resources/routes.xml';
	$router = new Router('', $path);
	$this->assertEquals(3, count($router->getRoutes()));
	return $router;
  }

  /**
   * @depends testConstruct
   */
  public function testGoodRoute(Router $router)
  {
	$route = $router->getRoute('twoVar-42-super');
	$this->assertEquals($route->getModule(), "News");
	$this->assertEquals($route->getAction(), "index");
	$this->assertEquals($route->getTabVars(), ['id' => 42, 'name' => 'super']);
  }


  /**
   * @depends testConstruct
   * @expectedException \RuntimeException
   */
  public function testWrongRoute(Router $router)
  {
	$route = $router->getRoute('twoVar-ll-super');

  }

}