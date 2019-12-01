<?php
require("xml-lib.php");
require("mysql-constants.php");

const XML_FILES = ["data/SIMORGH-en.XML",
		   "data/SIMORGH-fa.XML"];

// MySQL Connection
$SQL = mysqli_connect(SQL_HOST, SQL_USER, SQL_PASS) or die("Mysql connection failed.\n");

// Create Database
$Q = "CREATE DATABASE IF NOT EXISTS `" . SQL_DATABASE . "`";
$R = mysqli_query($SQL, $Q);
if(!$R) die("Mysql creating database failed.\n{$Q}\n");

mysqli_select_db($SQL, SQL_DATABASE);

// Create Table(s)
foreach(SQL_TABLES as $T)
{
    $COLUMNS = sql_columns(list_fields($T));
    $Q = "CREATE TABLE IF NOT EXISTS `{$T}` ({$COLUMNS})";
    $R = mysqli_query($SQL, $Q);
    if(!$R) die("Mysql creating table `{$T}` failed.\n{$Q}\n");
    
    // Truncate Table(s)
    $Q = "TRUNCATE TABLE `{$T}`";
    $R = mysqli_query($SQL, $Q);
    if(!$R) die("Mysql truncating table `{$T}` failed.\n{$Q}\n");
}

// Import data
foreach(XML_FILES as $L => $XML_FILE)
{
    $COLUMNS = list_fields(SQL_TABLES[$L]);
    $COLUMNS_STR = "";
    foreach($COLUMNS as $COL)
    {
	$COLUMNS_STR .= "`{$COL}`,";
    }
    $COLUMNS_STR = substr($COLUMNS_STR, 0, -1);
    
    $XML = tokenize(file_get_contents($XML_FILE));
    $RECORDS = extract_tags($XML, "<Record", "</Record>");
    foreach($RECORDS as $I => $R)
    {
	$FIELDS_NAMES = tag_names($R, "<Field");
	$FIELDS = extract_tags($R, "<Field", "</Field>");
	$C = [];
	foreach($COLUMNS as $_)
	{
	    $C[] = "''";
	}
	foreach($FIELDS as $II => $F)
	{
	    $SUBFIELDS_NAMES = tag_names($F, "<SubField");
	    $SUBFIELDS = extract_tags($F, "<SubField", "</SubField>");
	    $V = "";
	    foreach($SUBFIELDS as $III => $S)
	    {
		if((sanitize_string($SUBFIELDS_NAMES[$III]) !=
		    sanitize_string($FIELDS_NAMES[$II])) or
		    (count($SUBFIELDS) > 1))
		$V .= sanitize_string($SUBFIELDS_NAMES[$III]) . ": ";
		$V .= $S[0] . "\n";
	    }
	    $V = trim(addslashes($V));	    
	    $INDEX = array_search(sanitize_string($FIELDS_NAMES[$II]), $COLUMNS);
	    $C[$INDEX] = "'{$V}'";
	}
	$C = implode("," , $C);
	
	// Save Record
	$Q = "INSERT INTO `" . SQL_TABLES[$L] . "` ({$COLUMNS_STR}) VALUES ({$C})";
	$RESULT = mysqli_query($SQL, $Q);
	if(! $RESULT) die("Mysqli row insertion failed.\n{$Q}\n");
	echo "-> Record {$I} inserted successfully.\n";
    }
}
?>
