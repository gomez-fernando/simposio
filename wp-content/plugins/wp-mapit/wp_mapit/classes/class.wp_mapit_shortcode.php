<?php
	
	if( ! class_exists( 'wp_mapit_shortcode' ) ) {
		/**
		 * Class to manage the shortcodes of the plugins
		 */
		class wp_mapit_shortcode
		{
			/**
		     * Add hooks and filters for shortcode
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     */
			public static function init()
			{
				/* Shortcode to display current page map */
				add_shortcode( 'wp_mapit', __CLASS__ . '::wp_mapit' );

				/* Shortcode to display map from map module (multipin map) */
				add_shortcode( 'wp_mapit_map', __CLASS__ . '::wp_mapit_map' );
			}

			/**
		     * Hook to handle the shortcode wp_mapit
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     * @return string Returns Map markup as string
		     */
			public static function wp_mapit() {
				if ( wp_mapit_map::has_map() ){
					return wp_mapit_map::generate_map();
				}
			}

			/**
		     * Hook to handle the shortcode wp_mapit_map
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     * @param $atts Array attrubutes of the shortcode
		     * @return string Returns Map markup as string
		     */
			public static function wp_mapit_map( $atts ) {

				$mapId = isset( $atts['id'] ) ? intval( $atts['id'] ) : 0;

				if( $mapId > 0 ) {
					if ( wp_mapit_multipin_map::has_map( $mapId ) ) {
						return wp_mapit_multipin_map::generate_map( $mapId );
					}
				}

			}
		}

		/**
		 * Calling init function to activate hooks and filters.
		 */
		wp_mapit_shortcode::init();

	}