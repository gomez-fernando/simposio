<?php
/**
 * Author : Mateusz Grzybowski
 * grzybowski.mateuszz@gmail.com
 */


namespace BeforeAfter\MapManager;


class FormBuilder {
    
    private $state;
    private $prefix;
    
    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * @param mixed $state
     */
    public function setState( $state )
    {
        $this->state = $state;
    }
    
    public function __construct()
    {
        
        $this->prefix = BAMAP_PREFIX;
        
    }
    
    /**
     *
     * Renders metabox for color scheme, and mappin
     *
     *
     * @param $fieldGroup
     * @param $keywords
     * @param $config
     * @param $index
     *
     * @return string
     */
    public function __renderConfigItems( $fieldGroup, $keywords, $config, $index )
    {
        $str = '';
        foreach( $config as $key => $item ){
            
            if( $item[ 'type' ] === 'radio' ){
                $str .= '<p class="config_section__title">'.$item[ 'label' ].'</p>';
                $str .= '<div class="config_items">';
                if( !empty( $item[ 'options' ] ) ){
                    
                    $str .= $this->getConfigOptions( $item, $keywords, $index );
                    
                }
                $str .= '</div>';
            }
            
            if( $item[ 'type' ] === 'number' ){
                $str .= '<p class="config_section__title">'.$item[ 'label' ].'</p>';
                $str .= '<div class="config_items">';
                
                $str .= $this->getConfigInput( $item, $keywords, $index );
                
                $str .= '</div>';
            }
            
            
        }
        
        
        return $str;
    }
    
    private function getConfigInput( $item, $keywords, $index )
    {
        $str = '';
        
        $class = '';
        $value = isset( $keywords[ $item[ 'name' ] ] ) ? $keywords[ $item[ 'name' ] ] : $item[ 'default' ];
        if( isset( $item[ 'class' ] ) ){
            $class = $item[ 'class' ];
        }
        
        //        debug( $value );
        //        debug( $keywords );
        
        
        $str .= '<label class="label '.$class.'">';
        $str .= '<input class="'.$class.'" min="1" type="'.$item[ 'type' ].'" name="'.$this->prefix.'config['.$item[ 'name' ].']" value="'.$value.'" >';
        
        $str .= '</label>';
        
        return $str;
    }
    
    /**
     * Renders config radio buttons with given options
     *
     * @param $item
     * @param $keywords
     * @param $index
     *
     * @return string
     */
    private function getConfigOptions( $item, $keywords, $index )
    {
        $str = '';
        foreach( $item[ 'options' ] as $key => $option ){
            $selected = '';
            
            /**
             * OPTION VALUES
             */
            $optionValue = isset( $option[ 'value' ] ) ? str_replace( 'http:', '', $option[ 'value' ] ) : '';
            /**
             * OPTION LABEL
             */
            $optionLabel = isset( $option[ 'label' ] ) ? str_replace( 'http:', '', $option[ 'label' ] ) : '';
            /**
             * CURRENT VALUE
             */
            $currentValue = isset( $keywords[ $item[ 'name' ] ] ) ? str_replace( 'http:', '', $keywords[ $item[ 'name' ] ] ) : '';
            
            
            if( isset( $keywords[ $item[ 'name' ] ] ) ){
                $selected = checked( $currentValue, $optionValue, FALSE );
            }
            elseif( $key == 0 ){
                $selected = 'checked="checked"';
            }
            //            debug( $keywords[ $item[ 'name' ] ] );
            
            $class = '--without_label';
            
            if( isset( $item[ 'class' ] ) ){
                $class = $item[ 'class' ];
            }
            elseif( isset( $option[ 'class' ] ) ){
                $class = $option[ 'class' ];
            }
            
            
            if( $item[ 'type' ] === 'radio' ){
                
                if( isset( $option[ 'disabled' ] ) && $option[ 'disabled' ] ){
                    $class .= ' availableOnPro';
                }
                if( $option[ 'name' ] === 'zoom_on_scroll' || $option[ 'name' ] === 'map_position' || $option[ 'name' ] === 'full_screen' ){
                    $class .= ' --longBtn';
                }
                $class .= ' --'.$item[ 'name' ];
                /**
                 * Check if exists custom pin
                 */
                $is_removeable = ( $class === 'customPin' ? '<img class="delete_custom_pin" src="'.BAMAP_PRO_URL.'/assets/images/delete.png">' : '' );
                
                $str .= '<label class="label '.$class.'">'.$is_removeable;
                //For disabled items
                
                if( !array_key_exists( 'disabled', $option ) ){
                    
                    $str .= '<input
                    class="'.$class.'"
                    type="'.$item[ 'type' ].'"
                    name="'.$this->prefix.'config['.$item[ 'name' ].']"
                    value="'.esc_attr( $optionValue ).'"
                     '.$selected.'>';
                    
                }
                
                
                if( $option[ 'name' ] === 'map_position' || $option[ 'name' ] === 'zoom_on_scroll' || $option[ 'name' ] === 'full_screen' ){
                    $str .= '<span>'.$optionLabel.'</span>';
                }
                $str .= isset( $option[ 'src' ] ) ? '<img src="'.esc_url( $option[ 'src' ] ).'">' : '';
                $str .= '</label>';
            }
            
        }
        
        return $str;
    }
    
