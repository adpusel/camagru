<?php
/**
 * User: adpusel
 * Date: 26/10/2018
 * Time: 09:00
 */

use Database\Connection;

echo "<pre>";

require 'DIC.php';
require 'database/connection.php';

class Model
{
  private $_connection;

  /**
   * Model constructor.
   *
   * @param $_connection
   */
  public function __construct(Connection $_connection, $table = [])
  {
	$this->_connection = $_connection;
	$this->id = uniqid();
  }
}

$dic = new Dic();
$connection = new Connection('db_name', 'root', 'root');


$dic->setInstance($connection);

//$dic->set('Model', function () use ($dic) {
//  return new Model($dic->get('Database\Connection'));
//});
var_dump($dic);
var_dump($dic->get('Model'));
var_dump($dic->get('Model'));
var_dump($dic->get('Model'));