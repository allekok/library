<?php
function tokenize ($S)
{
    $I = 0;
    $TOKENS = [];
    
    while(FALSE !== ($C = substr($S, $I, 1)))
    {
	if($C == "\n" or $C == "\r")
	{
	    $I++;
	    continue;
	}
	
	if($C == '<')
	{
	    $T = '';
	    while(FALSE !== ($C = substr($S, $I, 1)))
	    {
		$T .= $C;
		$I++;
		if($C == '>')
		    break 1;
	    }
	    $TOKENS[] = $T;
	}
	else
	{
	    $T = '';
	    while(FALSE !== ($C = substr($S, $I, 1)))
	    {
		if($C == '<')
		    break 1;
		$T .= $C;
		$I++;
	    }
	    $TOKENS[] = $T;
	}
    }
    return $TOKENS;
}

function extract_tags (array $A, $start, $end, $N=-1)
{
    $I = 0;
    $R = [];
    $CARRY = FALSE;
    foreach($A as $O)
    {
	if($N == $I)
	    break;
	
	if($O == $end)
	{
	    $CARRY = FALSE;
	    $I++;
	}
	elseif(strpos($O, $start) === 0)
	{
	    $CARRY = TRUE;
	    continue;
	}

	if($CARRY)
	{
	    $R[$I][] = $O;
	}
    }
    return $R;
}

function tag_names ($ARR, $TAG)
{
    $TAG_LEN = strlen($TAG);
    $RES = [];
    foreach($ARR as $T)
    {
	if(substr($T, 0, $TAG_LEN) != $TAG)
	    continue;

	preg_match_all("/ Name=\".+\" /", $T, $R);
	if($R[0]) $RES[] = substr($R[0][0], 7, -2);
    }
    return $RES;
}

function sanitize_string ($S)
{
    $S = trim($S);

    if(mb_substr($S,-2) == " ‏")
	$S = mb_substr($S, 0, -2);

    return $S;
}

function list_fields ($LANG="en")
{
    $RES = [];
    $PATH = "data/SIMORGH-{$LANG}.XML";
    $S = file_get_contents($PATH);
    $TOKENS = tokenize($S);
    $RECORDS = extract_tags($TOKENS, "<Record", "</Record>");
    foreach($RECORDS as $I => $R)
    {
	$FIELDS = extract_tags($R, "<Field", "</Field>");
	$FIELDS_NAMES = tag_names($R, "<Field");

	foreach($FIELDS as $II => $F)
	{
	    $SUBFIELDS = extract_tags($F, "<SubField", "</SubField>");
	    $SUBFIELDS_NAMES = tag_names($F, "<SubField");

	    $F_NAME = sanitize_string($FIELDS_NAMES[$II]);
	    if(! in_array($F_NAME, $RES))
		$RES[] = $F_NAME;
	}
    }
    return $RES;
}

function sql_columns ($FIELDS, $LANG='en')
{
    if($LANG == 'en')
	$S = "`id`";
    elseif($LANG == 'fa')
	$S = "`ردیف`";
    $S .= " INT(16) UNSIGNED AUTO_INCREMENT PRIMARY KEY";
    foreach($FIELDS as $F)
    {
	$S .= ",`$F` TEXT";
    }
    return $S;
}
?>
