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
    "more" => [
	"ckb" => "زیاتر",
	"fa" => "بیشتر",
	"en" => "more",
    ],
    "less" => [
	"ckb" => "کەمتر",
	"fa" => "کمتر",
	"en" => "less",
    ],
    "not found" => [
	"ckb" => "بەداخەوە گەڕان هیچ ئاکامی نەبوو.",
	"fa" => "متاسفانه نتیجه‌ای یافت نشد.",
	"en" => "Sorry, no results found.",
    ],
    "empty error" => [
	"ckb" => "تکایە تەنانەت یەکێک لە فیلدەکانی سەرەوە پڕ کەنەوە.",
	"fa" => "لطفا حداقل یکی از فیلدهای بالا را پر کنید.",
	"en" => "Please, fill in one of the above fields at least.",
    ],
    "site lang" => [
	"ckb" => "کوردی",
	"fa" => "فارسی",
	"en" => "English",
    ],
    "persian books" => [
	"ckb" => "کتێبە فارسییەکان",
	"fa" => "کتاب‌های فارسی",
	"en" => "Persian books",
    ],
    "english books" => [
	"ckb" => "کتێبە ئینگلیسیەکان",
	"fa" => "کتاب‌های انگلیسی",
	"en" => "English books",
    ],
    "search in" => [
	"ckb" => "گەڕان لە",
	"fa" => "جست‌وجو در",
	"en" => "Search in",
    ],
];
?>
