<ul>


  <?php foreach ($db->query('select * from article') as $article): ?>

      <li><?= $article->titre ?></li>

  <?php endforeach; ?>

</ul>
