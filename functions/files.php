<?php

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
