<?php
namespace Core\App;

abstract class ApplicationComponent
{
  protected $app;
  
  public function __construct(App $app)
  {
    $this->app = $app;
  }
  
  public function app()
  {
    return $this->app;
  }
}