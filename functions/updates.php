<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_build_update_checker')){
    function ifwp_build_update_checker(...$args){
        ifwp_include_puc();
        return call_user_func_array(['Puc_v4_Factory', 'buildUpdateChecker'], $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_include_puc')){
    function ifwp_include_puc(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            if(!class_exists('\Puc_v4_Factory')){
                require_once(plugin_dir_path(IFWP_FUNCTIONS) . 'includes/plugin-update-checker-4.9/vendor/autoload.php');
            }
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
