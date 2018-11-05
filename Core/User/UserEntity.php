<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 10:56
 */


namespace Core\User;


use Core\Entity\Entity;

/*˜*
 * Class UserEntity
 *
 * @package Core\User
 */

class UserEntity extends Entity
{
  protected
	$email,
	$password,
	$email_check,
	$hash;

  /**
   * @return mixed
   */
  public function getHash(): string
  {
	return $this->hash;
  }

  /**
   * @param mixed $hash
   */
  public function setHash(string $hash): void
  {
	$this->hash = $hash;
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
   * @return mixed
   */
  public function getPassword(): string
  {
	return $this->password;
  }

  /**
   * @param mixed $password
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
   * @param mixed $email_check
   */
  public function setEmailCheck(string $email_check): void
  {
	$this->email_check = $email_check;
  }


}