<?php header('Content-type: text/html; charset=utf-8'); ?>
<?php

include_once '../src/Epi/Epi.php';
Epi::setPath('base', '../src/Epi');
Epi::init('route');
// Epi::init('base','cache','session');
// Epi::init('base','cache-apc','session-apc');
// Epi::init('base','cache-memcached','session-apc');

/*
 * This is a sample page whch uses EpiCode.
 * There is a .htaccess file which uses mod_rewrite to redirect all requests to index.php while 
 * preserving GET parameters.
 * The $_['routes'] array defines all uris which are handled by EpiCode.
 * EpiCode traverses back along the path until it finds a matching page.
 *  i.e. If the uri is /foo/bar and only 'foo' is defined then it will execute that route's action.
 * It is highly recommended to define a default route of '' for the home page or root of the site 
 * (yoursite.com/).
 */
getRoute()->get(	'/', 		array('Markdoco', 'Welcome'));
getRoute()->get(	'/up/.*', 	array('Markdoco', 'getMarkUp'));
getRoute()->get(	'/down/.*', array('Markdoco', 'getMarkDown'));
getRoute()->post(	'/down/.*', array('Markdoco', 'setMarkDown'));
getRoute()->run(); 





/*
 * ******************************************************************************************
 * Define functions and classes which are executed by EpiCode based on the $_['routes'] array
 * ******************************************************************************************
 */
class Markdoco
{


  static public function Welcome()
  	{
  	 	
  	 	require('../src/Markdoco/config.php');
  		echo '<h1>Markdoco</h1>
          <ul>
            <li><a href="'. $APP_CONTEXT .'/a/display?file=ReadMe.md">Wiki Read Me</a></li>
          </ul>';
	}


  static public function getMarkUp() {

	    require('../src/Markdoco/markdown.php');
		require('../src/Markdoco/config.php');	

		$displayContext = $APP_CONTEXT."/a/up/";

		// Pull document location from request
		$documentURI = $_SERVER['REQUEST_URI'];
		$documentURI = substr($documentURI, strlen($displayContext));
		$file = $DOCUMENT_ROOT ."/". $documentURI;
	
	
		if($file &&
		   in_array(strtolower(substr($file, strrpos($file, '.') + 1)),
				$LEGAL_EXTENSIONS)) {
		  echo Markdown(file_get_contents($file));
		}
		else {
		  echo "Bad filename given".$file;
		}
	}
	
	static public function getMarkDown() {

	    require('../src/Markdoco/markdown.php');
		require('../src/Markdoco/config.php');	

		$displayContext = $APP_CONTEXT."/a/down/";

		// Pull document location from request
		$documentURI = $_SERVER['REQUEST_URI'];
		$documentURI = substr($documentURI, strlen($displayContext));
		$file = $DOCUMENT_ROOT ."/". $documentURI;
	
		if($file &&
		   in_array(strtolower(substr($file, strrpos($file, '.') + 1)),
				$LEGAL_EXTENSIONS)) {
		  echo file_get_contents($file);
		}
		else {
		  echo "Bad filename given".$file;
		}
	
	}
	

	static public function setMarkDown() {

		require('../src/Markdoco/config.php');	
		
		$displayContext = $APP_CONTEXT."/a/down/";
		
		// Pull document location from request
		$documentURI = $_SERVER['REQUEST_URI'];
		$documentURI = substr($documentURI, strlen($displayContext));
		$file = $DOCUMENT_ROOT ."/". $documentURI;
		
		$data = $_POST['data'];
		
		try {
			if($file &&
			   in_array(strtolower(substr($file, strrpos($file, '.') + 1)),
					$LEGAL_EXTENSIONS)) {
			
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
			die ("Bugger, save failed". $e->getMessage());
		 }
	}
}

?>