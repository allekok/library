<?php
require_once("constants.php");
require_once("lib.php");
require_once("Ps.php");

const map_path = "lib/data/map/map.json";

header("Content-type:text/html; Charset=UTF-8");

$label = isset($_REQUEST["label"]) ? $_REQUEST["label"] : die();
$site_lang = isset($_REQUEST["lang"]) ?
	     filter_var($_REQUEST["lang"], FILTER_SANITIZE_STRING) :
	     die();
if(!in_array($site_lang, AVAILABLE_LANGS))
	die();

$align = $site_lang == "en" ? "left" : "right";
$map = json_decode(file_get_contents(map_path), true);
if(!($L = $map[$label]))
	die();
$html = "";

foreach($L as $SET => $columns) {
	$html .= "-&rsaquo; " . SP("set") . ":" .
		 num_convert($SET, "en", $site_lang) .
		 "<br>\n";

	foreach($columns as $COL => $_ROWS) {
		$ROWS = num_convert(implode(", ", $_ROWS), "en", $site_lang);
		$html .= "<i style='padding-{$align}:2em'>-&rsaquo; " .
			 SP("col") . ": " .
			 num_convert($COL, "en", $site_lang) .
			 "</i><br>\n" .
			 "<i class='icon' style='padding-{$align}:4em'>" .
			 "arrow_downward</i> <i>" .
			 SP("row") . ": " .
			 $ROWS .
			 "</i><br>\n";
	}
}

echo $html;
?>
