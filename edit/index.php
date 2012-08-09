<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php

require('../display/config.php');

$LEGAL_EXTENSION= array('md', 'markdown');

$file = $_POST['file'];
$data = $_POST['data'];

$file = $DOCUMENT_ROOT . $file;
try {
	if($file &&
	   in_array(strtolower(substr($file, strrpos($file, '.') + 1)),
		    $LEGAL_EXTENSION)) {
	
		$myFile = $file;
		$fh = fopen($myFile, 'w') or die("can't open file");
		$stringData = $data;
		fwrite($fh, $stringData);
		fclose($fh);
	}
	else {
  		echo "save failed: file extention incorrect";
	}
 } catch (Exception $e) {
 	echo "Bugger, save failed". $e->getMessage();
 	die("Bugger, save failed". $e->getMessage());
 }
?>
