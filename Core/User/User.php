<?php
/**
 * User: adpusel
 * Date: 10/31/18
 * Time: 07:48
 */

namespace Core;

/**
 * Interface User
 * premet de manager mon utilisateur
 * avec les messages flash
 * avec les droits
 *
 * @package Core\User
 */
interface User
{
  /**
   * User constructor.
   */
  public function __construct();

  /**
   * @return mixed
   */
  public function getFlash();

  /**
   * @return mixed
   */
  public function hasFlash();

  /** si true deblocke la vue de sa partie admin
   *
   * @return mixed
   */
  public function isAuthenticated();

  /**
   * @param $attr
   * @param $value
   *
   * @return mixed
   */
  public function setAttribute($attr, $value);

  /**
   * @param bool $authenticated
   *
   * @return mixed
   */
  public function setAuthenticated($authenticated = true);

  /**
   * @param $value
   *
   * @return mixed
   */
  public function setFlash($value);

}