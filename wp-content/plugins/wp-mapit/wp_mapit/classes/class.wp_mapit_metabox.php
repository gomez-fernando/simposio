<?php
	
	if( ! class_exists( 'wp_mapit_metabox' ) ) {
		
		/**
		 * Class to manage metaboxes displayed on various pages.
		 */
		class wp_mapit_metabox
		{
			/**
		     * Define fields to be displayed in the custom metabox.
		     * 
		     * @since 1.0
		     * @static
		     * @access public
		     */
			public static function init()
			{
				$arrAllowedPostTypes = wp_mapit_admin_settings::get_allowed_posttypes();

				$arrMapTypes = array();
				$_mapTypes = wp_mapit_admin_settings::get_map_types();
				array_walk($_mapTypes, function( $label, $value ) use(&$arrMapTypes) { $arrMapTypes[] = array( 'value' => $value, 'label' => $label ); });

				if( is_array( $arrAllowedPostTypes ) && count( $arrAllowedPostTypes ) > 0 ) {
					
					$arrMapPosition = array();
					$_mapPositions = wp_mapit_admin_settings::get_map_positions();
					array_walk($_mapPositions, function( $label, $value ) use(&$arrMapPosition) { $arrMapPosition[] = array( 'value' => $value, 'label' => $label ); });

					$arrMapMetaboxFields = array(
						array(
							'label' => __( 'Map', WP_MAPIT_TEXTDOMAIN ),
							'id' => 'wp_mapit_map',
							'type' => 'map',
							'desc' => __( 'Changes in the map settings will affect the map and vice versa. Once you add a pin, you can drag it to set a correct position.', WP_MAPIT_TEXTDOMAIN )
						),
						array(
							'type' => 'section',
							'class' => 'right-margin',
							'label' => __( 'Map Settings', WP_MAPIT_TEXTDOMAIN ),
							'fields' => array(
								array(
									'label' => __( 'Search Location', WP_MAPIT_TEXTDOMAIN ),
									'desc' => __( 'Search for a location to pin it on the map. Do not find what you are looking for? Click on the map to mark the location.', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_search',
									'type' => 'map_search'
								),
								array(
									'label' => __( 'Latitude', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_map_latitiude', 
									'type' => 'number',
									'desc' => __( 'Latitude for the map pin.', WP_MAPIT_TEXTDOMAIN ),
									'step' => 'any'
								),
								array(
									'label' => __( 'Longitude', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_map_longitude', 
									'type' => 'number',
									'desc' => __( 'Longitude for the map pin.', WP_MAPIT_TEXTDOMAIN ),
									'step' => 'any'
								),
								array(
									'label' => __( 'Zoom', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_map_zoom', 
									'type' => 'number',
									'desc' => __( 'Zoom level for the map.', WP_MAPIT_TEXTDOMAIN ),
									'min' => '1',
									'max' => '20',
									'step' => '1'
								),
								array(
									'label' => __( 'Map Type', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_map_type',
									'type' => 'select',
									'options' => array_merge( array( array( 'value' =>'', 'label' => __( 'Default Map Type', WP_MAPIT_TEXTDOMAIN ) ) ), $arrMapTypes ),
									'desc' => __( 'Type of the map to be displayed.', WP_MAPIT_TEXTDOMAIN ),
									'row_class' => 'no-margin'
								)
							)
						),
						array(
							'type' => 'section',
							'class' => 'left-margin',
							'label' => __( 'General Settings', WP_MAPIT_TEXTDOMAIN ),
							'fields' => array(
								array(
									'label' => __( 'Marker Image', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_marker_image',
									'type' => 'image',
									'desc' => __( 'Marker image to be displayed on the map. If no image is selected, default image will be displayed. Max size 100px X 100px. Image bigger then the size will be resized.', WP_MAPIT_TEXTDOMAIN )
								),
								array(
									'label' => __( 'Marker Title', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_marker_title',
									'type' => 'text',
									'desc' => __( 'Title to be displayed on the map marker.', WP_MAPIT_TEXTDOMAIN ),
								),
								array(
									'label' => __( 'Marker Content', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_marker_content',
									'type' => 'textarea',
									'desc' => __( 'Content to be displayed on the map marker.', WP_MAPIT_TEXTDOMAIN ),
									'sanitize' => 'sanitize_textarea'
								),
								array(
									'label' => __( 'Marker URL', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_marker_url',
									'type' => 'url',
									'desc' => __( 'URL which will open on marker click. If Marker URL is entered, Marker Title and Marker Content will be ignored.', WP_MAPIT_TEXTDOMAIN ),
									'sanitize' => 'sanitize_textarea'
								),
								array(
									'label' => __( 'Map Position', WP_MAPIT_TEXTDOMAIN ),
									'id' => 'wpmi_map_position',
									'type' => 'select',
									'options' => array_merge( array( array( 'value' =>'', 'label' => __( 'Default Map Position', WP_MAPIT_TEXTDOMAIN ) ) ), $arrMapPosition ),
									'desc' => __( 'Position in the content where the map to be displayed.<br>For custom, use one of the following: <br>1. Shortcode [wp_mapit].<br>2. Sidebar widget<br>3. Gutenberg block "WP MAPIT".', WP_MAPIT_TEXTDOMAIN ),
									'row_class' => 'no-margin'
								)
							)
						)						
					);

					new wp_mapit_create_metabox( 'wp-mapit-metabox', __( 'WP MapIt', WP_MAPIT_TEXTDOMAIN ), $arrMapMetaboxFields, $arrAllowedPostTypes, true );
				}

				$arrMapMultiMetaboxShortcodeFields = array(
					array(
						'label' => __( 'Map Shortcode', WP_MAPIT_TEXTDOMAIN ),
						'id' => 'wpmi_multipin_shortcode',
						'type' => 'multimap_shortcode',
						'row_class' => 'no-margin',
						'desc' => __( 'Place the shortcode anywhere you want to display this map.', WP_MAPIT_TEXTDOMAIN )
					)
				);

				new wp_mapit_create_metabox( 'wp-mapit-metabox-shortcode', __( 'WP MapIt Shortcode', WP_MAPIT_TEXTDOMAIN ), $arrMapMultiMetaboxShortcodeFields, 'wp_mapit_map', true );

				$arrMapMultiMetaboxMapFields = array(
					array(
						'label' => __( 'Map', WP_MAPIT_TEXTDOMAIN ),
						'id' => 'wpmi_multipin_map',
						'type' => 'map',
						'row_class' => 'no-margin',
						'desc' => __( 'Changes in the map settings will affect the map and vice versa. You can search, drag or zoom the map to set the center of the map.', WP_MAPIT_TEXTDOMAIN )
					)
				);

				new wp_mapit_create_metabox( 'wp-mapit-metabox-map', __( 'WP MapIt Map', WP_MAPIT_TEXTDOMAIN ), $arrMapMultiMetaboxMapFields, 'wp_mapit_map', true );

				$arrMapMultiMetaboxSettingsFields = array(
					array(
						'type' => 'section',
						'class' => 'right-margin',
						'row_class' => 'no-margin',
						'label' => __( 'General Settings', WP_MAPIT_TEXTDOMAIN ),
						'fields' => array(
							array(
								'label' => __( 'Search Location', WP_MAPIT_TEXTDOMAIN ),
								'desc' => __( 'Search for a location to center the map. Do not find what you are looking for? Drag or zoom the map to set the center.', WP_MAPIT_TEXTDOMAIN ),
								'id' => 'wpmi_multipin_map_search',
								'type' => 'map_search'
							),
							array(
								'label' => __( 'Map Type', WP_MAPIT_TEXTDOMAIN ),
								'id' => 'wpmi_multipin_map_type',
								'type' => 'select',
								'options' => array_merge( array( array( 'value' =>'', 'label' => __( 'Default Map Type', WP_MAPIT_TEXTDOMAIN ) ) ), $arrMapTypes ),
								'desc' => __( 'Type of the map to be displayed.', WP_MAPIT_TEXTDOMAIN )
							),
							array(
								'label' => __( 'Marker Image', WP_MAPIT_TEXTDOMAIN ),
								'id' => 'wpmi_multipin_map_marker_image',
								'type' => 'image',
								'desc' => __( 'Marker image to be displayed on the map. If no image is selected, default image will be displayed. Max size 100px X 100px. Image bigger then the size will be resized.', WP_MAPIT_TEXTDOMAIN ),
								'row_class' => 'no-margin'
							),
						)
					),
					array(
						'type' => 'section',
						'class' => 'left-margin',
						'row_class' => 'no-margin',
						'label' => __( 'Map Center', WP_MAPIT_TEXTDOMAIN ),
						'fields' => array(
							array(
								'label' => __( 'Latitude', WP_MAPIT_TEXTDOMAIN ),
								'id' => 'wpmi_multipin_map_latitiude', 
								'type' => 'number',
								'desc' => __( 'Latitude for the map pin.', WP_MAPIT_TEXTDOMAIN ),
								'step' => 'any'
							),
							array(
								'label' => __( 'Longitude', WP_MAPIT_TEXTDOMAIN ),
								'id' => 'wpmi_multipin_map_longitude', 
								'type' => 'number',
								'desc' => __( 'Longitude for the map pin.', WP_MAPIT_TEXTDOMAIN ),
								'step' => 'any'
							),
							array(
								'label' => __( 'Zoom', WP_MAPIT_TEXTDOMAIN ),
								'id' => 'wpmi_multipin_map_zoom', 
								'type' => 'number',
								'desc' => __( 'Zoom level for the map.', WP_MAPIT_TEXTDOMAIN ),
								'min' => '1',
								'max' => '20',
								'step' => '1',
								'row_class' => 'no-margin'
							)
						)
					)
				);

				new wp_mapit_create_metabox( 'wp-mapit-metabox-settings', __( 'WP MapIt Map Settings', WP_MAPIT_TEXTDOMAIN ), $arrMapMultiMetaboxSettingsFields, 'wp_mapit_map', true );

				$arrMapMultiMetaboxPinFields = array(
					array(
						'label' => __( 'Map Pin', WP_MAPIT_TEXTDOMAIN ),
						'id' => 'wp_mapit_pins',
						'type' => 'mappins'
					)
				);

				new wp_mapit_create_metabox( 'wp-mapit-metabox-pins', __( 'WP MapIt Map Pins', WP_MAPIT_TEXTDOMAIN ), $arrMapMultiMetaboxPinFields, 'wp_mapit_map', true );
			}
		}

		/**
		 * Calling init function to activate hooks and filters.
		 */
		wp_mapit_metabox::init();
	}