<?php

class DIC
{

  public $registry = [];
  public $instance = [];
  public $factories = [];

  public function set($key, Callable $resolver)
  {
	$this->registry[$key] = $resolver;
  }

  public function setFactory($key, callable $resolver)
  {
	$this->factories[$key] = $resolver;
  }

  // todo mettre ici l'autolaoder et faire des test !!

  public function get($key)
  {
	// return fresh
	if (isset($this->factories[$key]))
	  return $this->factories[$key]();

	// return same
	if (!isset($this->instance[$key]))
	{
	  if (isset($this->registry[$key]))
		$this->instance[$key] = $this->registry[$key]();
	  else
	  {
		$reflected_class = new ReflectionClass($key);

		// si c'est instaciable
		if ($reflected_class->isInstantiable())
		{
		  $construct = $reflected_class->getConstructor();

		  if ($construct)
		  {
			$parameters = $construct->getParameters();
			$constructor_parameter = array();

			var_dump($construct);
			var_dump($parameters);

			foreach ($parameters as $parameter)
			{
			  if ($parameter->getClass())
			  {
				$constructor_parameter[] = $this->get($parameter
				  ->getClass()
				  ->getName());
			  }
			  else
				$constructor_parameter[] = $parameter->getDefaultValue();
			}
			$this->instance[$key] =
			  $reflected_class->newInstanceArgs($constructor_parameter);
		  }
		  else
			$this->instance[$key] =
			  $reflected_class->newInstance();
		}
		else
		  throw new Exception('Je ne peux pas resoudre ' . $key);
	  }
	}
	return $this->instance[$key];
  }

  public function setInstance($instance)
  {
	$reflected = new ReflectionClass($instance);
	$this->instance[$reflected->getName()] = $instance;
  }
}
