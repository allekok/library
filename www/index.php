<?php
require_once("constants.php");
require_once("Ps.php");

$dark = isset($_GET["dark"]) || (@$_COOKIE["theme"] == "dark");

$tbl = @strtolower($_GET['table']);
if(!$tbl) $tbl = @$_COOKIE["table"];
$site_lang = @strtolower($_GET['lang']);
if(!$site_lang) $site_lang = @$_COOKIE["lang"];
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
	<link rel="stylesheet" href="style/<?php 
					   echo $dark ? 
						"style-dark-comp.css" :
						"style-comp.css";
					   ?>" />
	<meta name='viewport' content='width=device-width, initial-scale=1' />
	<style>
	 input[type=text]
	 {
	     direction:<?php echo ($tbl == 'en') ?
				  "ltr" : "rtl"; ?>;
	 }
	 #response
	 {
	     direction:<?php echo ($tbl == 'en') ?
				  "ltr" : "rtl"; ?>;
	 }
	</style>
    </head>
    <body>
	<header>
	    <h1>
		<?php P("site title"); ?>
	    </h1>
	    <a href="<?php echo $dark ? "?" : "?dark"; ?>"
	       class="icon" id="dark-icon"
	    ><?php echo $dark ? "brightness_5" :
			"brightness_2"; ?></a>
	    <div class="dropdown" id="dd-lang">
		<div class="dd-label" id="dd-lang-label"
		><?php P("site lang"); ?>
		    <span class='icon'
		    >keyboard_arrow_down</span></div>
		<div class="dd-frame">
		    <ul>
			<?php
			foreach(AVAILABLE_LANGS as $i => $L)
			{
			    echo "<li>";
			    if($site_lang != $L)
				echo "<a href='?lang={$L}' onclick='set_lang(\"{$L}\")'>" .
				     LANGS_LIT[$i] . "</a></li>";
			    else
				echo LANGS_LIT[$i] . "</li>";
			}
			?>
		    </ul>
		</div>
	    </div>
	</header>
	<?php
	/* List fields */
	if(!in_array($tbl, SQL_TABLES))
	    $tbl = SQL_TABLE_DEF;
	$sql = mysqli_connect(SQL_HOST,SQL_USER,SQL_PASS,SQL_DATABASE);
	$query = "DESCRIBE `{$tbl}`";
	$result = mysqli_query($sql, $query);
	if(!$result) die();

	$main_html = "";
	$not_main_html = "";
	echo "<form action='find.php' method='post' id='the-form'>";
	while($c = mysqli_fetch_assoc($result))
	{
	    $field_name = str_replace(["."," "], "_", $c["Field"]);
	    if(in_array($c["Field"], DEF_COLS[$tbl]))
		$main_html .= "<div class='row'><input type='text' name='{$field_name}' placeholder='{$c["Field"]}'></div>";
	    else
		$not_main_html .= "<div class='row'><input type='text' name='{$field_name}' placeholder='{$c["Field"]}'></div>";
	}
	echo "<div id='form-main'>{$main_html}</div>";
	echo "<div id='form-not-main' style='display:none'>{$not_main_html}</div>";
	echo "<button type='button' class='more-btn' id='more-btn'>" . SP("more") . "...</button>";
	echo "<input type='hidden' name='table' value='{$tbl}' />";
	echo "<button type='submit'>" . SP("send") . "</button>";
	echo "</form>";

	mysqli_close($sql);
	?>
	<div class="loading" id="main-loading" style="display:none"></div>
	<div id="response"></div>
	
	<script>
	 const site_lang = "<?php echo $site_lang; ?>";
	 const site_dir = site_lang == "en" ? "ltr" : "rtl";
	 const site_align = site_lang == "en" ? "left" : "right";
	 const lang = "<?php echo $tbl; ?>";
	 const dir = lang == "en" ? "ltr" : "rtl";
	 const align = lang == "en" ? "left" : "right";
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
	 
	 function search (target_id="response")
	 {
	     const loadingDiv = document.getElementById("main-loading");
	     const form = document.getElementById("the-form");
	     const target = document.getElementById(target_id);
	     const main_fields = {fa : ["ردیف","عنوان‏","پديدآور"],
				  en : ["id","Title‎","Author‎"]};
	     let request = "";
	     let empty = true;
	     form.querySelectorAll("input").forEach(function (o) {
		 const k = encodeURIComponent(o.name);
		 const v = encodeURIComponent(o.value.trim());
		 if(v != "")
		 {
		     request += `${k}=${v}&`;
		     if(k != "table")
			 empty = false;
		 }
	     });
	     if(empty)
	     {
		 target.innerHTML = `<p style='direction:${site_dir};text-align:${site_align}'><?php P("empty error"); ?></p>`;
		 return;
	     }
	     loadingDiv.style.display = "block";
	     postUrl("find.php", request, function (response) {
		 try
		 {
		     response = JSON.parse(response);
		 }
		 catch (e)
		 {
		     response = false;
		 }
		 if(response)
		 {
		     let html = "<ul>";
		     for(const i in response)
		     {
			 html += "<li>";
			 let html_m = "<div class='li-main'>";
			 let html_n_m = "<div class='li-not-main' style='display:none'>";
			 for(const j in response[i])
			 {
			     if(! response[i][j]) continue;
			     if(main_fields[lang].indexOf(j) !== -1)
				 html_m += `${j}:<br><i style='padding-${align}:1.5em'>${response[i][j]}</i><br>`;
			     else
				 html_n_m += `${j}:<br><i style='padding-${align}:1.5em'>${response[i][j]}</i><br>`;
			 }
			 html_m += "</div>";
			 html_n_m += "</div>";
			 html += html_m + html_n_m + "<button type='button' \
onclick='more(this)' class='more-btn'><?php P("more"); ?>...</button></li>";
		     }
		     if(response.length == 0)
		     {
			 html += "<p><?php P("not found"); ?></p>";
		     }
		     html += "</ul>";
		     target.innerHTML = html;
		     loadingDiv.style.display = "none";
		 }
		 else
		 {
		     target.innerHTML = "<p><?php P("not found"); ?></p>";
		     loadingDiv.style.display = "none";
		 }
	     });
	 }

	 const more_btn = document.getElementById("more-btn");
	 more_btn.addEventListener("click", function () {
	     const form_not_main = document.getElementById("form-not-main");
	     if(form_not_main.style.display != "none")
	     {
		 form_not_main.style.display = "none";
		 more_btn.innerText = "<?php P("more") ?>...";
	     }
	     else
	     {
		 form_not_main.style.display = "block";
		 more_btn.innerHTML = "<i class='icon'>keyboard_arrow_up</i> <?php P("less") ?>";
	     }
	 });

	 function more (btn)
	 {
	     const li_n_m = btn.parentNode.
				querySelector(".li-not-main");
	     if(li_n_m.style.display == "none")
	     {
		 li_n_m.style.display = "block";
		 btn.innerHTML = "<?php P("less"); ?> <i class='icon'>keyboard_arrow_up</i>";
	     }
	     else
	     {
		 li_n_m.style.display = "none";
		 btn.innerText = "<?php P("more"); ?>...";
	     }
	 }
	 
	 document.getElementById("the-form").addEventListener("submit", function(e) {
	     e.preventDefault();
	     search();
	 });

	 function set_cookie (cookie_name, value, days=1000, path="")
	 {
	     let expires = new Date();
	     expires.setTime(expires.getTime() + (days*24*3600*1000));
	     expires = expires.toUTCString();
	     const cookie = `${cookie_name}=${value};expires=${expires};path=${path}`;
	     document.cookie = cookie;
	     return cookie;
	 }

	 const dark_icon = document.getElementById("dark-icon");
	 dark_icon.addEventListener("click", function (e) {
	     e.preventDefault();
	     
	     if(dark_icon.innerText == "brightness_2")
	     {
		 /* Light */
		 set_cookie("theme", "dark");
		 window.location.reload();
	     }
	     else
	     {
		 /* Dark */
		 set_cookie("theme", "light");
		 window.location.reload();
	     }
	     dark_icon.innerText = "sync";
	 });

	 const dd_lang = document.getElementById("dd-lang");
	 const dd_lang_label = dd_lang.querySelector(".dd-label");
	 const dd_lang_frame = dd_lang.querySelector(".dd-frame");
	 dd_lang_label.addEventListener("click", function () {
	     const dd_lang_label_icon = dd_lang_label.querySelector(".icon");
	     if(dd_lang_frame.style.display != "block")
	     {
		 dd_lang_frame.style.display = "block";
		 dd_lang_label_icon.innerText = "keyboard_arrow_up";
	     }
	     else
	     {
		 dd_lang_frame.style.display = "none";
		 dd_lang_label_icon.innerText = "keyboard_arrow_down";
	     }
	 });

	 function set_lang (lang)
	 {
	     set_cookie("lang", lang);
	     window.location.reload();
	 }
	</script>
    </body>
</html>
