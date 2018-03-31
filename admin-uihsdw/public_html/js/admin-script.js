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

jQuery(document).ready(function () {
    jQuery('body').on('click', '.get_header_body_footer', function () {
        var file = jQuery(this).data('file');
        var values = { file: file};
        jQuery.post( pluginUrl + "admin-uihsdw/public_html/index.php", values)
        .done(function( data ) {
            console.log(data);
            jQuery('#file_to_import').val(file);
            jQuery('#page_template').val('page-home.php'); //# default value = page-home.php
            jQuery('#retrieved_header').val('<head>' + data.get_head + '</head>');
            jQuery('#retrieved_body').val('<body>' + data.get_body + '</body>');
            jQuery('#retrieved_footer').val(data.get_footer);
        })
        .fail(function( data ) {
            console.log( "FAIL: " );
            console.log( data );
        });
    });

    jQuery('body').on('click', '#btnCreateTemplate', function () {
        var is_index = 0;
        var copy_all_folders = 0;
        if(jQuery('#is_index').is(':checked')){
            is_index = 1;
        }
        if(jQuery('#copy_all_folders').is(':checked')){
            copy_all_folders = 1;
        }
        
        var values = {
            btnCreateTemplate: 'btnCreateTemplate',
            file_to_import: jQuery('#file_to_import').val(),
            is_index: is_index,
            copy_all_folders: copy_all_folders,
            page_template: jQuery('#page_template').val(),
            retrieved_header: jQuery('#retrieved_header').val(),
            retrieved_body: jQuery('#retrieved_body').val(),
            retrieved_footer: jQuery('#retrieved_footer').val()
        };

        jQuery.post( pluginUrl + "admin-uihsdw/public_html/index.php", values)
        .done(function( data ) {
            console.log(data);
        })
        .fail(function( data ) {
            console.log( "FAIL: " );
            console.log( data );
        });
        return false;
    });
});
