<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php

require('markdown.php');
require('config.php');

$LEGAL_EXTENSION= array('md', 'markdown');

$file = $_GET['file'];

$file = $DOCUMENT_ROOT . $file;

if($file &&
   in_array(strtolower(substr($file, strrpos($file, '.') + 1)),
	    $LEGAL_EXTENSION)) {
  echo Markdown(file_get_contents($file));
}
else {
  echo "<p>Bad filename given</p>";
}
?>
