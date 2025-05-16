<?php
namespace Element_Bits\Widgets;

defined( 'ABSPATH' ) || exit;

use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;

/**
 * Class Widget_Styled_Maps
 *
 * @package Element_Bits\Widgets
 * @todo Create a leaflet widget
 */
class EB_Google_Map extends Widget_Base {

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eb-google-map';
    }

    /**
     * Get widget title.
     *
     * Retrieve oEmbed widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'EB: Google Map', 'element-bits' );
    }

    /**
     * Keywords
     *
     * @since 1.0.0
     * @access public
     *
     * @return array.
     */
    public function get_keywords() {
        return [ 'novel', 'bits', 'map', 'eb', 'google' ];
    }

    /**
     * Used to set scripts dependencies required to run the widget.
     *
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends() {
        return ['eb-google-map', 'eb-google-maps-lib'];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_map',
            [
                'label' => __( 'Map', 'element-bits' ),
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => __( 'Map Height', 'element-bits' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 40,
                        'max' => 1440,
                    ],
                ],
                'default' => [
                    'size' => 400,
                ],
            ]
        );

        $this->add_control(
            'lat_lng',
            [
                'label' => __( 'Latitude / Longtidute', 'element-bits' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '',
                'default' => '35.1483115, 33.3497305',
                'label_block' => true,
                'description' => 'Latitude / Longtidute seperated by coma (34.847528, 32.435806)',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            'map_zoom',
            [
                'label' => __( 'Zoom Level', 'element-bits' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 15,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
            ]
        );

        $this->add_control(
            'no_scroll',
            [
                'label'     => __( 'Prevent Scrolling', 'element-bits' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => __( 'Yes', 'element-bits' ),
                'label_off' => __( 'No', 'element-bits' ),
                'default'   => 'yes',
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_marker',
            [
                'label' => __( 'Marker', 'element-bits' ),
            ]
        );

        $this->add_control(
            'marker',
            [
                'label' => __( 'Custom Marker', 'element-bits' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => ELEBITS_URL . 'assets/images/map-marker.png'
                ]
            ]
        );

        $this->add_control(
            'info',
            [
                'label'   => __( 'Map Info Window', 'element-bits' ),
                'type'    => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'Content for the infowindow popup when marker is clicked.', 'element-bits' ),
            ]
        );

        $this->end_controls_section();


        $this->start_controls_section(
            'section_style',
            [
                'label' => __( 'Style', 'element-bits' ),
            ]
        );

        $this->add_control(
            'style',
            [
                'label'   => __( 'Map Style', 'element-bits' ),
                'type'    => Controls_Manager::SELECT,
                'default' => '0',
                'options' => apply_filters( 'ep_map_styles', $this->map_styles() ),
            ]
        );

        $this->add_control(
            'custom_style',
            [
                'label' => __( 'Custom Style', 'element-bits' ),
                'type'  => Controls_Manager::TEXTAREA,
                'placeholder' => __( 'You can paste style code from Snazzy maps.', 'element-bits' ),
            ]
        );

        $this->end_controls_section();
    }

    protected function map_styles() {
        return [
            '0'                       => __( 'Default', 'element-bits' ),
            'blue_water'              => __( 'Blue Waters', 'element-bits' ),
            'blue_essence'            => __( 'Blue Essence', 'element-bits' ),
            'multi_brand_network'     => __( 'Multi Brand Network', 'element-bits' ),
            'gold'                    => __( 'Gold', 'element-bits' ),
            'gold_dark'               => __( 'Gold Dark', 'element-bits' ),
            'dropoff3'                => __( 'Dropoff 3', 'element-bits' ),
            'elevation'               => __( 'Elevation', 'element-bits' ),
            'sincity'                 => __( 'Sin City', 'element-bits' ),
            'light_mono'              => __( 'Light Monochrome', 'element-bits' ),
            'apple_green'             => __( 'Apple Green', 'element-bits' ),
            'apple_esque'             => __( 'Apple Esque', 'element-bits' ),
            'windsor_gardens'         => __( 'Windsor Gardens', 'element-bits' ),
            'muscat_maps'             => __( 'Muscat Maps', 'element-bits' ),
            'clr'                     => __( 'CLR', 'element-bits' ),
            'light_lables'            => __( 'Light Labels', 'element-bits' ),
            'digital_media'           => __( 'Digital Media', 'element-bits' ),
            'gray'                    => __( 'Gray', 'element-bits' ),
        ];
    }

    protected function render() {

        $settings = $this->get_settings_for_display();

        $styles = require_once ELEBITS_PATH . 'widgets/eb-google-map/map-styles.php';

        if ( empty( $settings['lat_lng'] ) || strpos( $settings['lat_lng'], ',' ) === false ) {
            echo 'Please enter Latitude / Longtidute seperated by coma (34.847528, 32.435806)';
            return;
        }

        $lat_lng = explode( ',', $settings['lat_lng'] );

        $w_data = [
            'map_id'   => 'eb-gmap-'. esc_attr( $this->get_id() ),
            'icon'   => !empty( $settings['marker']['url'] ) ? esc_url( $settings['marker']['url'] ) : false,
            'lat'      => (float) trim( $lat_lng[0] ),
            'lng'      => (float) trim( $lat_lng[1] ),
            'scroll'   => $settings['no_scroll'] === 'yes' ? false : true,
            'info'     => esc_html( $settings['info'] ),
            'zoom'     => absint( $settings['map_zoom']['size'] )
        ];


        $this->add_render_attribute(
            'eb-wrapper',
            [
                'id' => 'eb-widget-wrapper-' . $this->get_id(),
                'class' => [ 'eb-widget-wrapper' ],
                'data-elebits' => json_encode( $w_data ),
                'data-eb-map-style' => $settings['style'] != '0' ? esc_attr( $styles[$settings['style']] ) : false,
            ]
        );

        $this->add_render_attribute(
            'map',
            [
                'id' => 'eb-gmap-' . $this->get_id(),
                'class' => [ 'eb-map-canvas' ],
                'style' => "background-color:#E6F1F2; width:100%; height:" . esc_attr( $settings['height']['size'] ) . "px;",
            ]
        );
        ?>
        <div <?php echo $this->get_render_attribute_string( 'eb-wrapper' ); ?> >
            <div <?php echo $this->get_render_attribute_string( 'map' ); ?> ></div>
        </div>
        <?php
    }
}
