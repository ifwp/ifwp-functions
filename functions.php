<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('_ifwp_authenticate_filter')){
	function _ifwp_authenticate_filter($user = null, $username = ''){
        if($user !== null){
            return $user;
        }
        $user = get_user_by('login', $username);
        if($user){
            return $user;
        }
        return null;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urldecode')){
	function ifwp_base64_urldecode($data = '', $strict = false){
		return base64_decode(strtr($data, '-_', '+/'), $strict);
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_base64_urlencode')){
	function ifwp_base64_urlencode($data = ''){
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_clone_role')){
	function ifwp_clone_role($source_role = '', $destination_role = '', $display_name = ''){
        if($source_role and $destination_role and $display_name){
            $role = get_role($source_role);
            $capabilities = $role->capabilities;
            add_role($destination_role, $display_name, $capabilities);
        }
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_format_function')){
	function ifwp_format_function($function_name = '', $args = []){
		$str = '';
		if($function_name){
			$str .= '<div style="color: #24831d; font-family: monospace; font-weight: 400;">' . $function_name . '(';
			$function_args = [];
			if($args){
				foreach($args as $arg){
					$arg = shortcode_atts([
                        'default' => 'null',
						'name' => '',
						'type' => '',
                    ], $arg);
					if($arg['default'] and $arg['name'] and $arg['type']){
						$function_args[] = '<span style="color: #cd2f23; font-family: monospace; font-style: italic; font-weight: 400;">' . $arg['type'] . '</span> <span style="color: #0f55c8; font-family: monospace; font-weight: 400;">$' . $arg['name'] . '</span> = <span style="color: #000; font-family: monospace; font-weight: 400;">' . $arg['default'] . '</span>';
					}
				}
			}
			if($function_args){
				$str .= ' ' . implode(', ', $function_args) . ' ';
			}
			$str .= ')</div>';
		}
		return $str;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_new')){
    function ifwp_new(...$args){
        if(!$args){
            return null;
        }
        $class_name = array_shift($args);
        if(!class_exists($class_name)){
            return null;
        }
        if($args){
            return new $class_name(...$args);
        } else {
            return new $class_name;
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_prepare')){
    function ifwp_prepare(...$args){
        global $wpdb;
        if(!$args){
            return '';
        }
        if(strpos($args[0], '%') !== false and count($args) > 1){
            return str_replace("'", '', $wpdb->remove_placeholder_escape(call_user_func_array([$wpdb, 'prepare'], $args)));
        } else {
            return $args[0];
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_json')){
    function ifwp_seems_json($str = ''){
        return (is_string($str) and preg_match('/^\{\".*\"\:.*\}$/', $str));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_signon_without_password')){
	function ifwp_signon_without_password($user_login = '', $remember = false){
        if(is_user_logged_in()){
            return new WP_Error('authentication_failed', 'You are currently logged in.');
        }
        add_filter('authenticate', '_ifwp_authenticate_filter', 10, 2);
		$user = wp_signon([
            'user_login' => $user_login,
            'user_password' => '',
            'remember' => $remember,
        ]);
		remove_filter('authenticate', '_ifwp_authenticate_filter', 10, 2);
        return $user;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
