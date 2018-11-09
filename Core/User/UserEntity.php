<?php
/**
 * User: adpusel
 * Date: 11/6/18
 * Time: 14:45
 */


namespace Core\User;

use Core\Model\Entity;

class UserEntity extends Entity
{
  use ManagePass;

  protected
	$email = '',
	$password = '',
	$email_check = '',
	$hash = '',
	$login = '',
	$is_check = false;

  /**
   * @return string
   */
  public function getLogin(): string
  {
	return $this->login;
  }

  /**
   * @param string $login
   */
  public function setLogin(string $login): void
  {
	$this->login = $login;
  }

  /**
   * @return bool
   */
  public function isCheck(): bool
  {
	return $this->is_check;
  }

  /**
   * @param bool $is_check
   */
  public function setIsCheck(bool $is_check): void
  {
	$this->is_check = $is_check;
  }

  public function generateHash()
  {
	$this->hash = $this->_hashPass($this->password);
	return $this;
  }

  /**
   * @return mixed
   */
  public function getHash(): string
  {
	return $this->hash;
  }

  /**
   * @return mixed
   */
  public function getEmail(): string
  {
	return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail(string $email): void
  {
	$this->email = $email;
  }

  /**
   * @param string $password
   */
  public function setPassword(string $password): void
  {
	$this->password = $password;
  }

  /**
   * @return mixed
   */
  public function getEmailCheck(): string
  {
	return $this->email_check;
  }

  /**
   * @return string
   */
  public function getPassword(): string
  {
	return $this->password;
  }

  public function generateEmailCheck(): UserEntity
  {
	$this->email_check = urlencode(bin2hex(random_bytes(50)));
	return $this;
  }

  public function sameCheck($check_get)
  {
	return $check_get === $this->email_check;
  }
}