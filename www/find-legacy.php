<?php
const VALID_LANGS = ["en", "fa"];
const VALID_OPERATORS = ["AND" , "OR"];
const DEFAULT_OP = "AND";
const DEFAULT_LANGS = "en,fa";
const DEFAULT_N = 20;

header("Content-type:text/plain; Charset=UTF-8");

require("../xml/mysql-constants.php");
$sql = mysqli_connect(SQL_HOST,SQL_USER,SQL_PASS,SQL_DATABASE);

$langs = @filter_var($_REQUEST['langs'], FILTER_SANITIZE_STRING);
$fields = @filter_var($_REQUEST['fields'], FILTER_SANITIZE_STRING);
$qs = @filter_var($_REQUEST['qs'], FILTER_SANITIZE_STRING);
$op = @filter_var($_REQUEST['op'], FILTER_SANITIZE_STRING);
$n = @filter_var($_REQUEST['n'], FILTER_VALIDATE_INT) ? $n : DEFAULT_N;

if(! $langs) $langs = DEFAULT_LANGS;
if(! $fields) die("Error: Empty Fields.\n");
if(! $qs) die("Error: Empty Queries.\n");
if(! in_array(strtoupper($op), VALID_OPERATORS)) $op = DEFAULT_OP;

$langs = explode("," , $langs);
$fields = explode("," , $fields);
$qs = explode("," , $qs);

$where_query = [];
foreach($fields as $i => $f)
{
    $where_query[$i] = "`{$f}`='{$qs[$i]}'";
}
$where_query = implode(" {$op} ", $where_query);

foreach($langs as $lang)
{
    if(! in_array($lang, VALID_LANGS) ) continue;
    
    $query = "SELECT * FROM `{$lang}` WHERE {$where_query}";
    $result = mysqli_query($sql, $query);

    if(! $result) continue;
    
    while(($R = mysqli_fetch_assoc($result)) and $n--)
    {
	echo implode("\n", $R);
	echo "+++++++++++++++++++++++++++++++++++++++++\n";
    }
}

mysqli_close($sql);
?>
