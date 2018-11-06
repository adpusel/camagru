<?php
/**
 * User: adpusel
 * Date: 11/5/18
 * Time: 15:26
 */

require '../vendor/autoload.php';

use Core\Auth\AuthController;
use Core\Database\MySqlDatabase;
use Core\User\UserEntity;


define('ROOT', 'Applications/mappstack-7.1.22-1/apache2/htdocs/42/camagru');
MySqlDatabase::getInstance(
  __DIR__ . "/../config/database.ini");

$authManager = new AuthController();
$user = new UserEntity([
  'email'    => 'adrien.pusel@gmail.com',
  'password' => 'toto'
]);

// same login
var_dump($authManager->inscription($user));
// test bad mdp

// test email