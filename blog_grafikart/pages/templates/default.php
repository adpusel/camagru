<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta
	http-equiv="X-UA-Compatible"
	content="IE=edge">
  <meta
	name="viewport"
	content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <meta
	name="description"
	content="">
  <meta
	name="author"
	content="">
  <link
	href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
	rel="stylesheet"
	integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
	crossorigin="anonymous">
</head>

<body>
<h1><?= \App\App::getTitle() ?></h1>
<nav class="navbar navbar-inverse navbar-fixed-top">
  <div class="container">
  </div><!--/.nav-collapse -->
  </div>
</nav>

<div class="container">

  <div class="starter-template">
	<?= $content ?>
  </div>

</div><!-- /.container -->
</body>
</html>
