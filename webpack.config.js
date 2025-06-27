// webpack.config.js
// This configuration extends the default WordPress Scripts Webpack config
// and conditionally adds the CopyPlugin based on the npm lifecycle event.
// It copies block.json and render.php files for the specified blocks.
// The patterns are dynamically built based on the lifecycle event, allowing
// for flexible block development and deployment.
"use strict";

// Import the default WordPress Scripts Webpack configuration
const defaultConfig = require("@wordpress/scripts/config/webpack.config");

// Import the CopyPlugin to handle copying files
const CopyPlugin   = require("copy-webpack-plugin");

// Determine which npm lifecycle event is running
const lifecycle = process.env.npm_lifecycle_event || "";

// Prepare only the patterns needed for this build
const patterns = [];

// If the lifecycle event includes "block-static", add its block.json
if (lifecycle.includes("block-static")) {
  patterns.push({
    from: "blocks/block-static/src/block.json",
    to:   ".", // copies into the output path (e.g., build/blocks/block-static)
  });
}

// If the lifecycle event includes "block-dynamic", add its files
if (lifecycle.includes("block-dynamic")) {
  patterns.push(
    {
      from: "blocks/block-dynamic/src/block.json",
      to:   ".",
    },
    {
      from: "blocks/block-dynamic/src/render.php",
      to:   ".",
    }
  );
}

// Filter out all existing CopyPlugin instances from the default plugins
let plugins = defaultConfig.plugins.filter(
  (p) => p.constructor.name !== "CopyPlugin"
);

// Only if there are patterns, add our CopyPlugin instance
if (patterns.length > 0) {
  plugins.push(new CopyPlugin({ patterns }));
}

module.exports = {
  ...defaultConfig,
  plugins,
};
