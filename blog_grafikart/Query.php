<?php

use Core\Database\QueryBulder;

/**
 * User: adpusel
 * Date: 25/10/2018
 * Time: 22:20
 */
class Query
{
  // je peux le mettre directement dans ma class pour tout appeler en static si je veux

  public static function __callStatic($method, $arguments)
  {
	$query = new QueryBulder();
	return call_user_func_array(
	  [
		$query,
		$method
	  ], $arguments);
  }
}