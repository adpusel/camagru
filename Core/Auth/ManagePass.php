<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 13:52
 */


namespace Core\Auth;


trait ManagePass
{
  private function _hashPass(string $password)
  {
	return password_hash($password, PASSWORD_DEFAULT);
  }

  private function _comparePassword(string $password, string $hash)
  {
	return password_verify($password, $hash);
  }
}