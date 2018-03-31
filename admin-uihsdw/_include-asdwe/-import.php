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

function create_tmpl_header($header, $new_theme_path){
    $header_path = $new_theme_path.'/header.php';
    if(!file_exists($header_path)){
        $_header = '<html>'.str_replace('</head>', '<?php wp_head(); ?></head>', $header);
        file_put_contents($new_theme_path.'/header.php', $_header);
    }else{
        return false;
    }
}

function create_tmpl_footer($footer, $new_theme_path){
    $footer_path = $new_theme_path.'/footer.php';
    if(!file_exists($footer_path)){
        $_footer = str_replace('</body>', '<?php wp_footer(); ?></body>', $footer);
        file_put_contents($new_theme_path.'/footer.php', $_footer);
    }else{
        return false;
    }
}

function create_tmpl_functions($functions, $new_theme_path){
    $functions_path = $new_theme_path.'/functions.php';
    if(!file_exists($functions_path)){
        file_put_contents($new_theme_path.'/functions.php', $functions);
    }else{
        return false;
    }
}

function create_tmpl_index($index, $new_theme_path){
    $functions_path = $new_theme_path.'/index.php';
    if(!file_exists($functions_path)){
        file_put_contents($new_theme_path.'/index.php', $index);
    }else{
        return false;
    }
}

function create_tmpl_style($index, $new_theme_path){
    $functions_path = $new_theme_path.'/style.css';
    if(!file_exists($functions_path)){
        file_put_contents($new_theme_path.'/style.css', $index);
    }else{
        return false;
    }
}

function _fix_url($page, $_theme_folder, $new_theme_path, $copy_all_folders){
    $folders_to_copy = array();
    $get_script_tag_and_content = get_script_tag_and_content($page);
    if(isset($get_script_tag_and_content) && is_array($get_script_tag_and_content)){
        foreach ($get_script_tag_and_content as $t){
            $tag_to_replace = $t['tag'];
            $content = $t['content'];
            if (strpos($content, 'http') === false) {
                $page = str_replace($content, '<?php echo get_template_directory_uri();?>/'.$content, $page);
                $folders_to_copy[] = explode('/', $content)[0];
            }
        }
    }
    
    $get_link_tag_and_content = get_link_tag_and_content($page);
    if(isset($get_link_tag_and_content) && is_array($get_link_tag_and_content)){
        foreach ($get_link_tag_and_content as $t){
            $tag_to_replace = $t['tag'];
            $content = $t['content'];
            if (strpos($content, 'http') === false) {
                $page = str_replace($content, '<?php echo get_template_directory_uri();?>/'.$content, $page);
                $folders_to_copy[] = explode('/', $content)[0];
            }
        }
    }

    if($copy_all_folders === 0){
        foreach ($folders_to_copy as $folder){
            copy_resources($_theme_folder.'/'.$folder, $new_theme_path.'/'.$folder);
        }
    }

    return $page;
}

function copy_resources($source, $destination) { 
    $dir = opendir($source); 
    @mkdir($destination); 
    while(false !== ( $file = readdir($dir)) ) { 
        if(($file != '.' ) && ($file != '..') && pathinfo($file, PATHINFO_EXTENSION) !== '.html'){ 
            if (is_dir($source.'/'.$file)){ 
                copy_resources($source.'/'.$file, $destination.'/'.$file); 
            }else{
                if(!file_exists($destination.'/'.$file)){
                    copy($source.'/'.$file, $destination.'/'.$file);
                }
            }
        } 
    } 
    closedir($dir); 
}
