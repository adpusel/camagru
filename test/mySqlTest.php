<?php
/**
 * User: adpusel
 * Date: 10/31/18
 * Time: 20:18
 */

class MySqlTest extends \PHPUnit\Framework\TestCase
{
  /**
   * @expectedException InvalidArgumentException
   */
  public function testErrFile()
  {
	new \Core\Database\MySqlDatabase("");
  }

//  public function testGoodFile()
//  {
//	$path =
//	  "/Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru/config/database.ini";
//	$pdo = new \Core\Database\MySqlDatabase($path);
//	$this->assertInstanceOf($pdo, new PDO($path));
//  }

}