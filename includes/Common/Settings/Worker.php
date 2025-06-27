<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

/**
 * Worker class for managing settings builders.
 *
 * This class provides a static interface to manage settings builders,
 * allowing for the addition, removal, and enqueuing of settings.
 *
 * @package RRZE\PluginBlueprint\Common\Settings
 */
class Worker
{
    /**
     * The builder instance used for managing settings.
     *
     * This static property holds the builder instance that is used to
     * add, remove, and enqueue settings.
     *
     * @var Builder
     */
    protected static Builder $builder;

    /**
     * Initializes the Worker with a Builder instance.
     *
     * This method sets the static builder property to the provided Builder instance.
     *
     * @param Builder $builder The Builder instance to be used.
     * @return void
     */
    public static function setBuilder($builder)
    {
        static::$builder = $builder;
    }

    /**
     * Retrieves the current Builder instance.
     *
     * This static method returns the Builder instance that is currently set.
     *
     * @return Builder The current Builder instance.
     */
    public static function builder()
    {
        return static::$builder;
    }

    /**
     * Adds a settings option to the builder.
     *
     * This static method allows you to add a settings option by providing a handle
     * and a callback function that defines the option's behavior.
     *
     * @param string $handle The unique identifier for the settings option.
     * @param callable $callback The callback function that defines the option.
     * @return void
     */
    public static function add($handle, $callback)
    {
        static::builder()->add($handle, $callback);
    }

    /**
     * Removes a settings option from the builder.
     *
     * This static method allows you to remove a settings option by its handle.
     *
     * @param string $handle The unique identifier for the settings option to be removed.
     * @return void
     */
    public function remove($handle)
    {
        static::builder()->remove($handle);
    }

    /**
     * Enqueues all added settings options.
     *
     * This static method iterates through the enqueued settings options and executes
     * their associated callback functions, effectively processing or displaying
     * the settings options as needed.
     *
     * @return void
     */
    public static function enqueue()
    {
        static::builder()->enqueue();
    }
}
