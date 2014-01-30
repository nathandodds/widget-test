<?php
/**
 * Replaces the root directory within the sass files
 */

$path = str_replace('cmd', '', getcwd());

$settings_file = $path.'/core/settings/site.php';

$_path = explode('/', $path);

$directory = end($_path);

$style_settings = $path.'/assets/styles/sass/configurations/_variables.scss';

if (file_exists($style_settings)) {

	$style_settings_content = file_get_contents($style_settings);

	if ($argv[1] == 'back') {
		$style_settings_content = str_replace('../', '/'.$directory.'/assets/', $style_settings_content);
	} else {
		$style_settings_content = str_replace('/'.$directory.'/assets/', '../', $style_settings_content);	
	}

	file_put_contents($style_settings, $style_settings_content);
}

?>