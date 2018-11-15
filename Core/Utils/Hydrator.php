<?php

namespace Core\Utils;


trait Hydrator
{
  public function hydrate($data)
  {
	foreach ($data as $key => $value)
	{
	  $method = 'set' . ucfirst($key);

	  if (is_callable([$this, $method]))
	  {
		$this->$method($value);
	  }
	}
  }

  public function getKeyNameStr($var): array
  {
	return [$var => $this->$var];
  }

  /**
   * @param array $askedFields
   *
   * @return array
   */
  public function getDataGiven(array $askedFields): array
  {
	$ar  = [];
	foreach ($askedFields as $askedField)
	{
	  $ar = array_merge($ar, $this->getKeyNameStr($askedField));
	}
	return $ar;
  }

}