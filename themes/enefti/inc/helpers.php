<?php 
defined( 'ABSPATH' ) || exit;

/*
 * Return fallback plugin version by slug
 * @param string plugin_slug
 * @return string plugin version by slug
 */
function enefti_fallback_plugin_version($plugin_slug = ''){
	$plugins = array(
	    "modeltheme-framework-enefti" => "2.2",
	    "modeltheme-addons-for-wpbakery" => "1.5.1",
	    "js_composer" => "7.4",
	    "revslider" => "6.6.20"
	);

	return $plugins[$plugin_slug];
}


/*
 * Return plugin version by slug from remote json
 * @param string plugin_slug
 * @return string plugin version by slug
 */
function enefti_plugin_version($plugin_slug = ''){
    // check if the transient did not expire
    if (!empty($value)) {
        if(($value = get_transient( $plugin_slug."_cache" )) === false) {
            
            $request = wp_remote_get('https://modeltheme.com/json/plugin_versions.json');
            $plugin_versions = json_decode(wp_remote_retrieve_body($request), true);

            // save to cache and return
            set_transient($plugin_slug."_cache",$plugin_versions,3600);

            return enefti_fallback_plugin_version($plugin_slug);
        }

        // return from cache
        return $value[0][$plugin_slug];
    }
}