<?php
require("constants.php");
$lang = @$_GET['lang'];
if(!in_array($lang, SQL_TABLES))
    $lang = SQL_TABLE_DEF;

$sql = mysqli_connect(SQL_HOST,SQL_USER,SQL_PASS,SQL_DATABASE);
$query = "DESCRIBE `{$lang}`";
$result = mysqli_query($sql, $query);
if(!$result) die();

while($c = mysqli_fetch_assoc($result))
    echo $c["Field"] . "\n";

mysqli_close($sql);
?>
