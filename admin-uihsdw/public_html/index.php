<?php
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

require realpath(__DIR__ . '/../../../../../wp-load.php');

if(!is_user_logged_in){
    return false;
}

//# Create template
if(
    isset($_POST['btnCreateTemplate']) &&
    isset($_POST['file_to_import']) &&
    isset($_POST['retrieved_header']) &&
    isset($_POST['page_template']) &&
    isset($_POST['retrieved_body']) &&
    isset($_POST['retrieved_footer'])
    ){
    global $_theme_folder;
    global $_get_fields;

    $plugin_folder_path = plugin_dir_path( __FILE__ );
    $themes_folder = '-themes-oisdhhwd';
    $theme_name = 'html_theme';
    $_theme_folder = $plugin_folder_path.'admin-uihsdw/'.$themes_folder.'/'.$theme_name;

    $new_theme_path = get_theme_root().'/'.$theme_name;

    include( str_replace('admin-uihsdw/public_html/', '', plugin_dir_path( __FILE__ )) . 'admin-uihsdw/_include-asdwe/-import.php'); //# TODO: improve this

    $file_to_import = $_POST['file_to_import'];
    $is_index = $_POST['is_index'];
    $page_template = $_POST['page_template'];
    $page_header = _fix_url(double_quote($_POST['retrieved_header']));
    $page_body = _fix_url(double_quote($_POST['retrieved_body']));
    $page_footer = _fix_url(double_quote($_POST['retrieved_footer'].'</html>'));

    //# 1) header.php
    create_tmpl_header($page_header, $new_theme_path);

    //# 2) footer.php
    create_tmpl_footer($page_footer, $new_theme_path);

    //# 3) functions.php
    create_tmpl_functions('', $new_theme_path);

    //# 4 index or page-template
    $get_fields_path = str_replace('admin-uihsdw/public_html/', '', plugin_dir_path( __FILE__ )) . '_include-sihdw/-get_fields.php';
    $created_pages_log = str_replace('admin-uihsdw/public_html/', '', plugin_dir_path( __FILE__ )) . '_include-sihdw/created_pages_log.log';

    $body_to_wp = body_to_wp($page_body, $get_fields_path, $created_pages_log, $page_template);

    if(intval($is_index) === 1){
        //# 5) index.php
        create_tmpl_index('<?php /*
Theme Name: Imported Template
Author: The original author
Description: This template has been imported
Version: 0.0.1
*/ get_header(); ?>'.$body_to_wp.'<?php get_footer(); ?>', $new_theme_path);

    }else{

        //# 4) page-[template].php
        $_page = '<?php 
/**
 * Template Name: '.str_replace('.php', '', $page_template).'
 * Description: '.$page_template.'
 *
 */ get_header(); ?> '.$body_to_wp.'<?php get_footer(); ?>';

        file_put_contents($new_theme_path.'/'.$page_template, $_page);

        //# standard index
        create_tmpl_index('<?php /*
Theme Name: Imported Template
Author: The original author
Description: This template has been imported
Version: 0.0.1
*/ get_header(); ?> <?php get_footer(); ?>', $new_theme_path);
    }

    //# 6) style.css
    create_tmpl_style('', $new_theme_path);

}

//# Parse HTML
if(isset($_POST['file'])){
    if(file_exists($_POST['file'])){
        $_get_head = get_custom_tag('head', $_POST['file']);
        $_get_body = get_custom_tag('body', $_POST['file']);
        $_get_footer = get_custom_tag('footer', $_POST['file']);
    }else{
        echo 'It doesn\'t exists: '.$_POST['file'];
    }
    
    $_response = array(
        'get_head' => $_get_head,
        'get_body' => $_get_body,
        'get_footer' => $_get_footer
    );
    
    echo response($_response);
}


//# FUNCTIONS
function response($response){
    header("Content-Type: application/json");
    if($response !== ''){
        echo json_encode($response);
    }
    die();
}

function double_quote($q){
    $q = str_replace('\"', '"', $q);
    $q = str_replace("\'", '"', $q);
    return $q;
}