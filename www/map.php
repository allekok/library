<?php
require_once("constants.php");
require_once("lib.php");
const map_path = "lib/data/map/map.json";
$label = isset($_REQUEST["label"]) ?
	 $_REQUEST["label"] : die();
$site_lang = isset($_REQUEST["lang"]) ?
	     filter_var($_REQUEST["lang"], FILTER_SANITIZE_STRING) :
	     die();
if(!in_array($site_lang, AVAILABLE_LANGS))
    die();
$align = $site_lang == "en" ? "left" : "right";
require_once("Ps.php");
$map = json_decode(file_get_contents(map_path), true);
$L = $map[$label];
if(!$L) die();
$html = "";
foreach($L as $SET => $columns) {
    $html .= SP("set") . ":" . num_convert($SET, "en", $site_lang) . "<br>\n";
    foreach($columns as $COL => $_ROWS) {
	$html .= "<i style='padding-{$align}:2em'>" .
		 SP("col") . ": " . num_convert($COL, "en", $site_lang) . "</i><br>\n";
	$ROWS = num_convert(implode(", ", $_ROWS), "en", $site_lang);
	$html .= "<i style='padding-{$align}:4em'>" .
		 SP("row") . ": " . $ROWS . "</i><br>\n";
    }
}
echo $html;
?>
