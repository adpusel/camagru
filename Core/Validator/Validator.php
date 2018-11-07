<?php
/**
 * User: adpusel
 * Date: 11/7/18
 * Time: 09:43
 */


namespace Core\Validator;


abstract class Validator
{
  protected $errorMessage;

  public function __construct($errorMessage)
  {
	$this->setErrorMessage($errorMessage);
  }

  abstract public function isValid($value);

  public function setErrorMessage($errorMessage)
  {
	if (is_string($errorMessage))
	{
	  $this->errorMessage = $errorMessage;
	}
  }

  public function errorMessage()
  {
	return $this->errorMessage;
  }
}