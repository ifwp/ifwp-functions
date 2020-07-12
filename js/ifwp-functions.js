// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//
// IFWP Functions
//
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof ifwp_add_query_arg !== 'function'){
    function ifwp_add_query_arg(key, value, url){
        'use strict';
        var a = document.createElement('a'), href = '';
        if(url === ''){
            a.href = jQuery(location).attr('href');
        } else {
            a.href = url;
        }
        if(a.protocol){
            href += a.protocol + '//';
        }
        if(a.hostname){
            href += a.hostname;
        }
        if(a.port){
            href += ':' + a.port;
        }
        if(a.pathname){
            if(a.pathname[0] !== '/'){
                href += '/';
            }
            href += a.pathname;
        }
        if(a.search){
            var search = [];
            var search_object = ifwp_parse_str(a.search);
            jQuery.each(search_object, function(k, v){
                if(k != key){
                    search.push(k + '=' + v);
                }
            });
            if(search.length > 0){
                href += '?' + search.join('&') + '&';
            } else {
                href += '?';
            }
        } else {
            href += '?';
        }
        href += key + '=' + value;
        if(a.hash){
            href += a.hash;
        }
        return href;
    };
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof ifwp_add_query_args !== 'function'){
    function ifwp_add_query_args(args, url){
        'use strict';
        var a = document.createElement('a'), href = '';
        if(url === ''){
            a.href = jQuery(location).attr('href');
        } else {
            a.href = url;
        }
        if(a.protocol){
            href += a.protocol + '//';
        }
        if(a.hostname){
            href += a.hostname;
        }
        if(a.port){
            href += ':' + a.port;
        }
        if(a.pathname){
            if(a.pathname[0] !== '/'){
                href += '/';
            }
            href += a.pathname;
        }
        if(a.search){
            var search = [];
            var search_object = ifwp_parse_str(a.search);
            jQuery.each(search_object, function(k, v){
                if(!(k in args)){
                    search.push(k + '=' + v);
                }
            });
            if(search.length > 0){
                href += '?' + search.join('&') + '&';
            } else {
                href += '?';
            }
        } else {
            href += '?';
        }
        jQuery.each(args, function(k, v){
            href += k + '=' + v;
        });
        if(a.hash){
            href += a.hash;
        }
        return href;
    };
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof ifwp_parse_str !== 'function'){
    function ifwp_parse_str(str){
        'use strict';
        var i = 0, search_object = {}, search_array = str.replace('?', '').split('&');
        for(i = 0; i < search_array.length; i ++){
            search_object[search_array[i].split('=')[0]] = search_array[i].split('=')[1];
        }
        return search_object;
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof ifwp_parse_url !== 'function'){
    function ifwp_parse_url(url, component){
        'use strict';
        var a = document.createElement('a'), components = {}, keys = ['protocol', 'hostname', 'port', 'pathname', 'search', 'hash'];
        if(url === ''){
            a.href = jQuery(location).attr('href');
        } else {
            a.href = url;
        }
        if(typeof component === 'undefined' || component === ''){
            jQuery.map(keys, function(c){
                components[c] = a[c];
            });
            return components;
        } else if(jQuery.inArray(component, keys) !== -1){
            return a[component];
        } else {
            return '';
        }
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

if(typeof ifwp_rem_to_px !== 'function'){
    function ifwp_rem_to_px(count){
        'use strict';
        var unit = jQuery('html').css('font-size');
    	if(typeof count !== 'undefined' && count > 0){
    		return (parseInt(unit) * count);
    	} else {
    		return parseInt(unit);
    	}
    }
}

// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
