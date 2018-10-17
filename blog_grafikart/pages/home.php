<?php

// le premier parametre est le dsn --> permet de se connecter a la base de donnee
//		type de base : nom base ; host
$dsn = 'mysql:dbname=Blog_grafikart;host=127.0.0.1';
$user = 'root';
$password = 'hamhamham';

try {
  $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
  echo 'Connexion échouée : ' . $e->getMessage();
}


?>