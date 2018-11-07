<?php
/**
 * User: adpusel
 * Date: 11/7/18
 * Time: 11:24
 */


namespace Core\Validator;


class PasswordValidator extends Validator
{
  public function isValid($value)
  {
    return
	  strlen($value) >= 8
	  && preg_match("#[0-9]+#", $value) !== 0
	  && preg_match("#[A-Z]+#", $value) !== 0
	  && preg_match("#[a-z]+#", $value) !== 0;
	;
  }
}