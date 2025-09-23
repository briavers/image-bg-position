<?php

/*
Plugin Name: Image Bg Position
Description: A plugin to set a focal point on images that can be used in custom templates.
Version: 1.1.0
Author: brianverschoore
License: MIT
*/


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Require plugin
 */
require_once 'includes/WPIBP_Plugin.php';

/**
 * Create plugin
 */
$plugin_file_name = plugin_basename(__FILE__);
WPIBP_Plugin::create($plugin_file_name);
