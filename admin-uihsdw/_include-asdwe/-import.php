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

//# TODO: don't add get_template_directory_uri() to the external URLs
function _fix_url($page){
    $_page = str_replace('<link href="', '<link href="<?php echo get_template_directory_uri();?>/', $page);
    $_page = str_replace('<script src="', '<script src="<?php echo get_template_directory_uri();?>/', $_page);

    return $_page;
}