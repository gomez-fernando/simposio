<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Map {
    
    private $ID;
    
    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->ID;
    }
    
    /**
     * @param mixed $ID
     */
    public function setID( $ID )
    {
        $this->ID = $ID;
    }
    
    
}