<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;

$flash = $settings->flash->has();
$errors = $settings->errors->hasErrors();
?>
<div class="wrap">
    <h1><?php echo $settings->title; ?></h1>

    <?php if ($flash) { ?>
        <div class="notice notice-<?php echo $flash['status']; ?> is-dismissible">
            <p><?php echo $flash['message']; ?></p>
        </div>
    <?php } ?>

    <?php if ($errors) { ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e('Settings issues detected.', 'rrze-plugin-blueprint'); ?></p>
        </div>
    <?php } ?>

    <?php $settings->renderTabMenu(); ?>

    <?php $settings->renderActiveSections(); ?>
</div>