<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if(isset($_POST['WX-cr-setting'])){

    if ( ! wp_verify_nonce( $_POST['WX_nonce_settings'], plugin_basename( __FILE__ ) ) ) {
        die( 'Security check failed' );
    }
    else
    {
            foreach ($_POST as $key => $val):
                $WX_cr_options_arr[$key]    =   esc_html(sanitize_text_field($val));
            endforeach;
            $WX_cr_options_arr_serialized   =   serialize($WX_cr_options_arr);
            update_option('WX_cr_options', $WX_cr_options_arr_serialized);
            $WX_success     =   'Settings update successfully';

    }

}
$pages  =   get_pages();
$WX_get_cr_options  = get_option('WX_cr_options');

if(is_serialized($WX_get_cr_options))
{
    $WX_get_cr_options  =   unserialize($WX_get_cr_options);
}
$return     =  '';
if(isset($WX_error))
{
    $return     .=  '<div class="notice notice-error"><p>'.$WX_error.'</p></div>';
}
if(isset($WX_success))
{
    $return     .=  '<div class="notice notice-success"><p>'.$WX_success.'</p></div>';
}
require_once 'wx-cr-info.php';
$return     .=  '<div class="wx-settings-outer-wrap">';
$return     .=  '<div class="wx-plugin-settings">';
$return     .=  '<form action="'.menu_page_url('wx-woo-thank-you',false ).'" name="WX-cr-setting" id="WX-cr-setting" method="post">';
$return		.=	'<table class="form-table"><tbody>';
$return     .=  '<input type="hidden" name="WX-cr-setting">';
$return     .=  wp_nonce_field( plugin_basename( __FILE__ ), 'WX_nonce_settings',true,false);
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-cr-page">'.__('Select Page',WX_CR_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><select name="WX-cr-page" id="WX-cr-page">';
$return     .=  '   <option value="default">Default</option>';
foreach($pages as $page)
{
    $return .=  '   <option value="'. get_page_link($page->ID) .'"';
    if(isset($WX_get_cr_options['WX-cr-page']) && !empty($WX_get_cr_options['WX-cr-page']) && $WX_get_cr_options['WX-cr-page'] == get_page_link($page->ID ))
    {
        $return .=  'selected="selected"';
    }
    $return .=  '>'. $page->post_title .'</option>';
}
$return     .=  '</select>';
$return		.=	'	<p class="description">'.__('Select a page where to redirect customer after successful checkout',WX_CR_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return		.=	'<tr><th scope="row">';
$return		.=	'	<label for="WX-cr-custom-page">'.__('Custom/External Page',WX_CR_TEXT_DOMAIN).'</label></th>';
$return		.=	'	<td><input type="url" name="WX-cr-custom-page" id="WX-cr-custom-page" class="widefat" value="'.@$WX_get_cr_options['WX-cr-custom-page'].'"> ';

$return		.=	'	<p class="description">'.__('you can give custom url too, it will override the above selection',WX_CR_TEXT_DOMAIN).'</p></td>';
$return		.=	'</tr>';
$return     .=  '<tr>';
$return		.=	'	<td>'.get_submit_button( 'Save' ).'</td>';
$return		.=	'</tr>';

$return     .=  '</tbody></table>';
$return     .=  '</form>';
$return     .=  '</div>';
$return     .=  '<div class="wx-plugin-info">';
$return     .=  $thisischeck;
$return     .=  '</div><div class="clear-fix"></div> ';
$return     .=  '</div>';


echo $return;