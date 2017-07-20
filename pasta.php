#!/bin/php
<?php
define('OUTPUT_DIR', 'sir');

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

if(defined('OUTPUT_DIR') && is_string(OUTPUT_DIR) && strlen(OUTPUT_DIR) > 0) {
	if((!file_exists(OUTPUT_DIR) || !is_dir(OUTPUT_DIR)) && !mkdir(OUTPUT_DIR)) {
		echo 'Cannot create create directory'.OUTPUT_DIR.PHP_EOL;
		exit(10);
	}
}

foreach($lines as $lineno => $oneline) {
	$precompilato = $template;
	$pieces = explode(',', $oneline);
	if($headercount !== count($pieces)) {
		echo 'Field count must match: there are ' . $headercount . ' fields in header and ' . count($pieces) . ' on line ' . ($lineno + 2) .PHP_EOL;
		exit(7);
	}
	if($fullname) {
		$filename = 'SIR ' . $pieces[array_search('[NAME]', $replace)] . ' ' . $pieces[array_search('[SURNAME]', $replace)] . ' ' . $pieces[array_search('[ID]', $replace)];
	} else {
		$filename = 'SIR ' . ($lineno + 1);
	}
	if(file_exists(OUTPUT_DIR.DIRECTORY_SEPARATOR.$filename.'.pdf')) {
		echo "File $filename.pdf already exists, skipping".PHP_EOL;
		continue;
		//exit(9);
	}
	echo "Building $filename.tex...".PHP_EOL;
	foreach($replace as $key => $placeholder) {
		$precompilato = str_replace($placeholder, trim($pieces[$key]), $precompilato);
	}
	file_put_contents($filename.'.tex', $precompilato);
	echo 'Calling pdflatex...' . PHP_EOL;
	system('pdflatex -interaction=nonstopmode ' . escapeshellarg($filename.'.tex'), $ret);
	if($ret !== 0) {
		echo PHP_EOL."Pdflatex failed".PHP_EOL;
		exit(10);
	}
	echo 'Moving to output directory...' . PHP_EOL;
	if(!rename($filename.'.tex', OUTPUT_DIR.DIRECTORY_SEPARATOR.$filename.'.tex') || ! rename($filename.'.pdf', OUTPUT_DIR.DIRECTORY_SEPARATOR.$filename.'.pdf')) {
		echo PHP_EOL."Cannot move files to ".OUTPUT_DIR.PHP_EOL;
		exit(11);
	}
	
	echo 'Removing temporary files...' . PHP_EOL;
	unlink($filename.'.aux');
	unlink($filename.'.log');
}
