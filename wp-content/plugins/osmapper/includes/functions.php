<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */

if( !function_exists( 'ba_map_manager_admin_enqueue_styles' ) ){
    
    /**
     * Adds scripts and styles in admin section
     */
    function ba_map_manager_admin_enqueue_styles( $hook )
    {
        
        if( get_current_screen()->post_type === BAMAP_CPT ){
            /**
             * Admin scripts thirdparty
             */
            wp_enqueue_script( 'leaflet', BAMAP_URL.'assets/js/min/osmapper_leaflet.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'leaflet-full-screen', BAMAP_URL.'assets/js/min/Leaflet.fullscreen.min.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'ba_map_admin_repeater', BAMAP_URL.'assets/js/min/osmapper_repeater.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'iziToast', BAMAP_URL.'assets/js/min/osmapper_toast.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            
            /**
             *
             */
            wp_enqueue_script( 'ba_map_admin_renderer', BAMAP_URL.'assets/js/min/osmapper_renderer.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'ba_map_admin_renderer', BAMAP_URL.'assets/js/ba_map_renderer.js', [], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'ba_map_admin_coords', BAMAP_URL.'assets/js/min/osmapper_geocoder.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'ba_map_admin_search', BAMAP_URL.'assets/js/min/osmapper_admin_search.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            wp_enqueue_script( 'ba_map_admin_scripts', BAMAP_URL.'assets/js/min/osmapper_admin.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
            /**
             * Admin styles
             */
			wp_enqueue_style( 'leaflet.fullscreen', BAMAP_URL.'assets/css/leaflet.fullscreen.css', [], BAMAP_VERSION );
            wp_enqueue_style( 'ba_map_admin_renderer', BAMAP_URL.'assets/css/ba_map_renderer.css', [], BAMAP_VERSION );
            
            
            
            wp_localize_script( 'ba_map_admin_renderer', 'ba_map_ajax', [
                'url'              => admin_url( 'admin-ajax.php' ),
                'gif'              => BAMAP_URL.'assets/images/loader.gif',
                'baloonMessage'    => __( 'You should save post after changes', 'osmapper' ),
                'noResultsMessage' => __( 'Could not find coordinates for given address. Check again given details or move manually pin', 'osmapper' ),
            ] );
        }
        
        wp_enqueue_style( 'ba_map_admin_styles', BAMAP_URL.'assets/css/ba_map_admin_styles.css', [], BAMAP_VERSION );
        wp_enqueue_script( 'osmapper_review', BAMAP_URL.'assets/js/min/osmapper_review.js', [ 'jquery' ], BAMAP_VERSION, TRUE );

        wp_localize_script( 'osmapper_review', 'ba_map_ajax', [
            'url'           => admin_url( 'admin-ajax.php' ),
           
        ] );
        
    }
}

add_action( 'admin_enqueue_scripts', 'ba_map_manager_admin_enqueue_styles', 20 );

if( !function_exists( 'ba_map_manager_enqueue_styles' ) ){
    
    /**
     * Adds scripts and styles in front-end section
     */
    function ba_map_manager_enqueue_styles()
    {
        /**
         * Thirdparty
         */
        wp_enqueue_script( 'leaflet', BAMAP_URL.'assets/js/min/osmapper_leaflet.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
		wp_enqueue_script( 'leaflet-full-screen', BAMAP_URL.'assets/js/min/Leaflet.fullscreen.min.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
        
        /**
         * Admin scripts
         */
        wp_enqueue_script( 'ba_map_admin_renderer', BAMAP_URL.'assets/js/min/osmapper_renderer.js', [ 'jquery' ], BAMAP_VERSION, TRUE );
        //wp_enqueue_script( 'ba_map_scripts', BAMAP_URL.'assets/js/ba_map_scripts.js', [], BAMAP_VERSION, TRUE );
        /**
         * Admin styles
         */
		wp_enqueue_style( 'leaflet.fullscreen', BAMAP_URL.'assets/css/leaflet.fullscreen.css', [], BAMAP_VERSION );
        wp_enqueue_style( 'ba_map_renderer', BAMAP_URL.'assets/css/ba_map_renderer.css', [], BAMAP_VERSION );
        //        wp_enqueue_style( 'ba_map_styles', BAMAP_URL.'assets/css/ba_map_styles.css', [], BAMAP_VERSION );
        
        
        wp_localize_script( 'ba_map_admin_renderer', 'ba_map_ajax', [
            'url'           => admin_url( 'admin-ajax.php' ),
            'gif'           => BAMAP_URL.'assets/images/loader.gif',
            'defaultConfig' => [
                'pin'    => BAMAP_URL.'assets/images/pins/pin-1.png',
                'scheme' => 'http://basemaps.cartocdn.com/light_all',
            ],
        ] );
        
    }
}

add_action( 'wp_enqueue_scripts', 'ba_map_manager_enqueue_styles', 20 );

if( !function_exists( 'is_admin_request' ) ){
	/**
	 * Check if this is a request at the backend.
	 *
	 * @return bool true if is admin request, otherwise false.
	 */
	function is_admin_request() {
		/**
		 * Get current URL.
		 *
		 * @link https://wordpress.stackexchange.com/a/126534
		 */
		$parts = parse_url( home_url() );
		$current_url = strtolower( "{$parts['scheme']}://{$parts['host']}" . add_query_arg( NULL, NULL ) );
		
		/**
		 * Get admin URL and referrer.
		 *
		 * @link https://core.trac.wordpress.org/browser/tags/4.8/src/wp-includes/pluggable.php#L1076
		 */
		$admin_url = strtolower( admin_url() );
		$referrer  = strtolower( wp_get_referer() );

		/**
		 * Check if this is a admin request. If true, it
		 * could also be a AJAX request from the frontend.
		 */
		if ( 0 === strpos( $current_url, $admin_url ) ) {
			/**
			 * Check if the user comes from a admin page.
			 */
			if ( 0 === strpos( $referrer, $admin_url ) ) {
				return true;
			} else {
				/**
				 * Check for AJAX requests.
				 *
				 * @link https://gist.github.com/zitrusblau/58124d4b2c56d06b070573a99f33b9ed#file-lazy-load-responsive-images-php-L193
				 */
				if ( function_exists( 'wp_doing_ajax' ) ) {
					return ! wp_doing_ajax();
				} else {
					return ! ( defined( 'DOING_AJAX' ) && DOING_AJAX );
				}
			}
		} else {
			return false;
		}
	}

}

/**
 * Function for debuging, uncomennt first to use
 *
 * @param array/item to debug
 * @return given aray/item with pre
 */
//if( !function_exists( 'debug' ) ){
//    
//    function debug( $item )
//    {
//        /**
//         * If debug is on in wp-config.php
//         */
//        if( WP_DEBUG ){
//            echo '<pre>';
//            print_r( debug_backtrace()[ 1 ][ 'function' ].':<br/>' );
//            print_r( $item );
//            
//            echo '</pre>';
//        }
//    }
//    
//}

