# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Element Bits is a WordPress plugin that adds custom widgets to the Elementor page builder. The plugin follows a modular architecture where each widget lives in its own directory with its own PHP, JS, and CSS files.

## Code Architecture

- **Plugin Bootstrap**: `element-bits.php` - Main entry point with path/version constants, Elementor hooks, and core functionality
- **Widget Registry**: `inc/functions.php` - Central registry of widgets via `elebits_get_modules()`
- **Settings System**: `inc/class-settings.php` - Admin page for toggling widgets and configuring API keys
- **Widgets**: Each widget is in its own directory under `widgets/` with:
  - `widget.php` - The main PHP class extending Elementor's Widget_Base
  - `widget.js` - Frontend JavaScript functionality (optional)
  - `widget.css` - Widget-specific styling (optional)

## Available Widgets

1. **EB Heading** (`eb-heading/`) - Advanced heading with highlighted words feature
2. **EB Google Map** (`eb-google-map/`) - Google Maps integration with style presets
3. **EB Swiper Arrow** (`eb-swiper-arrow/`) - Navigation arrow for Swiper sliders
4. **EB Dual List** (`eb-dual-list/`) - Widget for displaying paired list items side by side (in development)

## Development Workflow

### Adding a New Widget

1. Create a directory in `widgets/eb-your-widget/`
2. Create `widget.php` with a class extending `\Elementor\Widget_Base`
3. Add optional `widget.js` and `widget.css` files
4. Register the widget in `inc/functions.php` using the `elebits_get_modules()` array:

```php
'eb-your-widget' => [
    'name'             => 'eb-your-widget',
    'title'            => esc_html__( 'EB: Your Widget', 'element-bits' ),
    'icon'             => 'eicon-some-icon',
    'script_url'       => ELEBITS_URL . 'widgets/eb-your-widget/widget.js', // or false
    'style_url'        => ELEBITS_URL . 'widgets/eb-your-widget/widget.css', // or false
    'hidden'           => false,
    'widget_file_path' => ELEBITS_PATH . 'widgets/eb-your-widget/widget.php',  
    'class_name'       => '\Element_Bits\Widgets\EB_Your_Widget',
],
```

### Widget Structure

Each widget should implement:

1. Basic identification methods:
   - `get_name()` - Returns the widget's unique name
   - `get_title()` - Returns the widget's display title
   - `get_icon()` - Returns the Elementor icon for the widget
   - `get_categories()` - Returns the widget's categories (use 'element-bits')
   - `get_keywords()` - Returns search keywords for the widget
   - `get_style_depends()` - Returns stylesheet dependencies
   - `get_script_depends()` - Returns script dependencies

2. Control registration:
   - `register_controls()` - Defines all Elementor controls for the widget
   - Group controls into sections using `start_controls_section()` and `end_controls_section()`
   - Use appropriate control types (text, select, repeater, etc.)

3. Rendering methods:
   - `render()` - Renders the widget on the frontend
   - `content_template()` - Renders the widget in the Elementor editor

### Elementor Controls Reference

Common controls to use in widgets:
- `Controls_Manager::TEXT` - Basic text input
- `Controls_Manager::TEXTAREA` - Multi-line text input
- `Controls_Manager::SELECT` - Dropdown selection
- `Controls_Manager::REPEATER` - Repeatable field groups
- `Controls_Manager::ICONS` - Icon selector
- `Controls_Manager::URL` - URL input with options
- `Controls_Manager::SLIDER` - Range slider control
- `Controls_Manager::DIMENSIONS` - Dimensions control (padding, margin, etc.)
- `Controls_Manager::COLOR` - Color picker

Group controls:
- `Group_Control_Typography::get_type()` - Typography settings
- `Group_Control_Border::get_type()` - Border settings
- `Group_Control_Box_Shadow::get_type()` - Box shadow settings
- `Group_Control_Text_Shadow::get_type()` - Text shadow settings

## Testing

The plugin uses manual testing through WordPress and Elementor:

1. Make your changes
2. View a page with Elementor editor
3. Test the widget in the editor and in the frontend

## Requirements

- WordPress 5.6+
- PHP 7.4+
- Elementor 3.0.0+
- Elementor Pro

## Dual List Widget Implementation

The EB Dual List widget is in development and should:
1. Display paired list items side by side
2. Use a repeater control for list items
3. Each list item should have left and right elements
4. Each element should include icon, title, description, and link
5. Include controls for styling both sides independently
6. Provide layout options for alignment and spacing

## Coding Standards

- **PHP**: WordPress Coding Standards + PSR-12 where possible
- **JS/CSS**: WordPress style guide, plain vanilla code with no build step required
- Maintain code structure and patterns consistent with existing code
- Use proper escaping and sanitization for WordPress security
- Use namespacing for all widget classes (`namespace Element_Bits\Widgets;`)
- Follow existing patterns for control registration and rendering