<?php header('Content-type: text/html; charset=utf-8'); ?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="/markdown/markdown/style.css"/>
	<link rel="stylesheet" href="http://yandex.st/highlightjs/6.2/styles/default.min.css">
    <meta name="content-type" http-equiv="content-type" value="text/html; utf-8"/>
    <script src="http://yandex.st/highlightjs/6.2/highlight.min.js"></script>
	
  </head>
  <body>
<?php

$SERVERROOT = "/var/www";
require('markdown.php');

$legalExtensions = array('md', 'markdown');

$file = $_GET['file'];
//$file = realpath($_SERVER['PATH_TRANSLATED']);
$file = $SERVERROOT . $file;

if($file &&
   in_array(strtolower(substr($file, strrpos($file, '.') + 1)),
	    $legalExtensions)) {
  echo Markdown(file_get_contents($file));
}
else {
  echo "<p>Bad filename given</p>";
}
?>
  </body>
</html>