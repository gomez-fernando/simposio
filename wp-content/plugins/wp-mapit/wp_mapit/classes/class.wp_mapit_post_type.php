<?php
	
	if( ! class_exists( 'wp_mapit_post_type' ) ) {
		/**
		 * Manages custom post for the multipin map
		 */
		class wp_mapit_post_type
		{
			/**
		     * Add hooks and filters for custom post type
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     */
			public static function init()
			{
				add_action( 'init', __CLASS__ . '::init_hook' );
				
				add_action( 'admin_menu', __CLASS__ . '::admin_menu' );

				add_filter( 'manage_edit-wp_mapit_map_columns', __CLASS__ . '::manage_edit_wp_mapit_map_columns' );
				add_action( 'manage_wp_mapit_map_posts_custom_column', __CLASS__ . '::manage_wp_mapit_map_posts_custom_column', 10, 2);
			}

			/**
		     * Handle init hook for the custom post type
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     */
			public static function init_hook(){
				$maps = array(
					'labels' => array(
						'name' => __( 'Maps', WP_MAPIT_TEXTDOMAIN ),
						'singular_name'  => __( 'Map', WP_MAPIT_TEXTDOMAIN ),
						'menu_name' => __( 'WP MapIt', WP_MAPIT_TEXTDOMAIN ),
						'all_items' => __( 'All Maps', WP_MAPIT_TEXTDOMAIN ),
						'add_new' => __( 'Add Map', WP_MAPIT_TEXTDOMAIN ),
						'add_new_item' => __( 'Add New Map', WP_MAPIT_TEXTDOMAIN ),
						'edit_item' => __( 'Edit Map', WP_MAPIT_TEXTDOMAIN ),
						'new_item' => __( 'New Map', WP_MAPIT_TEXTDOMAIN ),
						'view_item' => __( 'View Map', WP_MAPIT_TEXTDOMAIN ),
						'search_items' => __( 'Search Maps', WP_MAPIT_TEXTDOMAIN ),
						'not_found' => __( 'No maps found', WP_MAPIT_TEXTDOMAIN ),
						'not_found_in_trash' => __( 'No maps found in Trash', WP_MAPIT_TEXTDOMAIN )
					),
					'public' => true,
					'has_archive' => false,
					'exclude_from_search' => true,
					'publicly_queryable' => false,
					'supports' => array( 'title' ),
					'show_in_menu' => false
				);

				register_post_type( 'wp_mapit_map', $maps );

				
			}

			/**
		     * Hook to manage WP MapIt admin menu
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     */
			public static function admin_menu() {
				add_submenu_page('wp_mapit', __('Multipin Map', WP_MAPIT_TEXTDOMAIN), __('Multipin Map', WP_MAPIT_TEXTDOMAIN), 'manage_options', 'edit.php?post_type=wp_mapit_map');
			}

			/**
		     * Handle filter for wp_mapit_map columns
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     * @param $columns Array columns for the table
		     * @return Array Columns for the table as array
		     */
			public static function manage_edit_wp_mapit_map_columns( $columns ) {

				$columns['shortcode'] = __( 'Shortcode', WP_MAPIT_TEXTDOMAIN );

				return $columns;
			}

			/**
		     * Handle action to display shortcode in admin list columns
		     *
		     * @since 1.0
		     * @static
		     * @access public
		     * @param $column String Name of the column
		     * @param $post_id Int Id of the current post
		     */
			public static function manage_wp_mapit_map_posts_custom_column( $column, $post_id ) {

				if( $column == 'shortcode' ) {
					echo '[wp_mapit_map id="' . $post_id . '"]';
				}
			}
		}

		/**
		 * Calling init function to activate hooks and filters.
		 */
		wp_mapit_post_type::init();
	}