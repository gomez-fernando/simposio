<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


/**
 *
 * Shows notifications on standard version
 *
 * Class Notification
 * @package BeforeAfter\MapManager
 */
class Standard {
    
    
    public function __construct()
    {
        $this->init();
    }
    
    protected function init()
    {
        add_action( 'admin_notices', [
            $this,
            'showAdminNotification',
        ], 15 );
        
        add_filter( 'register_post_type_args', [
            $this,
            'restrictPostView',
        ], 15, 2 );
        
        add_action( 'admin_notices', [
            $this,
            'showReviewNotification',
        ], 15 );
		
		add_option( 'show_review', 
			1 );
        
    }
    
    public function showReviewNotification()
    {
//        if( !isset( $_COOKIE[ 'osmapper_review_nag' ] ) ){
        if( get_option( 'show_review' ) ){
//			print_r( get_option( 'show_review' ) );
            ?>
			<div class="ba_map_notice review_plugin notice notice-success is-dismissible">
		        <h2><?php _e( 'Do you like OSMapper?', 'osmapper' ) ?></h2>
		        <p><?php _e( 'Give us 5 stars!', 'osmapper' ) ?> </p>
				<p>
			        <span class="dashicons dashicons-star-filled"></span>
			        <span class="dashicons dashicons-star-filled"></span>
			        <span class="dashicons dashicons-star-filled"></span>
			        <span class="dashicons dashicons-star-filled"></span>
			        <span class="dashicons dashicons-star-filled"></span>
			        <span class="dashicons dashicons-star-filled"></span>
		        </p>
		        
		       
			<a href="https://wordpress.org/support/plugin/osmapper/reviews/" target="_blank"><?php _e( 'Review plugin' ) ?></a>
			<a class="dismiss-nag" href="#"><?php _e( 'I already did it' ) ?></a>
            </div>
            <?php
        }
        
    }
    
    /**
     * Show banners in standard version
     */
    public function showAdminNotification()
    {
        global $pagenow;
        
        if( get_current_screen()->parent_file === 'edit.php?post_type=ba_map' ){
            
            ?>
			<div class="ba_map_notice notice  ">
                <p>
	                <?php
//	                debug($_SERVER);
	                $url = get_bloginfo('url');
	                echo '<a target="_blank" href="https://b4after.pl/osmapper/en?utm_source='.$url.'&utm_medium=admin_panel&utm_campaign=OSMapper">
					<img style="max-width:100%; height: auto;" src="'.BAMAP_URL.'assets/images/pro-banner.png">
					</a>'; ?>
                </p>
            </div>
            <?php
        }
        
        
    }
    
    /**
     *
     * Disallow adding more map than 1 in standard version
     *
     * @param $args
     * @param $post_type
     *
     * @return array
     *
     * TODO: Secure from adding more than 1 map
     * TODO: Autodelete new posts if something exists
     */
    public function restrictPostView( $args, $post_type )
    {
        
        
        if( $post_type !== BAMAP_CPT ){
            
            return $args;
            
        }
        
        $posts = get_posts( [
            'post_type' => BAMAP_CPT,
        ] );
        
        
        if( count( $posts ) > 0 )// Add additional Products CPT options.
        {
            $mapsNewArgs = [
                'capabilities' => [
                    'create_posts' => FALSE,
                    // Removes support for the "Add New" function ( use 'do_not_allow' instead of false for multisite set ups )
                ],
                'map_meta_cap' => TRUE,
            ];
            
            
            // Merge args together.
            return array_merge( $args, $mapsNewArgs );
        }
        else{
            return $args;
        }
    }
    
    
}