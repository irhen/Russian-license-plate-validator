<?php

$data = file(__DIR__ . '/input.txt', FILE_SKIP_EMPTY_LINES | FILE_IGNORE_NEW_LINES);

if (!$data) {
  echo 'An error occured while trying to load file input.txt!';
  die();
}

// pattern includes valid letters written in Russian and in English (just in case)
$ruLetters = 'АВЕКМНОРСТУХ';
$enLetters = 'ABEKMHOPCTYX';
$pattern = "[{$ruLetters}{$enLetters}]";

// all common non-multiline number patterns I found
$privateCarRegEx = "{$pattern}\d{3}{$pattern}{2}";
$trailerAndPublicTransportRegEx = "{$pattern}{2}\d{3,4}";
$transitNumbersRegEx = "{$pattern}{2}\d{3}{$pattern}";
$policeCarRegEx = "{$pattern}\d{4}";
$policeCarTrailerRegEx = "\d{3}{$pattern}";
$militaryCarRegEx = "\d{4}{$pattern}{2}";

$regExpArr = [
  $privateCarRegEx, 
  $trailerAndPublicTransportRegEx, 
  $transitNumbersRegEx, 
  $policeCarRegEx, 
  $policeCarTrailerRegEx, 
  $militaryCarRegEx
];
$regExpStr = implode("|", $regExpArr);

$regExp = "/^({$regExpStr})$/u";

$validateNumbers = function ($str) use ($regExp) {
  // if the intention isn't to recognize strings that start with whitespace as valid the following line should be removed
  $str = trim($str);
  $result = preg_match($regExp, $str) ? 'Y' : 'N';  
  return "{$result} - {$str}\r\n";
};

$outputData = array_map(function ($elm) use ($validateNumbers) { return $validateNumbers($elm); }, $data);

$output = fopen(__DIR__ . '/output.txt', 'w');

if (!$output) { 
  echo 'An error occured while trying to create file output.txt!'; 
  die();
}

fwrite($output, implode("", $outputData));
fclose($output);
