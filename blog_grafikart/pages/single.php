<?php
// je ne prends pas en get pour eviter les injections sql
namespace App;

$article = App::getDb() ->prepare(
  "SELECT * FROM Article WHERE id= ?",
  [$_GET['id']],
  "App\Table\Article",
  true);
?>

<?php var_dump($article); ?>

<h1>
  <?= $article->titre ?>
</h1>

<p>
  <?= $article->contenu ?>

</p>
