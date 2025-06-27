<?php

namespace RRZE\PluginBlueprint\Common\Plugin;

defined('ABSPATH') || exit;

/**
 * Plugin class
 * 
 * This class represents a WordPress plugin and provides methods to retrieve various properties
 * and metadata about the plugin, such as its file path, basename, directory, URL, and other details.
 * It also includes methods for setting and getting plugin data, such as the name, version,
 * required WordPress version, and required PHP version.
 * 
 * @package RRZE\PluginBlueprint
 * @since 1.0.0
 */
class Plugin
{
    /**
     * The full path and filename of the plugin.
     * 
     * @var string
     */
    protected $pluginFile;

    /**
     * The basename of the plugin.
     * 
     * @var string
     */
    protected $basename;

    /**
     * The filesystem directory path (with trailing slash) for the plugin.
     * 
     * @var string
     */
    protected $directory;

    /**
     * The URL directory path (with trailing slash) for the plugin.
     * 
     * @var string
     */
    protected $url;

    /**
     * The data of the plugin.
     * 
     * @var array
     */
    protected $data;

    /**
     * Constructor.
     * 
     * @param string $pluginFile The full path and filename of the plugin.
     * @return void
     */
    public function __construct(string $pluginFile)
    {
        $this->pluginFile = $pluginFile;
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }

    /**
     * This method is called when the plugin is loaded.
     * It sets the basename, directory, URL, and data properties of the plugin.
     * 
     * @return void
     */
    public function loaded()
    {
        $this->setBasename()
            ->setDirectory()
            ->setUrl()
            ->setData();
    }

    /**
     * Get the full path and filename of the plugin.
     * 
     * @return string The full path and filename.
     */
    public function getFile(): string
    {
        return $this->pluginFile;
    }

    /**
     * Get the basename of the plugin.
     * 
     * @return string The basename.
     */
    public function getBasename(): string
    {
        return $this->basename;
    }

    /**
     * Set the basename of the plugin.
     * 
     * @return object This Plugin object.
     */
    public function setBasename(): object
    {
        $this->basename = plugin_basename($this->pluginFile);
        return $this;
    }

    /**
     * Get the filesystem directory path (with trailing slash) for the plugin.
     * 
     * @return string The filesystem directory path.
     */
    public function getDirectory(): string
    {
        return $this->directory;
    }

    /**
     * Set the filesystem directory path (with trailing slash) for the plugin.
     * @return object This Plugin object.
     */
    public function setDirectory(): object
    {
        $this->directory = rtrim(plugin_dir_path($this->pluginFile), '/') . '/';
        return $this;
    }

    /**
     * Get the filesystem directory path (with trailing slash) for the plugin.
     * 
     * @param string $path The path name.
     * @return string The filesystem directory path.
     */
    public function getPath(string $path = ''): string
    {
        return $this->directory . ($path ? trim($path, '/') . '/' : '');
    }

    /**
     * Get the URL directory path (with trailing slash) for the plugin.
     * 
     * @param string $path The path name.
     * @return string The URL directory path.
     */
    public function getUrl(string $path = ''): string
    {
        return $this->url . ($path ? trim($path, '/') . '/' : '');
    }

    /**
     * Set the URL directory path (with trailing slash) for the plugin.
     * 
     * @return object This Plugin object.
     */
    public function setUrl(): object
    {
        $this->url = rtrim(plugin_dir_url($this->pluginFile), '/') . '/';
        return $this;
    }

    /**
     * Get the slug of the plugin.
     * 
     * @return string The slug.
     */
    public function getSlug(): string
    {
        return sanitize_key(dirname($this->basename));
    }

    /**
     * Set the data of the plugin.
     * 
     * @return object This Plugin object.
     */
    public function setData(): object
    {
        $this->data = get_plugin_data($this->pluginFile, false);
        return $this;
    }

    /**
     * Get the data of the plugin.
     * 
     * @return array The data.
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the name of the plugin.
     * 
     * @return string The name.
     */
    public function getName(): string
    {
        return $this->data['Name'];
    }

    /**
     * Get the version of the plugin.
     * 
     * @return string The version.
     */
    public function getVersion(): string
    {
        return $this->data['Version'];
    }

    /**
     * Get the required WordPress version of the plugin.
     * 
     * @return string The required WordPress version.
     */
    public function getRequiresWP(): string
    {
        return $this->data['RequiresWP'];
    }

    /**
     * Get the required PHP version of the plugin.
     * 
     * @return string The required PHP version.
     */
    public function getRequiresPHP(): string
    {
        return $this->data['RequiresPHP'];
    }

    /**
     * This method is called when an undefined method is called on the object.
     * It checks if the method exists in the class and throws an exception if it does not.
     * This is useful for debugging and ensuring that only defined methods are called.
     * 
     * @param string $name The name of the method being called.
     * @param array $arguments The arguments passed to the method.
     * @throws \Exception If the method does not exist.
     * 
     * @return void
     */
    public function __call(string $name, array $arguments)
    {
        if (!method_exists($this, $name)) {
            $message = sprintf('Call to undefined method %1$s::%2$s', __CLASS__, $name);
            if (defined('WP_DEBUG') && WP_DEBUG) {
                throw new \Exception($message);
            }
        }
    }
}
