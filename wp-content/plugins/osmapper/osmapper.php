<?php
/**
 * Plugin Name: OSMapper
 * Author: Agencja Interaktywna Before After
 * Author URI: https://www.b4after.pl
 * Description: Plugins help with adding map on page
 * Plugin URI: https://www.b4after.pl/osmapper
 * Version: 2.1.1
 * License: GPLv2
 * Text Domain: osmapper
 * Domain Path: /languages
 */



if( !defined( 'ABSPATH' ) ){
    
    exit; // Exit if accessed directly.
}
require_once( 'vendor/autoload.php' );

class osmapper {
    
    public $version;
    
    
    public function __construct( $version )
    {
        $this->version = $version;
        
        $this->define_constants();
        $this->load_textdomain();
        new \BeforeAfter\MapManager\AjaxHandler();
        new \BeforeAfter\MapManager\Controller();
        
        
    }
    
    /**
     * Define constants used in plugin
     */
    public function define_constants()
    {
        define( 'BAMAP_PLUGIN_FILE', __FILE__ );
        
        define( 'BAMAP_ABSPATH', dirname( __FILE__ ).'/' );
        
        define( 'BAMAP_VERSION', $this->version );
        
        define( 'BAMAP_PATH', plugin_dir_path( __FILE__ ) );
        
        define( 'BAMAP_URL', plugin_dir_url( __FILE__ ) );
        
        define( 'BAMAP_CPT', 'ba_map' );
        
        define( 'BAMAP_PREFIX', 'ba_map__' );
        
        
    }
    
    /**
     * Makes plugin translable
     */
    public function load_textdomain()
    {
        
        load_plugin_textdomain( 'osmapper', FALSE, dirname( plugin_basename( __FILE__ ) ).'/languages/' );
    }
    
    
}


// Check if get_plugins() function exists. This is required on the front end of the
// site, since it is in a file that is normally only loaded in the admin.
if( !function_exists( 'get_plugins' ) ){
    require_once ABSPATH.'wp-admin/includes/plugin.php';
}
//$all_plugins = get_plugins();
$pluginData = get_plugin_data( plugin_dir_path( __DIR__ ).'osmapper/osmapper.php' );
$mapManager = new osmapper( $pluginData[ 'Version' ] );



add_action( 'deactivated_plugin', [
    new \BeforeAfter\MapManager\Activation(),
    'deactivationPlugin',
], 10, 2 );

add_action( 'activated_plugin', [
    new \BeforeAfter\MapManager\Activation(),
    'activationPlugin',
], 10, 2 );