    /**
     *
     * Renders fields based on config file
     *
     *
     *
     * @param $fieldGroup
     * @param $keywords
     *
     * @return string
     */
    public function __renderRepeaterItems( $fieldGroup, $keywords, $config, $index )
    {
        $modalContent = '';
        $tableContent = '';
        
        $repeaterItems = '';
        $repeaterItems .= '<div class="repeaterRow" data-repeater-item>';
        
        /**
         * Render predefined fields in config property
         */
        foreach( $config as $key => $item ){
            
            $class = isset( $item[ 'class' ] ) ? $item[ 'class' ] : '';
            
            $readonly = isset( $item[ 'attr' ] ) && array_key_exists( 'readonly', $item[ 'attr' ] ) ? 'readonly="readonly"' : '';
            
            if( $item[ 'type' ] === 'text' ){
                
                
                $str = '<input type="'.$item[ 'type' ].'" name="'.$item[ 'name' ].'" data-name="'.esc_attr( $item[ 'name' ] ).'" value="'.( isset( $keywords[ $item[ 'name' ] ] ) ? esc_attr( $keywords[ $item[ 'name' ] ] ) : '' ).'" '.$readonly.' placeholder="'.esc_attr( $item[ 'placeholder' ] ).'">';
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].' '.$class.'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
            }
            
            if( $item[ 'type' ] === 'hidden' ){
                
                $salt = wp_create_nonce( 'nonce_salt_'.$index );
                $id = hash( 'crc32', $salt );
                
                
                $str = '<input id="'.( isset( $keywords[ $item[ 'name' ] ] ) ? $keywords[ $item[ 'name' ] ] : $id ).'" type="'.$item[ 'type' ].'" name="'.$item[ 'name' ].'" data-name="'.esc_attr( $item[ 'name' ] ).'" value="'.$id.'" '.$readonly.' placeholder="'.esc_attr( $item[ 'placeholder' ] ).'">';
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].' '.$class.'">'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
            }
            
