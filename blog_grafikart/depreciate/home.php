<?php

// le premier parametre est le dsn --> permet de se connecter a la base de donnee
//		type de base : nom base ; host
// je viens de set le pdo
$pdo = 'mysql:dbname=Blog_grafikart;host=127.0.0.1';
$user = 'tuto';
$password = 'pass';

try
{
  $pdo = new PDO($pdo, $user, $password);
} catch (PDOException $e)
{
  echo 'Connexion échouée : ' . $e->getMessage();
}


// je set les err a visible, ne pas laisser en prod
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// query me return un entier , pas possible a utiliser pour get les data
$query = 'INSERT INTO article SET titre="lalal", create_at="' .
  date('Y-m-d h:i:s') . '"';

// fait ma query et me retourn un int contenant le nombre de line affecter
// ou false en cas d'errer
$pdo->exec($query);


// cet class query return un obj de type pdo_statement
$pdo_statement = $pdo->query('select * from article');

// cet obj a plusieur metho dont fetch all
// fetch all return un tab associatif et numerique, je lui passe une option pour
// avoir un objet a la place de type < stdClass qui est  >
$datas = $pdo_statement->fetchAll(PDO::FETCH_OBJ);

// je peux acceder aux resultat car ils sont dans un tab
var_dump($datas[0]->titre);


// je prends querry pour faire ca

var_dump($count);
?>