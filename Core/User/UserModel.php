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
}

