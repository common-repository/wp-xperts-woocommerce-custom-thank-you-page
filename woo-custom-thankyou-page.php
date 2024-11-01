<?php
/**
 *Plugin Name: WP-xPerts Woocommerce Custom Thank-You Page
 * Plugin URI: http://wp-xperts.com/
 * Description:  Wp-xPerts woocommerce custom thank you page plugin enables you to create a custom thank you page. Admin will be redirected to custom thank you page after successful checkout. This way you can create a custom thank you message too.
 * Version: 1.2.2
 * Author: Sajid Hussain
 * Author URI: http://wp-xperts.com/
 * Text Domain: wx-custom-thankyou-page
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


define( 'WX_CR_PATH_INCLUDES', dirname( __FILE__ ) . '/inc' );
define('WX_CR_TEXT_DOMAIN', 'wx-custom-thankyou-page');


class WX_WOO_thankyou
{
    public function __construct()
    {
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this, 'WX_add_settings_link') );
        add_action('admin_menu', array($this, 'WX_cr_admin_page'));
        add_action( 'admin_enqueue_scripts', array( $this, 'WX_cr_admin_scripts_styles' ) );
    }

    public function WX_add_settings_link($links)
    {
        $WX_links = array(
            '<a href="' . admin_url( 'options-general.php?page=wx-woo-thank-you' ) . '">Settings</a>',
        );
        return array_merge( $links, $WX_links );
    }

    public function WX_cr_admin_page()
    {
        add_options_page(
            'Woo Thank-You Page',
            'Woo Thank-You Page',
            'manage_options',
            'wx-woo-thank-you',
            array(
                $this,
                'WX_thank_you_page'
            )
        );
    }

    public function WX_thank_you_page()
    {
        echo '<div class="wrap">';
        _e( '<h1>Woo Custom Thank You Page</h1>', 'wx-custom-thankyou-page' );
        require_once WX_CR_PATH_INCLUDES.'/wx-cr-settings.php';
        echo '</div>';
    }

    public function WX_cr_admin_scripts_styles( $hook )
    {
        if( $hook != 'settings_page_wx-woo-thank-you' )
        {
            return;
        }

        wp_register_style( 'wx-ct-admin-styles', plugins_url( '/css/styles-admin.css', __FILE__ ), array(), '1.2.2', 'screen' );
        wp_enqueue_style( 'wx-ct-admin-styles' );
    }

}
$WX_WOO_obj =   new WX_WOO_thankyou();


add_action( 'template_redirect', 'WX_cr_thankyou' );
function WX_cr_thankyou() {
    global $wp;
    $WX_get_cr_options  = get_option('WX_cr_options');
    if(is_serialized($WX_get_cr_options))
    {
        $WX_get_cr_options  =   unserialize($WX_get_cr_options);
    }
    $WX_cr_page =   $WX_get_cr_options['WX-cr-custom-page'];
    if(!$WX_cr_page || empty($WX_cr_page))
    {
        $WX_cr_page =   $WX_get_cr_options['WX-cr-page'];
    }


    if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) )
    {
        if($WX_cr_page != 'default')
        {
            wp_redirect( $WX_cr_page );
            exit;
        }

    }
}