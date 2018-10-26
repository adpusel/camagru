<?php
/**
 * User: adpusel
 * Date: 26/10/2018
 * Time: 14:37
 */


namespace Event;


class Emitter
{
  private static $_instance;
  private $_listeners = array();


  public static function getIstance(): Emitter
  {
	if (self::$_instance === null)
	  self::$_instance = new self();
	return self::$_instance;
  }

  public function emit(string $event, ...$argv)
  {
	if ($this->_hasListener($event))
	{
	  foreach ($this->_listeners[$event] as $listener)
	  {
		call_user_func_array($listener, $argv);
	  };
	}
  }

  public function on(string $event, callable $callback)
  {
	if ($this->_hasListener($event))
	  $this->_listeners[$event] = [];
	$this->_listeners[$event][] = $callback;
  }

  private function _hasListener(string $event): bool
  {
	return array_key_exists($event, $this->_listeners);
  }

}