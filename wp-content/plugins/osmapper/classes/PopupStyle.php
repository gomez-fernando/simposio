<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class PopupStyle {
    
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
                'class' => 'osmapper_standard-border-radius',
                'src'   => BAMAP_URL.'assets/images/popups/standard.jpg',
                'name'  => __( 'Standard with border radius', 'osmapper' ),
            ],
            //TODO: zrobić kupe
            //TODO: tylko 1 dostepna, reszta dostepna w wersji pro
            [
                'src'      => BAMAP_URL.'assets/images/popups/standard-darkmode.jpg',
                'disabled' => TRUE,
            ],
            [
                'src'      => BAMAP_URL.'assets/images/popups/flat.jpg',
                'disabled' => TRUE,
            ],
            [
                'src'      => BAMAP_URL.'assets/images/popups/flat-darkmode.jpg',
                'disabled' => TRUE,
            ],
        ];
    }
    
    public function getPopupStyles()
    {
        return $this->config;
    }
    
    public function getPopupStylesHtml( $attribute )
    {
        $popupStyle = isset( $attribute[ 'popup_style' ] ) ? $attribute[ 'popup_style' ] : NULL;
        $styles = $this->getPopupStyles();
        
        $h = '';
        if( !empty( $styles ) ){
            $h .= '<div class="config_items --with-small-space --without-radius --big-images">';
            foreach( $styles as $item ){
                
                if( isset( $item[ 'disabled' ] ) && $item[ 'disabled' ] ){
                    $h .= '<label class="label --without_label availableOnPro">';
                    $h .= '<img src="'.$item[ 'src' ].'">';
                }
                else{
                    $h .= '<label class="label --without_label">';
                    $active = checked( $popupStyle, $item[ 'class' ], FALSE );
                    $h .= '<input class="" type="radio" name="popup_style" value="'.esc_attr( $item[ 'class' ] ).'" '.$active.' >';
                    $h .= '<img src="'.$item[ 'src' ].'">';
                    $h .= '<p class="text-center">'.$item[ 'name' ].'</p>';
                }
                
                $h .= '</label>';
            }
            $h .= '</div>';
        }
        else{
            return FALSE;
        }
        
        return $h;
    }
    
}
