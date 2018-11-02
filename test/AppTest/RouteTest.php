<?php
/**
 * User: adpusel
 * Date: 11/2/18
 * Time: 10:01
 */

namespace Core\Router;

use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
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

	// je fais le match
	$oneVarRoute->match('oneVar-42');
	$this->assertEquals($oneVarRoute->getTabVars(), ['id' => 42]);


	// test twoVar
	$twoVarRoute = new Route($routes_xml_tab[2]);
	$this->assertEquals($twoVarRoute->hasVars(), true);
	$this->assertEquals($twoVarRoute->getTabVars(), ['id', 'name']);

	// je fais le match
	$res = $twoVarRoute->match('twoVar-42-super');
	$this->assertEquals(1, $res);
	$this->assertEquals(['id' => 42, 'name' => 'super'],

	);

  }
}