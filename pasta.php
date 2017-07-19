#!/bin/php
<?php
if(!file_exists('template.tex')) {
	echo 'No template.tex file'.PHP_EOL;
	exit(1);
}
if(!file_exists('data.csv')) {
	echo 'No data.csv file'.PHP_EOL;
	exit(2);
}
$template = file_get_contents('template.tex');
if($template === false) {
	echo 'Cannot read template.tex'.PHP_EOL;
	exit(3);
}
$data = file_get_contents('data.csv');
if($data === false) {
	echo 'Cannot read data.csv'.PHP_EOL;
	exit(4);
}
$lines = preg_split('/[\n\r]/', $data, -1, PREG_SPLIT_NO_EMPTY);
if(count($lines) <= 0) {
	echo 'Empty data.csv'.PHP_EOL;
	exit(5);
}
if(count($lines) <= 1) {
	echo 'Only one line in data.csv'.PHP_EOL;
	exit(6);
}
$header = array_shift($lines);
$replace = explode(',', $header);
foreach($replace as &$value) {
	$value = '[' . $value . ']';
	if(strstr($template,$value) === false) {
		echo "$value not found in template".PHP_EOL;
		exit(8);
	}
}
$fullname = in_array('[NAME]',$replace) && in_array('[SURNAME]',$replace) && in_array('[ID]',$replace);
$headercount = count($replace);
foreach($lines as $lineno => $oneline) {
	$pieces = explode(',', $oneline);
	if($headercount !== count($pieces)) {
		echo 'Field count must match: there are ' . $headercount . ' fields in header and ' . count($pieces) . ' on line ' . ($lineno + 2) .PHP_EOL;
		exit(7);
	}
	if($fullname) {
		$filename = 'SIR ' . $pieces(array_search('[NAME]', $replace)) . ' ' . $pieces(array_search('[SURNAME]', $replace)) . ' ' . $pieces(array_search('[ID]', $replace)) . '.pdf';
	} else {
		$filename = 'SIR ' . $lineno . '.pdf';
	}
	if(file_exists($filename)) {
		echo "File $filename already exists".PHP_EOL;
		exit(9);
	}
}

#pdflatex -synctex=1 -interaction=nonstopmode "template-example".tex
