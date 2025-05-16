<?php
/**
 * EB: Heading widget module
 *
 * @package Element_Bits
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

return [
	'name'            => 'eb-heading',
	'title'           => esc_html__( 'EB: Heading', 'element-bits' ),
	'icon'            => 'eicon-t-letter',
	'categories'      => [ 'element-bits' ],
	'keywords'        => [ 'heading', 'title', 'text', 'eb' ],
	'widget_icon'     => 'eicon-t-letter',
	'view_widget'     => 'widget',
	'script_handles'  => [ 'eb-heading' ],
	'style_handles'   => [ 'eb-heading' ],
];
