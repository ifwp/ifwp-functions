<?php

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_attachment_url_to_postid')){
	function ifwp_attachment_url_to_postid($url = ''){
		if($url){
			/** original */
			$post_id = ifwp_guid_to_postid($url);
			if($post_id){
				return $post_id;
			}
            /** resized */
			preg_match('/^(.+)(-\d+x\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
			if($matches){
				$url = $matches[1];
				if(isset($matches[3])){
					$url .= $matches[3];
				}
                $post_id = ifwp_guid_to_postid($url);
				if($post_id){
					return $post_id;
				}
			}
			/** scaled */
			preg_match('/^(.+)(-scaled)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
			if($matches){
				$url = $matches[1];
				if(isset($matches[3])){
					$url .= $matches[3];
				}
                $post_id = ifwp_guid_to_postid($url);
				if($post_id){
					return $post_id;
				}
			}
			/** edited */
			preg_match('/^(.+)(-e\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
			if($matches){
				$url = $matches[1];
				if(isset($matches[3])){
					$url .= $matches[3];
				}
                $post_id = ifwp_guid_to_postid($url);
				if($post_id){
					return $post_id;
				}
			}
		}
		return 0;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_byte_value')){
    function ifwp_byte_value($value = ''){
    	if(is_numeric($value)){
    		return intval($value);
    	}
        $value = strtoupper($value);
        if(!preg_match('/^\d+[KMG]{1}$/', $value)){
            return 0;
        }
    	$bytes = 0;
    	$shorthand_byte_values = [
            'K' => KB_IN_BYTES,
    		'M' => MB_IN_BYTES,
    		'G' => GB_IN_BYTES,
        ]; // https://www.php.net/manual/en/faq.using.php#faq.using.shorthandbytes
    	foreach($shorthand_byte_values as $k => $v){
    		if(substr($value, -1) === $k){
    			$bytes = intval(substr($value, 0, -1)) * $v;
    			break;
    		}
    	}
    	return $bytes;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_guid_to_postid')){
	function ifwp_guid_to_postid($guid = ''){
        global $wpdb;
		if($guid){
			$str = "SELECT ID FROM $wpdb->posts WHERE guid = %s";
			$sql = $wpdb->prepare($str, $guid);
			$post_id = $wpdb->get_var($sql);
			if($post_id){
				return (int) $post_id;
			}
		}
		return 0;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_is_extension_allowed')){
	function ifwp_is_extension_allowed($extension = ''){
        if(!$extension){
            return false;
        }
        foreach(wp_get_mime_types() as $exts => $mime){
            if(preg_match('!^(' . $exts . ')$!i', $extension)){
                return true;
            }
        }
        return false;
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_maybe_require_media_functions')){
	function ifwp_maybe_require_media_functions(){
		if(!is_admin()){
			require_once(ABSPATH . 'wp-admin/includes/file.php');
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			require_once(ABSPATH . 'wp-admin/includes/media.php');
		}
	}
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_read_file_chunk')){
    function ifwp_read_file_chunk($handle = null, $chunk_size = 0){
    	$giant_chunk = '';
    	if(is_resource($handle) and is_int($chunk_size)){
    		$byte_count = 0;
    		while(!feof($handle)){
                $length = apply_filters('ifwp_read_file_chunk_lenght', (8 * KB_IN_BYTES));
    			$chunk = fread($handle, $length);
    			$byte_count += strlen($chunk);
    			$giant_chunk .= $chunk;
    			if($byte_count >= $chunk_size){
    				return $giant_chunk;
    			}
    		}
    	}
        return $giant_chunk;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_remove_filename_accents')){
	function ifwp_remove_filename_accents(){
        ifwp_on('sanitize_file_name', 'remove_accents');
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(!function_exists('ifwp_solve_filetype_conflicts')){
	function ifwp_solve_filetype_conflicts(){
        ifwp_on('wp_check_filetype_and_ext', function($wp_check_filetype_and_ext, $file, $filename, $mimes, $real_mime){
    		if($wp_check_filetype_and_ext['ext'] and $wp_check_filetype_and_ext['type']){
    			return $wp_check_filetype_and_ext;
    		}
    		if(strpos($real_mime, 'audio/') === 0 or strpos($real_mime, 'video/') === 0){
    			$filetype = wp_check_filetype($filename);
    			if(in_array(substr($filetype['type'], 0, strcspn($filetype['type'], '/')), ['audio', 'video'])){
    				$wp_check_filetype_and_ext['ext'] = $filetype['ext'];
    				$wp_check_filetype_and_ext['type'] = $filetype['type'];
    			}
    		}
    		return $wp_check_filetype_and_ext;
    	}, 10, 5);
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
