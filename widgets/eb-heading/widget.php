<?php
/**
 * EB: Heading widget
 *
 * @package Element_Bits
 */

namespace Element_Bits\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Check if Elementor is loaded
if ( ! did_action( 'elementor/loaded' ) ) {
	return;
}

// Include Elementor classes
use \Elementor\Widget_Base;
use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Text_Shadow;
use \Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use \Elementor\Core\Kits\Documents\Tabs\Global_Typography;

/**
 * EB Heading widget.
 *
 * @since 1.0.0
 */
class EB_Heading extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'eb-heading';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'EB: Heading', 'element-bits' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-t-letter';
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
		return [ 'heading', 'title', 'text', 'eb' ];
	}

	/**
	 * Get style dependencies.
	 *
	 * Retrieve the list of style dependencies the widget requires.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget style dependencies.
	 */
	public function get_style_depends() {
		return [ 'eb-heading' ];
	}

	/**
	 * Get script dependencies.
	 *
	 * Retrieve the list of script dependencies the widget requires.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return array Widget script dependencies.
	 */
	public function get_script_depends() {
		return [ 'eb-heading' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'element-bits' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'element-bits' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Add your heading text here', 'element-bits' ),
				'placeholder' => esc_html__( 'Enter your heading', 'element-bits' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'header_size',
			[
				'label'   => esc_html__( 'HTML Tag', 'element-bits' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span'  => 'span',
					'p'     => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'element-bits' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'element-bits' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center'  => [
						'title' => esc_html__( 'Center', 'element-bits' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'   => [
						'title' => esc_html__( 'Right', 'element-bits' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .eb-heading' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Highlighted Words Section
		$this->start_controls_section(
			'section_highlighted_words',
			[
				'label' => esc_html__( 'Highlighted Words', 'element-bits' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'highlighted_text',
			[
				'label'       => esc_html__( 'Text to Highlight', 'element-bits' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => esc_html__( 'Enter the word or phrase you want to highlight', 'element-bits' ),
			]
		);

		///--------------------------------------
		$repeater->add_control(
			'display_mode',
			[
				'label'   => esc_html__( 'Display Mode', 'element-bits' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline'       => esc_html__( 'Inline with others', 'element-bits' ),
					'block' => esc_html__( 'On its own line', 'element-bits' ),
				],
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'display: {{VALUE}}; width: fit-content;',
				],
			]
		);

		$repeater->start_controls_tabs( 'highlighted_words_style_tabs' );

		$repeater->start_controls_tab(
			'highlighted_words_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'element-bits' ),
			]
		);

		$repeater->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Text Color', 'element-bits' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'highlighted_typography',
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			]
		);

		$repeater->add_control(
			'background_color',
			[
				'label'     => esc_html__( 'Background Color', 'element-bits' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'background-color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'padding',
			[
				'label'      => esc_html__( 'Padding', 'element-bits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->add_control(
			'border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'element-bits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} {{CURRENT_ITEM}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$repeater->end_controls_tab();

		$repeater->start_controls_tab(
			'highlighted_words_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'element-bits' ),
			]
		);

		$repeater->add_control(
			'text_color_hover',
			[
				'label'     => esc_html__( 'Text Color', 'element-bits' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$repeater->add_control(
			'background_color_hover',
			[
				'label'     => esc_html__( 'Background Color', 'element-bits' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}}:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$repeater->end_controls_tab();
		$repeater->end_controls_tabs();

		$this->add_control(
			'highlighted_words',
			[
				'label'       => esc_html__( 'Highlighted Words', 'element-bits' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [],
				'title_field' => 'Highlight: {{{ highlighted_text }}}',
				'separator'   => 'before',
			]
		);

		$this->end_controls_section();

		// Style Tab: Title
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'element-bits' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Text Color', 'element-bits' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .eb-heading' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .eb-heading',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			[
				'name'     => 'text_shadow',
				'selector' => '{{WRAPPER}} .eb-heading',
			]
		);

		$this->add_responsive_control(
			'heading_margin',
			[
				'label'      => esc_html__( 'Margin', 'element-bits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eb-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_padding',
			[
				'label'      => esc_html__( 'Padding', 'element-bits' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .eb-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['title'] ) ) {
			return;
		}

		$this->add_render_attribute( 'title', 'class', 'eb-heading' );

		$title = $settings['title'];

		// Process highlighted words
		if ( ! empty( $settings['highlighted_words'] ) ) {
			foreach ( $settings['highlighted_words'] as $index => $item ) {
				if ( empty( $item['highlighted_text'] ) ) {
					continue;
				}

				$highlighted_text = preg_quote($item['highlighted_text'], '/');
				$new_line_class = isset($item['display_mode']) && 'inline-block' === $item['display_mode'] ? ' eb-highlighted-text-new-line' : '';
				$replacement      = '<span class="elementor-repeater-item-' . esc_attr( $item['_id'] ) . ' eb-highlighted-text' . $new_line_class . '">$0</span>';
				$title            = preg_replace( '/' . $highlighted_text . '/', $replacement, $title );
			}
		}

		// Output the title
		$title_html = sprintf( '<%1$s %2$s>%3$s</%1$s>', 
			tag_escape( $settings['header_size'] ), 
			$this->get_render_attribute_string( 'title' ), 
			$title 
		);

		echo wp_kses_post( $title_html );
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
		?>
		<#
		var title = settings.title;

		if ( '' === title ) { 
			return; 
		} 
		
		view.addRenderAttribute( 'title', 'class', 'eb-heading' );
		
		// Process highlighted words
		if ( settings.highlighted_words && settings.highlighted_words.length > 0 ) { 
			_.each( settings.highlighted_words, function( item ) { 
				if ( ! item.highlighted_text ) { 
					return; 
				} 
				
				var highlightedText = item.highlighted_text;
				var newLineClass = item.display_mode === 'inline-block' ? ' eb-highlighted-text-new-line' : '';
				var replacement = '<span class="elementor-repeater-item-' + item._id + ' eb-highlighted-text' + newLineClass + '">' + highlightedText + '</span>';
				
				// Simple string replacement
				title = title.split(highlightedText).join(replacement);
			});
		}

		var title_html = '<' + settings.header_size + ' ' + view.getRenderAttributeString( 'title' ) + '>' + title + '</' + settings.header_size + '>';
		
		print( title_html );
		#>
		<?php
	}
}
