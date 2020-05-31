<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Update {
    
	  
    public function __construct()
    {
        add_action( 'upgrader_process_complete', [
            $this,
            'afterUpgrade',
        ], 15 );
    }

    public function afterUpgrade( $upgrader_object, $options )
    {
		$current_plugin_path_name = plugin_basename( __FILE__ );

		if ($options['action'] == 'update' && $options['type'] == 'plugin' ){
			foreach($options['plugins'] as $each_plugin){
				if ($each_plugin==$current_plugin_path_name){
					update_option( 'show_review', 1, true );
				}
			}
		}
    }
    
}