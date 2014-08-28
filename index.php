<?php
/**
 * Plugin Name: Simple Post Preview
 * Version: 2.0 (beta)
 * Plugin URI: http://www.davidajnered.com/
 * Description: Simple Post Preview is a widget that creates pushes for posts.
 * Author: David Ajnered
 */
namespace SimplePostPreview;

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define some helping constants
if (!defined('SPP_RELPATH')) {
    define('SPP_RELPATH', str_replace(ABSPATH, '', plugin_dir_path(__FILE__)));
}

if (!defined('SPP_ABSPATH')) {
    define('SPP_ABSPATH', plugin_dir_path(__FILE__));
}

// Autoload classes
spl_autoload_register(function ($className) {
    if (strpos($className, __NAMESPACE__) === 0) {
        $classPath = str_replace(__NAMESPACE__, '', str_replace('\\', '/', $className)) . '.php';
        $classPath = SPP_ABSPATH . 'app' . $classPath;

        if (file_exists($classPath)) {
            include_once($classPath);
        }
    }
});

// Alright, let's get started!
$simplePostPreview = new Core\Widget();

if (is_admin()) {
    new Admin\SettingsPage();
}
