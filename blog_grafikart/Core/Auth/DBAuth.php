<?php
/**
 * User: adpusel
 * Date: 23/10/2018
 * Time: 08:34
 */

namespace Core\Auth;

use Core\Database\Database;

/**
 * Class DBAuth
 *
 * @package Core\Auth
 */
class DBAuth
{
  /**
   * @var Database
   */
  protected $_db;

  /**
   * DBAuth constructor.
   *
   * @param Database $db
   */
  public function __construct(Database $db)
  {
	$this->_db = $db;
  }

  /** permet de se loger
   *
   * @param $username
   * @param $passport
   *
   * @return boolean if existe
   */
  public function login($username, $passport)
  {
	$user = $this->_db->prepare(
	  'SELECT * FROM User WHERE username = ?', [$username], null, true
	);
	if ($user && $user->password === sha1($passport))
	{
	  $_SESSION['auth'] = $user->id;
	  return true;
	}
	else
	  return false;
  }

  public function logged()
  {
	return isset($_SESSION['auth']);
  }

  public function getUserId()
  {
	if ($this->logged())
	  return $_SESSION['auth'];
	else
	  return false;
  }

}