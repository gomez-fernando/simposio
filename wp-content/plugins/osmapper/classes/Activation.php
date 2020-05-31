<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Activation {
    
    public function activationPlugin( $plugin )
    {
        if( 'osmapper/osmapper.php' === $plugin ){
            $osmapperVersion = get_plugin_data( BAMAP_PLUGIN_FILE )[ 'Version' ];
        }
        if( 'osmapper_pro/osmapper_pro.php' === $plugin ){
            $osmapperProVersion = get_plugin_data( BAMAP_PRO_PLUGIN_FILE )[ 'Version' ];
            
            if( $osmapperProVersion < '1.7' ){
                wp_die( 'You should update OSMapper Pro to version at least 1.7' );
            }
        }
    }
    
    public function deactivationPlugin( $plugin )
    {
        //on normal plugin deactivation
        if( 'osmapper/osmapper.php' === $plugin ){
            if( is_plugin_active( 'osmapper_pro/osmapper_pro.php' ) ){
                wp_die( 'You should deactivate OSMapper Pro first!' );
            }
        }
    }
}