<?php

	if( ! class_exists( 'wp_mapit_create_metabox' ) ) {
		/**
		 * Class to manage metabox creation for WP MapIt
		 */
		class wp_mapit_create_metabox
		{
			/**
		     * @since 1.0
		     * @var id to manage id of the metabox
		     * @access private
		     */
			private $id;

			/**
		     * @since 1.0
		     * @var title to manage the title of the metabox
		     * @access private
		     */
			private $title;

			/**
		     * @since 1.0
		     * @var fields to manage fields of the metabox
		     * @access private
		     */
			private $fields;

			/**
		     * @since 1.0
		     * @var postTypes to manage post types where the metabox will be displayed
		     * @access private
		     */
			private $postTypes;

			/**
		     * Add hooks and filters and initialize values for custom metaboxes
		     * 
		     * @since 1.0
		     * @access public
		     * @param $id Int Id of the custom metabox
		     * @param $title String Title of the custom metabox
		     * @param $fields Array Fields of the custom metabox
		     * @param $postTypes Array Post types for the custom metabox
		     */
			public function __construct($id, $title, $fields, $postTypes)
			{
				$this->id = $id;
				$this->title = $title;
				$this->fields = $fields;
				$this->postTypes = ( is_array( $postTypes ) ? $postTypes : ( $postTypes != '' ? array( $postTypes ) : array() ) );

				add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
				add_action( 'save_post',  array( $this, 'save_post' ));
			}

			/**
		     * Hook to handle custom meta boxes
		     *
		     * @since 1.0
		     * @access public
		     */
			public function add_meta_boxes() {
				add_meta_box( $this->id, $this->title, array( $this, 'meta_box_callback' ), $this->postTypes, 'normal', 'high' );
			}

			/**
		     * Callback function to display custom metabox
		     *
		     * @since 1.0
		     * @access public
		     * @param $post Object Wordpress Post object
		     */
			public function meta_box_callback( $post ) {

				wp_nonce_field( 'wp_mapit_metabox', 'wp_mapit_metabox_nonce' );

				if( is_array( $this->fields ) && count( $this->fields ) > 0 ) {
?>
					<div class="wp-mapit-metabox-container">
						<?php
							foreach ($this->fields as $field) {
								if( $field['type'] == 'section' ) {
						?>
									<div class="wp-mapit-section <?php echo ( isset( $field['class'] ) ? $field['class'] : '' ); ?>">
										<div class="wp-mapit-row <?php echo ( isset( $field['row_class'] ) ? $field['row_class'] : '' ); ?>">
											<label><?php echo $field['label']; ?></label>
											<?php
												if( is_array( $field['fields'] ) && count( $field['fields'] ) > 0 ) {
													foreach ($field['fields'] as $curField) {
											?>
														<div class="wp-mapit-row <?php echo ( isset( $curField['row_class'] ) ? $curField['row_class'] : '' ); ?>">
															<label><?php echo $curField['label']; ?></label>
															<?php
																$value = get_post_meta( $post->ID, $curField['id'], true );
																echo $this->meta_box_field( $post->ID, $curField, $value );
															?>
														</div>
											<?php
													}
												}
											?>
										</div>
									</div>
						<?php
								} else if( $field['type'] == 'multimap_shortcode' ) {
									$screen = get_current_screen();

									if( $screen->action == 'add' ) {
						?>
										<p><?php _e( 'Please save the map to view the shortcode', WP_MAPIT_TEXTDOMAIN ); ?></p>
						<?php
									} else {
						?>
										<span><?php _e( 'Shortcode: ', WP_MAPIT_TEXTDOMAIN ); ?> <strong>[wp_mapit_map id="<?php echo $post->ID; ?>"]</strong></span>
										<?php
											if( isset( $field['desc'] ) ) {
										?>
												<p class="description"><?php echo $field['desc']; ?></p>
										<?php
											}
										?>
						<?php
									}

								} else if ( $field['type'] == 'mappins' ) {

									$arrPins = get_post_meta( $post->ID, $field['id'], true );

									$counter = ( is_array( $arrPins ) ? count( $arrPins ) : 0 );

						?>
									<div class="wp-mapit-row button-container text-right">
										<a href="#wp-mapit-metabox-map" class="button"><?php _e( 'Preview Map', WP_MAPIT_TEXTDOMAIN ); ?></a>
										<a href="#" id="add_multipin" data-pinid="<?php echo $field['id']; ?>" data-counter="<?php echo $counter; ?>" class="button"><?php _e( 'Add Map Pin', WP_MAPIT_TEXTDOMAIN ); ?></a>
									</div>
									<div id="wpmi_mappin_container">
										<?php

											if( $counter > 0 ) {
												$pinCnt = 0;
												foreach ($arrPins as $pin) {
										?>
													<div id="pin_container_<?php echo $pinCnt; ?>" class="wp-mapit-row pin_container">
														<a href="#" title="<?php _e( 'Remove Pin', WP_MAPIT_TEXTDOMAIN ); ?>" data-counter="<?php echo $pinCnt; ?>" class="remove_pin"></a>
														<div class="column-3">
															<div class="wp-mapit-row">
																<label><?php _e( 'Search Map', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<div class="wp-mapit-search">
																	<input type="text" id="search_map_<?php echo $pinCnt; ?>">
																	<a href="#" title="<?php _e( 'Search Map', WP_MAPIT_TEXTDOMAIN ); ?>" data-counter="<?php echo $pinCnt; ?>" class="button map-pin-search"></a>
																</div>
															</div>
															<div class="wp-mapit-row">
																<label><?php _e( 'Latitude', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<input type="number" step="any" name="<?php echo $field['id']; ?>[<?php echo $pinCnt; ?>][lat]" required="required" data-counter="<?php echo $pinCnt; ?>" class="pin_latitude" value="<?php echo ( isset( $pin['lat'] ) ? $pin['lat'] : '' ); ?>">
															</div>
															<div class="wp-mapit-row">
																<label><?php _e( 'Longitude', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<input type="number" step="any" name="<?php echo $field['id']; ?>[<?php echo $pinCnt; ?>][lng]" required="required" data-counter="<?php echo $pinCnt; ?>" class="pin_longitude" value="<?php echo ( isset( $pin['lng'] ) ? $pin['lng'] : '' ); ?>">
															</div>
															<div class="wp-mapit-row no-margin pin-img-container">
																<label><?php _e( 'Marker Image', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<?php

																	$pinUrl = ( isset( $pin['marker_image'] ) ? $pin['marker_image'] : '' );

																?>
																<input type="hidden" name="<?php echo $field['id']; ?>[<?php echo $pinCnt; ?>][marker_image]" data-counter="<?php echo $pinCnt; ?>" class="pin_marker_image" value="<?php echo $pinUrl; ?>">
																<?php
																	if( $pinUrl != '' ) {
																?><img src="<?php echo $pinUrl; ?>"><?php
																	}
																?><a href="#" class="upload_image button"><?php _e( 'Choose Image', WP_MAPIT_TEXTDOMAIN ); ?></a><span>&nbsp;</span><a href="#" class="remove_image button"><?php _e( 'Remove Image', WP_MAPIT_TEXTDOMAIN ); ?></a>
															</div>
														</div>
														<div class="column-3">
															<div class="wp-mapit-row">
																<label><?php _e( 'Marker Title', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<input type="text" name="<?php echo $field['id']; ?>[<?php echo $pinCnt; ?>][marker_title]" data-counter="<?php echo $pinCnt; ?>" class="pin_title" value="<?php echo ( isset( $pin['marker_title'] ) ? $pin['marker_title'] : '' ); ?>">
															</div>
															<div class="wp-mapit-row">
																<label><?php _e( 'Marker Content', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<textarea name="<?php echo $field['id']; ?>[<?php echo $pinCnt; ?>][marker_content]"  data-counter="<?php echo $pinCnt; ?>" class="pin_content"><?php echo ( isset( $pin['marker_content'] ) ? $pin['marker_content'] : '' ); ?></textarea>
															</div>
															<div class="wp-mapit-row no-margin">
																<label><?php _e( 'Marker URL', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<input type="text" name="<?php echo $field['id']; ?>[<?php echo $pinCnt; ?>][marker_url]" data-counter="<?php echo $pinCnt; ?>" class="pin_url" value="<?php echo ( isset( $pin['marker_url'] ) ? $pin['marker_url'] : '' ); ?>">
															</div>
														</div>
														<div class="column-3 no-margin">
															<div class="wp-mapit-row no-margin">
																<label><?php _e( 'Map', WP_MAPIT_TEXTDOMAIN ); ?></label>
																<div id="pin_map_<?php echo $pinCnt; ?>" data-counter="<?php echo $pinCnt; ?>" class="pin_map"></div>
															</div>
														</div>
														<div class="clearfix"></div>
													</div>
										<?php
													$pinCnt++;
												}
											}
										?>
									</div>
						<?php
								} else {
						?>
									<div class="wp-mapit-row <?php echo ( isset( $field['row_class'] ) ? $field['row_class'] : '' ); ?>">
										<label><?php echo $field['label']; ?></label>
										<?php
											$value = get_post_meta( $post->ID, $field['id'], true );
											echo $this->meta_box_field( $post->ID, $field, $value );
										?>
									</div>
						<?php
								}
							}
						?>
						<div class="clearfix"></div>
					</div>
<?php
				}
			}

			/**
		     * Function to display the field for the custom metabox
		     *
		     * @since 1.0
		     * @access private
		     * @param $postId Int Id of the post
		     * @param $field Array Metabox field
		     * @param $value String Value for the metabox field
		     */
			private function meta_box_field( $postId, $field, $value = null ) {
				$type = isset( $field['type'] ) ? $field['type'] : null;
				$label = isset( $field['label'] ) ? $field['label'] : null;
				$desc = isset( $field['desc'] ) ? '<p class="description">' . $field['desc'] . '</p>' : null;
				$options = isset( $field['options'] ) ? $field['options'] : null;
				$settings = isset( $field['settings'] ) ? $field['settings'] : null;
				$repeatable_fields = isset( $field['repeatable_fields'] ) ? $field['repeatable_fields'] : null;
				$id = $name = isset( $field['id'] ) ? esc_attr( $field['id'] ) : null;

				switch ( $type ) {
					case 'text':
					case 'tel':
					case 'email':
?>
						<input type="<?php echo $type ?>" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_attr( $value ); ?>">
<?php
						break;
					case 'url':
?>
						<input type="url" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo esc_url( $value ); ?>">
<?php
						break;
					case 'number':
						$step = ( isset( $field['step'] ) ? $field['step'] : '' );
						$min = ( isset( $field['min'] ) ? $field['min'] : '' );
						$max = ( isset( $field['max'] ) ? $field['max'] : '' );
?>
						<input type="number" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo floatval( $value ); ?>" step="<?php echo $step; ?>" min="<?php echo $min; ?>" max="<?php echo $max; ?>">
<?php
						break;
					case 'textarea':
?>
						<textarea id="<?php echo $id; ?>" name="<?php echo $name; ?>"><?php echo esc_textarea( $value ); ?></textarea>
<?php
						break;
					case 'editor':
						echo wp_editor( $value, $id );
						break;
					case 'checkbox':
?>
						<input type="checkbox" id="<?php echo $id; ?>" name="<?php echo $name; ?>" <?php echo checked( $value, true, false ) ?> value="1">
<?php
						break;
					case 'checkbox_group':
						if( is_array( $options ) && count( $options ) > 0 ) {
							$value = is_array( $value ) ? $value : array();
?>
							<div class="wp-mapit-checkbox-group">
<?php
								foreach ($options as $option) {
?>
									<div class="wp-mapit-checkbox"><input type="checkbox" id="<?php echo $id . $option['value']; ?>" name="<?php echo $name; ?>[]" <?php echo ( in_array( $option['value'] , $value) ? 'checked="checked"' : '' ); ?> value="<?php echo $option['value']; ?>"><span for="<?php echo $id . $option['value']; ?>"><?php echo $option['label']; ?></span></div>
<?php
								}
?>
							</div>
<?php
						}

						break;
					case 'select':
?>
						<select id="<?php echo $id; ?>" name="<?php echo $name; ?>">
							<?php
								if( is_array( $options ) && count( $options ) > 0 ) {
									foreach ($options as $option) {
							?>
										<option value="<?php echo $option['value']; ?>" <?php echo selected( $value, $option['value'], false ); ?>><?php echo $option['label']; ?></option>
							<?php
									}
								}
							?>
						</select>
<?php
						break;
					case 'radio':
						if( is_array( $options ) && $options == '' ) {
?>
							<ul>
								<?php
									foreach ($options as $option) {
								?>
										<li><input type="radio" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $option['value']; ?>"> <?php echo $option['label']; ?></li>
								<?php
									}
								?>
							</ul>
<?php
						}
						break;
					case 'image':
?>
						<input type="hidden" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $value; ?>">
						<?php
							if( trim( $value ) != '' ) {
						?>
								<img src="<?php echo $value; ?>">
						<?php
							}
						?>
						<a href="#" class="upload_image button"><?php _e( 'Choose Image', WP_MAPIT_TEXTDOMAIN ); ?></a> 
						<a href="#" class="remove_image button"><?php _e( 'Remove Image', WP_MAPIT_TEXTDOMAIN ); ?></a>
<?php
						break;
					case 'map':
						$defaultMapType = wp_mapit_admin_settings::get_map_type();
						$defaultZoom = wp_mapit_admin_settings::get_map_zoom();
						$defaultLat = wp_mapit_admin_settings::get_map_latitude();
						$defaultLng = wp_mapit_admin_settings::get_map_longitude();
						$defaultMarker = wp_mapit_admin_settings::get_map_marker();
?>
						<div id="<?php echo $id; ?>" data-maptype="<?php echo $defaultMapType; ?>" data-zoom="<?php echo $defaultZoom; ?>" data-latitude="<?php echo $defaultLat; ?>" data-longitude="<?php echo $defaultLng; ?>" data-marker="<?php echo $defaultMarker; ?>"></div>
<?php
						break;
					case 'map_search':
?>
						<div class="wp-mapit-search">
							<input type="text" id="<?php echo $id; ?>" name="<?php echo $name; ?>">
							<a href="#" id="<?php echo $id; ?>_btn" class="button"><?php _e( 'Search', WP_MAPIT_TEXTDOMAIN ); ?></a>
						</div>
<?php
					default:
						do_action( 'wp_mapit_custom_field_' . $type, $postId, $field, $value );
				}

				echo $desc;
			}

			/**
		     * Hook to save values for the custom meta boxes
		     *
		     * @since 1.0
		     * @access public
		     * @param $post_id Int Id of the post
		     */
			public function save_post( $post_id ) {
				$post_type = get_post_type();
		
				// verify nonce
				if ( ! isset( $_POST['wp_mapit_metabox_nonce'] ) )
					return $post_id;
				if ( ! ( in_array( $post_type, $this->postTypes ) || wp_verify_nonce( $_POST['wp_mapit_metabox_nonce'],  'wp_mapit_metabox' ) ) ) 
					return $post_id;
				// check autosave
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
					return $post_id;
				// check permissions
				if ( ! current_user_can( 'edit_page', $post_id ) )
					return $post_id;

				if( is_array( $this->fields ) && count( $this->fields ) > 0 && in_array( $post_type, $this->postTypes ) ) {
					foreach ( $this->fields as $field ) {

						if( ! in_array( $field['type'] , apply_filters ( 'wp_mapit_exclude_save_fields', array( 'map', 'map_search' ) ) ) ) {
							if( $field['type'] == 'section' ) {
								if( isset( $field['fields'] ) && is_array( $field['fields'] ) && count( $field['fields'] ) > 0 ) {
									foreach ( $field['fields'] as $sectionField ) {
										$sanitize = ( isset( $sectionField['sanitize'] ) ? $sectionField['sanitize'] : '' );
										update_post_meta( $post_id, $sectionField['id'], ( isset( $_POST[$sectionField['id']] ) ? $this->sanitize_field( $_POST[$sectionField['id']], $sanitize ) : '' ) );
									}
								}
							} else if( $field['type'] == 'mappins' ) {
								update_post_meta( $post_id, $field['id'], ( ( isset( $_POST[$field['id']] ) && is_array( $_POST[$field['id']] ) ) ? $_POST[$field['id']] : array() ) );
							} else {
								$sanitize = ( isset( $field['sanitize'] ) ? $field['sanitize'] : '' );
								update_post_meta( $post_id, $field['id'], ( isset( $_POST[$field['id']] ) ? $this->sanitize_field( $_POST[$field['id']], $sanitize ) : '' ) );
							}
						}
					}

					do_action( 'wp_mapit_after_save', $post_id );
				}
			}

			/**
		     * Function to sanitize user input
		     *
		     * @since 1.0
		     * @access private
		     * @param $string String String to be sanitized
		     * @param $function String Function used to sanitize the string
		     * @return string Returns the sanitize string
		     */
			private function sanitize_field( $string, $function = 'sanitize_text_field' ) {
				switch ( $function ) {
					case 'intval':
						return intval( $string );
					case 'absint':
						return absint( $string );
					case 'wp_kses_post':
						return wp_kses_post( $string );
					case 'wp_kses_data':
						return wp_kses_data( $string );
					case 'esc_url_raw':
						return esc_url_raw( $string );
					case 'is_email':
						return is_email( $string );
					case 'sanitize_title':
						return sanitize_title( $string );
					case 'santitize_boolean':
						return santitize_boolean( $string );
					case 'sanitize_textarea':
						return sanitize_textarea_field( $string );
					case 'sanitize_array':
						return array_map( 'sanitize_text_field', wp_unslash( $string ) );
					case 'sanitize_text_field':
					default:
						return sanitize_text_field( $string );
				}
			}
		}
	}