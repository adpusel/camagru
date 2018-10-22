<?php
// je ne prends pas en get pour eviter les injections sql
namespace App;

use App;

// ici je protege contre les movaise id
$article = App::getTable("Post")->one($_GET['id']);
//if ($item === false)

?>

<h1>
  <?= $article->titre ?>
</h1>

<p>
  <?= $article->contenu ?>

</p>
