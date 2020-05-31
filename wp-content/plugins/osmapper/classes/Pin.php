<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class Pin {
    
    
    protected $config;
    protected $prefix;
    
    public function __construct()
    {
        $this->prefix = BAMAP_PREFIX;
        
        $this->config = [
            [
                'name'  => 'pin',
                'value' => BAMAP_URL.'assets/images/pins/pin-1.png',
                'src'   => BAMAP_URL.'assets/images/pins/pin-1.png',
            ],
            [
                'name'  => 'pin',
                'value' => BAMAP_URL.'assets/images/pins/pin-3.png',
                'src'   => BAMAP_URL.'assets/images/pins/pin-3.png',
            ],
            [
                'name'     => 'pin',
                'src'      => BAMAP_URL.'assets/images/pins/upload.png',
                'disabled' => TRUE,
            ]
            
            
            //todo : Ikonka uploadu. Niedostępna w wersji darmowej
        ];
    }
    
    
    public function getPins( $attribute )
    {
        $pin = isset( $attribute[ 'pin' ] ) ? $attribute[ 'pin' ] : NULL;
        //        var_dump( $pin );
        $h = '';
        $h .= '<div class="config_items --with-small-space">';
        foreach( $this->config as $key => $item ){
            $class = '';
            if( !array_key_exists( 'disabled', $item ) ){
                if( is_null( $pin ) && $key == 0 ){
                    $active = 'checked="checked"';
                }
                else{
                    $active = checked( $pin, $item[ 'value' ], FALSE );
                }
            }
            else{
                $class .= ' availableOnPro';
            }
            /**
             * Class for custom pin
             */
            
            if( isset( $item[ 'class' ] ) ){
                $class .= $item[ 'class' ];
                $h .= '<label class="label '.$item[ 'class' ].'">';
                
                if( $item[ 'class' ] == 'customPin' ){
                    $h .= '<img class="delete_custom_pin" src="'.BAMAP_PRO_URL.'/assets/images/delete.png">';
                }
            }
            else{
                $class .= ' --without_label';
                $h .= '<label class="label '.$class.'">';
            }
            
            if( !array_key_exists( 'disabled', $item ) ){
                $h .= '<input class="'.$class.'" type="radio" name="pin" value="'.esc_attr( $item[ 'value' ] ).'" '.$active.' >';
            }
            $h .= '<img src="'.$item[ 'src' ].'">';
            $h .= '</label>';
        }
        $h .= '</div>';
        
        return $h;
    }
    
    
}