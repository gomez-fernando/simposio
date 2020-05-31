<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class ColorScheme {
    
    protected $config;
    
    protected $prefix;
    
    public function __construct()
    {
        $this->prefix = BAMAP_PREFIX;
        
        $this->config = $this->getConfig();
    }
    
    protected function getConfig()
    {
        return [
            [
                'name'  => 'scheme',
                'src'   => BAMAP_URL.'assets/images/schemes/light_all.png',
                'value' => '//basemaps.cartocdn.com/light_all/',
            ],
            
            [
                'name'  => 'scheme',
                'src'   => BAMAP_URL.'assets/images/schemes/pitney-bowes-dark.png',
                'value' => '//basemaps.cartocdn.com/dark_all/',
            ],
            [
                'name'  => 'scheme',
                'src'   => BAMAP_URL.'assets/images/schemes/default.png',
                'value' => '//a.tile.openstreetmap.org/',
            ],
            [
                'name'     => 'scheme',
                'src'      => BAMAP_URL.'assets/images/schemes/osm-intl.png',
                'disabled' => TRUE,
            ],
            [
                'name'     => 'scheme',
                'src'      => BAMAP_URL.'assets/images/schemes/default_hot.png',
                'disabled' => TRUE,
            ],
            [
                'name'     => 'scheme',
                'src'      => BAMAP_URL.'assets/images/schemes/voyager.png',
                'disabled' => TRUE,
            ],
            [
                'name'     => 'scheme',
                'src'      => BAMAP_URL.'assets/images/schemes/spotify_dark.png',
                'disabled' => TRUE,
            ],
            
            
            //TODO: Reszta konfiguracji pokazana ale dostępna tylko w pro wersji
        ];
    }
    
    public function getSchemes()
    {
        
        
        return $this->config;
    }
}