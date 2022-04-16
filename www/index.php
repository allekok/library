<?php
require_once("constants.php");
require_once("Ps.php");
require_once("lib.php");

$dark = isset($_GET["dark"]) || (@$_COOKIE["theme"] == "dark");
$LIMIT = isset($_COOKIE["limit"]) ? $_COOKIE["limit"] : 10;
$tbl = isset($_GET["table"]) ? strtolower($_GET["table"]) :
       (isset($_COOKIE["table"]) ? $_COOKIE["table"] : "");
$site_lang = isset($_GET["lang"]) ? strtolower($_GET["lang"]) :
	     (isset($_COOKIE["lang"]) ? $_COOKIE["lang"] : "");

if(!in_array($site_lang, AVAILABLE_LANGS))
	$site_lang = SITE_LANG_DEF;

$html_dir = $site_lang == "en" ? "ltr" : "rtl";
$html_lang = $site_lang;
$html_attr = "dir='{$html_dir}' lang='{$html_lang}'";
?>
<!DOCTYPE html>
<html <?php echo $html_attr; ?>>
	<head>
		<title>
			<?php P("site title"); ?>
		</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width">
		<link rel="stylesheet" href="style/<?php 
						   echo $dark ? 
							"style-dark-comp.css" :
							"style-comp.css";
						   ?>?v2">
		<style>
		 #response {
			 direction:<?php echo $tbl == "en" ? "ltr" : "rtl"; ?>
		 }
		</style>
		<script>
		 navigator.serviceWorker.register('./sw.js')
		</script>
	</head>
	<body>
		<header>
			<h1>
				<?php P("site title"); ?>
			</h1>
			<a href="<?php echo $dark ? "?" : "?dark"; ?>"
			   class="icon"
			   id="dark-icon">
				<?php
				echo $dark ? "brightness_5" : "brightness_2";
				?>
			</a>
			<div class="dropdown" id="dd-lang">
				<div class="dd-label">
					<?php P("site lang"); ?>
					<span class="icon">
						keyboard_arrow_down
					</span>
				</div>
				<?php
				echo "<div class='dd-frame'><ul>";
				foreach(AVAILABLE_LANGS as $i => $L) {
					echo "<li>";
					if($site_lang != $L) {
						echo "<a href='?lang={$L}'" .
						     " onclick='set_lang(" .
						     "\"{$L}\", event)'>" .
						     LANGS_LIT[$i] .
						     "</a></li>";
					}
					else
						echo LANGS_LIT[$i] . "</li>";
				}
				echo "</ul></div>";
				?>
			</div>
		</header>
		<?php
		/* List fields */
		if(!in_array($tbl, SQL_TABLES))
			$tbl = SQL_TABLE_DEF;
		
		$sql = mysqli_connect(SQL_HOST,
				      SQL_USER,
				      SQL_PASS,
				      SQL_DATABASE);
		$query = "DESCRIBE `{$tbl}`";
		$result = mysqli_query($sql, $query);
		if(!$result)
			die();
		
		$main_html = "";
		$not_main_html = "";
		echo "<form action='find.php' method='post' id='the-form'>";
		?>
		<?php P("search in"); ?>: 
		<div class="dropdown" id="dd-table">
			<div class="dd-label">
				<?php
				$_ = array_search($tbl, SQL_TABLES);
				if($_ !== false)
					P(TABLES_LIT[$_]);
				?>
				<span class="icon">
					keyboard_arrow_down
				</span>
			</div>
			
			<?php
			echo "<div class='dd-frame'><ul>";
			foreach(SQL_TABLES as $i => $L) {
				echo "<li>";
				if($tbl != $L) {
					echo "<a href='?table={$L}' " .
					     "onclick='set_table(\"{$L}\"," .
					     " event)'>" .
					     SP(TABLES_LIT[$i]) .
					     "</a></li>";
				}
				else
					echo SP(TABLES_LIT[$i]) . "</li>";
			}
			echo "</ul></div>";
			?>
		</div>
		<?php
		while($c = mysqli_fetch_assoc($result))	{
			$field_name = str_replace([".", " "],
						  "_",
						  $c["Field"]);
			
			if(in_array($c["Field"], DEF_COLS[$tbl])) {
				$main_html .= "<input type='text' " .
					      "name='{$field_name}' " .
					      "placeholder='" .
					      SP($c["Field"]) .
					      "'>";
			} else {
				$not_main_html .= "<input type='text' " .
						  "name='{$field_name}' " .
						  "placeholder='" .
						  SP($c["Field"]) .
						  "'>";
			}
		}
		echo "<div id='form-main'>{$main_html}</div>";
		echo "<div id='form-not-main' style='display:none'>" .
		     $not_main_html .
		     "</div>";
		echo "<button type='button' class='more-btn' id='more-btn'>" .
		     SP("more") .
		     "...</button>";
		echo "<input type='hidden' name='table' value='{$tbl}'>";
		echo "<div class='row'><label for='limitTxt'>" .
		     SP("number of results") .
		     ": </label><input type='text' name='limit' " .
		     "id='limitTxt' value='" .
		     num_convert($LIMIT, "en", $site_lang) .
		     "'></div>";
		echo "<button type='submit'>" . SP("send") . "</button>";
		echo "</form>";
		
		mysqli_close($sql);
		?>
		<div class="loading" id="main-loading" style="display:none">
		</div>
		<div id="response"></div>
		<footer>
			<a href="/"><?php P("home"); ?></a>
		</footer>

		<script defer src="script.js?v3"></script>
	</body>
	<script>
	 /* Constants */
	 const site_lang = '<?php echo $site_lang; ?>',
	       site_dir = site_lang == 'en' ? 'ltr' : 'rtl',
	       site_align = site_lang == 'en' ? 'left' : 'right',
	       lang = '<?php echo $tbl; ?>',
	       dir = lang == 'en' ? 'ltr' : 'rtl',
	       align = lang == 'en' ? 'left' : 'right'
	 
	 const more_btn = document.getElementById('more-btn'),
	       the_form = document.getElementById('the-form'),
	       dark_icon = document.getElementById('dark-icon'),
	       dd_lang = document.getElementById('dd-lang'),
	       dd_lang_label = dd_lang.querySelector('.dd-label'),
	       dd_lang_frame = dd_lang.querySelector('.dd-frame'),
	       dd_table = document.getElementById('dd-table'),
	       dd_table_label = dd_table.querySelector('.dd-label'),
	       dd_table_frame = dd_table.querySelector('.dd-frame')

	 /* Events */
	 more_btn.addEventListener('click', () => {
		 const form_not_main = document.getElementById('form-not-main')
		 if(form_not_main.style.display != 'none') {
			 form_not_main.style.display = 'none'
			 more_btn.innerText = '<?php P("more") ?>...'
		 }
		 else {
			 form_not_main.style.display = 'block'
			 more_btn.innerHTML = '<i class="icon">' +
					      'keyboard_arrow_up</i> ' +
					      '<?php P("less") ?>'
		 }
	 })
	 
	 the_form.addEventListener('submit', e => {
		 e.preventDefault()
		 search()
		 set_limit('limitTxt')
	 })
	 
	 dark_icon.addEventListener('click', e => {
		 e.preventDefault()
		 
		 if(dark_icon.innerText == 'brightness_2')
			 set_cookie('theme', 'dark')
		 else
			 set_cookie('theme', 'light')
		 
		 window.location.reload()
		 dark_icon.innerText = 'sync'
	 })
	 
	 dd_lang_label.addEventListener('click', () => 
		 toggle(dd_lang_label, dd_lang_frame))
	 
	 dd_table_label.addEventListener('click', () => 
		 toggle(dd_table_label, dd_table_frame))
	 
	 /* Functions */
	 function search(target_id='response') {
		 const target = document.getElementById(target_id),
		       form = document.getElementById('the-form'),
		       loadingDiv = document.getElementById('main-loading')
		 
		 let request = '', empty = true
		 
		 form.querySelectorAll('input').forEach(o => {
			 const k = encodeURIComponent(o.name),
			       v = encodeURIComponent(o.value.trim())
			 if(v) {
				 request += `${k}=${v}&`
				 if(k != 'table' && k != 'limit')
					 empty = false
			 }
		 })
		 
		 if(empty) {
			 target.innerHTML = `<p style='direction:` +
					    `${site_dir};text-align:` +
					    `${site_align}'>` +
					    `<?php P("empty error"); ?>` +
					    `</p>`;
			 return
		 }
		 
		 loadingDiv.style.display = 'block'
		 
		 postUrl('find.php', request, response => {
			 try {
				 response = JSON.parse(response)
			 }
			 catch(e) {
				 response = false
			 }

			 if(!response) {
				 target.innerHTML =
					 `<p style='direction:${site_dir};` +
					 `text-align:${site_align}'>` +
					 `<?php P("not found"); ?></p>`;
				 loadingDiv.style.display = 'none'
				 return
			 }
			 
			 target.innerHTML = htmlify(response)
			 loadingDiv.style.display = 'none'
		 })
	 }
	 
	 function htmlify(response) {
		 const main_fields = {
			 fa: ['عنوان‏', 'پديدآور', 'شماره راهنما (کنگره)‏'],
			 en: ['Title‎', 'Author‎', 'LC No.‎']
		 }
		 
		 let html = '<ul>'
		 
		 for(const i in response) {
			 html += `<li><div class='resp-num' ` +
				 `style='` +
				 `${align == 'right' ? 'left' : 'right'}` +
				 `:-1em'>` +
				 num_convert(String(+i + 1), 'en', site_lang) +
				 `</div>`;
			 
			 let htm_m = '<div class="li-main">',
			     htm_n_m = '<div class="li-not-main"' +
				       ' style="display:none">',
			     title = ''
			 
			 for(const j in response[i]) {
				 if(!response[i][j])
					 continue
				 
				 response[i][j] = response[i][j].replace(
					 /\n/g, '<br>')
				 
				 if(j == 'عنوان‏') {
					 title = `<p style='font-` +
						 `weight:bold;text` +
						 `-align:center;` +
						 `padding:1em;` +
						 `margin-bottom:1em` +
						 `;border-bottom:1px` +
						 `solid'>` +
						 response[i][j] +
						 `</p>`;
				 }
				 else if(main_fields[lang].indexOf(j) !== -1) {
					 const _dir = j == 'شماره راهنما (کنگره)‏' ?
						      'direction:ltr;' :
						      '';
					 htm_m += `${j}:<p style='` +
						  `padding-${align}` +
						  `:1em;margin-` +
						  `${align}:.5em;` +
						  `${_dir}'>`;
					 
					 if(j == 'شماره راهنما (کنگره)‏') {
						 const loc = get_loc(
							 response[i][j])
						 htm_m += `<button type="` +
							  `button" onclick` +
							  `="map('${loc}', ` +
							  `this)" class="` +
							  `mapBtn">` +
							  `<?php P("map") ?>` +
							  `</button><span ` +
							  `id="mapRes" style` +
							  `="direction:` +
							  `${site_dir}">` +
							  `</span>`;
					 }
					 
					 htm_m += `${response[i][j]}</p>`;
				 }
				 else {
					 const _dir = j == 'شماره راهنما (ديويي)‏' ?
						      'direction:ltr;' :
						      '';
					 htm_n_m += `${j}:<p style='padding` +
						    `-${align}:1.5em;margin` +
						    `-${align}:.5em;${_dir}` +
						    `'>${response[i][j]}</p>`;
				 }
			 }
			 
			 const _dir = site_align == 'right' ? 'left' : 'right'
			 htm_m += '</div>'
			 htm_n_m += '</div>'
			 html += title +
				 htm_m +
				 htm_n_m +
				 `<button type='button' onclick='more(this)'` +
				 `class='more-btn' style='direction:` +
				 `${site_dir};text-align:${_dir}'>` +
				 `<?php P("more"); ?>...</button></li>`;
		 }
		 
		 if(!response.length) {
			 html += `<p style='direction:${site_dir};` +
				 `text-align:${site_align}'>` +
				 `<?php P("not found"); ?></p>`;
		 }
		 
		 html += '</ul>'
		 return html
	 }

	 function get_loc(res) {
		 return res.substr(0, res.indexOf('\u{200E}'))
	 }

	 function more(btn) {
		 const li_n_m = btn.parentNode.querySelector('.li-not-main')
		 if(li_n_m.style.display == 'none') {
			 li_n_m.style.display = 'block'
			 btn.innerHTML = '<i class="icon">' +
					 'keyboard_arrow_up</i> ' +
					 '<?php P("less"); ?>'
		 }
		 else {
			 li_n_m.style.display = 'none'
			 btn.innerText = '<?php P("more"); ?>...'
		 }
	 }

	 function map(label, el) {
		 getUrl(`map.php?label=${label}&lang=${site_lang}`, resp => {
			 const mapRes = el.parentNode.querySelector('#mapRes')
			 mapRes.innerHTML = resp
			 mapRes.style.display = 'block'
		 })
	 }
	</script>
</html>
