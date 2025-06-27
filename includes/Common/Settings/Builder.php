<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

/**
 * Builder class for managing settings options.
 *
 * This class provides methods to add, remove, and enqueue settings options.
 * It is used to build the settings structure for the plugin.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Builder
{
    /**
     * Array to hold enqueued settings options.
     *
     * This property stores the settings options that have been added
     * and are ready to be processed or displayed.
     *
     * @var array
     */
    public array $enqueued = [];

    /**
     * Adds a settings option to the builder.
     *
     * This method allows you to add a settings option by providing a handle
     * and a callback function that defines the option's behavior.
     *
     * @param string $handle The unique identifier for the settings option.
     * @param callable $callback The callback function that defines the option.
     * @return void
     */
    public function add($handle, $callback)
    {
        $this->enqueued[$handle] = $callback;
    }

    /**
     * Removes a settings option from the builder.
     *
     * This method allows you to remove a settings option by its handle.
     *
     * @param string $handle The unique identifier for the settings option to be removed.
     * @return void
     */
    public function remove($handle)
    {
        unset($this->enqueued[$handle]);
    }

    /**
     * Enqueues all added settings options.
     *
     * This method iterates through the enqueued settings options and executes
     * their associated callback functions, effectively processing or displaying
     * the settings options as needed.
     * 
     * @return void
     */
    public function enqueue()
    {
        foreach ($this->enqueued as $enqueue) {
            $enqueue();
        }
    }
}
