<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('_ifwp_use_remote_request')){
    function _ifwp_use_remote_request(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            if(!class_exists('\IFWP_Remote_Request')){
                require_once(plugin_dir_path(IFWP_FUNCTIONS) . 'classes/remote-request.php');
            }
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('_ifwp_use_remote_response')){
    function _ifwp_use_remote_response(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            if(!class_exists('\IFWP_Remote_Response')){
                require_once(plugin_dir_path(IFWP_FUNCTIONS) . 'classes/remote-response.php');
            }
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_cloudflare')){
    function ifwp_is_cloudflare(){
        return !empty($_SERVER['HTTP_CF_RAY']);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_successful')){
    function ifwp_is_successful($code = 0){
        return ($code >= 200 and $code < 300);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_delete')){
    function ifwp_remote_delete($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->delete();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_download')){
    function ifwp_remote_download($url = '', $filename = '', $parent = 0, $args = []){
        if(!$url){
            return new WP_Error('ifwp_remote_download_url_required', 'URL is required.');
        }
        if($filename){
            $filename = basename($filename);
        } else {
            $filename = preg_replace('/\?.*/', '', basename($url));
        }
        $filetype_and_ext = wp_check_filetype($filename);
        $type = $filetype_and_ext['type'];
        if(!$type){
            return new WP_Error('ifwp_remote_download_invalid_filename', 'Invalid filename.');
        }
        $wp_upload_dir = wp_upload_dir();
        $path = apply_filters('ifwp_remote_download_path', $wp_upload_dir['path']);
        if(strpos($path, $wp_upload_dir['basedir']) !== 0){
            return new WP_Error('ifwp_remote_download_invalid_path', 'Invalid path.');
        }
        $filename = wp_unique_filename($path, $filename);
        $file = trailingslashit($path) . $filename;
        $max_execution_time = ini_get('max_execution_time');
        $timeout = ifwp_byte_value($max_execution_time); // A value of 0 will allow an unlimited timeout.
        $args = wp_parse_args($args, [
            'filename' => $file,
            'timeout' => $timeout,
        ]);
        if(ifwp_is_cloudflare()){
            if(!$args['timeout'] or $args['timeout'] > 90){
                $args['timeout'] = 90; // Prevents Error 524: https://support.cloudflare.com/hc/en-us/articles/115003011431#524error
            }
        }
        if(strpos($args['filename'], $wp_upload_dir['basedir']) !== 0){
            return new WP_Error('ifwp_remote_download_invalid_filename', 'Invalid filename.');
        }
        $args['stream'] = true;
        $response = wp_remote_get($url, $args);
        if(is_wp_error($response)){
            if(file_exists($file)){
                unlink($file);
            }
            return $response;
        }
        $code = wp_remote_retrieve_response_code($response);
        if(!ifwp_is_successful($code)){
            $message = wp_remote_retrieve_response_message($response);
            if(!$message){
                $message = get_status_header_desc($code);
            }
            if(file_exists($file)){
                unlink($file);
            }
            return new WP_Error('ifwp_remote_download_invalid_response', $message);
        }
        $filetype_and_ext = wp_check_filetype_and_ext($file, $filename);
        $type = $filetype_and_ext['type'];
        if(!$type){
            if(file_exists($file)){
                unlink($file);
            }
            return new WP_Error('ifwp_remote_download_invalid_filetype', 'Invalid filetype.');
        }
        if($filetype_and_ext['proper_filename']){
            $filename = $filetype_and_ext['proper_filename'];
        }
        $post_id = wp_insert_attachment([
            'guid' => str_replace($wp_upload_dir['basedir'], $wp_upload_dir['baseurl'], $file),
            'post_mime_type' => $type,
            'post_status' => 'inherit',
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
        ], $file, $parent, true);
        if(is_wp_error($post_id)){
            if(file_exists($file)){
                unlink($file);
            }
            return $post_id;
        }
        if(wp_attachment_is_image($post_id)){
            $metadata = wp_generate_attachment_metadata($post_id, $file);
            if($metadata){
                wp_update_attachment_metadata($post_id, $metadata);
            }
        }
        return $post_id;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_get')){
    function ifwp_remote_get($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->get();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_head')){
    function ifwp_remote_head($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->head();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_options')){
    function ifwp_remote_options($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->options();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_patch')){
    function ifwp_remote_patch($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->patch();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_post')){
    function ifwp_remote_post($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->post();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_put')){
    function ifwp_remote_put($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->put();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_request')){
    function ifwp_remote_request($url = '', $args = []){
        _ifwp_use_remote_request();
        return new IFWP_Remote_Request($url, $args);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_response')){
    function ifwp_remote_response($response = null){
        _ifwp_use_remote_response();
        $code = 500;
        $data = '';
        $message = '';
        $success = false;
        switch(true){
            case ifwp_seems_remote_response($response):
                $code = $response['code'];
                $data = $response['data'];
                $message = $response['message'];
                $success = $response['success'];
                break;
            case is_a($response, 'Requests_Exception'):
                $data = $response->getData();
                $message = $response->getMessage();
                break;
            case is_a($response, 'Requests_Response'):
                $code = $response->status_code;
                $data = $response->body;
                $message = get_status_header_desc($code);
                $success = ifwp_is_successful($code);
                break;
            case is_wp_error($response):
                $data = $response->get_error_data();
                $message = $response->get_error_message();
                break;
            case ifwp_seems_wp_http_requests_response($response):
                $code = wp_remote_retrieve_response_code($response);
                $data = wp_remote_retrieve_body($response);
                $message = wp_remote_retrieve_response_message($response);
                if(!$message){
                    $message = get_status_header_desc($code);
                }
                $success = ifwp_is_successful($code);
                break;
            default:
                $message = __('Invalid object type.');
        }
        return new IFWP_Remote_Response(compact('code', 'data', 'message', 'success'), $response);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_response_error')){
    function ifwp_remote_response_error($message = '', $code = 500, $data = ''){
        if(!$message){
            $message = get_status_header_desc($code);
        }
        $success = false;
        return ifwp_remote_response(compact('code', 'data', 'message', 'success'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_response_success')){
    function ifwp_remote_response_success($data = '', $code = 200, $message = ''){
        if(!$message){
            $message = get_status_header_desc($code);
        }
        $success = true;
        return ifwp_remote_response(compact('code', 'data', 'message', 'success'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remote_trace')){
    function ifwp_remote_trace($url, $args = []){
        $remote_request = ifwp_remote_request($url, $args);
        return $remote_request->trace();
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_remote_response')){
    function ifwp_seems_remote_response($response = []){
        return (is_array($response) and count($response) === 4 and isset($response['code'], $response['data'], $response['message'], $response['success']));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_seems_wp_http_requests_response')){
    function ifwp_seems_wp_http_requests_response($response = []){
        return (is_array($response) and array_key_exists('headers', $response) and array_key_exists('body', $response) and array_key_exists('response', $response) and array_key_exists('cookies', $response) and array_key_exists('filename', $response) and array_key_exists('http_response', $response) and is_a($response['http_response'], 'WP_HTTP_Requests_Response'));
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_support_authorization_header')){
    function ifwp_support_authorization_header(){
        static $already_called = false;
        if(!$already_called){
            $already_called = true;
            add_filter('mod_rewrite_rules', function($rules){
                return str_replace("RewriteEngine On\n", "RewriteEngine On\nRewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]\n", $rules);
            });
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
