<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Renderer {
    
    public function __construct()
    {
        add_shortcode( 'osmapper', [
            $this,
            'generate_base_shortcode',
        ] );
        add_shortcode( 'ba_map', [
            $this,
            'generate_base_shortcode',
        ] );
    }
    
    function generate_base_shortcode( $atts )
    {
        
        
        $a = shortcode_atts( [
            'id' => 0,
        ], $atts );
        
        if( $a[ 'id' ] == FALSE ){
            $response = __( 'You must enter ID of generated map', 'osmapper' );
        }
        else{
            $response = $this->prepareMapFromShortcode( $a[ 'id' ] );
        }
        
        return $response;
    }
    
    /**
     *
     * Translates render function to shortcode callback
     *
     * @param $mapID
     *
     * @return string
     */
    public function prepareMapFromShortcode( $mapID )
    {
        ob_start();
        
        $this->renderMapFromShortcode( $mapID );
        
        $html = ob_get_contents();
        
        ob_end_clean();
        
        
        return $html;
    }
    
    
    /**
     *
     * Main function which renders a map
     *
     * @param $mapID
     */
    private function renderMapFromShortcode( $mapID )
    {
        /**
         * Generate ID of map
         */
        $date = new \DateTime( 'now' );
        $ID = md5( $date->format( 'd_m_y__h_i_s' ).$mapID );
        
        if( get_post( $mapID ) ){
            echo '<div class="ba_map_holder" id="'.$ID.'" data-map-id="'.$mapID.'"></div>';
            
        }
        //        else{
        //            echo '<strong>'.__( 'Map doesn\'t exists', 'osmapper' ).'</strong>';
        //        }
        
    }
    
}