<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */

namespace BeforeAfter\MapManager;


class Controller {
    
    /**
     *
     *
     * Controller constructor.
     */
    public function __construct()
    {
        
        $installer = new Install();
        
        $Renderer = new Renderer();
        
        $Metabox = new Metabox();
        
        $Notification = new Standard();
        
		// $Update = new Update();
    }
    
    
}