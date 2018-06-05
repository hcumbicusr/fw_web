<?php 
function getRenderedHTML($path)
{
  global $lang;
  ob_start();
  include($path);
  $var=ob_get_contents(); 
  ob_end_clean();
  return $var;
}

$path = "pages";
$ext = "php";
$lang = "es_ES";

$str = $_SERVER['QUERY_STRING'];
$params = [];
parse_str($str, $params);

session_start();
require_once 'core/helpers/constantes.php';
if ( empty($_SESSION) ) {
  echo getRenderedHTML("$path/login.$ext");
} else {
	if ( empty($_SESSION['name_session']) || $_SESSION['name_session'] != PY_NAME ) {
		session_destroy();
		echo getRenderedHTML("$path/login.$ext");
	} else {
		$view =!empty($params['view'])? $params['view'] : 'home';
		echo getRenderedHTML("$path/$view.$ext");
	}
}