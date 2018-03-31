<?php
/*
Plugin Name: itmits - html to wp
Plugin URI: http://www.imthemanintheshower.com/itmits-html-to-wp
Description: html to wp
Version: 0.1
Author: imthemanintheshower
Author URI: http://www.imthemanintheshower.com
License: MIT - https://opensource.org/licenses/mit-license.php
*/
/*
Copyright 2017 https://github.com/iamthemanintheshower - imthemanintheshower@gmail.com

Permission is hereby granted, free of charge, to any person obtaining a copy of 
this software and associated documentation files (the "Software"), to deal in 
the Software without restriction, including without limitation the rights to use, 
copy, modify, merge, publish, distribute, sublicense, and/or sell copies 
of the Software, and to permit persons to whom the Software is furnished 
to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in 
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
PARTICULAR PURPOSE AND NONINFRINGEMENT. 
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, 
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
*/

global $_theme_folder;
global $theme_name;
global $_get_fields;
global $plugin_folder_path;
global $plugin_dir_url;

$plugin_dir_url = plugin_dir_url( __FILE__ );
$plugin_folder_path = plugin_dir_path( __FILE__ );
$themes_folder = '-themes-oisdhhwd';
$theme_name = 'html_theme';
$_theme_folder = $plugin_folder_path.'admin-uihsdw/'.$themes_folder.'/'.$theme_name;

include($plugin_folder_path . '_include-sihdw/-get_fields.php');
include($plugin_folder_path . 'admin-uihsdw/_include-asdwe/-functions-import-panel.php'); //# Admin panel
include($plugin_folder_path . 'admin-uihsdw/_include-asdwe/-functions-import.php'); //# Parser and Create template button
include($plugin_folder_path . 'admin-uihsdw/_include-asdwe/-functions-customizer.php'); //# Customizer

//# admin panel
add_action('admin_menu', 'theme_customizer__menu');
add_action('admin_print_scripts', 'admin_inline_js');
add_action('admin_enqueue_scripts', 'admin_enqueue');

//# customizer panel
add_action('customize_register', 'add_fields_to_wp_customize');
add_action('customize_register', 'add_fields_to_wp_customize_image');
add_action('customize_register', 'itmits__set_theme_customizer');
