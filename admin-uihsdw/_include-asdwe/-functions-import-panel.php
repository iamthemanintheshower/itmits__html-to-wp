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

//# References
//https://codex.wordpress.org/Administration_Menus
//https://codex.wordpress.org/Creating_Options_Pages

function admin_inline_js(){
    global $plugin_dir_url;
    echo "<script type='text/javascript'>\n";
        echo 'var pluginUrl = "' . $plugin_dir_url . '";';
    echo "\n</script>";
}

function admin_enqueue() { //https://codex.wordpress.org/Plugin_API/Action_Reference/admin_enqueue_scripts
    global $plugin_dir_url;
    wp_enqueue_style( 'admin', $plugin_dir_url . '/admin-uihsdw/public_html/css/admin.css' );
    wp_enqueue_script( 'admin', $plugin_dir_url . '/admin-uihsdw/public_html/js/admin-script.js' );
}


function theme_customizer__menu() {
    global $plugin_dir_url;
    add_menu_page('Settings', 'HTML to WP', 'administrator', __FILE__, 'theme_customizer_init__settings_page' , $plugin_dir_url . '/admin-uihsdw/public_html/imgs/icon.png' );
    add_submenu_page(__FILE__, 'Import Settings', 'Import', 'administrator', 'config-theme-customizer', 'theme_customizer__settings_page');
}

function theme_customizer_init__settings_page() {?>
    <div class="wrap">
        <h1>HTML to WP</h1>
    </div><?php
}

function theme_customizer__settings_page() {
    global $_theme_folder;?>
    <div class="wrap">
        <h1>Import Template</h1>
        <h3>theme folder: <?php echo $_theme_folder;?></h3>
        <?php

        //# template files buttons
        $_getTemplateFiles = _getTemplateFiles($_theme_folder);
        foreach ($_getTemplateFiles as $k=>$v){
            $label_slug = $v['label_slug'];
            $fields = $v['fields'];

            foreach ($fields as $field_label => $field_slug){
                if(pathinfo($_theme_folder.'/'.$field_label, PATHINFO_EXTENSION) === 'html'){
                    echo '<button id="" class="get_header_body_footer" data-file="'.$_theme_folder.'/'.$field_label.'">'.$field_label.'</button>';
                }else{
                    echo '<button id="" class="grey">'.$field_label.'</button>';
                }
            }
        }
        
        //# import fields: File to import, Page template, Header, Body, Footer
        ?>
        <table class="form-table">
            <?php
            $_getImportFields = _getImportFields();
            foreach ($_getImportFields as $k=>$v){
                $label_slug = $v['label_slug'];
                $fields = $v['fields'];
                ?>
                <tr valign="top">
                    <th scope="row"><span><?php echo $k;?></span></th>
                    <td>&nbsp;</td>
                </tr>
                <?php
                foreach ($fields as $field_label => $args){?>
                    <tr valign="top">
                        <td scope="row">- <?php echo $field_label;?></td>
                        <td><?php echo _get_field($args);?></td>
                    </tr>
                    <?php
                }
            }
            ?>
        </table>

    <button id="btnCreateTemplate" class="button">Create template</button>

    </div>
<?php 
}

function _getTemplateFiles($_theme_folder){
    $files = array();
    $scandir = scandir($_theme_folder);

    if(file_exists($_theme_folder)){
        foreach ($scandir as $k=>$v){
            if($v !== '.' && $v !== '..'){
                $files[$v] = $v;
            }
        }
        $ary_ = array(
            'Import' => 
                array(
                    'label_slug' => 'import__config_htmltowp',
                    'fields' => $files
                ),
        );
    }
    
    return $ary_;
}

function _getImportFields(){
    $ary_ = array(
        'Import template' => 
            array(
                'label_slug' => 'import__config_htmltowp',
                'fields' => array(
                    'File to import' => 
                        array(
                            'field_slug' =>'file_to_import',
                            'field_type' => 'text'
                        ),
                    'Is index?' =>
                        array(
                            'field_slug' =>'is_index',
                            'field_type' => 'checkbox'
                        ),
                    'Page template' =>
                        array(
                            'field_slug' =>'page_template',
                            'field_type' => 'text'
                        ),
                    'Header (header.php)' => 
                        array(
                            'field_slug' =>'retrieved_header',
                            'field_type' => 'textarea'
                        ),
                    'Body (page-home.php)' => 
                        array(
                            'field_slug' =>'retrieved_body',
                            'field_type' => 'textarea'
                        ),
                    'Footer (footer.php)' => 
                        array(
                            'field_slug' =>'retrieved_footer',
                            'field_type' => 'textarea'
                        ),
                )
            ),
    );
    
    return $ary_;
}

function _get_field($args){
    switch ($args['field_type']) {
        case 'text':
            return '<input type="text" id="'.$args['field_slug'].'" name="'.$args['field_slug'].'" class="retrieved_input"/>';
        case 'textarea':
            return '<textarea id="'.$args['field_slug'].'" name="'.$args['field_slug'].'" class="retrieved_text_area"></textarea>';
        case 'checkbox':
            return '<input id="'.$args['field_slug'].'" name="'.$args['field_slug'].'" class="retrieved_checkbox" type="checkbox"/>';
        default:
            return '';
    }
}