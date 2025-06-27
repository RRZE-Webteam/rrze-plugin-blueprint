# Static Block

This is a static block included in the **RRZE Plugin Blueprint** plugin.

## Description

The static block displays predefined content in both the editor and the frontend. It does not use dynamic PHP rendering; all output is handled by the block's JavaScript and `block.json` metadata.

## Structure

- `src/` – Block source code (JS/JSX, SCSS).
- `build/` – Compiled output after running the build process.
- `block.json` – Block metadata.

## Usage

The block will be available in the WordPress block editor under the category defined in `block.json`.

## Customization

Edit files in `src/` to change the block's behavior or styles.
