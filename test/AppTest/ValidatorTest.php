<?php
/**
 * User: adpusel
 * Date: 11/7/18
 * Time: 11:37
 */

use Core\Validator\EmailValidator;
use Core\Validator\PasswordValidator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
  public function testEmailValidator()
  {
	$validator = new EmailValidator("bad email");

	$this->assertSame(false, $validator->isValid("nana"));
	$this->assertSame(true, $validator->isValid("nana@em.com"));
  }

  public function testPasswordValidator()
  {
	$validator = new PasswordValidator("bad mdp");

	$this->assertSame(false, $validator->isValid("nana"));
	$this->assertSame(false, $validator->isValid("nana7nn"));
	$this->assertSame(false, $validator->isValid("nanaCCCC"));
	$this->assertSame(true, $validator->isValid("DDeuauaou888"));
  }
}