            /**
             * Text area
             */
            if( $item[ 'type' ] === 'textarea' ){
                
                $str = '<textarea rows="6" cols="50" placeholder="'.$item[ 'placeholder' ].'" data-name="'.$item[ 'name' ].'" name="'.$item[ 'name' ].'">'.( isset( $keywords[ $item[ 'name' ] ] ) ? esc_textarea( $keywords[ $item[ 'name' ] ] ) : '' ).'</textarea>';
                
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].' '.$class.'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
                
            }
            
            if( $item[ 'type' ] === 'wysiwig' ){
                
                $id = 'wp_editor_'.$index;
                
                $args = [
                    'textarea_name' => $item[ 'name' ],
                    'media_buttons' => FALSE,
                    'textarea_rows' => 5,
                    'teeny'         => TRUE,
                
                ];
                
                $value = isset( $keywords[ $item[ 'name' ] ] ) ? htmlspecialchars_decode( $keywords[ $item[ 'name' ] ] ) : '';
                
                
                ob_start();
                wp_editor( $value, $id, $args );
                $wysiwyg = ob_get_contents();
                ob_end_clean();
                
                $str = $wysiwyg;
                
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].' '.$class.'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
                
                
            }
            
            /**
             * Map preview with drag and drop
             */
            if( $item[ 'type' ] === 'map' ){
            }
            
            if( $item[ 'type' ] === 'number' ){
                
                $salt = wp_create_nonce( 'nonce_salt_'.$index );
                $id = hash( 'crc32', $salt.$item[ 'name' ] );
                
                
                $str = '<input id="'.$id.'"
                data-name="'.$item[ 'name' ].'"
                type="text"
                name="'.$item[ 'name' ].'"
                value="'.( isset( $keywords[ $item[ 'name' ] ] ) ? esc_attr( $keywords[ $item[ 'name' ] ] ) : '' ).'"
                placeholder="'.$item[ 'placeholder' ].'" >';
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
                
            }
            if( $item[ 'type' ] === 'radio' ){
                //                $repeaterItems .= '<input type="'.$item[ 'type' ].'" name="'.$item[ 'name' ].'" value="'.( isset( $keywords[ $item[ 'name' ] ] ) ? $keywords[ $item[ 'name' ] ] : '' ).'" placeholder="'.$item[ 'placeholder' ].'">';
                $str = '<div class="pseudoInput">';
                if( !empty( $item[ 'options' ] ) ){
                    foreach( $item[ 'options' ] as $key => $option ){
                        
                        $selected = isset( $keywords[ $item[ 'name' ] ] ) ? checked( $keywords[ $item[ 'name' ] ], $key, FALSE ) : '';
                        
                        $str .= '<label>'.$option.'<input type="'.$item[ 'type' ].'" name="'.$item[ 'name' ].'" value="'.$key.'" '.$selected.'></label>';
                    }
                }
                
                $str .= '</div>';
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
                
            }
            /**
             * Selects
             */
            if( $item[ 'type' ] === 'select' ){
                
                $str = '<select name="'.$item[ 'name' ].'" placeholder="'.$item[ 'placeholder' ].'">';
                $str .= '<option value="0">--</option>';
                
                if( !empty( $item[ 'options' ] ) ){
                    foreach( $item[ 'options' ] as $key => $option ){
                        
                        $selected = isset( $keywords[ $item[ 'name' ] ] ) ? selected( $keywords[ $item[ 'name' ] ], $key, FALSE ) : '';
                        
                        $str .= '<option value="'.$key.'" '.$selected.'>'.$option.'</option>';
                    }
                }
                
                $str .= '</select>';
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
                
            }
            /**
             * Select2 is with optgroups
             */
            if( $item[ 'type' ] === 'select2' ){
                
                $str = '<select name="'.$item[ 'name' ].'" placeholder="'.$item[ 'placeholder' ].'">';
                $str .= '<option value="0">--</option>';
                
                if( !empty( $item[ 'options' ] ) ){
                    foreach( $item[ 'options' ] as $key => $option ){
                        /**
                         * group post type by optgroup
                         */
                        $str .= '<optgroup label="'.$option[ 'label' ].'">';
                        /**
                         * Insert posts groupsed by post type
                         */
                        foreach( $option[ 'posts' ] as $value ){
                            
                            $selected = isset( $keywords[ $item[ 'name' ] ] ) ? selected( $keywords[ $item[ 'name' ] ], $value[ 'id' ], FALSE ) : '';
                            
                            $str .= '<option value="'.$value[ 'id' ].'" '.$selected.'>'.$value[ 'name' ].'</option>';
                        }
                        
                        $str .= '</optgroup>';
                    }
                }
                
                $str .= '</select>';
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder type-'.$item[ 'type' ].'"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
            }
            
            
            if( $item[ 'type' ] === 'checkbox' ){
                $str = '';
                
                
                foreach( $item[ 'options' ] as $key => $option ){
                    $str .= '<div class="scopeGroup"><label class="scopeLabel"><span>'.$option[ 'label' ].'</span><div>';
                    
                    foreach( $option[ 'posts' ] as $value ){
                        
                        $selected = '';
                        if( !empty( $keywords ) ){
                            if( array_key_exists( $item[ 'name' ], $keywords ) && in_array( $value[ 'id' ], $keywords[ $item[ 'name' ] ] ) ){
                                $selected = 'checked="checked"';
                            }
                        }
                        
                        
                        $str .= '<label class="itemLabel"><span>'.$value[ 'name' ].'</span><input type="'.$item[ 'type' ].'" value="'.esc_attr( $value[ 'id' ] ).'" name="'.$item[ 'name' ].'" '.$selected.'></label>';
                    }
                    //
                    $str .= '</div></label></div>';
                }
                
                
                if( $item[ 'scope' ] === 'config' ){
                    $modalContent .= '<div class="inputHolder"><label class="groupLabel">'.$item[ 'label' ].'</label>'.$str.'</div>';
                }
                elseif( $item[ 'scope' ] === 'table' ){
                    $tableContent .= $str;
                }
            }
            
        }
        $repeaterItems .= '<div class="rowActions">';
        $repeaterItems .= '<button class="modalTrigger '.( $this->state ? '' : 'clicked' ).'" data-id="'.$index.'">Open</button>';
        $repeaterItems .= $this->state ? '<button class="deleteRow" data-repeater-delete type="button" value="'.__( 'Delete', 'osmapper' ).'" />' : '';
        $repeaterItems .= '</div>';
        
        
        $repeaterItems .= '<div class="table">'.$tableContent.'</div>';
        //        $repeaterItems .= '<div class="config '.( $index === 0 ? 'show' : '' ).'">'.$modalContent.'</div>';
        $repeaterItems .= '<div class="config '.( $this->state ? '' : 'show' ).'">'.$modalContent.'</div>';
        
        
        $repeaterItems .= '</div>';
        
        return $repeaterItems;
    }
    
}