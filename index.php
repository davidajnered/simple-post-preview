<?php
/**
 * Plugin Name: Simple Post Preview
 * Version: 1.2.6
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post Preview is a widget that creates pushes for posts.
 * Author: David Ajnered
 */

spl_autoload_register(function ($class) {
    // Extract class name from function call parameter
    // Ignore namespace
    $class_parts = explode('\\', $class);
    $class_name = end($class_parts);

    // Define path for requested file and include it if it exists
    $class_path = plugin_dir_path(__FILE__) . $class_name . '.php';
    if (file_exists($class_path)) {
        include_once($class_path);
    }
});

$simplePostPreview = new SimplePostPreview\SimplePostPreview();
