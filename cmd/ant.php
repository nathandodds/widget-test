<?php

$env = $argv[1];

$folder = 'httpdocs';
$replace = 'dev';

if ($env == 'development') {
	$folder = 'dev';
	$replace = 'httpdocs';
}

$build_file = getcwd().'/build.xml';

if (file_exists($build_file)) {

	$build_file_contents = file_get_contents($build_file);

	$build_file_contents = str_replace($replace, $folder, $build_file_contents);

	file_put_contents($build_file, $build_file_contents);
}
?>