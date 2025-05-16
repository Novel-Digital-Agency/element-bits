<?php

function elebits_get_modules() {
    return [
       'eb-heading' => [
            'name'             => 'eb-heading',
            'title'            => esc_html__( 'EB: Heading', 'element-bits' ),
            'icon'             => 'eicon-t-letter',
            'script_url'       => false,
            'style_url'        => false,
            'hidden'           => false,
            'widget_file_path' => ELEBITS_PATH . 'widgets/eb-heading/widget.php',  
            'class_name'       => '\Element_Bits\Widgets\EB_Heading',
        ],

        'eb-google-map' => [
            'name'             => 'eb-google-map',
            'title'            => esc_html__( 'EB: Google Map', 'element-bits' ),
            'icon'             => 'eicon-t-letter',
            'script_url'       => ELEBITS_URL . 'widgets/eb-google-map/widget.js',
            'style_url'        => false,
            'hidden'           => false,
            'widget_file_path' => ELEBITS_PATH . 'widgets/eb-google-map/widget.php',  
            'class_name'       => '\Element_Bits\Widgets\EB_Google_Map',
        ]    
    ];
}