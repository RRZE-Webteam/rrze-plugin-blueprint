# Dynamic Block

This is a dynamic block included in the **RRZE Plugin Blueprint** plugin.

## Description

The dynamic block uses a PHP file (`render.php`) to render its content on the frontend, allowing for dynamic output based on WordPress context.

## Structure

- `src/` – Block source code (JS/JSX, SCSS).
- `build/` – Compiled output after running the build process.
- `block.json` – Block metadata.
- `render.php` – PHP file for dynamic rendering.

## Usage

The block will be available in the WordPress block editor and will render dynamically on the frontend.

## Customization

- Modify `render.php` to change the dynamic rendering logic.
- Edit files in `src/` to change the block's behavior or styles.
