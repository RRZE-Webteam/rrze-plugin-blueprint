#!/usr/bin/env node
// scripts/build-plugin.js
// This script updates the version in package.json from the plugin header
// and generates a readme.txt file from the plugin header comments.

'use strict';

// Dependencies
const fs   = require('fs');
const path = require('path');

// Paths
const pkgPath    = path.resolve(__dirname, 'package.json');
const pkg        = JSON.parse(fs.readFileSync(pkgPath, 'utf8'));
const pluginFile = path.resolve(__dirname, `${pkg.name}.php`);
const outFile    = path.resolve(__dirname, 'readme.txt');

let pluginContent;
try {
  pluginContent = fs.readFileSync(pluginFile, 'utf8');
} catch (err) {
  console.error(`Unable to read plugin file at ${pluginFile}`);
  process.exit(1);
}

// 1) Sync version from plugin header to package.json
const versionMatch = pluginContent.match(/^[ \t\/*]*Version:\s*(\d+\.\d+\.\d+)/m);
if (!versionMatch) {
  console.error('"Version:" header not found in plugin file.');
  process.exit(1);
}
const pluginVersion = versionMatch[1].trim();

if (pkg.version === pluginVersion) {
  console.log(`package.json is already at version ${pkg.version}`);
} else {
  pkg.version = pluginVersion;
  try {
    fs.writeFileSync(pkgPath, JSON.stringify(pkg, null, 2) + '\n', 'utf8');
    console.log(`Updated package.json to version ${pluginVersion}`);
  } catch (err) {
    console.error('Error writing package.json:', err);
    process.exit(1);
  }
}

// 2) Generate readme.txt from plugin header comments
const headerMatch = pluginContent.match(/\/\*[\s\S]*?\*\//);
if (!headerMatch) {
  console.error('Plugin header comment block (/* ... */) not found in', pluginFile);
  process.exit(1);
}

const lines = headerMatch[0]
  .split('\n')
  .map(line => line.replace(/^\s*\/?\*+\s?/, '').trim())
  .filter(line => line && /^[A-Za-z ]+?:/.test(line));

try {
  fs.writeFileSync(outFile, lines.join('\n') + '\n', 'utf8');
  console.log(`Generated ${outFile}`);
} catch (err) {
  console.error('Failed to write readme.txt:', err);
  process.exit(1);
}
