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

  public function _comparePassword(string $hash)
  {
	return password_verify($this->password, $hash);
  }
}