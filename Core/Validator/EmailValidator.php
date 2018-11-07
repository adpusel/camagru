<?php
/**
 * User: adpusel
 * Date: 11/7/18
 * Time: 11:24
 */


namespace Core\Validator;


use const FILTER_VALIDATE_EMAIL;
use function filter_var;

class EmailValidator extends Validator
{
  public function isValid($value)
  {
	return !!filter_var($value, FILTER_VALIDATE_EMAIL);
  }
}