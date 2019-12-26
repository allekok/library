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
	"fa" => "جست‌وجو",
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
	"ckb" => "کتێبە ئینگلیسییەکان",
	"fa" => "کتاب‌های انگلیسی",
	"en" => "English books",
    ],
    "search in" => [
	"ckb" => "گەڕان لە",
	"fa" => "جست‌وجو در",
	"en" => "Search in",
    ],
    "number of results" => [
	"ckb" => "ئەژماری ئاکامەکان",
	"fa" => "تعداد نتایج",
	"en" => "Number of results",
    ],
    "ردیف" => [
	"ckb" => "ڕەدیف",
	"fa" => "ردیف",
	"en" => "Id",
    ],
    "پديدآور" => [
	"ckb" => "نووسەر",
	"fa" => "نویسنده",
	"en" => "Writer",
    ],
    "عنوان‏" => [
	"ckb" => "ناوی کتێب",
	"fa" => "نام کتاب",
	"en" => "Book title",
    ],
    "زبان‏" => [
	"ckb" => "زمان",
	"fa" => "زبان",
	"en" => "Language",
    ],
    "شماره راهنما (کنگره)‏" => [
	"ckb" => "ژمارەی ڕەدەبەندی کۆنگرە",
	"fa" => "شماره رده‌بندی کنگره",
	"en" => "Library of congress classification number",
    ],
    "شماره راهنما (ديويي)‏" => [
	"ckb" => "ژمارەی ڕەدەبەندی دێوێی",
	"fa" => "شماره رده‌بندی دیویی",
	"en" => "Dewey decimal classification number",
    ],
    "شماره مدرک‏" => [
	"ckb" => "",
	"fa" => "شماره مدرک",
	"en" => "",
    ],
    "نوع سرشناسه‏" => [
	"ckb" => "",
	"fa" => "نوع سرشناسه",
	"en" => "",
    ],
    "شرح پديدآور‏" => [
	"ckb" => "سەبارەت بە نووسەر",
	"fa" => "شرح نویسنده",
	"en" => "",
    ],
    "وضعيت نشر‏" => [
	"ckb" => "",
	"fa" => "وضعیت نشر",
	"en" => "",
    ],
    "سال نشر‏" => [
	"ckb" => "",
	"fa" => "سال نشر",
	"en" => "",
    ],
    "مشخصات ظاهري‏" => [
	"ckb" => "",
	"fa" => "مشخصات ظاهری",
	"en" => "",
    ],
    "فروست‏" => [
	"ckb" => "",
	"fa" => "فروست",
	"en" => "",
    ],
    "يادداشت‏" => [
	"ckb" => "یاداشت",
	"fa" => "یادداشت",
	"en" => "Notes",
    ],
    "مندرجات‏" => [
	"ckb" => "",
	"fa" => "مندرجات",
	"en" => "",
    ],
    "موضوع‏" => [
	"ckb" => "بابەت",
	"fa" => "موضوع",
	"en" => "Subject",
    ],
    "شناسه افزوده‏" => [
	"ckb" => "",
	"fa" => "شناسه افزوده",
	"en" => "",
    ],
    "موجودي فيزيکي‏" => [
	"ckb" => "",
	"fa" => "موجودی فیزیکی",
	"en" => "",
    ],
    "ويرايش‏" => [
	"ckb" => "نڤیسیاری",
	"fa" => "ویرایش",
	"en" => "Edition",
    ],
    "عنوان قراردادي‏" => [
	"ckb" => "",
	"fa" => "عنوان قراردادی",
	"en" => "",
    ],
    "شابک مجموعه‏" => [
	"ckb" => "",
	"fa" => "شابک مجموعه",
	"en" => "",
    ],
    "مرجع‏" => [
	"ckb" => "",
	"fa" => "مرجع",
	"en" => "Reference",
    ],
    "عناوين ديگر‏" => [
	"ckb" => "عینوانەکانی‌تر",
	"fa" => "عناوین دیگر",
	"en" => "Other titles",
    ],
    "id" => [
	"ckb" => "ڕەدیف",
	"fa" => "ردیف",
	"en" => "Id",
    ],
    "title‎" => [
	"ckb" => "نێوی کتێب",
	"fa" => "نام کتاب",
	"en" => "Book title",
    ],
    "author‎" => [
	"ckb" => "نووسەر",
	"fa" => "نویسنده",
	"en" => "Author",
    ],
    "lc no.‎" => [
	"ckb" => "ژمارەی ڕەدەبەندی کۆنگرە",
	"fa" => "شماره رده‌بندی کنگره",
	"en" => "Library of congress classification number",
    ],
    "dewey no.‎" => [
	"ckb" => "ژمارەی ڕەدەبەندی دێوێی",
	"fa" => "شماره رده‌بندی دیویی",
	"en" => "Dewey classification number",
    ],
    "main entry code‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Main entry code",
    ],
    "statement resp.‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Statement resp.",
    ],
    "edition‎" => [
	"ckb" => "نڤیسیاری",
	"fa" => "ویرایش",
	"en" => "Edition",
    ],
    "imprint‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Imprint",
    ],
    "pub. year‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Pub. year",
    ],
    "collation‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Collation",
    ],
    "notes‎" => [
	"ckb" => "یاداشت",
	"fa" => "یادداشت",
	"en" => "Notes",
    ],
    "subject‎" => [
	"ckb" => "بابەت",
	"fa" => "موضوع",
	"en" => "Subject",
    ],
    "added entry‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Added entry",
    ],
    "physical holding‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Physical holding",
    ],
    "brief holding‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Brief holding",
    ],
    "uniform title‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Uniform title",
    ],
    "series‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Series",
    ],
    "language‎" => [
	"ckb" => "زمان",
	"fa" => "زبان",
	"en" => "Language",
    ],
    "contents‎" => [
	"ckb" => "",
	"fa" => "",
	"en" => "Contents",
    ],
];
?>
