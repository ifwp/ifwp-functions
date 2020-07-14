<?php

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
