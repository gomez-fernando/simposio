<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class AjaxHandler {
    
    private $prefix;
    
    public function __construct()
    {
        $this->prefix = BAMAP_PREFIX;
        
        //ba_check_user
        add_action( 'wp_ajax_nopriv_ba_map_ajax_handler', [
            $this,
            'handleRequest',
        ] );
        add_action( 'wp_ajax_ba_map_ajax_handler', [
            $this,
            'handleRequest',
        ] );
        
    }  
    
    public function handleRequest()
    {
        /**
         *
         */
        $requestType = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING )[ 'requestType' ];
        $requestParams = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING )[ 'requestParams' ];
        
        
        $dataToSend = [];
        
        /**
         * Prevent to catch other ajax request in wordpress ex. heatbeat
         */
        if( $requestType === 'getConfig' ){
            
            $dataToSend[ 'markers' ] = get_post_meta( $requestParams['mapID'], $this->prefix.'attributes', TRUE );
			$dataToSend[ 'check' ] = $requestParams['mapID'];
			
            $dataToSend[ 'config' ] = get_post_meta( $requestParams['mapID'], $this->prefix.'config', TRUE );
            /**
             * Check if is on admin area or in frontend area
             */
            $dataToSend[ 'is_admin' ] = boolval( is_admin_request() );
            
            
        }
        
        if( $requestType === 'delete_map' ){
            
            
            $status = wp_delete_post( $requestParams, TRUE );
            
            $message = '';
            
            /**
             *(WP_Post|false|null) Post data on success, false or null on failure.
             */
            if( is_object( $status ) ){
                $message = __( 'Successful delete', 'osmapper' );
            }
            elseif( is_null( $status ) OR !$status ){
                $message = __( 'Failure delete', 'osmapper' );
            }
            else{
                $message = __( 'Successful delete', 'osmapper' );
            }
            $dataToSend[ 'addNewUrl' ] = add_query_arg( [ 'post_type' => BAMAP_CPT ], admin_url( 'post-new.php' ) );
            
            
            $dataToSend[ 'status' ] = $status;
        }
		
		if( $requestType === 'close_review' ) {
			update_option( 'show_review', 0, true );
		}
        
        
        $dataToSend[ 'type' ] = $requestType;
        $dataToSend[ 'params' ] = $requestParams;
        
        
        echo json_encode( $dataToSend );
        
        die();
    }
    
    
}