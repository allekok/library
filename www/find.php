<?php
header("Content-type:text/plain; Charset=UTF-8");
require("constants.php");
$tbl = @$_REQUEST['table'];
if(!in_array($tbl, SQL_TABLES))
    $tbl = SQL_TABLE_DEF;

$sql = mysqli_connect(SQL_HOST,SQL_USER,SQL_PASS,SQL_DATABASE);
$query = "DESCRIBE `{$tbl}`";
$result = mysqli_query($sql, $query);
if(!$result) die();

$fields = [];
while($c = mysqli_fetch_assoc($result))
{
    $fields["orig"][] = $c["Field"];
    $fields["comp"][] = str_replace(["."," "], "_", $c["Field"]);
}

$queries = [];
foreach($_REQUEST as $k => $q)
{
    if(false !== ($i = array_search($k, $fields["comp"])))
	$queries[$fields["orig"][$i]] = $q;
}
$query = "SELECT * FROM `{$tbl}`";
$result = mysqli_query($sql, $query);
if(! $result) die();

$limit = 10;
while($r = mysqli_fetch_assoc($result))
{
    
    foreach($r as $k => $d)
    {
	if(false !== @strpos($d, $queries[$k]))
	{
	    if(! $limit-- ) break 2;
	    print_r($r);
	}
    }
}

mysqli_close($sql);
?>
