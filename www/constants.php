<?php
require("../xml/mysql-constants.php");

const AVAILABLE_LANGS = ["ckb", "en", "fa"];
const LANGS_LIT = ["کوردی" ,"English" ,"فارسی"];
const SITE_LANG_DEF = "fa";

const DEF_COLS = [
	"fa" => ["شماره راهنما (کنگره)‏", "عنوان‏", "پديدآور"],
	"en" => ["LC No.‎", "Title‎", "Author‎"]
];

const TABLES_LIT = ["english books", "persian books"];
?>
