<?php
/**
 * User: adpusel
 * Date: 19/10/2018
 * Time: 17:25
 */

namespace Core\Table;

use Core\Database\mySqlDatabase;

class Table
{
  protected $_table;
  protected $_db;

  public function __construct(MySqlDatabase $db)
  {
	$this->_db = $db;
  }

  public function all()
  {
	return $this->_db->query('SELECT * FROM Articles');
  }
}