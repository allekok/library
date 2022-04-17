<?php
require_once("xml-lib.php");

$LANG = (@$argv[1] == "fa") ? "fa" : "en";
$PATH = "data/SIMORGH-{$LANG}.XML";

mkdir("out/{$LANG}", 0755, TRUE);
$XML = file_get_contents($PATH);
$TOKENS = tokenize($XML);
$RECORDS = extract_tags($TOKENS, "<Record", "</Record>");

foreach($RECORDS as $I => $R) {
	$FIELDS_NAMES = tag_names($R, "<Field");
	$FIELDS = extract_tags($R, "<Field", "</Field>");

	$O = fopen("out/{$LANG}/{$I}", "w");

	foreach($FIELDS as $II => $F) {
		$SUBFIELDS_NAMES = tag_names($F, "<SubField");
		$SUBFIELDS = extract_tags($F, "<SubField", "</SubField>");

		$F_NAME = sanitize_string($FIELDS_NAMES[$II]);
		fwrite($O, "{$F_NAME}:\n");

		foreach($SUBFIELDS as $III => $S) {
			$S_NAME = sanitize_string($SUBFIELDS_NAMES[$III]);
			$S_0 = sanitize_string($S[0]);
			fwrite($O, "\t{$S_NAME}: {$S_0}\n");
		}
	}

	echo "-> out/{$LANG}/{$I}\n";
	fclose($O);
}
?>
