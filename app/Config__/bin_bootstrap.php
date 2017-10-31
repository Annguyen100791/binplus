<?php
/**
 * Cache configuration
 */
    $cacheConfig = array(
        'duration' => '+1 hour',
        'path' => CACHE.'queries',
        'engine' => 'File',
    );

    // models
    //Cache::config('setting_write_configuration', $cacheConfig);

/**
 * Libraries
 */
    App::import('Vendor', 'Spyc/Spyc');

/**
 * Settings
 */
    if (file_exists(APP . 'Config' . DS.'settings.yml')) {
        $settings = Spyc::YAMLLoad(file_get_contents(APP . 'Config' . DS.'settings.yml'));
        foreach ($settings AS $settingKey => $settingValue) {
            Configure::write($settingKey, $settingValue);
        }
    }
/**
  *	Inflections
  */
  	
	require_once 'inflections.php';
?>