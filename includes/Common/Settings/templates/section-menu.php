<?php

namespace RRZE\PluginBlueprint\Common\Settings;

defined('ABSPATH') || exit;
?>
<?php if ($linkedSections = $settings->getActiveTab()->getSectionLinks()) { ?>
    <ul class="subsubsub" style="display: block; width: 100%; margin-bottom: 15px;">
        <?php foreach ($linkedSections as $section) { ?>
            <li><a href="<?php echo $settings->getUrl(); ?>&tab=<?php echo $section->tab->slug; ?>&section=<?php echo $section->slug; ?>" class="<?php echo $section->slug == $settings->getActiveTab()->getActiveSection()->slug ? 'current' : null; ?>"><?php echo $section->title; ?></a> | </li>
        <?php } ?>
    </ul>
<?php } ?>