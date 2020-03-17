<?php
/**
* Plugin Name: Export Page Plugin
* Plugin URI: http://anysoftsolutions.com/export-page-plugin/
* Description: This plugin it make static html page using wordpress in less time.This plugin easy to use and flexible anyone can use it.
* Version: 1.0.0
* Author: Sachin Patel
* Author URI: http://www.anysoftsolutions.com/
* License: GPL-2.0+
* Text Domain: export-page-plugin
*/


include_once( plugin_dir_path( __FILE__ ) . 'expot_option.php');  

add_action( 'add_meta_boxes', 'export_page_plugin' );
function export_page_plugin()
{
    add_meta_box( 'export-page-plugin', 'Export Page Plugin', 'export_page_plugin_fields', 'page', 'normal', 'high' );
}
function export_page_plugin_fields()
{   
    global $post;   	
    $img_name = get_post_meta( $post->ID, 'images_folder_name', TRUE );
	$image_folder_name = isset( $img_name ) ? $img_name : 'images'; 
    $html_name = get_post_meta( $post->ID, 'html_file_name', TRUE );	
    $html_file_name = isset( $html_name ) ? $html_name : 'index';
	$css_name = get_post_meta( $post->ID, 'css_file_name', TRUE );
    $css_file_name = isset( $css_name ) ? $css_name : 'style';   
    $clean_js_code = isset( $values['clean_js_code'] ) ? esc_attr( $values['clean_js_code'] ) : ''; 
    $clean_form_code = isset( $values['clean_form_code'] ) ? esc_attr( $values['clean_form_code'] ) : ''; 
    $disable_url = isset( $values['disable_url'] ) ? esc_attr( $values['disable_url'] ) : ''; 
    $clean_links = isset( $values['clean_links'] ) ? esc_attr( $values['clean_links'] ) : ''; 
    $clean_srcset_code = isset( $values['clean_srcset_code'] ) ? esc_attr( $values['clean_srcset_code'] ) : ''; 
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <label for="images_folder_name"><?php echo _e('Image Folder Name','export-page-plugin');?></label>
        <input type="text" name="images_folder_name" id="images_folder_name" value="images" readonly>
    </p>     
    <p>
        <label for="html_file_name"><?php echo _e('HTML File Name','export-page-plugin');?></label>
        <input type="text" name="html_file_name" id="html_file_name" value="index" readonly>
    </p>
	<p>
        <label for="css_file_name"><?php echo _e('Css File Name','export-page-plugin');?></label>
        <input type="text" name="css_file_name" id="css_file_name" value="style" readonly>
    </p> 	
	<p>
        <input type="checkbox" id="clean_js_code" name="clean_js_code" checked >
        <label for="clean_js_code"><?php echo _e('Clean js Code & Fiels','export-page-plugin');?></label>
    </p>
	<p>
        <input type="checkbox" id="disable_url" name="disable_url" checked>
        <label for="disable_url"><?php echo _e('Disable URL into Form Action','export-page-plugin');?></label>
    </p> 
	<p>
        <input type="checkbox" id="clean_srcset_code" name="clean_srcset_code" checked>
        <label for="clean_srcset_code"><?php echo _e('Clean srcset in img','export-page-plugin');?></label>
    </p>
	<p>
        <input type="checkbox" id="clean_links" name="clean_links" checked>
        <label for="clean_links"><?php echo _e('Clean Links','export-page-plugin');?></label>
    </p>
	<p>
        <input type="checkbox" id="clean_domain" name="clean_domain" checked> 
        <label for="clean_domain"><?php echo _e('Clean domain name','export-page-plugin');?></label>
    </p>
	<p>
        <input type="hidden" id="postid" name="postid" value="<?php echo $post->ID; ?>" />        
    </p> 
	<p>
        <!--<a href="?idd2=404" name="generates" id="generates" value="">Export RSVP</a>-->
		<button id="generates" type="submit">Genrate the Zip File to Download</button>
		<a href="?adtdownload" id="adtdownload" type="submit">Download</a>
    </p> 
	<p>
      Activate all option go to  premium plugin <a href="">Export page Plugin Pro</a>
    </p> 
<script type="text/javascript">
    jQuery(document).ready(function($){
	jQuery('#adtdownload').hide();
    jQuery('#generates').click(function(){
		jQuery('#adtdownload').hide();
		jQuery('#generates').text('Loading....');
		var images_folder_name = $("#images_folder_name").val();
		var html_file_name = $("#html_file_name").val();
		var css_file_name = $("#css_file_name").val();
		var clean_js_code = $("#clean_js_code:checked").length;
		var clean_form_code = $("#clean_form_code:checked").length;
		var disable_url = $("#disable_url:checked").length;
		var clean_links = $("#clean_links:checked").length;
		var clean_srcset_code = $("#clean_srcset_code:checked").length;
		var clean_domain = $("#clean_domain:checked").length;
		var postid = $("#postid").val();
		jQuery.ajax({
            action : 'make_download',
            type   : "POST",
            url    : "admin-ajax.php",
            data   : {action:'make_download',images_folder_name: images_folder_name, html_file_name: html_file_name, css_file_name: css_file_name, clean_js_code: clean_js_code, disable_url: disable_url, clean_domain:clean_domain,clean_srcset_code:clean_srcset_code, clean_links:clean_links, clean_form_code:clean_form_code,url:'<?php the_permalink();?>',postid: postid},
            success: function(data){
                console.log(data);
				jQuery('#generates').text('Genrate the Zip File to Download');
				jQuery('#adtdownload').show();
            }
        });
	});
	});
</script>
    <?php    
}
include('backend.php');
?>