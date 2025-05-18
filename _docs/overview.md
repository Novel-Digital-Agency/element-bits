# Element Bits – Project Overview

A high-level view of the *Element Bits* WordPress plugin code-base. Use this document as the single starting point when onboarding or making changes.

## Purpose
Element Bits adds a curated set of custom widgets to the Elementor page-builder while keeping the footprint small and the codebase easy to maintain.

## Directory layout
```text
element-bits/
├── assets/               # Static assets used by the plugin
│   ├── css/
│   │   └── admin.css     # Admin-only styles for the settings screen
│   └── images/
│       └── map-marker.png
├── inc/                  # Re-usable PHP helpers & admin classes
│   ├── class-settings.php
│   └── functions.php
├── widgets/              # Each widget lives in its own sub-folder
│   ├── eb-google-map/
│   │   ├── map-styles.php
│   │   ├── widget.js
│   │   └── widget.php
│   ├── eb-swiper-arrow/
│   │   ├── widget.css
│   │   ├── widget.js
│   │   └── widget.php
│   └── eb-heading/
│       └── widget.php
├── element-bits.php       # Bootstrap & core plugin class
├── project-rules.md       # Contribution rules (coding style, git, etc.)
└── README.md              # Short public-facing read-me
```

## Core files
### `element-bits.php`
The plugin bootstrap. It:
* Defines path/version constants.
* Boots the `Element_Bits` singleton.
* Registers activation/deactivation hooks.
* Loads dependencies (`inc/` & widgets) and wires Elementor hooks.

### `inc/class-settings.php`
Implements a WordPress admin page ( *Element Bits → Settings* ) where users can:
* Toggle individual widgets on/off.
* Provide a Google Maps API key (used by **EB Google Map**).
All data is persisted with `register_setting()` and sanitised.

### `inc/functions.php`
Central registry returned by `elebits_get_modules()`. Each entry describes a widget:
```php
'name'  => 'eb-heading',
'title' => 'EB: Heading',
'icon'  => 'eicon-t-letter',
// … script/style URLs & class name
```
When you add a new widget, **add its entry here** and the rest of the system (autoload + settings toggle) will pick it up.

## Widgets
| Folder            | Class                                   | Notes |
|-------------------|-----------------------------------------|-------|
| `eb-heading`      | `\Element_Bits\Widgets\EB_Heading`     | Advanced heading with highlighted words. |
| `eb-google-map`   | `\Element_Bits\Widgets\EB_Google_Map` | Google Maps widget with style presets & lazy JS loader. |
| `eb-swiper-arrow` | `\Element_Bits\Widgets\EB_Swiper_Arrow` | Navigation arrow for Swiper sliders. |

## Settings & asset loading flow
1. Admin toggles widgets in the settings screen (`class-settings.php`).
2. Enabled widget slugs are stored in `element_bits_active_modules` option.
3. On frontend requests `Element_Bits::register_widgets()` loops through **only enabled** modules and:
   * `require`s the widget file.
   * `wp_register_script/style()` for any declared assets.

Google Maps library is only registered when:
* **EB Google Map** is enabled **and**
* a valid API key exists in options.

## Development quick-start
```bash
# Typical local setup
wp plugin activate element-bits
# Make changes → visit a page in Elementor → verify widgets load
```
No build-step is required; all code is plain PHP/JS/CSS.

### Coding standards
* **PHP** – WordPress Coding Standards + PSR-12 where possible.
* **JS/CSS** – WordPress style guide. No build step: keep vanilla, readable code.
* Follow the commit message & coding rules in `project-rules.md`.

## Adding a new widget checklist
1. Create a folder under `widgets/your-widget-slug/`.
2. Add `widget.php` (extend `\Elementor\Widget_Base`).
3. Register optional `widget.js` / `widget.css` and reference them in `elebits_get_modules()`.
4. Update screenshots & README as needed.
5. Commit with `feat: add your widget name`.

---
Happy hacking :rocket:
