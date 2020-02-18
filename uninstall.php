<?php

if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

// Delete Options
$recipes_options = 'recipes_data';
delete_option($recipes_options);
