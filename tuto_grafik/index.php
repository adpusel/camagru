<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
            rel="stylesheet"
            integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
            crossorigin="anonymous">
</head>


<body>

<?php
require "class/Autoloader.class.php";

Autoloader::register();

// je peux mettre direct dans le forme ce que je fais :)
$form = new BootstrapForm($_POST) ;


?>

<form
        action="#"
        method="post">
  <?php

  echo $form->input("username");
  echo $form->input('password');
  echo $form->submit("ok");

  ?>
</form>
</body>
</html>