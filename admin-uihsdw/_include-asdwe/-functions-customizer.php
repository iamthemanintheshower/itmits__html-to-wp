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

//# References:
//# https://codex.wordpress.org/Theme_Customization_API
//# https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_control
//# https://codex.wordpress.org/Plugin_API/Action_Reference/customize_register
//# https://codex.wordpress.org/Class_Reference/WP_Customize_Manager/add_setting#Example

function itmits__set_theme_customizer( $wp_customize ) {
    global $_get_fields;
    if($_get_fields){
       $description = ''; 
    }else{
        $description = 'No getFields() please setup the theme customizer.';
    }

    $wp_customize->add_section( 'itmits__customizer_section' , array(
        'title'       => __( 'HTMLtoWP', 'itmits__customizer' ),
        'priority'    => 30,
        'description' => $description,
    ) );
  
    $wp_customize->add_setting( 'itmits__customizer_header_img' );
    
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'itmits__customizer', array(
        'label'    => __( 'HTMLtoWP', 'itmits__customizer' ),
        'section'  => 'itmits__customizer_section',
        'settings' => 'itmits__customizer_header_img',
    ) ) );
}

function add_fields_to_wp_customize($wp_customize) {
    global $_get_fields;
    $ary_fields = getFields();
    if(isset($ary_fields) && is_array($ary_fields)){
        foreach ($ary_fields as $f){
            $wp_customize->add_setting($f['code']);

            if(isset($f['type']) && $f['type'] !== ''){ $type = $f['type']; }else{ $type = 'textarea'; }

            $wp_customize->add_control(
                $f['code'], 
                array( 'label' => $f['label'], 'section' => 'itmits__customizer_section', 'type' => $type ) 
            );
        }
        $_get_fields = true;
    }else{
        $_get_fields = false;
    }
    
}

function add_fields_to_wp_customize_image($wp_customize) {
    global $_get_images;
    $ary_images = getImages();
    if(isset($ary_images) && is_array($ary_images)){
        foreach ($ary_images as $f){
            $wp_customize->add_setting($f['code']);

            if(isset($f['type']) && $f['type'] !== ''){ $type = $f['type']; }else{ $type = 'textarea'; }

            $wp_customize->add_control(
                new WP_Customize_Upload_Control(
                    $wp_customize, $f['code'], 
                        array(
                            'label'    => __( $f['label'], 'itmits__customizer_image' ),
                            'section'  => 'itmits__customizer_section',
                            'settings' => $f['code']
                        )
                )
            );
        }
        $_get_images = true;
    }else{
        $_get_images = false;
    }
}


//# print field content (used in the template page)
function print_field($section, $id_field){
    echo get_theme_mod($section.'_'.$id_field);
}