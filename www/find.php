<?php
$ar_signs =["ِ", "ُ", "ٓ", "ٰ", "ْ", "ٌ", "ٍ", "ً", "ّ", "َ"];
$extras = ["?", "!", "#", "&","*", "(", ")", "-","+",
	   "=", "_","[", "]", "{","}","<",">","\\","/",
	   "|", "'","\"", ";", ":", ",",".", "~", "`",
	   "؟", "،", "»", "«","ـ","؛","›","‹","•","‌",
	   "\u{200E}","\u{200F}"];
$_assoc = [
    'en' => ['0','1','2','3','4','5','6','7','8','9'],	
    'fa' => ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'],
    'ckb' => ['٠', '١', '٢', '٣', '٤','٥', '٦', '٧', '٨', '٩'],
];

// From php.net's manual
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

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

$die = true;
$queries = [];
foreach($_REQUEST as $k => $q)
{
    if(false !== ($i = array_search($k, $fields["comp"])))
    {
	$queries[$fields["orig"][$i]] =
	    @san_text(filter_var($q, FILTER_SANITIZE_STRING));
	if($q != "") $die = false;
    }
}
if($die) die(json_encode([]));

$query = "SELECT * FROM `{$tbl}`";
$result = mysqli_query($sql, $query);
if(! $result) die();

$limit = @filter_var($_REQUEST["limit"], FILTER_VALIDATE_INT) ?
	 $_REQUEST["limit"] : 10;
$R = [];
while($r = mysqli_fetch_assoc($result))
{    
    foreach($r as $k => $d)
    {
	if(false !== @strpos(san_text($d), $queries[$k]))
	{
	    if(! $limit-- ) break 2;
	    if($tbl == "fa")
	    {
		$rk = array_key_first($r);
		$r[$rk] = num_convert($r[$rk], "en", "fa");
	    }
	    $R[] = $r;
	}
    }
}

echo json_encode($R);

mysqli_close($sql);

function san_text ($s)
{
    global $ar_signs, $extras;
    $s = str_replace($extras, "", $s);
    $s = str_replace($ar_signs, "", $s);
    $s = str_replace(["ي","ك"], ["ی","ک"], $s);
    $s = strtolower($s);
    $s = num_convert($s, "fa", "en");
    $s = num_convert($s, "ckb", "en");
    $s = preg_replace("/\s+/u", "", $s);
    return $s;
}

function num_convert($_string, $_from, $_to)
{
    /* Convert a string of numbers from 
       (en,fa,ckb) > (en,fa,ckb) */
    global $_assoc;
    return str_replace($_assoc[$_from],
		       $_assoc[$_to],
		       $_string);
}
?>
