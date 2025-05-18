<?php
namespace Element_Bits\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * EB Swiper Arrow Widget
 * 
 * Custom Elementor widget that adds customizable navigation arrow for Swiper sliders.
 */
class EB_Swiper_Arrow extends Widget_Base {

    /**
     * Get widget name.
     *
     * @return string Widget name.
     */
    public function get_name() {
        return 'eb-swiper-arrow';
    }

    /**
     * Get widget title.
     *
     * @return string Widget title.
     */
    public function get_title() {
        return esc_html__( 'EB: Swiper Arrow', 'element-bits' );
    }
    
    /**
     * Get script dependencies.
     *
     * @return array Script dependencies.
     */
    public function get_script_depends() {
        return ['eb-swiper-arrow'];
    }

    /**
     * Get widget icon.
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'eicon-arrow-circle';
    }

    /**
     * Get widget categories.
     *
     * @return array Widget categories.
     */
    public function get_categories() {
        return [ 'element-bits' ];
    }

    /**
     * Get widget keywords.
     *
     * @return array Widget keywords.
     */
    public function get_keywords() {
        return [ 'arrow', 'navigation', 'swiper', 'slider', 'carousel' ];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {
        $this->start_controls_section(
            'section_content',
            [
                'label' => esc_html__( 'Arrow Settings', 'element-bits' ),
            ]
        );

        // Add a note about the widget's purpose
        $this->add_control(
            'widget_note',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => sprintf(
                    '%s <a href="%s" target="_blank">%s</a>',
                    esc_html__('This widget adds a single navigation arrow for Swiper sliders. It will automatically connect to the nearest Swiper slider on the page or use the specified Swiper container ID.', 'element-bits'),
                    'https://swiperjs.com/',
                    esc_html__('Learn more about Swiper', 'element-bits')
                ),
                'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
            ]
        );

        // Add Swiper Container ID control
        $this->add_control(
            'swiper_container_id',
            [
                'label' => esc_html__('Swiper Container ID', 'element-bits'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => 'my-swiper-container',
                'description' => esc_html__('Enter the ID of the Swiper container (without #). Leave empty to auto-detect the nearest Swiper container.', 'element-bits'),
                'label_block' => true,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        // Arrow Direction
        $this->add_control(
            'arrow_direction',
            [
                'label' => esc_html__( 'Arrow Direction', 'element-bits' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'prev' => [
                        'title' => esc_html__( 'Previous', 'element-bits' ),
                        'icon' => 'eicon-arrow-left',
                    ],
                    'next' => [
                        'title' => esc_html__( 'Next', 'element-bits' ),
                        'icon' => 'eicon-arrow-right',
                    ],
                ],
                'default' => 'next',
                'toggle' => false,
                'frontend_available' => true,
            ]
        );

        // Arrow Type
        $this->add_control(
            'arrow_type',
            [
                'label' => esc_html__( 'Arrow Type', 'element-bits' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'arrow',
                'options' => [
                    'arrow' => esc_html__( 'Arrow', 'element-bits' ),
                    'arrow2' => esc_html__( 'Arrow 2', 'element-bits' ),
                    'chevron' => esc_html__( 'Chevron', 'element-bits' ),
                    'circle-arrow' => esc_html__( 'Circle Arrow', 'element-bits' ),
                    'custom' => esc_html__( 'Custom Image', 'element-bits' ),
                ],
                'frontend_available' => true,
            ]
        );

        // Arrow Custom Image
        $this->add_control(
            'arrow_image',
            [
                'label' => esc_html__( 'Choose Arrow Image', 'element-bits' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'arrow_type' => 'custom',
                ],
            ]
        );
        
        // Custom Media Upload (Overrides default icons)
        $this->add_control(
            'custom_media_heading',
            [
                'label' => esc_html__( 'Custom Media', 'element-bits' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        
        $this->add_control(
            'use_custom_media',
            [
                'label' => esc_html__( 'Override Default Icons', 'element-bits' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'element-bits' ),
                'label_off' => esc_html__( 'No', 'element-bits' ),
                'return_value' => 'yes',
                'default' => '',
            ]
        );
        
        $this->add_control(
            'custom_media',
            [
                'label' => esc_html__( 'Custom Arrow Media', 'element-bits' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
                'description' => esc_html__( 'Upload SVG or image to override the default arrow icon.', 'element-bits' ),
                'condition' => [
                    'use_custom_media' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section - Arrow
        $this->start_controls_section(
            'section_style_arrow',
            [
                'label' => esc_html__( 'Arrow Style', 'element-bits' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        // Size
        $this->add_responsive_control(
            'arrow_size',
            [
                'label' => esc_html__( 'Size', 'element-bits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'em', 'rem' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 24,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eb-swiper-arrow svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .eb-swiper-arrow img' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
                ],
            ]
        );

        // Normal State
        $this->start_controls_tabs( 'arrow_styles' );

        $this->start_controls_tab(
            'arrow_normal',
            [
                'label' => esc_html__( 'Normal', 'element-bits' ),
            ]
        );

        $this->add_control(
            'arrow_color',
            [
                'label' => esc_html__( 'Color', 'element-bits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eb-swiper-arrow svg' => 'fill: {{VALUE}};',
                ],
                'default' => 'var(--e-global-color-primary, #6EC1E4)',
            ]
        );

        $this->add_control(
            'arrow_bg_color',
            [
                'label' => esc_html__( 'Background Color', 'element-bits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow' => 'background-color: {{VALUE}};',
                ],
                'default' => 'transparent',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrow_border',
                'selector' => '{{WRAPPER}} .eb-swiper-arrow',
            ]
        );

        $this->add_control(
            'arrow_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-bits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrow_box_shadow',
                'selector' => '{{WRAPPER}} .eb-swiper-arrow',
            ]
        );

        $this->add_responsive_control(
            'arrow_padding',
            [
                'label' => esc_html__( 'Padding', 'element-bits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

        // Hover State
        $this->start_controls_tab(
            'arrow_hover',
            [
                'label' => esc_html__( 'Hover', 'element-bits' ),
            ]
        );

        $this->add_control(
            'arrow_hover_color',
            [
                'label' => esc_html__( 'Color', 'element-bits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow:hover' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .eb-swiper-arrow:hover svg' => 'fill: {{VALUE}};',
                ],
                'default' => 'var(--e-global-color-primary, #6EC1E4)',
            ]
        );

        $this->add_control(
            'arrow_bg_hover_color',
            [
                'label' => esc_html__( 'Background Color', 'element-bits' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow:hover' => 'background-color: {{VALUE}};',
                ],
                'default' => 'transparent',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'arrow_hover_border',
                'selector' => '{{WRAPPER}} .eb-swiper-arrow:hover',
            ]
        );

        $this->add_control(
            'arrow_hover_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'element-bits' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'arrow_hover_box_shadow',
                'selector' => '{{WRAPPER}} .eb-swiper-arrow:hover',
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        
        // Add hover animation control inside the section
        $this->add_control(
            'hover_animation',
            [
                'label' => esc_html__( 'Hover Animation', 'element-bits' ),
                'type' => Controls_Manager::HOVER_ANIMATION,
                'separator' => 'before',
            ]
        );
        
        // Arrow Rotation
        $this->add_control(
            'arrow_rotation',
            [
                'label' => esc_html__( 'Rotation', 'element-bits' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'deg' ],
                'range' => [
                    'deg' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 5,
                    ],
                ],
                'default' => [
                    'unit' => 'deg',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .eb-swiper-arrow' => 'transform: rotate({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .eb-swiper-arrow-svg' => 'transform: rotate({{SIZE}}{{UNIT}});',
                    '{{WRAPPER}} .eb-swiper-arrow img' => 'transform: rotate({{SIZE}}{{UNIT}});',
                ],
            ]
        );
        
        $this->end_controls_section();


    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $arrow_type = $settings['arrow_type'];
        $arrow_direction = $settings['arrow_direction'];
        $use_custom_media = isset($settings['use_custom_media']) && $settings['use_custom_media'] === 'yes';
        
        // Get the image URL for custom arrow
        $arrow_url = '';
        $custom_media_url = '';
        
        // Check for custom media override
        if ($use_custom_media && !empty($settings['custom_media']['url'])) {
            $custom_media_url = $settings['custom_media']['url'];
        }
        
        // If no custom media, check for custom arrow type
        if (empty($custom_media_url) && $arrow_type === 'custom' && !empty($settings['arrow_image']['url'])) {
            $arrow_url = $settings['arrow_image']['url'];
        }
        
        // Get hover animation class
        $hover_animation = !empty($settings['hover_animation']) ? ' elementor-animation-' . $settings['hover_animation'] : '';
        $swiper_container_id = !empty($settings['swiper_container_id']) ? $settings['swiper_container_id'] : '';
        
        // Set aria label and CSS class based on direction
        $aria_label = $arrow_direction === 'prev' ? esc_attr__('Previous', 'element-bits') : esc_attr__('Next', 'element-bits');
        $arrow_class = 'eb-swiper-arrow eb-swiper-' . $arrow_direction . $hover_animation;
        
        // Determine if we're using SVG or image
        $is_svg = false;
        $media_url = $custom_media_url ?: $arrow_url;
        
        if (!empty($media_url)) {
            $file_ext = pathinfo($media_url, PATHINFO_EXTENSION);
            $is_svg = strtolower($file_ext) === 'svg';
        }
        ?>
        <style>
            .eb-swiper-arrow-wrapper {
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            .eb-swiper-arrow {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                background: none;
                border: none;
                padding: 0;
                margin: 0;
                transition: all 0.3s ease;
                line-height: 1;
                color: #333;
            }
            
            .eb-swiper-arrow-svg {
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 0.3s ease;
            }
            
            .eb-swiper-arrow img {
                display: block;
                max-width: 100%;
                height: auto;
                transition: all 0.3s ease;
            }
            
            .eb-swiper-arrow svg {
                width: 100%;
                height: 100%;
            }
        </style>
        
        <div 
            class="eb-swiper-arrow-wrapper" 
            role="navigation" 
            data-swiper-container="<?php echo esc_attr($swiper_container_id); ?>"
        >
            <button 
                data-elebits-swiper-arrow='<?php echo json_encode([
                    'direction' => $arrow_direction,
                    'swiper_id' => $swiper_container_id,
                ]); ?>'
                class="<?php echo esc_attr($arrow_class); ?>" 
                aria-label="<?php echo $aria_label; ?>"
            >
                <?php if (!empty($custom_media_url)) : ?>
                    <?php if ($is_svg && function_exists('wp_get_attachment_image')) : ?>
                        <?php 
                        // Get attachment ID from URL
                        $attachment_id = attachment_url_to_postid($custom_media_url);
                        if ($attachment_id) :
                            // Use WordPress function to get SVG content safely
                            echo wp_get_attachment_image($attachment_id, 'full', false, array('class' => 'eb-svg-icon', 'alt' => $aria_label));
                        else: 
                        ?>
                            <img src="<?php echo esc_url($custom_media_url); ?>" alt="<?php echo $aria_label; ?>">
                        <?php endif; ?>
                    <?php else : ?>
                        <img src="<?php echo esc_url($custom_media_url); ?>" alt="<?php echo $aria_label; ?>">
                    <?php endif; ?>
                <?php elseif ($arrow_type === 'custom' && $arrow_url) : ?>
                    <img src="<?php echo esc_url($arrow_url); ?>" alt="<?php echo $aria_label; ?>">
                <?php else : ?>
                    <span class="eb-swiper-arrow-svg">
                        <?php echo $this->get_arrow_svg($arrow_type, $arrow_direction); ?>
                    </span>
                <?php endif; ?>
            </button>
        </div>
        <?php
    }
    
    /**
     * Get SVG markup for an arrow
     * 
     * @param string $type Arrow type (e.g., 'arrow', 'chevron', etc.)
     * @param string $direction Direction (prev/next)
     * @return string SVG markup
     */
    private function get_arrow_svg($type, $direction = 'next') {
        // Add the direction to the type
        $type_with_direction = $type . '-' . ($direction === 'prev' ? 'left' : 'right');
        
        // Get the SVG based on type
        $svg = $this->get_svg_by_type($type_with_direction);
        
        // If no SVG found, use fallback
        if (empty($svg)) {
            $svg = $this->get_fallback_arrow_svg($direction);
        }
        
        return $svg;
    }
    
    /**
     * Get SVG markup by type
     * 
     * @param string $type The type of SVG to get
     * @return string SVG markup or empty string if not found
     */
    private function get_svg_by_type($type) {
        $svgs = [
            // Arrow styles
            'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>',
            'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>',
            
            // Arrow 2 styles (thicker)
            'arrow2-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>',
            'arrow2-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>',
            
            // Chevron styles
            'chevron-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12l4.58-4.59z"/></svg>',
            'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>',
            
            // Circle arrow styles
            'circle-arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13l-4 4 4 4 1.41-1.41L10.83 12l2.58-2.59L11 7z"/></svg>',
            'circle-arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-8.59L10.83 12l-2.58 2.59L11 17l4-4 4 4 1.41-1.42L11.41 12z"/></svg>',
            
            // Double arrow styles
            'double-arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M11.41 12l4.29-4.29L14.71 6.3 8.41 12l6.29 5.7 1.41-1.42L11.41 12z"/><path d="M17.41 12l4.29-4.29L21.71 6.3 15.41 12l6.29 5.7 1.41-1.42L17.41 12z"/></svg>',
            'double-arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.59 12l-4.29-4.29L9.71 6.3 16 12l-6.29 5.7-1.41-1.42L12.59 12z"/><path d="M18.59 12l-4.29-4.29L15.71 6.3 22 12l-6.29 5.7-1.41-1.42L18.59 12z"/></svg>',
        ];
        
        return $svgs[$type] ?? '';
    }
    
    /**
     * Get a fallback SVG arrow
     * 
     * @param string $direction Direction (prev/next)
     * @return string SVG markup
     */
    private function get_fallback_arrow_svg($direction = 'next') {
        if ($direction === 'prev') {
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>';
        } else {
            return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>';
        }
    }
}

// Register the widget
//add_action( 'elementor/widgets/register', function( $widgets_manager ) {
//    $widgets_manager->register( new \Element_Bits\Widgets\EB_Swiper_Arrow() );
//} );
