<?php

namespace Core\Utils;

use Exception;

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
}