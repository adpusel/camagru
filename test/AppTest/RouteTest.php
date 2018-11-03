<?php
/**
 * User: adpusel
 * Date: 11/2/18
 * Time: 10:01
 */

namespace Core\Router;

use PHPUnit\Framework\TestCase;


/**
 * Class RouterTest
 *
 * @package Core\Router
 */
class RouteTest extends TestCase
{
  /**
   * test the route object
   */
  public function testConstruct()
  {
	$xml = new \DOMDocument;
	$xml->load(__DIR__ . '/../resources/routes.xml');
	$routes_xml_tab = $xml->getElementsByTagName('route');

	$noVarRoute = new Route($routes_xml_tab[0]);
	// test noVar
	$this->assertEquals($noVarRoute->getModule(), "News");
	$this->assertEquals($noVarRoute->getAction(), "index");
	$this->assertEquals($noVarRoute->getTabVars(), []);
	$this->assertEquals($noVarRoute->hasVars(), false);


	// test oneVar
	$oneVarRoute = new Route($routes_xml_tab[1]);
	$this->assertEquals($oneVarRoute->hasVars(), true);
	$this->assertEquals($oneVarRoute->getTabVars(), ['id']);

	// test twoVar
	$twoVarRoute = new Route($routes_xml_tab[2]);
	$this->assertEquals($twoVarRoute->hasVars(), true);
	$this->assertEquals($twoVarRoute->getTabVars(), ['id', 'name']);

	return compact('noVarRoute',
	  'oneVarRoute', 'twoVarRoute');
  }

  /**
   *
   * @var $routeTab array create pre function
   * @depends testConstruct
   */
  public function testMatch(array $routeTab)
  {
	extract($routeTab);

	// No
	/** @noinspection PhpUndefinedVariableInspection */

	$res = $noVarRoute->match('noVar');
	$this->assertEquals(1, $res);

	$res = $noVarRoute->match('noVaraoeu');
	$this->assertEquals(false, $res);

	// je fais le One
	/** @noinspection PhpUndefinedVariableInspection */

	$res = $oneVarRoute->match('oneVar-42');
	$this->assertEquals(1, $res);
	$this->assertEquals($oneVarRoute->getTabVars(), ['id' => 42]);

	// je fais le Two
	/** @noinspection PhpUndefinedVariableInspection */

	$res = $twoVarRoute->match('twoVar-42-super');
	$this->assertEquals(1, $res);
	$this->assertEquals(['id' => 42, 'name' => 'super'],
	  $twoVarRoute->getTabVars()
	);
  }
}