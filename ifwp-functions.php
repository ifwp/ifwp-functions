<?php
/*
Author: Improvements and Fixes for WordPress
Author URI: https://github.com/ifwp
Description: A collection of useful functions for your WordPress theme's functions.php.
Domain Path:
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Network:
Plugin Name: IFWP Functions
Plugin URI: https://github.com/ifwp/ifwp-functions
Text Domain: ifwp-functions
Version: 0.8.2.3
*/

if(!defined('ABSPATH')){
    die("Hi there! I'm just a plugin, not much I can do when called directly.");
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(defined('IFWP_FUNCTIONS')){
    wp_die('IFWP Functions already exists.');
} else {
    define('IFWP_FUNCTIONS', __FILE__);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

foreach(glob(plugin_dir_path(IFWP_FUNCTIONS) . 'functions/*.php') as $functions){
    require_once($functions);
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

ifwp_build_update_checker('https://github.com/ifwp/ifwp-functions', IFWP_FUNCTIONS, 'ifwp-functions');

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

add_action('wp_enqueue_scripts', function(){
    $src = plugin_dir_url(IFWP_FUNCTIONS) . 'functions.js';
    $ver = filemtime(plugin_dir_path(IFWP_FUNCTIONS) . 'functions.js');
    wp_enqueue_script('ifwp-functions', $src, ['jquery'], $ver, true);
});

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

add_action('after_setup_theme', function(){
    $src = get_stylesheet_directory() . '/ifwp-functions.php';
    if(file_exists($src)){
        require_once($src);
    }
});
