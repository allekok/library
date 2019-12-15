<?php
function SP ($key)
{
    global $site_lang, $Ps;
    return $Ps[strtolower($key)][strtolower($site_lang)];
}
function P ($key)
{
    echo SP($key);
}
$Ps = [
    "site title" => [
	"ckb" => "کتێبخانە",
	"fa" => "کتاب‌خانه",
	"en" => "Library",
    ],
    "send" => [
	"ckb" => "گەڕان",
	"fa" => "جستجو",
	"en" => "Search",
    ],
];
?>
