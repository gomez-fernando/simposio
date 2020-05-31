<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Metabox {
    
    /**
     * @var string
     */
    protected $prefix;
    
    /**
     * Based on this config marker form is rendered
     * @var array
     */
    protected $markerConfig = [];
    
    /**
     *  Based on this config map for is renderd
     * @var array
     */
    protected $mainConfig = [];
    
    /**
     * @var bool
     */
    protected $is_valid;
    
    protected $defaults = [];
    
    public function __construct()
    {
        /**
         * Plugin main prefix
         */
        $this->prefix = BAMAP_PREFIX;
        /**
         * Build config to pass it to form renderer
         */
        $this->initConfig();
        /**
         * Add metaboxes
         */
        $this->init();
        
        $this->is_valid = TRUE;
        
        $this->defaults = [
            'pin' => BAMAP_URL.'assets/images/pins/pin-1.png',
        ];
    }
    
    protected function initConfig()
    {
        
        
        $this->mainConfig = [
            [
                'type'        => 'radio',
                'name'        => 'layer',
                'label'       => __( 'Color Scheme', 'osmapper' ),
                'placeholder' => __( 'Color Scheme', 'osmapper' ),
                'options'     => $this->__getColorSchemes(),
            ],
            [
                'type'        => 'radio',
                'name'        => 'marker_position',
                'label'       => __( 'Map offset', 'osmapper' ),
                'placeholder' => __( 'Map offset', 'osmapper' ),
                'class'       => '--without__input',
                'options'     => $this->__getMarkerPositions(),
            ],
            [
                'type'        => 'number',
                'name'        => 'map_zoom',
                'label'       => __( 'Map zoom', 'osmapper' ),
                'placeholder' => __( 'Map zoom', 'osmapper' ),
                'class'       => '--with_label',
                'default'     => '14',
                'options'     => [
                    'name'  => 'map_zoom',
                    'value' => '',
                ],
            ],
            [
                'type'        => 'number',
                'name'        => 'map_height',
                'label'       => __( 'Map height (px)', 'osmapper' ),
                'placeholder' => __( 'Map height', 'osmapper' ),
                'class'       => '--with_label',
                'default'     => '300',
                'options'     => [
                    'name'  => 'map_height',
                    'value' => '',
                ],
            ],
            [
                'type'        => 'radio',
                'name'        => 'zoom_on_scroll',
                'label'       => __( 'Zoom on scroll', 'osmapper' ),
                'placeholder' => __( 'Zoom on scroll', 'osmapper' ),
                'class'       => '--without__input',
                'options'     => $this->__getZoomOnScroll(),
            
            ],
            [
                'type'        => 'radio',
                'name'        => 'full_screen',
                'label'       => __( 'Full screen', 'osmapper' ),
                'placeholder' => __( 'Full screen', 'osmapper' ),
                'class'       => '--without__input',
                'options'     => $this->__getFullScreen(),
            
            ],
            $this->__addMoreFields(),
        
        ];
        
    }
    
    public function init()
    {
//		$this->registerMetabox();
        /**
         * Adds metaboxes
         * DUH!
         */
        add_action( 'add_meta_boxes', [
            $this,
            'registerMetabox',
        ] );
        /**
         * Fires up only when BA_LOC_CPT is saving
         */
        add_action( 'save_post_'.BAMAP_CPT, [
            $this,
            'saveMetabox',
        ] );
        
    }
    
    /**
     *
     */
    public function registerMetabox()
    {
        global $pagenow;
        
        add_meta_box( 'ba_map_lat_long', __( 'Map localization', 'osmapper' ), [
            $this,
            'renderMetabox',
        ], BAMAP_CPT, 'normal', 'high' );
        
        add_meta_box( 'ba_map_config', __( 'Map config', 'osmapper' ), [
            $this,
            'renderConfig',
        ], BAMAP_CPT, 'side', 'low' );
        
        add_meta_box( 'ba_map_shortcode', __( 'Map shortcode', 'osmapper' ), [
            $this,
            'renderShortcode',
        ], BAMAP_CPT, 'side', 'high' );
        
        /**
         * Disble render preview on post-new action
         */
        if( $pagenow !== 'post-new.php' ){
            add_meta_box( 'ba_map_preview', __( 'Map preview', 'osmapper' ), [
                $this,
                'renderPreview',
            ], BAMAP_CPT, 'normal', 'low' );
            
        }
        
        
    }
    
    /**
     * Renders a shortcode which can be used to renders a map
     *
     * @param $post
     */
    public function renderShortcode( $post )
    {
        echo '<p class="config_section__title">'.__( 'Shortcode to paste on page', 'osmapper' ).'</p>';
        echo '<input type="text" onfocus="this.select()" readonly="readonly" value=\'[osmapper id="'.$post->ID.'"]\' class="large-text" style="line-height: 1.7">';
        
        
    }
    
    /**
     * Renderd config options in side area of post
     *
     * In config we can change map color layer and markers pins
     *
     * @param $post
     */
    public function renderConfig( $post )
    {
        $formBuilder = new FormBuilder();
        $formBuilder->setState( $this->is_valid );
        
        $config = get_post_meta( $post->ID, $this->prefix.'config', TRUE );
        
        
        //        debug( $config );
        
        echo $formBuilder->__renderConfigItems( 'ba_map', $config, $this->mainConfig, 0 );
        
        
    }
    
    /**
     * Renders an preview of map with an option to change position of marker
     *
     * @param $post
     */
    public function renderPreview( $post )
    {
        
        
        echo '<p class="config_section__title">'.__( 'You can dragg markers to place them in right spot', 'osmapper' ).'</p>';
        echo do_shortcode( '[osmapper id="'.$post->ID.'"]' );
        
    }
    
    /**
     * Echo'es metabox fields
     *
     * @param $post
     */
    public function renderMetabox( $post )
    {
        $attribute = get_post_meta( $post->ID, $this->prefix.'attributes', TRUE ) ? get_post_meta( $post->ID, $this->prefix.'attributes', TRUE )[ 0 ] : [];
        
        //        debug( $attribute );
        
        
        ?>
		<div class="repeater">

		<div class="modernTable">

		<div class="tableHeader">
			<?php
            echo $this->getTableHeader();
            ?>
		</div>
        
        <?php
        echo '<div class="tableContent">';
        echo '<div id="ba_map_addresses" data-repeater-list="'.$this->prefix.'attributes'.'">';
        
        
        $this->getTheRow( $attribute, 0 );
        
        
        echo '</div>'; //#ba_map_addresses && data-repeater-list
        echo '</div>'; //.tableContent
        
        echo '<input class="ba-btn getCoords" type="button" value="'.__( 'Get coordinates', 'osmapper' ).'" />';
        
        echo '</div>'; //div.repeater
    }
    
    /**
     * Saving function
     *
     * @param $postID
     */
    public function saveMetabox( $postID )
    {
        
        $map_atts = [];
        $map_config = [];
        
        $data = $_POST;
        
        foreach( $data as $valueName => $valueToSave ){
            /**
             * Search for ba_map__ prefix in $_POST array
             */
            if( strpos( $valueName, $this->prefix.'attributes' ) !== FALSE ){
                
                
//                $debug = [];
                foreach( $valueToSave as $key => $values ){
                    
                    foreach( $values as $name => $value ){
                        
                        /**
                         * Saving infobox requires esc_textarea
                         */
                        if( $name === 'infobox' ){
                            $map_atts[ $key ][ sanitize_key( $name ) ] = wp_kses_post( $value );
                        }
                        else{
                            $map_atts[ $key ][ sanitize_key( $name ) ] = sanitize_text_field( $value );
                        }
                        /**
                         * Prevent of unseting row_id values
                         * It might be cause errors when it isnt set because we cant further mover pin
                         */
                        if( $name === 'row_id' AND !$value ){
                            $salt = wp_create_nonce( 'nonce_salt_'.$key );
                            $id = hash( 'crc32', $salt.$name );
                            
                            $map_atts[ $key ][ sanitize_key( $name ) ] = sanitize_text_field( $id );
                        }
                    }
                }
                //                wp_die( debug( $map_atts ) );
            }
            if( strpos( $valueName, $this->prefix.'config' ) !== FALSE ){
                
                /**
                 * Safe save strings
                 */
                foreach( $valueToSave as $key => $value ){
                    
                    if( $key === 'layer' || $key === 'pin' ){
                        /**
                         * pin and layer vales are URLs
                         */
                        $map_config[ sanitize_key( $key ) ] = esc_url( $value );
                    }
                    else{
                        $map_config[ sanitize_key( $key ) ] = sanitize_text_field( $value );
                    }
                }
            }
        }
        
        update_post_meta( $postID, $this->prefix.'attributes', $map_atts );
        update_post_meta( $postID, $this->prefix.'config', $map_config );
        
        
    }
    
    
    /**
     *
     *
     *
     * @return array
     */
    protected function __addMoreFields()
    {
        return NULL;
    }
    
    /**
     * Renders aviable radio options with color schemes for map
     *
     * @return array
     */
    protected function __getColorSchemes()
    {
        $schemes = new ColorScheme();
        
        return $schemes->getSchemes();
    }
    
    
    /***
     *
     * Static options
     *
     * @return array
     */
    private function __getZoomOnScroll()
    {
        return [
            [
                'name'  => 'zoom_on_scroll',
                'value' => 'Yes',
				'label' => __( 'Yes', 'osmapper' ),
            ],
            [
                'name'  => 'zoom_on_scroll',
                'value' => 'No',
				'label' => __( 'No', 'osmapper' ),
            ],
        ];
    }
    
	
    /***
     *
     * Static options
     *
     * @return array
     */
    private function __getFullScreen()
    {
        return [
            [
                'name'  => 'full_screen',
                'value' => 'Yes',
				'label' => __( 'Yes', 'osmapper' ),
            ],
            [
                'name'  => 'full_screen',
                'value' => 'No',
				'label' => __( 'No', 'osmapper' ),
            ],
        ];
    }
    
    /**
     *
     * Static options
     *
     *
     * @return array
     */
    private function __getMarkerPositions()
    {
        return [
            [
                'name'  => 'map_position',
                'value' => 'center',
				'label' => __( 'center', 'osmapper' ),
            ],
            [
                'name'  => 'map_position',
                'value' => 'bottom',
				'label' => __( 'bottom', 'osmapper' ),
            ],
            [
                'name'  => 'map_position',
                'value' => 'left',
				'label' => __( 'left', 'osmapper' ),
            ],
            [
                'name'  => 'map_position',
                'value' => 'right',
				'label' => __( 'right', 'osmapper' ),
            ],
            [
                'name'  => 'map_position',
                'value' => 'top',
				'label' => __( 'top', 'osmapper' ),
            ],
        ];
    }
    
    /**
     * @param array $attribute
     * @param       $value
     * @param       $default
     *
     * @return mixed
     */
    private function getValue( $attribute, $value, $default = '' )
    {
        if( !empty( $attribute ) ){
            return isset( $attribute[ $value ] ) ? $attribute[ $value ] : '';
        }
        else{
            return $default;
        }
    }
    
    
    protected function getTheRow( array $attribute, $key )
    {
        $salt = wp_create_nonce( 'nonce_salt_'.$key );
        $id = hash( 'crc32', $salt );
        
        echo '<div class="repeaterRow" data-repeater-item>';
        
        echo $this->getRowActions();
        
        echo '<div class="table">';
        echo '<img class="selectedPin" src="'.( isset( $attribute[ 'pin' ] ) ? $attribute[ 'pin' ] : $this->defaults[ 'pin' ] ).'">';
        echo '<input id="'.uniqid( 'latitude_' ).'" data-name="latitude" type="text" name="latitude" value="'.$this->getValue( $attribute, 'latitude' ).'" placeholder="'.__( 'latitude', 'osmapper' ).'">';
        echo '<input id="'.uniqid( 'longitude_' ).'" data-name="longitude" type="text" name="longitude" value="'.$this->getValue( $attribute, 'longitude' ).'" placeholder="'.__( 'longitude', 'osmapper' ).'">';
        echo '<div class="osmapper_search-results"><input type="text" data-name="street" name="street" value="'.$this->getValue( $attribute, 'street' ).'" placeholder="'.__( 'Street name and number', 'osmapper' ).'"></div>';
        echo '<input type="text" data-name="city" name="city" value="'.$this->getValue( $attribute, 'city' ).'" placeholder="'.__( 'City', 'osmapper' ).'">';
        echo '<input type="text" data-name="zip_code" name="zip_code" value="'.$this->getValue( $attribute, 'zip_code' ).'" placeholder="'.__( 'Zip code', 'osmapper' ).'">';
        echo '<input id="'.uniqid( 'row_id_' ).'" type="hidden" data-name="row_id" name="row_id" value="'.$this->getValue( $attribute, 'row_id', uniqid( 'row_id_' ) ).'">';
        echo '</div>'; // .table
        
        echo $this->getConfigItems( $attribute, $id );
        
        echo '</div>'; //.repeaterRow
    }
    
    /**
     * Returns a config items tabs
     *
     * @param $attribute
     * @param $id
     *
     * @return string
     */
    protected function getConfigItems( $attribute, $id )
    {
        $h = '';
        /**
         * More options
         * like popup window
         * or
         * map pins
         */
        $h .= '<div class="config ">';
        $h .= '<ul class="tabs-headers">';
        $h .= '<li class="active"><a href="#tab-pin-'.$id.'">'.__( 'Pin', 'osmapper' ).'</a></li>';
        $h .= '<li><a href="#tab-popup-'.$id.'">'.__( 'Popup content', 'osmapper' ).'</a></li>';
        $h .= '<li><a href="#popup-style-'.$id.'">'.__( 'Popup style', 'osmapper' ).'</a></li>';
        $h .= '</ul>';
        
        $h .= '<div class="tabs-content">';
        
        /**
         * Map pins
         */
        $h .= '<div class="inputHolder pins active" id="tab-pin-'.$id.'">';
        $h .= '<label class="groupLabel">'.__( 'Map Pin', 'osmapper' ).'</label>';
        $h .= $this->getPins( $attribute );
        $h .= '</div>'; // inputHolder
        
        /**
         * Popup box
         */
        $h .= '<div class="inputHolder popup" id="tab-popup-'.$id.'">';
        $h .= '<label class="groupLabel">'.__( 'Popup information', 'osmapper' ).'</label>';
        ob_start();
        wp_editor( $this->getValue( $attribute, 'infobox' ), $id, [
            'textarea_name' => 'infobox',
            'media_buttons' => TRUE,
            'textarea_rows' => 20,
            'teeny'         => TRUE,
            'editor_height' => 250,
        
        ] );
        $wysiwyg = ob_get_contents();
        ob_end_clean();
        $h .= $wysiwyg;
        $h .= '</div>'; // inputHolder
        
        $h .= '<div class="inputHolder popup-style" id="popup-style-'.$id.'">';
        $h .= '<label class="groupLabel">'.__( 'Popups styles', 'osmapper' ).'</label>';
        $h .= $this->getPopupStyles( $attribute );
        $h .= '</div>'; // inputHolder
        
        
        $h .= '</div>'; // .tabs-content
        $h .= '</div>'; // .config
        
        
        return $h;
    }
    
    /**
     * Returns a header for table
     *
     * @return string
     */
    protected function getTableHeader()
    {
        $h = '';
        $h .= '<p class="pin">'.__( 'Pin' ).'</p>';
        $h .= '<p>'.__( 'Latitude', 'osmapper' ).'</p>';
        $h .= '<p>'.__( 'Longitude', 'osmapper' ).'</p>';
        $h .= '<p>'.__( 'Street name and number', 'osmapper' ).'</p>';
        $h .= '<p>'.__( 'City', 'osmapper' ).'</p>';
        $h .= '<p>'.__( 'Zip code', 'osmapper' ).'</p>';
        
        return $h;
    }
    
    /**
     * renders a row actions like open config
     *
     * @return string
     */
    protected function getRowActions()
    {
        return '<div class="rowActions"><button class="modalTrigger " data-id="0">'.__( 'Open', 'osmapper' ).'</button></div>';
    }
    
    /**
     * Helps with overriding in pro version
     *
     * @param $attribute
     *
     * @return string
     */
    protected function getPins( $attribute )
    {
        $pins = new Pin();
        
        return $pins->getPins( $attribute );
    }
    
    /**
     *
     * Renders a popup styles
     *
     * @param $attribute
     *
     * @return string
     */
    protected function getPopupStyles( $attribute )
    {
        $h = '';
        $styles = new PopupStyle();
        $html = $styles->getPopupStylesHtml( $attribute );
        if( $html ){
            $h .= $html;
        }
        else{
            return '';
        }
        
        return $h;
    }
}

