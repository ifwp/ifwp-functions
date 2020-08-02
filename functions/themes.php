<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_add_larger_image_sizes')){
	function ifwp_add_larger_image_sizes(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_image_size('hd', 1280, 1280);
            add_image_size('full-hd', 1920, 1920);
            ifwp_on('image_size_names_choose', function($sizes){
                $sizes['hd'] = 'HD';
                $sizes['full-hd'] = 'Full HD';
        		return $sizes;
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_support_floating_labels')){
    function ifwp_support_floating_labels(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_action('wp_enqueue_scripts', function(){
                $src = plugin_dir_url(IFWP_FUNCTIONS) . 'assets/floating-labels.css';
                $ver = filemtime(plugin_dir_path(IFWP_FUNCTIONS) . 'assets/floating-labels.css');
                wp_enqueue_style('ifwp-floating-labels', $src, [], $ver);
                $src = plugin_dir_url(IFWP_FUNCTIONS) . 'assets/floating-labels.js';
                $ver = filemtime(plugin_dir_path(IFWP_FUNCTIONS) . 'assets/floating-labels.js');
                wp_enqueue_script('ifwp-floating-labels', $src, ['jquery'], $ver, true);
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
