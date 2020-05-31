<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Posttype {
    public $name;
    
    public $translates = [];
    
    public $args = [];
    
    public function __construct( $name, $translate, $args )
    {
        
        $this->name = $name;
        
        $this->translate = $translate;
        
        $this->args = $args;
    }
    
    public function register_posttype()
    {
        
        $labels = [
            'name'               => sprintf( __( '%s', 'osmapper' ), $this->translate[ 'single' ] ),
            'singular_name'      => sprintf( __( '%s', 'osmapper' ), $this->translate[ 'single' ] ),
            'menu_name'          => sprintf( __( '%s', 'osmapper' ), $this->translate[ 'single' ] ),
            'name_admin_bar'     => sprintf( __( '%s', 'osmapper' ), $this->translate[ 'single' ] ),
            'add_new'            => sprintf( __( 'New %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'add_new_item'       => sprintf( __( 'New %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'new_item'           => sprintf( __( 'New %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'edit_item'          => sprintf( __( 'Edit %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'view_item'          => sprintf( __( 'View %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'all_items'          => sprintf( __( 'All %s', 'osmapper' ), $this->translate[ 'plural' ] ),
            'search_items'       => sprintf( __( 'Search %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'parent_item_colon'  => sprintf( __( 'Parent %s', 'osmapper' ), $this->translate[ 'single' ] ),
            'not_found'          => sprintf( __( '%s not found', 'osmapper' ), $this->translate[ 'single' ] ),
            'not_found_in_trash' => sprintf( __( '%s not found in trash', 'osmapper' ), $this->translate[ 'single' ] ),
        
        ];
		
        //wont show while public parameter is set to false
        $rewrite = [
            'slug'       => $this->name,
            'with_front' => TRUE,
            'pages'      => TRUE,
            'feeds'      => TRUE,
        ];
        
        $args = [
            'labels'              => $labels,
            'public'              => FALSE,
            'exclude_from_search' => TRUE,
            'show_ui'             => TRUE,
            'show_in_nav_menus'   => TRUE,
            'show_in_menu'        => TRUE,
            'capability_type'     => 'post',
            'hierarchical'        => FALSE,
            'rewrite'             => $rewrite,
            'supports'            => [ 'title' ],
            'menu_icon'           => array_key_exists( 'menu_icon', $this->args ) ? $this->args[ 'menu_icon' ] : 'dashicons-tag',
            'menu_position'       => array_key_exists( 'menu_position', $this->args ) ? $this->args[ 'menu_position' ] : 9999,
        
        ];
        
        register_post_type( $this->name, $args );
        
        //		Add new column
        add_filter( 'manage_'.$this->name.'_posts_columns', [
            $this,
            'columns_header',
        ], 10 );
        
        //Removes default column
        add_filter( 'manage_'.$this->name.'_posts_columns', [
            $this,
            'remove_defaults',
        ], 10 );
        
        //Add content to new columns
        add_action( 'manage_'.$this->name.'_posts_custom_column', [
            $this,
            'columns_content',
        ], 10, 2 );
        
        //TODO: Delete map straight to hell / skip trash /
        //        add_filter( 'post_row_actions', [
        //            $this,
        //            'remove_row_actions_post',
        //        ], 10, 2 );
        
    }
    
    public function remove_row_actions_post( $actions, $post )
    {
        if( get_post_type() === $this->name ){
            unset( $actions[ 'clone' ] );
            unset( $actions[ 'trash' ] );
            
            $actions[ 'in_google' ] = '<a style="color:#a00" class="delete_map" data-post="'.$post->ID.'" href="#">'.__( 'Delete map', 'osmapper' ).'</a>';
            
            
        }
        
        return $actions;
    }
    
    /**
     * Add custom headers
     *
     * @param type $defaults
     *
     * @return string
     */
    function columns_header( $defaults )
    {
        //    $defaults['miniatura'] = 'miniatura';
        $defaults[ 'shortcode' ] = 'Shortcode';
        
        
        return $defaults;
    }
    
    /**
     * Remove default fields from CAR LIST
     *
     * @param type $defaults
     *
     * @return type
     */
    function remove_defaults( $defaults )
    {
        
        //    unset($defaults['title']);
        unset( $defaults[ 'date' ] );
        
        return $defaults;
    }
    
    /**
     * Add custom value's
     *
     * @param type $column_name
     * @param type $post_ID
     */
    function columns_content( $column_name, $post_ID )
    {
        
        if( $column_name == 'shortcode' ){
            //			echo '['.$this->name.' id="'.$post_ID.'"]';
            
            echo '<input type="text" onfocus="this.select()" readonly="readonly" value=\'[osmapper id="'.$post_ID.'"]\' class="large-text" style="line-height: 1.7">';
            
            
            //            echo "<input type="text" onfocus="this.select()" readonly="readonly" value='[$this->name id=\"$post_ID\"]' class="large - text code" style="line - height: 1.7;">";
        }
    }
    
    
}