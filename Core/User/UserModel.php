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


  public function userExist(string $email, $exept = false)
  {
	$query = 'SELECT email FROM Users WHERE email = :email';
	$data = ['email' => $email];
	if ($exept)
	{
	  $query .= ' AND id != :id';
	  $data['id'] = $exept;
	}

	return $this
	  ->database
	  ->query(
		$query,
		$data,
		$this->entity,
		true
	  );
  }


  public function loginExist(string $login, $exept = false)
  {
	$query = 'SELECT email FROM Users WHERE login = :login';
	$data = ['login' => $login];
	if ($exept)
	{
	  $query .= ' AND id != :id';
	  $data['id'] = $exept;
	}

	return $this
	  ->database
	  ->query(
		$query,
		$data,
		$this->entity,
		true
	  );
  }


  public function getUserById(int $id)
  {
	$fetchedUser = $this->fetchOne(
	  [$id],
	  $this->entity,
	  true
	);
	return $fetchedUser;
  }


  public function getUserByLogin(string $login)
  {
	$fetchedUser = $this->fetchBy(
	  ['login' => $login],
	  $this->entity,
	  true
	);
	return $fetchedUser;
  }

}

