<?php
require_once("constants.php");
require_once("lib.php");

header("Content-type:application/json; Charset=UTF-8");

$tbl = isset($_REQUEST["table"]) ? $_REQUEST["table"] : "";
if(!in_array($tbl, SQL_TABLES))
	$tbl = SQL_TABLE_DEF;

$sql = mysqli_connect(SQL_HOST, SQL_USER, SQL_PASS, SQL_DATABASE);
$query = "DESCRIBE `{$tbl}`";
$result = mysqli_query($sql, $query);
if(!$result)
	die();

$fields = [];
while($c = mysqli_fetch_assoc($result)) {
	$fields["orig"][] = $c["Field"];
	$fields["comp"][] = str_replace([".", " "], "_", $c["Field"]);
}

$die = true;
$queries = [];
foreach($_REQUEST as $k => $q) {
	if(false !== ($i = array_search($k, $fields["comp"]))) {
		$queries[$fields["orig"][$i]] =	@san_text(filter_var(
			$q, FILTER_SANITIZE_STRING));
		if($q != "")
			$die = false;
	}
}
if($die)
	die(json_encode([]));

$query = "SELECT * FROM `{$tbl}`";
$result = mysqli_query($sql, $query);
if(!$result)
	die();

$limit = @num_convert($_REQUEST["limit"], "ckb", "en");
$limit = num_convert($limit, "fa", "en");
$limit = intval($limit);
if(!$limit)
	$limit = 10;

$R = [];
while($r = mysqli_fetch_assoc($result)) {
	foreach($r as $k => $d) {
		$ned = @$queries[$k];
		if($ned and @strpos(san_text($d), $ned) !== false) {
			if($limit-- == 0)
				break 2;
			if($tbl == "fa") {
				$rk = array_key_first($r);
				$r[$rk] = num_convert($r[$rk], "en", "fa");
			}
			$R[] = $r;
		}
	}
}

echo json_encode($R);

mysqli_close($sql);
?>
