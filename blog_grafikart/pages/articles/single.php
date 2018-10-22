<?php
// je ne prends pas en get pour eviter les injections sql
namespace App;

use App\Table\Article;

$article = Article::find($_GET['id']);
if ($article === false)
    App::NotFound();

App::setTitle($article->titre)

?>

<h1>
  <?= $article->titre ?>
</h1>

<p>
  <?= $article->contenu ?>

</p>
