<?php
/**
 * User: adpusel
 * Date: 20/10/2018
 * Time: 15:36
 */

use App\Table\Article;
use App\Table\Categorie;

$cat = Categorie::find($_GET['id']);
$article = Article::lastByCategory($_GET['id']);
$cats = Categorie::getAll();
var_dump($cat);
