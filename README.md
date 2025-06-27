# RRZE Plugin Blueprint

**RRZE Plugin Blueprint** is a modern, modular starter framework for building custom WordPress plugins with best practices in mind. It provides a robust foundation with PSR-4 autoloading, advanced options and settings management, block support, internationalization (i18n) for both PHP and JavaScript, and system requirements checks—so you can focus on your plugin’s unique features.

---

## Key Features

- **PSR-4 Autoloading**  
  All PHP classes under the `RRZE\PluginBlueprint` namespace are autoloaded from the `includes/` directory.

- **Activation & Deactivation Hooks**  
  Easily add setup or cleanup routines using the provided `activation()` and `deactivation()` methods.

- **System Requirements Check**  
  The plugin checks for minimum WordPress and PHP versions before initializing, displaying admin notices if requirements are not met.

- **Singleton Initialization**  
  Access the main plugin instance anywhere via the `plugin()` and `main()` helper functions.

- **Centralized Defaults**  
  The `Defaults` class manages all plugin default values, which can be filtered and reused throughout your code.

- **Advanced Settings API Integration**  
  The `Settings` class lets you easily create settings pages, sections, and fields with validation and sanitization.  
  👉 [See the Settings class README](includes/Common/Settings/README.md)

- **Custom Post Types & Taxonomies**  
  Generic `CPT` and `Taxonomy` classes make it easy to register custom post types and taxonomies.

- **Shortcode Registration**  
  A generic `Shortcode` class allows for quick and clean shortcode registration.

- **Gutenberg Block Support**  
  Example: Includes a structure for both static and dynamic blocks, each with their own build process and assets.  
  👉 [Static Block README](blocks/block-static/README.md)  
  👉 [Dynamic Block README](blocks/block-dynamic/README.md)

- **Internationalization (i18n) for PHP and JS**  
  All user-facing strings are translatable. The build process and scripts support extracting and generating `.pot`, `.po`, `.mo`, and `.json` files for both PHP and JS translations.

---

## Directory Structure

```
rrze-plugin-blueprint/
│
├── blocks/
│   ├── block-dynamic/
│   │   ├── README.md
│   │   └── src/
│   └── block-static/
│       ├── README.md
│       └── src/
├── build/
│   └── blocks/
│       ├── block-dynamic/
│       └── block-static/
├── includes/
│   ├── Defaults.php
│   ├── Main.php
│   └── Common/
│       ├── Blocks/
│       ├── CPT/
│       ├── Plugin/
│       ├── Settings/
│       └── Shortcode/
├── languages/
│   └── rrze-plugin-blueprint.pot
├── package.json
├── rrze-plugin-blueprint.php
├── README.md
├── readme.txt
├── build-plugin.js
├── webpack.config.js
```

---

## Development Workflow

1. **Install dependencies:**
   ```sh
   npm install
   ```

2. **Build all blocks:**
   ```sh
   npm run build
   ```
   Each block will have its own build directory with compiled assets and static files.

3. **Internationalization:**
   - Extract PHP and JS strings:
     ```sh
     wp i18n make-pot . languages/rrze-plugin-blueprint.pot --domain=rrze-plugin-blueprint --exclude=node_modules,vendor,build
     ```
   - Generate JS translation JSON files:
     ```sh
     wp i18n make-json languages/rrze-plugin-blueprint-LOCALE.po --no-purge
     ```

---

## Customization

- **Register a new block:**  
  Add a folder under `blocks/`, create a `src/` and `block.json`, and add a build script in `package.json`.

- **Override default values:**  
  Edit `Defaults.php` or use the `rrze_plugin_blueprint_defaults` filter.

- **Change the PHP namespace:**  
  You can update the PHP namespace throughout the plugin by running:
  ```sh
  npm run update:namespace
  ```
  This will replace the namespace in all PHP files (except in the `build/` directory).

- **Change the text domain**
  You can update the text domain throughout the plugin by running:
  ```sh
  npm run update:textdomain
  ```
  This will replace the textdomain in all PHP and JS files (except in the `build/` directory).

- **Change the plugin slug**
  You can update the plugin slug throughout the plugin by running:
  ```sh
  npm run update:slug
  ```
  This will replace the plugin slug in all PHP and JS files (except in the `build/` directory).

  Note: Don’t forget to change the plugin directory and file names accordingly.

---

## Internationalization

- All strings use the `rrze-plugin-blueprint` text domain.
- Translation files (`.pot`, `.po`, `.mo`, `.json`) are in the `languages/` directory.
- JS translations are loaded using `wp_set_script_translations()`.

---

Clone, customize, and start building professional WordPress plugins with RRZE Plugin Blueprint!