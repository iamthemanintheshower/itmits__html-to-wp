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

//# TODO: many things must to be improved here to enforce the parsing

function get_custom_tag($tag, $page_path){
    return _get_tag_content($tag, $page_path);
}


function body_to_wp($page_body, $get_fields_path, $created_pages_log, $page_template_name, $_theme_folder){
    $date = new DateTime();

    $_standard = manage_tags('standard', $page_body, $_theme_folder, $page_template_name);
    $_div = manage_tags('div', $_standard['_replace_tags']['page'], $_theme_folder, $page_template_name);
    $_image = manage_tags('image', $_div['_replace_tags']['page'], $_theme_folder, $page_template_name);

    $_get_fields__file = $_standard['_replace_tags']['get_fields__file'];
    $getFields = '<?php function getFields(){'.$_get_fields__file.' return $ary_fields; }';

    $getDivs = PHP_EOL.'$fieldsByPage["'.$page_template_name.'"] = array('.$_div['get_fields__file'].');';

    $_get_fields__file__image = $_image['_replace_tags']['get_fields__file'];
    $getImages = 'function getImages(){'.$_get_fields__file__image.' return $ary_fields; }';

    file_put_contents($get_fields_path,$getFields.PHP_EOL.$getImages.PHP_EOL.$getDivs);

    file_put_contents($created_pages_log, $date->format('d-m-Y_H:i:s').'-'.basename($page_template_name).print_r($_get_fields__file, true).PHP_EOL);

    return $_image['_replace_tags']['page'];
}

function manage_tags($tag_type, $page_body, $_theme_folder){
    switch ($tag_type) {
        case 'div':
            $_setDataIdToAllSelectedTags = setDataIdToAllSelectedTags('div', $page_body);
            return 
                array(
                '_replace_tags' => array('page' => $_setDataIdToAllSelectedTags['page']), 
                'get_fields__file' => $_setDataIdToAllSelectedTags['get_fields__file']
            );
        case 'standard':
            $aryTagsToEdit = array( 'p', 'h1', 'h2', 'h3', 'h4' );
            foreach ($aryTagsToEdit as $t){
                $_ary[$t] = get_tag_and_content($t, $page_body);
            }
            $_replace_tags = replace_tags($_ary, $page_body, $tag_type, $_theme_folder);

            return array('_replace_tags' => $_replace_tags, 'fields' => '<?php function getFields(){'.$_replace_tags['get_fields__file'].' return $ary_fields; }');
        case 'image':
            $aryTagsToEdit = array( 'img' );
            foreach ($aryTagsToEdit as $t){
                $_ary[$t] = get_img_tag_and_content($page_body);
            }
            $_replace_tags = replace_tags($_ary, $page_body, $tag_type, $_theme_folder);

            return array('_replace_tags' => $_replace_tags, 'fields' => '<?php function getImages(){'.$_replace_tags['get_fields__file'].' return $ary_fields; }');
        default:
            break;
    }
}

function replace_tags($ary, $page, $tag_type, $_theme_folder){
    foreach ($ary as $tag){
        if(isset($tag)){
            foreach ($tag as $t){
                $tag_to_replace = $t['tag'];

                $field_id = generateRandomString();

                $replaced_tag = str_replace($t['content'], '<?php echo print_field("all", "'.$field_id.'"); ?>', $tag_to_replace);

                $replaced_tag = _setClass($replaced_tag, 'ed-cst-fld');

                $replaced_tag = _setDataFieldId($replaced_tag, $field_id);

                switch ($tag_type) {
                    case 'standard':
                        break;
                    case 'image':
                        $t['content'] = _setMedia($_theme_folder, $t['content']);
                        break;
                    default:
                        break;
                }

                set_theme_mod('all_'.$field_id, $t['content']);

                $page = str_replace($tag_to_replace, $replaced_tag, $page);

                $get_fields__file .= 
                    '$ary_fields[] = array(\'html_section\' => \'all\', \'code\' => \'all_'.
                        $field_id.'\', \'label\' => "'.$field_id
                        .'", \'section\' => \'title_tagline\', \'type\' => \'textarea\');'
                ;
            }
        }
    }
    return array(
        'page' => $page, 
        'get_fields__file' => $get_fields__file
    );
}

function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

