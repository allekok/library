<?php
require_once("constants.php");
require_once("Ps.php");

$site_lang = @strtolower($_GET['lang']);
if(!in_array($site_lang, AVAILABLE_LANGS))
    $site_lang = SITE_LANG_DEF;
$html_dir = ($site_lang == 'en') ? 'ltr' : 'rtl';
$html_lang = $site_lang;
$html_attr = "dir='{$html_dir}' lang='{$html_lang}'";
?>
<!DOCTYPE html>
<html <?php echo $html_attr; ?>>
    <head>
	<title>
	    <?php P("site title"); ?>
	</title>
	<meta charset="utf-8" />
	<link rel="stylesheet" href="style.css" />
	<meta name='viewport' content='width=device-width, initial-scale=1' />
    </head>
    <body>
	<header>
	    <h1>
		<?php p("site title"); ?>
	    </h1>
	</header>
	<?php
	/* List fields */
	$tbl = @strtolower($_GET['table']);
	if(!in_array($tbl, SQL_TABLES))
	    $tbl = SQL_TABLE_DEF;
	$sql = mysqli_connect(SQL_HOST,SQL_USER,SQL_PASS,SQL_DATABASE);
	$query = "DESCRIBE `{$tbl}`";
	$result = mysqli_query($sql, $query);
	if(!$result) die();

	echo "<form action='find.php' method='post'>";
	while($c = mysqli_fetch_assoc($result))
	{
	    $field_name = str_replace(["."," "], "_", $c["Field"]);
	    echo "<div class='row'><input type='text' name='{$field_name}' placeholder='{$c["Field"]}'></div>";
	}
	echo "<input type='hidden' name='table' value='{$tbl}' />";
	echo "<button type='submit'>" . SP("send") . "</button>";
	echo "</form>";

	mysqli_close($sql);
	?>

	<script>
	 function postUrl (url, request, callback)
	 {
	     const client = new XMLHttpRequest();
	     client.open('post', url);
	     client.onload = function ()
	     {
		 callback(this.responseText);
	     }
	     client.setRequestHeader(
		 "Content-type","application/x-www-form-urlencoded");
	     client.send(request);
	 }
	 function search (key)
	 {
	     key = encodeURIComponent(key, lang='fa');
	     const req = `find.php?q=${key}&lang=${}`;
	 }
	</script>
    </body>
</html>
