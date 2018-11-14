<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 13:52
 */


namespace Core\User;


trait ManagePass
{
  private function _hashPass(string $password)
  {
	return password_hash($password, PASSWORD_DEFAULT);
  }


  // TODO : c'est pas optimal de faire ca faudrait que je le deplace et juste 1 !!
  public function _comparePassword(string $hash)
  {
	return password_verify($this->password, $hash);
  }


  public function _compareHash(string $pass)
  {
	return password_verify($pass, $this->hash);
  }

}