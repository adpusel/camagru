<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 19:51
 */


namespace Core\User;

use Core\Database\MySqlDatabase;
use Core\Model\Model;

class UserModel extends Model
{

 const EXISTING_EMAIL = 'Cet email existe deja';
 const EXISTING_LOGIN = 'Ce login existe deja';

  public function userExist(string $email)
  {
	return $this
	  ->database
	  ->query(
	    'SELECT email FROM Users WHERE email = :email',
		['email' => $email],
		$this->entity,
		true
	  );
  }

  public function loginExist(string $login)
  {
	return $this
	  ->database
	  ->query(
		'SELECT login FROM Users WHERE login = :login',
		['login' => $login],
		$this->entity,
		true
	  );
  }
}

