<?php

namespace Database;

class Connection
{
  private $_db_name;
  private $_db_password;
  private $_db_user;

  /**
   * Connection constructor.
   *
   * @param $_db_name
   * @param $_db_password
   * @param $_db_user
   */
  public function __construct($_db_name, $_db_password, $_db_user)
  {
	$this->_db_name = $_db_name;
	$this->_db_password = $_db_password;
	$this->_db_user = $_db_user;
  }
}

?>