//#TODO: improve and merge get_tag_and_content(), _get_tag_content(), get_img_tag_and_content()
function get_tag_and_content($tag_string, $page){
    $out = '';
    preg_match_all(
        "|<".$tag_string."[^>]*>(.*)</[^>]+>|U",
        $page,
        $out, PREG_PATTERN_ORDER
    );

    $ary = null;

    $count = sizeof($out[0]);

    for($i = 0; $i < $count; $i++){
        $ary[] = array('tag' => $out[0][$i], 'content' => $out[1][$i]);
    }

    return $ary;
}
function setDataIdToAllSelectedTags($tag, $page){
    $page_lines = explode(PHP_EOL, $page);
    $lines = $get_fields__file = '';
    foreach ($page_lines as $line){
        $field_id = generateRandomString();
        $lines .= str_replace('<'.$tag, '<'.$tag.' data-field_id="'.$field_id.'"', $line).PHP_EOL;
        $get_fields__file .= '"'.$field_id.'", ';
    }
    return array('page' => $lines, 
        'get_fields__file' => $get_fields__file);
    
}
function _get_tag_content($tag, $page_path){
    $match = '';
    $start = '<'.$tag.'>';
    $end = '<\/'.$tag.'>';

    $fp = fopen( $page_path, 'r' );

    $cont = "";

    while( !feof( $fp ) ) {
        $buf = trim( fgets( $fp, 4096 ) );
        $cont .= $buf.PHP_EOL;
    }
    
    preg_match( "/$start(.*)$end/s", $cont, $match );

    return $match[1]; 
}

function get_img_tag_and_content($page){
    $out = '';
    preg_match_all(
        '/< *img[^>]*src *= *["\']?([^"\']*)/i',
        $page,
        $out, PREG_PATTERN_ORDER
    );

    $ary = null;

    $count = sizeof($out[0]);

    for($i = 0; $i < $count; $i++){
        $ary[] = array('tag' => $out[0][$i], 'content' => $out[1][$i]);
    }

    return $ary;
}

function get_script_tag_and_content($page){
    $out = '';
    preg_match_all(
        '/< *script[^>]*src *= *["\']?([^"\']*)/i',
        $page,
        $out, PREG_PATTERN_ORDER
    );

    $ary = null;

    $count = sizeof($out[0]);

    for($i = 0; $i < $count; $i++){
        $ary[] = array('tag' => $out[0][$i], 'content' => $out[1][$i]);
    }

    return $ary;
}

function get_link_tag_and_content($page){
    $out = '';
    preg_match_all(
        '/< *link[^>]*href *= *["\']?([^"\']*)/i',
        $page,
        $out, PREG_PATTERN_ORDER
    );

    $ary = null;

    $count = sizeof($out[0]);

    for($i = 0; $i < $count; $i++){
        $ary[] = array('tag' => $out[0][$i], 'content' => $out[1][$i]);
    }

    return $ary;
}
//#-

function _setClass($tag_to_replace, $_class){
    if (strpos($tag_to_replace, 'class="') !== false) {
        return str_replace('class="', 'class="'.$_class.' ', $tag_to_replace);
    }else{
        $tag_to_replace__ary = explode('><?php', $tag_to_replace);
        $tag_to_replace = $tag_to_replace__ary[0].' class=""><?php'.$tag_to_replace__ary[1];
    }
    
}

function _setDataFieldId($tag_to_replace, $field_id){
    return str_replace('class="', 'data-field_id="'.$field_id.'" class="', $tag_to_replace);
}

function _setMedia($_theme_folder, $content){
    $wp_upload_dir = wp_upload_dir();
    $filename = $_theme_folder.'/'.$content;
    if(file_exists($filename)){
        $attachment = array(
            'guid'           => $wp_upload_dir['url'] . '/' . basename ($filename), 
            'post_mime_type' => 'image/jpeg',
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename ($filename) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

        //#Reference: https://codex.wordpress.org/Function_Reference/wp_insert_attachment
        if (copy($filename, $wp_upload_dir['path'].'/'.basename ($filename))) {
            $attach_id = wp_insert_attachment( $attachment, $wp_upload_dir['path'].'/'.basename ($filename) );
            require_once( ABSPATH . 'wp-admin/includes/image.php' );
            $attach_data = wp_generate_attachment_metadata($attach_id, $wp_upload_dir['path'].'/'.basename ($filename));
            wp_update_attachment_metadata($attach_id, $attach_data);
            return $wp_upload_dir['url'] . '/' . basename ($filename);
        }else{
            error_log('!COPY-NOT|'.$wp_upload_dir['path'].'/'.basename ($filename).'|', 3, __DIR__.'/log.log');
        }
    }else{
        error_log('*** FILE NON ESISTE:'.$attach_id, 3, __DIR__.'/log.log');
    }
}
