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
// From php.net's manual
if (!function_exists('array_key_first')) {
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}
?>
