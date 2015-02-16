<?php

if (is_ajax())
{
	// removing !empty so 0 (zero) returns "0 -> 4"
	//if (isset($_POST["path"]) && !empty($_POST["path"])) { //Checks if action value exists
	if (isset($_POST["path"])) {
		echo json_encode(validateInput($_POST["path"]));
	}
	elseif (isset($_POST["soln"])) { //&& !empty($_POST["soln"])) { //Checks if action value exists
		echo json_encode(validateInput($_POST["soln"], true));
	}
}

//Function to check if the request is an AJAX request
function is_ajax() {
  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

// most user input checks were moved to javascript,
// but we can check that it's an int again, just to be sure
function validateInput( $userInput, $soln = false)
{	$y = intval($userInput);
	if(is_int($y))
	{	return showPath($y, $soln);	}
	else
	{	return $userInput." is not an integer. Cannot continue.";	}
}

//	showPath
//	description: given an integer input, converts int to
//		text representation of that int, then calculates
//		the length of that string. repeats until string
//		length is 4
//	parameters: x - integer to start with
//		soln - whether to show just the path or the strings
//		retStr - the return string (passed in for recursiveness)
//	returns: text string
function showPath($x, $soln = false, $retStr="")
{	$fullWord = fullIntToStr($x);
	$shortWord = str_replace(' ','',$fullWord);
	$shortWord = str_replace('-','',$shortWord);
	$next = strlen($shortWord);
	$directly = "";
	if($next == 4)
		$directly = "directly ";
	if($soln==false)
	{	
		$retStr .= "$x leads ".$directly."to $next<br />";	}
	else
	{	$retStr .= "$x ($fullWord) has $next characters<br />";	}
	if( $next != 4)
	{	$retStr = showPath($next, $soln, $retStr);	}
	return $retStr;
}

//	fullIntToStr
//	description: converts an integer to the string of
//		that integer
//	parameters: intInpt - integer to convert to text
//	returns: text string
// 	assumes: $intInpt is an integer
function fullIntToStr($intInpt)
{	if($intInpt == 0)
	{	return "zero";	}

	$retStr = "";
	$powers = array( "", "thousand", "million", "billion", "trillion", "quadrillion", "quintillion", "sextillion");

	if( $intInpt < 0)
	{	$intInpt *= -1;
		$retStr = "negative ";
	}
	// *********************************
	// *** IMPORTANT ***
	// input needs to be an INT for this to work. Decimals will break this :(
	// so validate the input before here
	$triplets = 0;
	if($intInpt>0)
	{	$triplets = ceil(strlen($intInpt)/3);	}
	
	while( $triplets>0)
	{	
		$triplets--;
		
		// first get rid of what we've already written out
		$len = strlen($intInpt);
		$start = 3*($triplets+1);
		$start = ($start > $len ? -1*$len : -1*$start);	// in case first triplet doesn't have 3 digits
		$writing = substr($intInpt, $start);

		// get rid of what we'll get to in the next pass
		$writing = floor($writing/pow(1000,$triplets));
		
		// now $writing is just 3 digits
		if($writing > 0)
		{	$retStr .= thousandsToString($writing);
			//$retStr .= " ".$powers[$triplets]." ";
			$retStr .= " ".$powers[$triplets]." ";
		}
	}

	$retStr = trim($retStr," ");
	return $retStr;
}

//	thousandsToString
//	description: converts a number between 0 and 999 to
//		a string
//	parameters: num - integer to convert to text
//	returns: text string
// 	assumes: $num is an integer
function thousandsToString($num)
{	$ones = array( "", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
	$teens = array( "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");
	$tens = array( "", "", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
	$numStr = "";
	
	$hund = floor($num/100);
	if( $hund > 0)
	{	$numStr .= $ones[$hund]." hundred";	}

	$num = $num-(100*$hund);
	if( $hund > 0)
	{	$numStr .= " ";	}
	
	if( $num > 19)
	{	$tensDigit = floor($num/10);
		$onesDigit = $num%10;
		$numStr .= $tens[$tensDigit];
		if($onesDigit>0)
		{	$numStr .= "-".$ones[$onesDigit];	}
	}
	elseif( $num>9)
	{	$numStr .= $teens[$num-10];	}
	else
	{	$numStr .= $ones[$num];	}

	return $numStr;
}

?>