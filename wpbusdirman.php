<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
Plugin Name: WP Business Directory Manager
Plugin URI: http://www.businessdirectoryplugin.com
Description: Provides the ability to maintain a free or paid business directory on your wordpress powered site.
Version: 1.9.4
Author: A. Lewis
Author URI: http://businessdirectoryplugin.com
Contributors: Mike Bronner - Rocking Double-M Services (http://rocking-mm.com)
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// WP Business Directory Manager provides the ability for you to add a business directory to your wordpress blog and charge a fee for users
// to submit their listing
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*  Copyright 2009,2010  A. Lewis  (email : wpbdm@businessdirectoryplugin)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' ); // no trailing slash, full paths only - WP_CONTENT_URL is defined further down

if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content'); // no trailing slash, full paths only - WP_CONTENT_URL is defined further down

$wpcontenturl=WP_CONTENT_URL;
$wpcontentdir=WP_CONTENT_DIR;
$wpinc=WPINC;


$wpbusdirman_plugin_path = WP_CONTENT_DIR.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
$wpbusdirman_plugin_url = WP_CONTENT_URL.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
$wpbusdirman_plugin_dir = basename(dirname(__FILE__));
$wpbusdirman_haspaypalmodule=0;
$wpbusdirman_hastwocheckoutmodule=0;
$wpbusdirman_hasgooglecheckoutmodule=0;

$wpbusdirman_imagespath = WP_CONTENT_DIR.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'images';
$wpbusdirman_imagesurl = WP_CONTENT_URL.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'images';

$uploaddir=get_option('upload_path');
if(!isset($uploaddir) || empty($uploaddir))
{
	$uploaddir=ABSPATH;
	$uploaddir.="wp-content/uploads";
}


$wpbusdirmanimagesdirectory=$uploaddir;
$wpbusdirmanimagesdirectory.="/wpbdm";
$wpbusdirmanthumbsdirectory=$wpbusdirmanimagesdirectory;
$wpbusdirmanthumbsdirectory.="/thumbnails";

$wpbdmimagesurl="$wpcontenturl/uploads/wpbdm";

$nameofsite=get_option('blogname');
$siteurl=get_option('siteurl');
$thisadminemail=get_option('admin_email');

$wpbdmposttype="wpbdm-directory";
$wpbdmposttypecategory="wpbdm-category";
$wpbdmposttypetags="wpbdm-tags";

$wpbusdirman_db_version = "1.9.3";

$wpbusdirmaname=__("WP Business Directory Manager","WPBDM");
$wpbusdirman_labeltext=__("Label","WPBDM");
$wpbusdirman_typetext=__("Type","WPBDM");
$wpbusdirman_associationtext=__("Association","WPBDM");
$wpbusdirman_optionstext=__("Options","WPBDM");
$wpbusdirman_ordertext=__("Order","WPBDM");
$wpbusdirman_actiontext=__("Action","WPBDM");
$wpbusdirman_valuetext=__("Value","WPBDM");
$wpbusdirman_amounttext=__("Amount","WPBDM");
$wpbusdirman_appliedtotext=__("Applied To","WPBDM");
$wpbusdirman_allcatstext=__("All categories","WPBDM");
$wpbusdirman_daytext=__("Day","WPBDM");
$wpbusdirman_daystext=__("Days","WPBDM");
$wpbusdirman_imagestext=__("Images","WPBDM");
$wpbusdirman_durationtext=__("Duration","WPBDM");
$wpbusdirman_validationtext=__("Validation","WPBDM");
$wpbusdirman_requiredtext=__("Required","WPBDM");
$wpbusdirman_showinexcerpttext=__("Excerpt","WPBDM");


define('WPBUSDIRMANURL', $wpbusdirman_plugin_url );
define('WPBUSDIRMANPATH', $wpbusdirman_plugin_path );
define('WPBUSDIRPLUGINDIR', 'wp-business-directory-manager');
define('WPBUSDIRMANMENUICO', $wpbusdirman_imagesurl .'/menuico.png');
define('WPBUSDIRMAN', $wpbusdirmaname);
define('WPBUSDIRMAN_TEMPLATES_PATH', $wpbusdirman_plugin_path . '/posttemplate');


$wpbusdirman_gpid=wpbusdirman_gpid();
$permalinkstructure=get_option('permalink_structure');
$wpbusdirmanconfigoptionsprefix="wpbusdirman";

$wpbusdirman_field_vals_pfl=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');


// Options array
$poststatusoptions=array("pending","publish");
$yesnooptions=array("yes","no");
$myloginurl=get_option('siteurl').'/wp-login.php?action=login';
$myregistrationurl=get_option('siteurl').'/wp-login.php?action=register';
$categoryorderoptions=array('name','ID','slug','count','term_group');
$categorysortoptions=array('ASC','DESC');
$drafttrashoptions=array("draft","trash");
$listingsorderoptions=array('date','title','id','author','modified');
$listingssortorderoptions=array('ASC','DESC');



$def_wpbusdirman_config_options = array (

array("name" => "Miscellaneous settings",
"type" => "titles"),

array("name" => "Listing Duration for no-fee sites (measured in days)?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_18",
"std" => "365",
"type" => "text"),

array("name" => "Hide all buy plugin module buttons?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_25",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Hide tips for use and other information?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_26",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Include listing contact form on listing pages?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_27",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Include comment form on listing pages?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_36",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Give credit to plugin author?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_34",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Turn on listing renewal option?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_38",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Use default picture for listings with no picture?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_39",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Show listings under categories on main page?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_44",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Override email Blocking?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_45",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Status of listings upon uninstalling plugin",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_46",
"std" => "draft",
"type" => "select",
"options" => $drafttrashoptions),

array("name" => "Status of deleted listings",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_47",
"std" => "draft",
"type" => "select",
"options" => $drafttrashoptions),

array("name" => "Login/Registration Settings",
"type" => "titles"),

array("name" => "Require login?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_3",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Login URL?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_4",
"std" => "$myloginurl",
"type" => "text"),

array("name" => "Registration URL?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_5",
"std" => "$myloginurl",
"type" => "text"),

array("name" => "Post/Category Settings",
"type" => "titles"),

array("name" => "Default new post status",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_1",
"std" => "pending",
"type" => "select",
"options" => $poststatusoptions),

array("name" => "Edit post status",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_19",
"std" => "publish",
"type" => "select",
"options" => $poststatusoptions),

array("name" => "Order Categories List By",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_7",
"std" => "name",
"type" => "select",
"options" => $categoryorderoptions),

array("name" => "Sort order for categories",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_8",
"std" => "ASC",
"type" => "select",
"options" => $categorysortoptions),

array("name" => "Show category post count?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_9",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Hide Empty Categories?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_10",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Show only parent categories category list?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_48",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Order Directory Listings By (Featured Listings will always appear first regardless of this setting)",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_52",
"std" => "name",
"type" => "select",
"options" => $listingsorderoptions),

array("name" => "Sort Directory Listings By (ASC for ascending order A-Z, DESC for descending order Z - A)",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_53",
"std" => "name",
"type" => "select",
"options" => $listingssortorderoptions),

array("name" => "Image Settings",
"type" => "titles"),

array("name" => "Allow image?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_6",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Number of free images?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_2",
"std" => "2",
"type" => "text"),

array("name" => "Show Thumbnail on main listings page?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_11",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Max Image File Size?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_13",
"std" => "100000",
"type" => "text"),

array("name" => "Minimum Image File Size?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_14",
"std" => "300",
"type" => "text"),

array("name" => "Max image width?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_15",
"std" => "500",
"type" => "text"),

array("name" => "Max image height?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_16",
"std" => "500",
"type" => "text"),

array("name" => "Thumbnail Width?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_17",
"std" => "120",
"type" => "text"),


array("name" => "General Payment Settings",
"type" => "titles"),

array("name" => "Currency Code",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_20",
"std" => "USD",
"type" => "text"),

array("name" => "Currency Symbol",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_12",
"std" => "$",
"type" => "text"),

array("name" => "Turn On Payments?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_21",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Put payment gateways in test mode?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_22",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Thank you for payment message",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_37",
"std" => "Thank you for your payment. Your payment is being verified and your listing reviewed. The verification and review process could take up to 48 hours.",
"type" => "text"),

array("name" => "Featured(Sticky) listing settings",
"type" => "titles"),

array("name" => "Offer Sticky Listings?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_31",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Sticky Listing Price(00.00)",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_32",
"std" => "39.99",
"type" => "text"),

array("name" => "Sticky listing page description text",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_33",
"std" => "You can upgrade your listing to featured status. Featured listings will always appear on top of regular listings.",
"type" => "text"),


array("name" => "Google Checkout Settings",
"type" => "titles"),

array("name" => "Google Checkout Merchant ID",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_23",
"std" => "",
"type" => "text"),

array("name" => "Google Checkout Sandbox Seller ID",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_24",
"std" => "",
"type" => "text"),

array("name" => "Hide Google Checkout?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_40",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "PayPal Gateway Settings (Will only work if paypal module installed)",
"type" => "titles"),

array("name" => "PayPal Business Email",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_35",
"std" => "",
"type" => "text"),

array("name" => "Hide PayPal?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_41",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),


array("name" => "2Checkout Gateway Settings (Will only work if 2checkout module installed)",
"type" => "titles"),

array("name" => "2Checkout Seller/Vendor ID",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_42",
"std" => "",
"type" => "text"),

array("name" => "Hide 2Checkout?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_43",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "ReCaptcha Settings",
"type" => "titles"),

array("name" => "reCAPTCHA Public Key",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_28",
"std" => "",
"type" => "text"),

array("name" => "reCAPTCHA Private Key",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_29",
"std" => "",
"type" => "text"),

array("name" => "Turn on reCAPTCHA?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_30",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Permalink Settings",
"type" => "titles"),

array("name" => "Directory Listings Slug",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_49",
"std" => "$wpbdmposttype",
"type" => "text"),

array("name" => "Categories slug",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_50",
"std" => "$wpbdmposttypecategory",
"type" => "text"),

array("name" => "Tags slug",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_51",
"std" => "$wpbdmposttypetags",
"type" => "text"),

);



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Add actions and filters etc
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	add_action('init', 'wpbusdirman_install');
	add_action( 'wpbusdirman_listingexpirations_hook', 'wpbusdirman_listings_expirations' );
	add_action('init', 'wpbusdirman_dir_post_type');
	add_action('admin_menu', 'wpbusdirman_launch');
	add_action('wp_print_styles', 'wpbusdirman_addcss');
	add_shortcode('WPBUSDIRMANUI', 'wpbusdirmanui_homescreen');
	add_shortcode('WPBUSDIRMANADDLISTING', 'wpBusDirManUi_addListingForm');
	add_shortcode('WPBUSDIRMANMANAGELISTING', 'wpbusdirman_managelistings');
	add_shortcode('WPBUSDIRMANMVIEWLISTINGS', 'wpbusdirman_viewlistings');
	add_filter('single_template', 'wpbusdirman_single_template');
	add_filter('taxonomy_template', 'wpbusdirman_category_template');
	add_filter("wp_footer", "wpbusdirman_display_ac");
	add_filter('wp_list_pages_excludes', 'wpbusdirman_exclude_payment_pages');


/*******************************************************************************
*	SETTING UP PLUGIN HOOKS TO ALLOW CUSTOM OVERRIDES
*******************************************************************************/
	//display add listing form
	add_filter('wpbdm_show-add-listing-form', 'wpbusdirman_displaypostform', 10, 4);
	//display directory
	add_filter('wpbdm_show-directory', 'wpbusdirmanui_directory_screen', 10, 0);
	//display image upload form
	add_filter('wpbdm_show-image-upload-form', 'wpbusdirman_image_upload_form', 10, 8);
	//form post handler
	add_filter('wpbdm_process-form-post', 'wpbusdirman_do_post', 10, 0);



	if( file_exists("$wpbusdirman_plugin_path/gateways/paypal.php") )
	{
		require("$wpbusdirman_plugin_path/gateways/paypal.php");
		$wpbusdirman_haspaypalmodule=1;
	}
	if( file_exists("$wpbusdirman_plugin_path/gateways/twocheckout.php") )
	{
		require("$wpbusdirman_plugin_path/gateways/twocheckout.php");
		$wpbusdirman_hastwocheckoutmodule=1;
	}
	if( file_exists("$wpbusdirman_plugin_path/gateways/googlecheckout.php") )
	{
		require("$wpbusdirman_plugin_path/gateways/googlecheckout.php");
		$wpbusdirman_hasgooglecheckoutmodule=1;
	}

	if($wpbusdirman_haspaypalmodule	== 1)
	{
		add_shortcode('WPBUSDIRMANPAYPAL', 'wpbusdirman_do_paypal');
	}
	if($wpbusdirman_hastwocheckoutmodule == 1)
	{
		add_shortcode('WPBUSDIRMANTWOCHECKOUT', 'wpbusdirman_do_twocheckout');
	}
	if($wpbusdirman_hasgooglecheckoutmodule == 1)
	{
		add_shortcode('WPBUSDIRMANGOOGLECHECKOUT', 'wpbusdirman_do_googlecheckout');
	}





	function wpbusdirman_exclude_payment_pages($output = '')
	{

		$wpbdmpaymentpages=array();
		global $wpdb,$table_prefix;

		$query="SELECT ID FROM {$table_prefix}posts WHERE post_content LIKE '%WPBUSDIRMANGOOGLECHECKOUT%' OR post_content LIKE '%WPBUSDIRMANPAYPAL%' OR post_content LIKE '%WPBUSDIRMANTWOCHECKOUT%'";
		 if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		 	while ($rsrow=mysql_fetch_row($res))
 			{
 				$wpbdmpaymentpages[]=$rsrow[0];
 			}

		if($wpbdmpaymentpages)
		{
			foreach ($wpbdmpaymentpages as $wpbdmpaymentpagestoexclude)
			{
				array_push($output, $wpbdmpaymentpagestoexclude);
			}
		}

		return $output;


	}

function wpbusdirman_single_template($single)
{
	global $wp_query, $post, $wpbdmposttype;
	$mywpbdmposttype=$post->post_type;


		if($mywpbdmposttype == $wpbdmposttype )
		{
			if(file_exists(get_template_directory() . '/single/wpbusdirman-single.php'))
			return get_template_directory() . '/single/wpbusdirman-single.php';
			if(file_exists(get_stylesheet_directory() . '/single/wpbusdirman-single.php'))
			return get_stylesheet_directory() . '/single/wpbusdirman-single.php';
			if(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-single.php'))
			return WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-single.php';
		}

return $single;

}

function wpbusdirman_category_template($category)
{
	global $wp_query, $post, $wpbdmposttype;

			if(file_exists(get_template_directory() . '/single/wpbusdirman-category.php'))
			return get_template_directory() . '/single/wpbusdirman-category.php';
			if(file_exists(get_stylesheet_directory() . '/single/wpbusdirman-category.php'))
			return get_stylesheet_directory() . '/single/wpbusdirman-category.php';
			if(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-category.php'))
			return WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-category.php';

	return $category;
}

function wpbusdirman_addcss()
{
    $wpbusdirmanstylesheet="wpbusdirman.css";
    if(file_exists(get_template_directory() .'/css/'.$wpbusdirmanstylesheet))
    {
		$myWPBDMStyleUrl = get_template_directory_uri() . '/css/' .$wpbusdirmanstylesheet;
    }
    elseif(file_exists(get_stylesheet_directory() .'/css/'.$wpbusdirmanstylesheet))
    {
		$myWPBDMStyleUrl = get_stylesheet_directory_uri() . '/css/' .$wpbusdirmanstylesheet;
    }
    elseif(file_exists(WPBUSDIRMANPATH .'css/'.$wpbusdirmanstylesheet))
    {
		$myWPBDMStyleUrl = WPBUSDIRMANURL . 'css/' .$wpbusdirmanstylesheet;
    }
    if (0 < strlen('myWPBDMStyleFile'))
    {
		wp_register_style('myWPBDMStyleSheets', $myWPBDMStyleUrl);
		wp_enqueue_style( 'myWPBDMStyleSheets');
    }
}

function wpbusdirman_install()
{

	global $wpdb,$wpbusdirman_db_version,$wpbdmposttype,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$installed_ver = get_option( "wpbusdirman_db_version" );


	// Form Labels
	$wpbusdirman_postform_field_label_1=__("Business Name","WPBDM");
	$wpbusdirman_postform_field_label_2=__("Business Genre","WPBDM"); // Display Listing associated categories
	$wpbusdirman_postform_field_label_3=__("Short Business Description","WPBDM");
	$wpbusdirman_postform_field_label_4=__("Long Business Description","WPBDM");
	$wpbusdirman_postform_field_label_5=__("Business Website Address","WPBDM");
	$wpbusdirman_postform_field_label_6=__("Business Phone Number","WPBDM");
	$wpbusdirman_postform_field_label_7=__("Business Fax","WPBDM");
	$wpbusdirman_postform_field_label_8=__("Business Contact Email","WPBDM");
	$wpbusdirman_postform_field_label_9=__("Business Tags","WPBDM");

			if( isset($wpbusdirman_config_options) && !empty($wpbusdirman_config_options) && (is_array($wpbusdirman_config_options)) )
			{
				$wpbusdirman_installed_already=1;
			}
			else { $wpbusdirman_installed_already=0; }


	if(!$wpbusdirman_installed_already)
	{
	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	//	Install the plugin
	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			// Add Version Number
			add_option("wpbusdirman_db_version", $wpbusdirman_db_version);

			// Add settings options


			// Add default form options
			add_option("wpbusdirman_postform_field_label_1", $wpbusdirman_postform_field_label_1);
			add_option("wpbusdirman_postform_field_label_2", $wpbusdirman_postform_field_label_2);
			add_option("wpbusdirman_postform_field_label_3", $wpbusdirman_postform_field_label_3);
			add_option("wpbusdirman_postform_field_label_4", $wpbusdirman_postform_field_label_4);
			add_option("wpbusdirman_postform_field_label_5", $wpbusdirman_postform_field_label_5);
			add_option("wpbusdirman_postform_field_label_6", $wpbusdirman_postform_field_label_6);
			add_option("wpbusdirman_postform_field_label_7", $wpbusdirman_postform_field_label_7);
			add_option("wpbusdirman_postform_field_label_8", $wpbusdirman_postform_field_label_8);
			add_option("wpbusdirman_postform_field_label_9", $wpbusdirman_postform_field_label_9);

			// text = 1, select = 2, textarea=3 radio =4 multiselect =5 checkbox =6
			add_option("wpbusdirman_postform_field_type_1", 1);
			add_option("wpbusdirman_postform_field_type_2", 2);
			add_option("wpbusdirman_postform_field_type_3", 3);
			add_option("wpbusdirman_postform_field_type_4", 3);
			add_option("wpbusdirman_postform_field_type_5", 1);
			add_option("wpbusdirman_postform_field_type_6", 1);
			add_option("wpbusdirman_postform_field_type_7", 1);
			add_option("wpbusdirman_postform_field_type_8", 1);
			add_option("wpbusdirman_postform_field_type_9", 1);

			add_option("wpbusdirman_postform_field_options_1", '');
			add_option("wpbusdirman_postform_field_options_2", '');
			add_option("wpbusdirman_postform_field_options_3", '');
			add_option("wpbusdirman_postform_field_options_4", '');
			add_option("wpbusdirman_postform_field_options_5", '');
			add_option("wpbusdirman_postform_field_options_6", '');
			add_option("wpbusdirman_postform_field_options_7", '');
			add_option("wpbusdirman_postform_field_options_8", '');
			add_option("wpbusdirman_postform_field_options_9", '');

			add_option("wpbusdirman_postform_field_order_1", 1);
			add_option("wpbusdirman_postform_field_order_2", 2);
			add_option("wpbusdirman_postform_field_order_3", 3);
			add_option("wpbusdirman_postform_field_order_4", 4);
			add_option("wpbusdirman_postform_field_order_5", 5);
			add_option("wpbusdirman_postform_field_order_6", 6);
			add_option("wpbusdirman_postform_field_order_7", 7);
			add_option("wpbusdirman_postform_field_order_8", 8);
			add_option("wpbusdirman_postform_field_order_9", 9);


			add_option("wpbusdirman_postform_field_association_1", 'title');
			add_option("wpbusdirman_postform_field_association_2", 'category');
			add_option("wpbusdirman_postform_field_association_3", 'excerpt');
			add_option("wpbusdirman_postform_field_association_4", 'description');
			add_option("wpbusdirman_postform_field_association_5", 'meta');
			add_option("wpbusdirman_postform_field_association_6", 'meta');
			add_option("wpbusdirman_postform_field_association_7", 'meta');
			add_option("wpbusdirman_postform_field_association_8", 'meta');
			add_option("wpbusdirman_postform_field_association_9", 'tags');

			add_option("wpbusdirman_postform_field_validation_1", 'missing');
			add_option("wpbusdirman_postform_field_validation_2", 'missing');
			add_option("wpbusdirman_postform_field_validation_3", '');
			add_option("wpbusdirman_postform_field_validation_4", 'missing');
			add_option("wpbusdirman_postform_field_validation_5", 'url');
			add_option("wpbusdirman_postform_field_validation_6", '');
			add_option("wpbusdirman_postform_field_validation_7", '');
			add_option("wpbusdirman_postform_field_validation_8", 'email');
			add_option("wpbusdirman_postform_field_validation_9", '');

			add_option("wpbusdirman_postform_field_required_1", 'yes');
			add_option("wpbusdirman_postform_field_required_2", 'yes');
			add_option("wpbusdirman_postform_field_required_3", 'no');
			add_option("wpbusdirman_postform_field_required_4", 'yes');
			add_option("wpbusdirman_postform_field_required_5", 'no');
			add_option("wpbusdirman_postform_field_required_6", 'no');
			add_option("wpbusdirman_postform_field_required_7", 'no');
			add_option("wpbusdirman_postform_field_required_8", 'yes');
			add_option("wpbusdirman_postform_field_required_9", 'no');


			add_option("wpbusdirman_postform_field_showinexcerpt_1", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_2", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_3", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_4", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_5", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_6", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_7", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_8", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_9", 'no');

			add_option("wpbusdirman_postform_field_hide_1", 'no');
			add_option("wpbusdirman_postform_field_hide_2", 'no');
			add_option("wpbusdirman_postform_field_hide_3", 'no');
			add_option("wpbusdirman_postform_field_hide_4", 'no');
			add_option("wpbusdirman_postform_field_hide_5", 'no');
			add_option("wpbusdirman_postform_field_hide_6", 'no');
			add_option("wpbusdirman_postform_field_hide_7", 'no');
			add_option("wpbusdirman_postform_field_hide_8", 'no');
			add_option("wpbusdirman_postform_field_hide_9", 'no');


		/*wp_schedule_event( time(), 'daily', 'wpbusdirman_listings_expirations' );*/

		//wpbusdirman_convert_old_posts();
	 }
     else
     {

	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	//	Update the plugin
	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if( $installed_ver != $wpbusdirman_db_version )
		{
			update_option("wpbusdirman_db_version", $wpbusdirman_db_version);
		}


    }

    $plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'WPBDM', null, $plugin_dir.'/languages' );


}


function wpbusdirman_change_taxonomy_type_category($taxonomy)
{
	global $wpdb,$table_prefix,$wpbdmposttypecategory;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypecategory."', parent='0',count='0' WHERE term_id='$taxonomy'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_change_taxonomy_type_tags($taxonomy)
{
	global $wpdb,$table_prefix,$wpbdmposttypetags;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypetags."',count='0' WHERE term_id='$taxonomy'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_update_taxonomy_type_category($taxonomynm)
{
	global $wpdb,$table_prefix,$wpbdmposttypecategory;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypecategory."' WHERE taxonomy='$taxonomynm'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_update_taxonomy_type_tags($taxonomynm)
{
	global $wpdb,$table_prefix,$wpbdmposttypetags;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypetags."' WHERE taxonomy='$taxonomynm'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_adexpirations_hook(){}

function wpbusdirman_dir_post_type()
{

	global $wpbdmposttype,$wpbdmposttypecategory,$wpbdmposttypetags,$wpbusdirmanconfigoptionsprefix;

$wpbusdirman_config_options=get_wpbusdirman_config_options();


if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_49']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_49'])){$wpbdmposttypeslug=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_49'];}else {$wpbdmposttyleslug=$wpbdmposttype;}
if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_50']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_50'])){$wpbdmposttypecategoryslug=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_50'];}else {$wpbdmposttypecategoryslug=$wpbdmposttypecategory;}
if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_51']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_51'])){$wpbdmposttypetagslug=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_51'];}else {$wpbdmposttypetagslug=$wpbdmposttypetags;}



			  $labels = array(
			    'name' => _x('Directory', 'post type general name'),
			    'singular_name' => _x('Directory', 'post type singular name'),
			    'add_new' => _x('Add New Listing', 'listing'),
			    'add_new_item' => __('Add New Listing'),
			    'edit_item' => __('Edit Listing'),
			    'new_item' => __('New Listing'),
			    'view_item' => __('View Listing'),
			    'search_items' => __('Search Listings'),
			    'not_found' =>  __('No listings found'),
			    'not_found_in_trash' => __('No listings found in trash'),
			    'parent_item_colon' => ''
			  );
			  $args = array(
			    'labels' => $labels,
			    'public' => true,
			    'publicly_queryable' => true,
			    'show_ui' => true,
			    'query_var' => true,
			    'rewrite' => array('slug'=>$wpbdmposttypeslug,'with_front'=>false),
			    'capability_type' => 'post',
			    'hierarchical' => false,
			    'menu_position' => null,
			    'supports' => array('title','editor','author','categories','tags','thumbnail','excerpt','comments','custom-fields','trackbacks')
			  );
			  register_post_type($wpbdmposttype,$args);

	//Register directory category taxonomy
	register_taxonomy( $wpbdmposttypecategory, $wpbdmposttype, array( 'hierarchical' => true, 'label' => 'Directory Categories', 'singular_name' => 'Directory Category', 'show_in_nav_menus' => true, 'update_count_callback' => '_update_post_term_count','query_var' => true, 'rewrite' => array('slug'=>$wpbdmposttypecategoryslug) ) );
	register_taxonomy( $wpbdmposttypetags, $wpbdmposttype, array( 'hierarchical' => false, 'label' => 'Directory Tags', 'singular_name' => 'Directory Tag', 'show_in_nav_menus' => true, 'update_count_callback' => '_update_post_term_count', 'query_var' => true, 'rewrite' => array('slug'=>$wpbdmposttypetagslug) ) );


		if(function_exists('flush_rewrite_rules')){flush_rewrite_rules( false );}
	}


function wpbusdirman_launch()
{
	global $wpbusdirman_plugin_path;
	add_menu_page(WPBUSDIRMAN, 'WPBusDirMan', 'activate_plugins', 'wpbusdirman.php', 'wpbusdirman_home_screen', WPBUSDIRMANMENUICO);
	add_submenu_page('wpbusdirman.php', 'Manage Options ', 'Manage Options', 'activate_plugins', 'wpbdman_c1', 'wpbusdirman_config_admin');
	add_submenu_page('wpbusdirman.php', 'Manage Fees', 'Manage Fees', 'activate_plugins', 'wpbdman_c2', 'wpbusdirman_opsconfig_fees');
	add_submenu_page('wpbusdirman.php', 'Manage Fields', 'Manage Form Fields', 'activate_plugins', 'wpbdman_c3', 'wpbusdirman_buildform');
	add_submenu_page('wpbusdirman.php', 'Manage Featured', 'Manage Featured', 'activate_plugins', 'wpbdman_c4', 'wpbusdirman_featured_pending');
	add_submenu_page('wpbusdirman.php', 'Manage Payments', 'Manage Payments', 'activate_plugins', 'wpbdman_c5', 'wpbusdirman_manage_paid');

	add_submenu_page('wpbusdirman.php', 'Uninstall WPDB Manager', 'Uninstall', 'activate_plugins', 'wpbdman_m1', 'wpbusdirman_uninstall');
}

function wpbusdirman_admin_head()
{
	$html = '';

	$html .= "<div class=\"wrap\"><div id=\"icon-edit-pages\" class=\"icon32\"><br></div><h2>" . WPBUSDIRMAN . "</h2><div id=\"dashboard-widgets-wrap\"><div class=\"postbox\" style=\"padding:20px;width:90%;\">";

	return $html;
}


function wpbusdirman_admin_foot()
{
	$html = '';

	$html .= "</div></div></div>";

	return $html;
}

function wpbusdirman_retrieveoptions($whichoptions)
{
	$wpbusdirman_field_vals=array();
	global $table_prefix;

	$query="SELECT count(*) FROM {$table_prefix}options WHERE option_name LIKE '%".$whichoptions."%'";
	if (!($res=mysql_query($query)))
	{
		die(__(' Failure retrieving table data ['.$query.'].'));
	}
	while ($rsrow=mysql_fetch_row($res))
	{
		list($wpbusdirman_count_label)=$rsrow;
	}
	for ($i=0;$i<($wpbusdirman_count_label);$i++)
	{
		$wpbusdirman_field_vals[]=($i+1);
	}

	return $wpbusdirman_field_vals;
}

function wpbdm_get_post_data($data,$wpbdmlistingid)
{
		global $table_prefix;
		// Set field label values
		$query="SELECT $data FROM {$table_prefix}posts WHERE ID = '$wpbdmlistingid'";
		if (!($res=mysql_query($query))) {die(__(' Failure retrieving table data ['.$query.'].'));}
		while ($rsrow=mysql_fetch_row($res))
		{
			list($wpbusdirman_post_data)=$rsrow;
		}

	return $wpbusdirman_post_data;
}





// Manage Fees
function wpbusdirman_opsconfig_fees()
{

	global $wpbusdirman_settings_config_label_21,$wpbusdirman_imagesurl,$wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule,$wpbusdirman_labeltext,$wpbusdirman_amounttext,$wpbusdirman_actiontext,$wpbusdirman_appliedtotext,$wpbusdirman_allcatstext,$wpbusdirman_daytext,$wpbusdirman_daystext,$wpbusdirman_durationtext,$wpbusdirman_imagestext,$wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_action='';
	$hidenolistingfeemsg='';
	$hasnomodules='';
	$html = '';

	$html .= wpbusdirman_admin_head();
	$html .= "<h3 style=\"padding:10px;\">" . __("Manage Fees","WPBDM") . "</h3><p>";

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == 'no')
	{
		$html .= "<p>" . __("Payments are currently turned off. To manage fees you need to go to the Manage Options page and check the box next to 'Turn on payments' under 'General Payment Settings'","WPBDM") . "</p>";
	}
	else
	{
		$html .= "<p><b>" . __("Installed Payment Gateway Modules","WPBDM") . "</b><ul>";
		if($wpbusdirman_hasgooglecheckoutmodule == 1)
		{
			$html .= "<li style=\"background:url($wpbusdirman_imagesurl/check.png) no-repeat left center; padding-left:30px;\">" . __("Google Checkout","WPBDM") . "</li>";
		}
		if($wpbusdirman_haspaypalmodule == 1)
		{
			$html .= "<li style=\"background:url($wpbusdirman_imagesurl/check.png) no-repeat left center; padding-left:30px;\">" . __("PayPal","WPBDM") . "</li>";
		}
		if($wpbusdirman_hastwocheckoutmodule == 1)
		{
			$html .= "<li style=\"background:url($wpbusdirman_imagesurl/check.png) no-repeat left center; padding-left:30px;\">" . __("2Checkout","WPBDM") . "</li>";
		}
		$html .= "</ul></p>";
		if(!$wpbusdirman_haspaypalmodule && !$wpbusdirman_hastwocheckoutmodule && !$wpbusdirman_hasgooglecheckoutmodule)
		{
			$hasnomodules=1;
			$html .= "<p>" . __("It does not appear you have any of the payment gateway modules installed. You need to purchase a payment gateway module in order to charge a fee for listings. To purchase payment gateways use the buttons below or visit","WPBDM") . "</p>";
			$html .= "<p><a href=\"http://businessdirectoryplugin.com/about/payment-gateway-modules/\">http://businessdirectoryplugin.com/about/payment-gateway-modules/</a></p>";
		}
		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_25'] != "yes")
		{
			if($wpbusdirman_hastwocheckoutmodule != 1
				|| $wpbusdirman_haspaypalmodule != 1 )
			{
				$html .= '<div style="width:100%;padding:10px;">';
				if(!($wpbusdirman_haspaypalmodule == 1))
				{
					$html .= '<div style="float:left;width:30%;padding:10px;">' . __("You can buy the PayPal gateway module to add PayPal as a payment option for your users.","WPBDM") . '<span style="color:red;font-weight:bold;text-transform:uppercase;">' . __("$49.99","WPBDM") . '</span><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="YU3X22KHQ53P8" /><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" /><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></form></div>';
				}
				if(!($wpbusdirman_hastwocheckoutmodule == 1))
				{
					$html .= '<div style="float:left;width:30%;padding:10px;">' . __("You can buy the 2Checkout gateway module to add 2Checkout as a payment option for your users.","WPBDM") . '<span style="color:red;font-weight:bold;text-transform:uppercase;">' . __("$49.99","WPBDM") . '</span><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick" /><input type="hidden" name="hosted_button_id" value="U6T9ZMBB3HWDL" /><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" /><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></form></div>';
				}
				if($wpbusdirman_hastwocheckoutmodule
					!= 1 && $wpbusdirman_haspaypalmodule != 1 )
				{
					$html .= '<div style="float:left;width:30%;padding:10px;"><span style="color:red;font-weight:bold;text-transform:uppercase;">' . __("Save $20","WPBDM") . '</span>' . __(" on your purchase of both the Paypal and the 2Checkout gateway modules","WPBDM") . '<span style="color:red;font-weight:bold;text-transform:uppercase;">' . __("$79.98","WPBDM") . '</span><form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick" /><input type="hidden" name="hosted_button_id" value="KG5MFBC2XAXKW" /><input type="image" src="https://www.paypal.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" /><img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" /></form></div>';
				}
				$html .= '</div><div style="clear:both;"></div>';
			}
		}
		$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_settings_fees_label_');
		if(!empty($wpbusdirman_field_vals))
		{
			$wpbusdirman_field_vals_max=max($wpbusdirman_field_vals);
		}
		else
		{
			$wpbusdirman_field_vals_max='';
		}
		if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
		{
			$wpbusdirman_action=$_REQUEST['action'];
		}
		if(($wpbusdirman_action == 'addnewfee') || ($wpbusdirman_action == 'editfee') )
		{
			$hidenolistingfeemsg=1;
			if(isset($_REQUEST['feeid']) && !empty($_REQUEST['feeid']))
			{
				$wpbusdirman_feeid=$_REQUEST['feeid'];
			}
			if(isset($wpbusdirman_feeid) && !empty($wpbusdirman_feeid))
			{
				$wpbusdirmansavedfeelabel=get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeeamount=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeeincrement=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeeimages=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeecategories=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirman_feeid);
				$whichfeeid="<input type=\"hidden\" name=\"whichfeeid\" value=\"$wpbusdirman_feeid\" />";
				$wpbusdirmanfeeadoredit="<input type=\"hidden\" name=\"wpbusdirmanfeeadoredit\" value=\"edit\" />";
			}
			else
			{
				$wpbusdirmansavedfeelabel='';
				$wpbusdirmansavedfeeamount='';
				$wpbusdirmansavedfeeincrement='';
				$wpbusdirmansavedfeeimages='';
				$wpbusdirmansavedfeecategories='';
				$whichfeeid='';
				$wpbusdirmanfeeadoredit='';
			}
			$html .= "<form method=\"post\"><p>" . __("Fee Label","WPBDM") . "<br />";
			$html .= "<input type=\"text\" name=\"wpbusdirman_fees_label\" style=\"width:50%;\" value=\"$wpbusdirmansavedfeelabel\" />";
			$html .= "</p><p>" . __("Fee Amount","WPBDM") . "<br />";
			$html .= "<input type=\"text\" name=\"wpbusdirman_fees_amount\" style=\"width:10%;\" value=\"$wpbusdirmansavedfeeamount\" />";
			$html .= "</p><p>" . __("Listing Run in days","WPBDM") . "<br />";
			$html .= "<input type=\"text\" name=\"wpbusdirman_fees_increment\" value=\"$wpbusdirmansavedfeeincrement\" style=\"width:10%;\" />";
			$html .= "</p><p>" . __("Number of Images Allowed","WPBDM") . "<br />";
			$html .= "<input type=\"text\" name=\"wpbusdirman_fees_images\" value=\"$wpbusdirmansavedfeeimages\" style=\"width:10%;\" />";
			$html .= "</p><p>" . __("Apply to Category","WPBDM") . "<br />";
			$html .= "<select name=\"wpbusdirman_fees_categories[]\" multiple=\"multiple\" style=\"width:25%;height:80px;\">";
			$html .= "<option value=\"0\">$wpbusdirman_allcatstext</option>";
			$html .= wpbusdirman_my_fee_cats();
			$html .= "</select></p>" . $whichfeeid . $wpbusdirmanfeeadoredit;
			$html .= "<input type=\"hidden\" name=\"action\" value=\"updateoptions\" />";
			$html .= "<input name=\"updateoptions\" type=\"submit\" value=\"";
			if(isset($wpbusdirman_feeid) && !empty($wpbusdirman_feeid))
			{
				$html .= __("Update Fee","WPBDM");
			}
			else
			{
				$html .= __("Add Fee","WPBDM");
			}
			$html .= "\" /></form>";
		}
		elseif($wpbusdirman_action == 'deletefee')
		{
			if(isset($_REQUEST['feeid']) && !empty($_REQUEST['feeid']))
			{
				$whichfeeid=$_REQUEST['feeid'];
				delete_option( 'wpbusdirman_settings_fees_label_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_amount_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_increment_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_images_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_categories_'.$whichfeeid);
			}
			else
			{
				$html .= "<p>" . __("Unable to determine the ID of the fee you are trying to delete. Action terminated","WPBDM") . "</p>";
			}
		}
		elseif($wpbusdirman_action == 'updateoptions')
		{
			if(isset($_REQUEST['whichfeeid']) && !empty($_REQUEST['whichfeeid']))
			{
				$whichfeeid=$_REQUEST['whichfeeid'];
			}
			$hidenolistingfeemsg=1;
			if(isset($whichfeeid) && !empty($whichfeeid))
			{
				$wpbusdirman_add_update_option="update_option";
			}
			else
			{
				$whichfeeid=($wpbusdirman_field_vals_max+1);
				$wpbusdirman_add_update_option="add_option";
			}
			$wpbusdirman_fees_categories=$_REQUEST['wpbusdirman_fees_categories'];
			$wpbusdirman_last = end($wpbusdirman_fees_categories);
			$wpbusdirmanfeecatids='';
			if(in_array(0,$wpbusdirman_fees_categories))
			{
				$wpbusdirmanfeecatids.=0;
			}
			else
			{
				if (count($wpbusdirman_fees_categories) > 0)
				{
					// loop through the array
					for ($i=0;$i<count($wpbusdirman_fees_categories);$i++)
					{
						$wpbusdirmanfeecatids.="$wpbusdirman_fees_categories[$i]";
						if(!($wpbusdirman_fees_categories[$i] == $wpbusdirman_last))
						{
							$wpbusdirmanfeecatids.=",";
						}
					}
				}
			}
			$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_label_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_label']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_amount_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_amount']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_increment_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_increment']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_images_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_images']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_categories_'.$whichfeeid, $wpbusdirmanfeecatids  );
			$html .= "<p>" . __("Task completed successfully","WPBDM") . "</p>";
			$html .= "<p><a href=\"?page=wpbdman_c2\">" . __("View current listing fees","WPBDM") . "</a></p>";
		}
		if(!empty($wpbusdirman_field_vals) && (!$hidenolistingfeemsg))
		{
			$html .= "<p><a href=\"?page=wpbdman_c2&action=addnewfee\">" . __("Add New Listing Fee","WPBDM") . "</a></p>";
			$html .= "<table class=\"widefat\" cellspacing=\"0\"><thead><tr><th scope=\"col\" class=\"manage-column\">";
			$html .= $wpbusdirman_labeltext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_amounttext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_durationtext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_imagestext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_appliedtotext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
			$html .= "</tr></thead><tfoot><tr>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_labeltext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_amounttext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_durationtext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_imagestext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_appliedtotext . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
			$html .= "</tr></tfoot><tbody>";

			if($wpbusdirman_field_vals)
			{
				foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
				{
					$html .= "<tr><td>".get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_field_val)."</td>";
					$html .= "<td>".get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_field_val)."</td>";
					$html .= "<td>".get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_field_val);
					if(get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_field_val) == 1)
					{
						$html .= " " . $wpbusdirman_daytext;
					}
					else
					{
						$html .= " " . $wpbusdirman_daystext;
					}
					$html .= "</td><td>".get_option('wpbusdirman_settings_fees_images_'.$wpbusdirman_field_val)."</td><td>";
					$wpbusdirman_sfeecats=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirman_field_val);
					$wpbusdirmansfeecats=explode(",",$wpbusdirman_sfeecats);
					$wpbusdirman_sfeecatitems=array();
					for ($i=0;isset($wpbusdirmansfeecats[$i]);++$i)
					{
						$wpbusdirman_sfeecatitems[]=$wpbusdirmansfeecats[$i];
					}
					if(in_array('0',$wpbusdirman_sfeecatitems))
					{
							$wpbusdirman_thecat_nameall=$wpbusdirman_allcatstext;
					}
					else
					{
						$wpbusdirman_thecat_nameall='';
					}
					if(!(strcasecmp($wpbusdirman_thecat_nameall, $wpbusdirman_allcatstext) == 0))
					{
						$wpbusdirman_myfeecats=array();
						if($wpbusdirman_sfeecatitems)
						{
							foreach ($wpbusdirman_sfeecatitems as $wpbusdirman_sfeecatitem)
							{
								$wpbusdirman_thecat_name=&get_term( $wpbusdirman_sfeecatitem, $wpbdmposttypecategory, '', '' );
								if(!empty($wpbusdirman_thecat_name))
								{
									$wpbusdirman_myfeecats[]=$wpbusdirman_thecat_name->name;
								}
							}
						}
						$wpbusdirman_myfeecat_names = implode(',',$wpbusdirman_myfeecats);
						$html .= $wpbusdirman_myfeecat_names;
					}
					else
					{
						$html .= " " . $wpbusdirman_thecat_nameall;
					}
					$html .= "</td><td><a href=\"?page=wpbdman_c2&action=editfee&feeid=$wpbusdirman_field_val\">" . __("Edit","WPBDM") . "</a> | <a href=\"?page=wpbdman_c2&action=deletefee&feeid=$wpbusdirman_field_val\">" . __("Delete","WPBDM") . "</a></td></tr>";
				}
			}
			$html .= "</tbody></table>";
		}
		else
		{
			if(!$hidenolistingfeemsg)
			{
				if(!$hasnomodules)
				{
					$html .= "<p>" . __("You do not have any listing fees setup yet.","WPBDM") . "</p><p><a href=\"?page=wpbdman_c2&action=addnewfee\">" . __("Add New Listing Fee","WPBDM") . "</a></p>";
				}
			}
		}
	}
	$html .= wpbusdirman_admin_foot();

	echo $html;
}

function wpbusdirman_my_fee_cats()
{
	global $wpbdmposttypecategory;

	$wpbusdirman_my_fee_cats='';
	$wpbusdirman_feecatitems=array();

			$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

			if($wpbusdirman_myterms)
			{
				foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
				{
					$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
				}
			}

			$wpbusdirman_feecats=array();
			$wpbusdirman_feecats=get_option('wpbusdirman_settings_fees_categories');

			if(isset($wpbusdirman_feecats) && !empty($wpbusdirman_feecats))
			{
				$wpbusdirmanfeecats=explode(",",$wpbusdirman_feecats);


				for ($i=0;isset($wpbusdirmanfeecats[$i]);++$i)
				{
					$wpbusdirman_feecatitems[]=$wpbusdirmanfeecats[$i];
				}
			}

			if($wpbusdirman_postcatitems)
			{
				foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
				{
					if(in_array($wpbusdirman_postcatitem,$wpbusdirman_feecatitems)){$wpbusdirman_theselcat="selected";}else{ $wpbusdirman_theselcat='';}

					$wpbusdirman_my_fee_cats.="<option value=\"";
					$wpbusdirman_my_fee_cats.=$wpbusdirman_postcatitem;
					$wpbusdirman_my_fee_cats.="\" $wpbusdirman_theselcat>";
					$wpbdmtname=&get_term( $wpbusdirman_postcatitem, $wpbdmposttypecategory, '', '' );

					$wpbusdirman_my_fee_cats.=$wpbdmtname->name;



					$wpbusdirman_my_fee_cats.="</option>";
				}
			}

	return	$wpbusdirman_my_fee_cats;
}

function wpbusdirman_gpid()
{
	global $wpdb;

	$wpbusdirman_pageid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_content LIKE '%[WPBUSDIRMANUI]%' AND post_status='publish' AND post_type='page'");

	return $wpbusdirman_pageid;
}

function wpbusdirman_home_screen()
{
	global $wpbusdirman_db_version,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_haspaypalmodule,$wpbusdirman_hasgooglecheckoutmodule;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$listyle="style=\"width:auto;float:left;margin-right:5px;\"";
	$listyle2="style=\"width:200px;float:left;margin-right:5px;\"";
	$html = '';

	$html .= wpbusdirman_admin_head();
	$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');
	if($wpbusdirman_myterms)
	{
		foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
		{
			$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
		}
	}
	if(!empty($wpbusdirman_postcatitems))
	{
		foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
		{
			$wpbusdirman_tlincat=&get_term( $wpbusdirman_postcatitem, $wpbdmposttypecategory, '', '' );
			$wpbusdirman_totallistingsincat[]=$wpbusdirman_tlincat->count;
		}
		$wpbusdirman_totallistings=array_sum($wpbusdirman_totallistingsincat);
		$wpbusdirman_totalcatsindir=count($wpbusdirman_postcatitems);
	}
	else
	{
		$wpbusdirman_totallistings=0;
		$wpbusdirman_totalcatsindir=0;
	}
	$html .= "<h3 style=\"padding:10px;\">" . __("Options Menu","WPBDM") . "</h3><p>" . __("You are using version","WPBDM") . " <b>$wpbusdirman_db_version</b> </p>";
if( $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_40'] == "yes"
		&& $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_41'] == "yes"
			&& $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_43'] == "yes"
				&& $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes" )
						{
							$html .= "<p style=\"padding:10px;background:#ff0000;color:#ffffff;font-weight:bold;\">";
							$html.=__("You have payments turned on but all your gateways are set to hidden. Your system will run as if payments are turned off until you fix the problem. To fix go to Configure/Manage options and unhide at least 1 payment gateway, or if it is your intention not to charge a payment fee set payments to off instead of on.","WPBDM");
							$html.="</p>";
						}
	$html .= "<ul><li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c1\">" . __("Configure/Manage Options","WPBDM") . "</a></li>";
	$html .= "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c2\">" . __("Setup/Manage Fees","WPBDM") . "</a></li>";
	$html .= "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3\">" . __("Setup/Manage Form Fields","WPBDM") . "</a></li>";
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_31'] == "yes")
	{
		$html .= "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c4\">" . __("Featured Listings Pending Upgrade","WPBDM") . "</a></li>";
	}
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
	{
		$html .= "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c5\">" . __("Manage Paid Listings","WPBDM") . "</a></li>";
	}
	$html .= "</ul><br /><div style=\"clear:both;\"></div><ul>";
	$html .= "<li $listyle2>" . __("Listings in directory","WPBDM") . ": (<b>$wpbusdirman_totallistings</b>)</li>";
	$html .= "<li $listyle2>" . __("Categories In Directory","WPBDM") . ": (<b>$wpbusdirman_totalcatsindir</b>)</li></ul><div style=\"clear:both;\"></div>";
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_26'] == "yes")
	{
		$html .= "<h4>" . __("Tips for Use and other information","WPBDM") . "</h4>";
		$html .= "<ol>";
		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
		{
			$html .= "<li>" . __("Leave default post status set to pending to avoid misuse","WPBDM") . "<br />" . __("Listing payment status is not automatically updated after payment has been made. For this reason it is best to leave the listing default post status set to pending so you can verify that a listing has been paid for before it gets publised.","WPBDM") . "</li>";
			$html .= "<li>" . __("Valid Merchant ID and sandbox seller ID required for Google checkout payment processing ","WPBDM") . "</li>";
		}
		$html .= "<li>" . __("The plugin uses it's own page template to display single posts and category listings. You can modify the templates to make them match your site by editing the template files in the posttemplates folder which you will find inside the plugin folder. ","WPBDM") . "</li>";
		$html .= "<li>" . __("To protect user privacy Email addresses are not displayed in listings. ","WPBDM") . "</li>";
		$html .= "<li>" . __("reCaptcha human verification is built into the plugin contact form but comes turned off by default. To use it you need to turn it on. You also need to have a recaptcha public and private key. To obtain these visit recaptcha.net then enter the keys into he related boxes from the manage options page. ","WPBDM") . "</li>";
		$html .= "<li>" . __("You can hide these tips by going to Configure/Manage Options and checking the box next to 'Hide tips for use and other information'","WPBDM") .  "</li></ol>";
	}
	$html .= wpbusdirman_admin_foot();

	echo $html;
}

function wpbusdirman_display_postform_preview()
{
	$html = '';

	$html .= "<h3 style=\"padding:10px;\">" . __("Previewing the post form","WPBDM") . "</h3>";
	$html .= "<div style=\"float:right; margin-top:-49px;margin-right:250px;border-left:1px solid#ffffff;padding:10px;\"><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3\">" . __("Manage Form Fields","WPBDM") . "</a></div>";
	$html .= apply_filters('wpbdm_show-add-listing-form', '-1', '', '', '');

	return $html;
}

function wpbusdirman_display_postform_add()
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_3'] == "yes")
	{
		if(!is_user_logged_in())
		{
			$wpbusdirman_loginurl=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_4'];
			if(!isset($wpbusdirman_loginurl) || empty($wpbusdirman_loginurl))
			{
				$wpbusdirman_loginurl=get_option('siteurl').'/wp-login.php';
			}
			$html .= "<p>" . __("You are not currently logged in. Please login or register first. When registering, you will receive an activation email. Be sure to check your spam if you don't see it in your email with 60 mintues.","WPBDM") . "</p>";
			$html .= "<form method=\"post\" action=\"$wpbusdirman_loginurl\"><input type=\"submit\" class=\"insubmitbutton\" value=\"" . __("Login or Register First","WPBDM") . "\"></form>";
		}
		else
		{
			$html .= "<h3 style=\"padding:10px;\">" . __("Add New Listing","WPBDM") . "</h3>";
			$html .= "<div style=\"float:right; margin-top:-49px;margin-right:250px;border-left:1px solid#ffffff;padding:10px;\"><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3\">" . __("Manage Form Fields","WPBDM") . "</a></div><p>";
			$html .= apply_filters('wpbdm_show-add-listing-form', 1, '', '', '');
		}
	}
	else
	{
		$html .= apply_filters('wpbdm_show-add-listing-form', 1, '', '', '');
	}

	return $html;
}

function wpbusdirman_buildform()
{
	global $table_prefix;
	$wpbusdirman_error=false;
	$wpbusdirman_notify='';
	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
	$wpbusdirman_field_vals_max=max($wpbusdirman_field_vals);
	$wpbusdirman_autoincrementfieldorder=0;
	$wpbusdirman_error_message='';
	$wpbusdirmanaction='';
	$html = '';

	$html .= wpbusdirman_admin_head();
	if(isset($_REQUEST['action'])
		&& !empty($_REQUEST['action']))
	{
		$wpbusdirmanaction=$_REQUEST['action'];
	}
	if ( $wpbusdirmanaction == 'viewpostform')
	{
		$html .= wpbusdirman_display_postform_preview();
	}
	elseif ( $wpbusdirmanaction == 'addnewlisting')
	{
		$html .= wpbusdirman_display_postform_add();
	}
	elseif ( $wpbusdirmanaction == 'updateoptions')
	{
		$whichtext=$_REQUEST['whichtext'];
		if(isset($whichtext) && !empty($whichtext))
		{
			$wpbusdirman_add_update_option="update_option";
		}
		else
		{
			$wpbdmmissing=array();
			foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
			{
				$wpbdm_thefieldlabel=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
				$wpbdm_thefieldassociation=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
				$wpbdm_thefieldtype=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);

				if(!$wpbdm_thefieldlabel && !$wpbdm_thefieldassociation && !$wpbdm_thefieldtype){
				$wpbdmmissing[]=$wpbusdirman_field_val;
				}
			}

			if($wpbdmmissing){$whichtext=$wpbdmmissing[0];$wpbusdirman_autoincrementfieldorder=0;}else {$whichtext=($wpbusdirman_field_vals_max+1);$wpbusdirman_autoincrementfieldorder=1;}
			$wpbusdirman_add_update_option="add_option";
		}
		if(!isset($_REQUEST['wpbusdirman_field_label'])
			|| empty($_REQUEST['wpbusdirman_field_label']))
		{
				$wpbusdirman_error=true;
				$wpbusdirman_error_message.="<li>";
				$wpbusdirman_error_message.=__("Field NOT added! You have submitted the form without a field label. A field label is required before the field can be added. Please try adding the field again.","WPBDM");
				$wpbusdirman_error_message.="</li>";
		}
		else
		{
			if(!isset($_REQUEST['wpbusdirman_field_association'])
				|| empty($_REQUEST['wpbusdirman_field_association']))
			{
				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, "meta"  );
			}
			elseif(isset($_REQUEST['wpbusdirman_field_association']) && !empty($_REQUEST['wpbusdirman_field_association']) )
			{
				if( $_REQUEST['wpbusdirman_field_association'] != 'meta')
				{
					if(wpbusdirman_exists_association($_REQUEST['wpbusdirman_field_association'],$_REQUEST['wpbusdirman_field_label']))
					{
						$wpbusdirman_error=true;
						$wpbusdirman_error_message.="<li>";
						$wpbusdirman_error_message.=__("You tried to associate a field with a wordpress post title, category, tag, description, excerpt but another field is already associated with the element. The field has been associated with the post meta entity instead.","WPBDM");
						$wpbusdirman_error_message.="</li>";
						$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, "meta"  );
					}
					else
					{
						$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, $_REQUEST['wpbusdirman_field_association']  );
					}
				}
				else
				{
					$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, $_REQUEST['wpbusdirman_field_association']  );
				}
			}
			if(!isset($_REQUEST['wpbusdirman_field_required'])
				|| empty($_REQUEST['wpbusdirman_field_required']))
			{
				$_REQUEST['wpbusdirman_field_required']="no";
			}
			if(!isset($_REQUEST['wpbusdirman_field_showinexcerpt'])
				|| empty($_REQUEST['wpbusdirman_field_showinexcerpt']))
			{
				$_REQUEST['wpbusdirman_field_showinexcerpt']="no";
			}
			if( $_REQUEST['wpbusdirman_field_association'] == 'category')
			{
				if( $_REQUEST['wpbusdirman_field_type'] == 1
					||  $_REQUEST['wpbusdirman_field_type'] == 3
					||  $_REQUEST['wpbusdirman_field_type'] == 4
					||  $_REQUEST['wpbusdirman_field_type'] == 5 )
				{
					$wpbusdirman_error=true;
					$wpbusdirman_error_message.="<li>";
					$wpbusdirman_error_message.=__("The category field can only be assigned to the single option dropdown select list or checkbox type. It has been defaulted to a select list. If you want the user to be able to select multiple categories use the checkbox field type.","WPBDM");
					$wpbusdirman_error_message.="</li>";
					$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_type_'.$whichtext, "2"  );
				}
				else
				{
					$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_type_'.$whichtext, $_REQUEST['wpbusdirman_field_type']  );
				}
			}
			if($_REQUEST['wpbusdirman_field_validation'] == 'email')
			{
				if(!wpbusdirman_exists_validation($validation='email'))
				{
					$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_validation_'.$whichtext, $_REQUEST['wpbusdirman_field_validation']  );
				}
				else
				{
					$wpbusdirman_error=true;
					$wpbusdirman_error_message.="<li>";
					$wpbusdirman_error_message.=__("You already have a field using the email validation. At this time the system will allow only 1 valid email field. Change the validation for that field to something else then try again.","WPBDM");
					$wpbusdirman_error_message.="</li>";
				}
			}
			$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_label_'.$whichtext, $_REQUEST['wpbusdirman_field_label']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_type_'.$whichtext, $_REQUEST['wpbusdirman_field_type']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_options_'.$whichtext, $_REQUEST['wpbusdirman_field_options']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_required_'.$whichtext, $_REQUEST['wpbusdirman_field_required']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_showinexcerpt_'.$whichtext, $_REQUEST['wpbusdirman_field_showinexcerpt']  );
			/* New option added by Mike Bronner */ $wpbusdirman_add_update_option( 'wpbusdirman_postform_field_hide_'.$whichtext, $_REQUEST['wpbusdirman_field_hide']  );
			$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_validation_'.$whichtext, $_REQUEST['wpbusdirman_field_validation']  );

		}
		$html .= wpbusdirman_fields_list();
		if($wpbusdirman_error)
		{
			$wpbusdirman_notify="<div class=\"updated fade\" style=\"padding:10px;background:#FF8484;font-weight:bold;\"><ul>";
			$wpbusdirman_notify.=$wpbusdirman_error_message;
			$wpbusdirman_notify.="</ul></div>";
			$html .= $wpbusdirman_notify;
		}
	}
	elseif (($wpbusdirmanaction == 'deletefield'))
	{
		if (isset($_REQUEST['id'])
			&& !empty($_REQUEST['id']))
		{
			$wpbusdirman_fieldid_todel=$_REQUEST['id'];
			if(get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_label_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_type_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_options_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_association_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_required_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_fieldid_todel);}
			/* New option added by Mike Bronner */if(get_option('wpbusdirman_postform_field_hide_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_hide_'.$wpbusdirman_fieldid_todel);}
			$wpbusdirman_delete_message=__("The field has been deleted.","WPBDM");
		}
		else
		{
			$wpbusdirman_delete_message=__("There was no ID supplied for the field. No action has been taken","WPBDM");
		}
		$wpbusdirman_notify="<div class=\"updated fade\" style=\"padding:10px;\"><ul>";
		$wpbusdirman_notify.=$wpbusdirman_delete_message;
		$wpbusdirman_notify.="</ul></div>";
		$html .= $wpbusdirman_notify;
		$html .= wpbusdirman_fields_list();
	}
	elseif(($wpbusdirmanaction == 'addnewfield')
		|| ($wpbusdirmanaction == 'editfield'))
	{
		$wpbusdirman_fieldtoedit='';
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbusdirman_fieldtoedit=$_REQUEST['id'];
		}
		if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{
			$html .= "<p>" . __("Make your changes then submit the form to update the field","WPBDM") . "<p><a href=\"?page=wpbdman_c3&action=addnewfield\">" . __("Add New Form Field","WPBDM") . "</a></p>";
		}
		else
		{
			$html .= "<p>" . __("Add extra fields to the standard fields used in the form that users will fill out to submit their business directory listing.","WPBDM") . "</p>";
		}
		$html .= "<h3 style=\"padding:10px;\">";
		if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{
			$html .= __("Edit Field","WPBDM");
		}
		else
		{
			$html .= __("Add New Field","WPBDM");
		}
		$html .= "</h3>";
		$wpbusdirman_currenttype=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentassociation=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentvalidation=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentrequired=get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentshowinexcerpt=get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_fieldtoedit);
		/* New option added by Mike Bronner */ $wpbusdirman_currenthide = get_option('wpbusdirman_postform_field_hide_'.$wpbusdirman_fieldtoedit);
		if($wpbusdirman_currentvalidation == 'email')
		{
			$wpbusdirman_validation1="selected";
		}
		else
		{
			$wpbusdirman_validation1="";
		}
		if($wpbusdirman_currentvalidation == 'url')
		{
			$wpbusdirman_validation2="selected";
		}
		else
		{
			$wpbusdirman_validation2="";
		}
		if($wpbusdirman_currentvalidation == 'missing')
		{
			$wpbusdirman_validation3="selected";
		}
		else
		{
			$wpbusdirman_validation3="";
		}
		if($wpbusdirman_currentvalidation == 'numericdeci')
		{
			$wpbusdirman_validation4="selected";
		}
		else
		{
			$wpbusdirman_validation4="";
		}
		if($wpbusdirman_currentvalidation == 'numericwhole')
		{
			$wpbusdirman_validation5="selected";
		}
		else
		{
			$wpbusdirman_validation5="";
		}
		if($wpbusdirman_currentvalidation == 'date')
		{
			$wpbusdirman_validation6="selected";
		}
		else
		{
			$wpbusdirman_validation6="";
		}
		if($wpbusdirman_currentassociation == 'title')
		{
			$wpbusdirman_associationselected1="selected";
		}
		else
		{
			$wpbusdirman_associationselected1="";
		}
		if($wpbusdirman_currentassociation == 'description')
		{
			$wpbusdirman_associationselected2="selected";
		}
		else
		{
			$wpbusdirman_associationselected2="";
		}
		if($wpbusdirman_currentassociation == 'category')
		{
			$wpbusdirman_associationselected3="selected";
		}
		else
		{
			$wpbusdirman_associationselected3="";
		}
		if($wpbusdirman_currentassociation == 'excerpt')
		{
			$wpbusdirman_associationselected4="selected";
		}
		else
		{
			$wpbusdirman_associationselected4="";
		}
		if($wpbusdirman_currentassociation == 'meta')
		{
			$wpbusdirman_associationselected5="selected";
		}
		else
		{
			$wpbusdirman_associationselected5="";
		}
		if($wpbusdirman_currentassociation == 'tags')
		{
			$wpbusdirman_associationselected6="selected";
		}
		else
		{
			$wpbusdirman_associationselected6="";
		}

		if($wpbusdirman_currenttype == 1)
		{
			$wpbusdirman_op_selected1="selected";
		}
		else
		{
			$wpbusdirman_op_selected1='';
		}
		if($wpbusdirman_currenttype == 2)
		{
			$wpbusdirman_op_selected2="selected";
		}
		else
		{
			$wpbusdirman_op_selected2='';
		}
		if($wpbusdirman_currenttype == 3)
		{
			$wpbusdirman_op_selected3="selected";
		}
		else
		{
			$wpbusdirman_op_selected3='';
		}
		if($wpbusdirman_currenttype == 4)
		{
			$wpbusdirman_op_selected4="selected";
		}
		else
		{
			$wpbusdirman_op_selected4='';
		}
		if($wpbusdirman_currenttype == 5)
		{
			$wpbusdirman_op_selected5="selected";
		}
		else
		{
			$wpbusdirman_op_selected5='';
		}
		if($wpbusdirman_currenttype == 6)
		{
			$wpbusdirman_op_selected6="selected";
		}
		else
		{
			$wpbusdirman_op_selected6='';
		}
		if($wpbusdirman_currentrequired == 'yes')
		{
			$wpbusdirman_required_selected1="selected";
		}
		else
		{
			$wpbusdirman_required_selected1='';
		}
		if($wpbusdirman_currentrequired == 'no')
		{
			$wpbusdirman_required_selected2="selected";
		}
		else
		{
			$wpbusdirman_required_selected2='';
		}
		if($wpbusdirman_currentshowinexcerpt == 'yes')
		{
			$wpbusdirman_showinexcerpt_selected1="selected";
		}
		else
		{
			$wpbusdirman_showinexcerpt_selected1='';
		}
		if($wpbusdirman_currentshowinexcerpt == 'no')
		{
			$wpbusdirman_showinexcerpt_selected2="selected";
		}
		else
		{
			$wpbusdirman_showinexcerpt_selected2='';
		}

		$wpbusdirman_hide_selected1 = '';
		$wpbusdirman_hide_selected2 = '';
		if($wpbusdirman_currenthide == 'no')
		{
			$wpbusdirman_hide_selected1 = "selected=\"selected\"";
		}
		if($wpbusdirman_currenthide == 'yes')
		{
			$wpbusdirman_hide_selected2 = "selected=\"selected\"";
		}




		$html .= "<div style=\"float:right; margin-top:-49px;margin-right:250px;border-left:1px solid#ffffff;padding:10px;\"><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3&action=viewpostform\">" . __("Preview the form","WPBDM") . "</a></div>";
		$html .= "<form method=\"post\"><p>" . __("Field Label","WPBDM") . "<br />";
		$html .= "<input type=\"text\" name=\"wpbusdirman_field_label\" style=\"width:50%;\" value=\"" . get_option('wpbusdirman_postform_field_label_' . $wpbusdirman_fieldtoedit) . "\"></p>" . __("Field Type","") . " <select name=\"wpbusdirman_field_type\">";
		$html .= "<option value=\"\">" . __("Select Field Type","WPBDM") . "</option>";
		$html .= "<option value=\"1\" $wpbusdirman_op_selected1>" . __("Input Text Box","WPBDM") . "</option>";
		$html .= "<option value=\"2\" $wpbusdirman_op_selected2>" . __("Select List","WPBDM") . "</option>";
		$html .= "<option value=\"5\" $wpbusdirman_op_selected5>" . __("Multiple Select List","WPBDM") . "</option>";
		$html .= "<option value=\"4\" wpbusdirman_op_selected4>" . __("Radio Button","WPBDM") . "</option>";
		$html .= "<option value=\"6\" $wpbusdirman_op_selected6>" . __("Checkbox","WPBDM") . "</option>";
		$html .= "<option value=\"3\" $wpbusdirman_op_selected3>" . __("Textarea","WPBDM") . "</option>";
		$html .= "</select><p>" . __("Field Options","WPBDM") . " (" . __("for drop down lists, radio buttons, checkboxes ","WPBDM") . ") (" . __("separate by commas","WPBDM") . ")<br />" . __("**Do not fill in options for the Post category associated field","WPBDM") . "<input type=\"text\" name=\"wpbusdirman_field_options\" style=\"width:90%;\" value=\"" . get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_fieldtoedit) . "\">";
		$html .= "<p>" . __("Associate Field With","WPBDM") . " <select name=\"wpbusdirman_field_association\">";
		$html .= "<option value=\"\">" . __("Select Option","WPBDM") . "</option>";
		$html .= "<option value=\"title\" $wpbusdirman_associationselected1>" . __("Post Title","WPBDM") . "</option>";
		$html .= "<option value=\"description\" $wpbusdirman_associationselected2>" . __("Post Content","WPBDM") . "</option>";
		$html .= "<option value=\"category\" $wpbusdirman_associationselected3>" . __("Post Category","WPBDM") . "</option>";
		$html .= "<option value=\"excerpt\" $wpbusdirman_associationselected4>" . __("Post Excerpt","WPBDM") . "</option>";
		$html .= "<option value=\"meta\" $wpbusdirman_associationselected5>" . __("Post Meta","WPBDM") . "</option>";
		$html .= "<option value=\"tags\" $wpbusdirman_associationselected6>" . __("Post Tags","WPBDM") . "</option>";
		$html .= "</select></p><p>" . __("Validate Against","WPBDM") . " <select name=\"wpbusdirman_field_validation\">";
		$html .= "<option value=\"\">" . __("Select Option","WPBDM") . "</option>";
		$html .= "<option value=\"email\" $wpbusdirman_validation1>" . __("Email Format","WPBDM") . "</option>";
		$html .= "<option value=\"url\" $wpbusdirman_validation2>" . __("URL format","WPBDM") . "</option>";
		$html .= "<option value=\"missing\" $wpbusdirman_validation3>" . __("Missing Value","WPBDM") . "</option>";
		$html .= "<option value=\"numericwhole\" $wpbusdirman_validation4>" . __("Whole Number Value","WPBDM") . "</option>";
		$html .= "<option value=\"numericdeci\" $wpbusdirman_validation5>" . __("Decimal Value","WPBDM") . "</option>";
		$html .= "<option value=\"date\" $wpbusdirman_validation6>" . __("Date Format","WPBDM") . "</option>";
		$html .= "</select></p><p>" . __("Is Field Required?","WPBDM") . " <select name=\"wpbusdirman_field_required\">";
		$html .= "<option value=\"\">" . __("Select Option","WPBDM") . "</option>";
		$html .= "<option value=\"yes\" $wpbusdirman_required_selected1>" . __("Yes","WPBDM") . "</option>";
		$html .= "<option value=\"no\" $wpbusdirman_required_selected2>" . __("No","WPBDM") . "</option>";
		$html .= "</select></p><p>" . __("Show this value in post excerpt?","WPBDM") . " <select name=\"wpbusdirman_field_showinexcerpt\">";
		$html .= "<option value=\"\">" . __("Select Option","WPBDM") . "</option>";
		$html .= "<option value=\"yes\" $wpbusdirman_showinexcerpt_selected1>" . __("Yes","WPBDM") . "</option>";
		$html .= "<option value=\"no\" $wpbusdirman_showinexcerpt_selected2>" . __("No","WPBDM") . "</option>";
		$html .= "</select></p><p>" . __("Hide this field from public viewing?","WPBDM") . " <select name=\"wpbusdirman_field_hide\">";
		$html .= "<option value=\"no\" $wpbusdirman_hide_selected1>" . __("No","WPBDM") . "</option>";
		$html .= "<option value=\"yes\" $wpbusdirman_hide_selected2>" . __("Yes","WPBDM") . "</option>";
		$html .= "</select></p>";
		$html .= "<input type=\"hidden\" name=\"action\" value=\"updateoptions\" />";
		$html .= "<input type=\"hidden\" name=\"whichtext\" value=\"$wpbusdirman_fieldtoedit\" />";
		$html .= "<input name=\"updateoptions\" type=\"submit\" value=\"";
		if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{
			$html .= __("Update Field","WPBDM");
		}
		else
		{
			$html .= __("Add New Field","WPBDM");
		}
		$html .= "\" /></form>";

	}
	elseif($wpbusdirmanaction == 'post')
	{
		$html .= apply_filters('wpbdm_process-form-post', null);
	}
	else
	{
		$html .=wpbusdirman_fields_list();
	}
	$html .= wpbusdirman_admin_foot();

	echo $html;
}

function wpbusdirman_exists_association($association,$label)
{

	$wpbusdirman_exists_association=false;

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_association_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
		{

			if(get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val) == $association)
			{
				$wpbdmassocid=$wpbusdirman_field_val;
				$wpbusdirman_ftitle=get_option('wpbusdirman_postform_field_label_'.$wpbdmassocid);


				//If the field label value is the same as the association value then return false
				if($wpbusdirman_ftitle == $label)
				{
					$wpbusdirman_exists_association=false;
				}
				else
				{
					//Otherwise return true
					$wpbusdirman_exists_association=true;
				}
			}
		}
	}

	return $wpbusdirman_exists_association;
}

function wpbusdirman_exists_validation($validation)
{

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_validation_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
		{

			if(get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val) == $validation)
			{
				$wpbusdirman_exists_validation=true;
			}
			else
			{
				$wpbusdirman_exists_validation=false;
			}

		}
	}

	return $wpbusdirman_exists_validation;
}


		function wpbusdirman_generatePassword($length=6,$level=2)
		{

		   list($usec, $sec) = explode(' ', microtime());
		   srand((float) $sec + ((float) $usec * 100000));

		   $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		   $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		   $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

		   $password  = "";
		   $counter   = 0;

		   while ($counter < $length)
		   {
			 $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

			 // All character must be different
			 if (!strstr($password, $actChar))
			 {
				$password .= $actChar;
				$counter++;
			 }
		   }

		   return $password;

		}

function wpbusdirman_filterinput($input) {
	$input = strip_tags($input);
	$input = trim($input);
	return $input;
}

function wpbusdirman_fields_list()
{
	global $wpbusdirman_hide_formlist, $wpbusdirman_labeltext, $wpbusdirman_typetext,$wpbusdirman_optionstext,$wpbusdirman_ordertext,$wpbusdirman_actiontext,$wpbusdirman_associationtext,$wpbusdirman_validationtext,$wpbusdirman_requiredtext,$wpbusdirman_showinexcerpttext;
	$html = '';

	if(!$wpbusdirman_hide_formlist)
	{
		$html .= "<h3 style=\"padding:10px;\">" . __("Manage Form Fields","WPBDM") . "</h3><p>" . __("Make changes to your existing form fields.","WPBDM") . "<p><a href=\"?page=wpbdman_c3&action=addnewfield\">" . __("Add New Form Field","WPBDM") . "</a> | <a href=\"?page=wpbdman_c3&action=viewpostform\">" . __("Preview Form","WPBDM") . "</a> | <a href=\"?page=wpbdman_c3&action=addnewlisting\">" . __("Add New Listing","WPBDM") . "</a></p>";
		$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
		$html .= "<table class=\"widefat\" cellspacing=\"0\"><thead><tr>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_labeltext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_typetext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_associationtext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_validationtext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_optionstext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_requiredtext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_showinexcerpttext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
		$html .= "</tr></thead><tfoot><tr>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_labeltext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_typetext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_associationtext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_validationtext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_optionstext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_requiredtext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_showinexcerpttext . "</th>";
		$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
		$html .= "</tr></tfoot><tbody>";
		if($wpbusdirman_field_vals)
		{
			foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
			{
				$wpbdm_thefieldlabel=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
				$wpbdm_thefieldassociation=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
				$wpbdm_thefieldrequired=get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_field_val);
				$wpbdm_thefieldshowinexcerpt=get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val);
				/* New option added by Mike Bronner */ $wpbdm_thefieldhide = get_option('wpbusdirman_postform_field_hide_'.$wpbusdirman_field_val);
				$wpbdm_thefieldtype=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);


				$html .= "<tr><td>".get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val)."</td><td>";
				$wpbusdirman_optypeval=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);
				switch ($wpbusdirman_optypeval)
				{
					case 1:
						$wpbusdirman_optype_descr="Text Box";
						break;
					case 2:
						$wpbusdirman_optype_descr="Select List";
						break;
					case 3:
						$wpbusdirman_optype_descr="Textarea";
						break;
					case 4:
						$wpbusdirman_optype_descr="Radio Button";
						break;
					case 5:
						$wpbusdirman_optype_descr="Multi-Select List";
						break;
					case 6:
						$wpbusdirman_optype_descr="Checkbox";
						break;
				}
				$html .= $wpbusdirman_optype_descr . "</td>";
				$html .= "<td>".get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val)."</td>";
				$html .= "<td>".get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val)."</td>";
				$html .= "<td>";
				$wpbusdirman_field_options=get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_field_val);
				$wpbusdirman_field_options_array=explode(",",$wpbusdirman_field_options);
				for ($i=0;isset($wpbusdirman_field_options_array[$i]);++$i)
				{
					$wpbusdirman_field_options_arritems[$i]=trim($wpbusdirman_field_options_array[$i]);
					$html .= "<ul><li>" . $wpbusdirman_field_options_array[$i] . "</li></ul>";
				}
				$html .= "</td><td>" . get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_field_val) . "</td>";
				$html .= "<td>".get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val)."</td>";
				$html .= "<td><a href=\"?page=wpbdman_c3&action=editfield&id=$wpbusdirman_field_val\">" . __("Edit","WPBDM") . "</a> | <a href=\"?page=wpbdman_c3&action=deletefield&id=$wpbusdirman_field_val\">" . __("Delete","WPBDM") . "</a></td></tr>";
			}
		}
		$html .= "</tbody></table>";
	}

	return $html;
}

function wpBusDirManUi_addListingForm()
{
	$wpbusdirmanaction = '';
	$html = '';

	if(isset($_REQUEST['action'])
		&& !empty($_REQUEST['action']))
	{
		$wpbusdirmanaction=$_REQUEST['action'];
	}
	elseif(isset($_REQUEST['do'])
		&& !empty ($_REQUEST['do']))
	{
		$wpbusdirmanaction=$_REQUEST['do'];
	}
	if ("post" == $wpbusdirmanaction)
	{
		$html .= apply_filters('wpbdm_process-form-post', null);
	}
	else
	{
		$html .= apply_filters('wpbdm_show-add-listing-form', 1, '', 'new', '');
	}

	return $html;
}

function wpbusdirman_displaypostform($makeactive = 1, $wpbusdirmanerrors = '', $neworedit = 'new', $wpbdmlistingid = '')
{
 	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory,$wpbdmposttypetags,$wpbdmposttype;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirmanselectedword="selected";
 	$wpbusdirmancheckedword="checked";
	$wpbusdirman_field_value='';
 	$args=array('hide_empty' => 0);
 	$wpbusdirman_postcats=get_terms( $wpbdmposttypecategory, $args);
 	$html = '';

 	if(!isset($wpbusdirman_postcats) || empty($wpbusdirman_postcats))
 	{
 		if(is_user_logged_in() && current_user_can('install_plugins'))
 		{
 			$html .= "<p>" . __("There are no categories assigned to the business directory yet. You need to assign some categories to the business directory. Only admins can see this message. Regular users are seeing a message that they cannot add their listing at this time. Listings cannot be added until you assign categories to the business directory.","WPBDM") . "</p>";
 		}
 		else
 		{
 			$html .= "<p>" . __("Your listing cannot be added at this time. Please try again later.","WPBDM") . "</p>";
 		}
 	}
 	else
	{
		if(($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_3'] == "yes") && !is_user_logged_in())
		{
			$wpbusdirman_loginurl=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_4'];
			if(!isset($wpbusdirman_loginurl) || empty($wpbusdirman_loginurl))
			{
				$wpbusdirman_loginurl=get_option('siteurl').'/wp-login.php';
			}
			$html .= "<p>" . __("You are not currently logged in. Please login or register first. When registering, you will receive an activation email. Be sure to check your spam if you don't see it in your email with 60 mintues.","WPBDM") . "</p>";
			$html .= "<form method=\"post\" action=\"$wpbusdirman_loginurl\"><input type=\"submit\" class=\"insubmitbutton\" value=\"" . __("Login Now","WPBDM") . "\"></form>";
		}
		else
		{
			$wpbusdirman_selectcattext=__("Choose One","WPBDM");
			$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
			global $wpbusdirman_gpid,$permalinkstructure;
			if(!isset($permalinkstructure)
				|| empty($permalinkstructure))
			{
				$querysymbol="&amp";
			}
			else
			{
				$querysymbol="?";
			}
			$html .= "<div><form method=\"post\" action=\"\"><input type=\"hidden\" name=\"action\" value=\"viewlistings\"><input type=\"submit\" class=\"viewlistingsbutton\" value=\"" . __("View Listings","WPBDM") . "\"></form>";
			$html .= "<form method=\"post\" action=\"\"><input type=\"submit\" class=\"viewlistingsbutton\" style=\"margin-right:10px;\" value=\"" . __("Directory","WPBDM") . "\"></form></div><div class=\"clear\"></div><form method=\"post\" action=\"\"
				 enctype=\"application/x-www-form-urlencoded\">";
			$html .= "<input type=\"hidden\" name=\"formmode\" value=\"$makeactive\" />";
			$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\" />";
			$html .= "<input type=\"hidden\" name=\"wpbdmlistingid\" value=\"$wpbdmlistingid\" />";
			$html .= "<input type=\"hidden\" name=\"action\" value=\"post\" />";
			if (isset($wpbusdirmanerrors)
				&& (!empty($wpbusdirmanerrors)))
			{
				$html .= "<ul id=\"wpbusdirmanerrors\">" . $wpbusdirmanerrors . "</ul>";
			}
			if($wpbusdirman_field_vals)
			{
				foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
				{
					$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
					$wpbusdirman_field_label_name=$wpbusdirman_field_label;
					$wpbusdirman_field_type=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);
					$wpbusdirman_field_options=get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_field_val);
					$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
					$class_required = '';
					if ("yes" == get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val))
					{
						$class_required = ' required';
					}
					switch ($wpbusdirman_field_association)
					{
						case 'title':
						case 'category':
						case 'excerpt':
						case 'description':
						case 'tags':
							$wpbusdirman_field_label_association = "_" . $wpbusdirman_field_association;
							break;
						default:
							$wpbusdirman_field_label_association = "_meta$wpbusdirman_field_val";
							break;
					}
					if(isset($wpbusdirmanerrors)
						&& !empty($wpbusdirmanerrors))
					{
						if($wpbusdirman_field_label_association == "_category")
						{
							if($wpbusdirman_field_type == 2){$wpbusdirman_field_value=$_REQUEST['cat'];}
							elseif($wpbusdirman_field_type == 6){$wpbusdirman_field_value=$_REQUEST['wpbusdirman_field_label_category'];}
						}
						else
						{
							$wpbusdirman_field_value=$_REQUEST['wpbusdirman_field_label'.$wpbusdirman_field_label_association];
						}
					}
					else
					{
						if(isset($wpbdmlistingid)
							&& !empty($wpbdmlistingid))
						{
							switch ($wpbusdirman_field_association)
							{
								case 'category':
									$wpbusdirman_field_value=array();
									$wpbusdirman_postvalues=get_the_terms($wpbdmlistingid, $wpbdmposttypecategory);


									if($wpbusdirman_postvalues)
									{
										foreach($wpbusdirman_postvalues as $wpbusdirman_postvalue)
										{
											$wpbusdirman_field_value[]=$wpbusdirman_postvalue->term_id;
										}

									}
									break;
								case 'title':
									$wpbusdirman_field_value=get_the_title($wpbdmlistingid);
									break;
								case 'description':
									$wpbusdirman_field_value=wpbdm_get_post_data($data='post_content',$wpbdmlistingid);
									break;
								case 'excerpt':
									$wpbusdirman_field_value=wpbdm_get_post_data($data='post_excerpt',$wpbdmlistingid);
									break;
								case 'tags':
									$wpbusdirman_field_value='';
									$wpbusdirmanfieldtagsarr=array();
									$wpbusdirmanfieldtagsobject=get_the_terms($wpbdmlistingid, $wpbdmposttypetags);
									if($wpbusdirmanfieldtagsobject)
									{
										foreach($wpbusdirmanfieldtagsobject as $wpbusdirmanfieldtags)
										{
										   $wpbusdirmantag=$wpbusdirmanfieldtags->slug;
										   $wpbusdirmanfieldtagsarr[]=$wpbusdirmantag;
										}
									}
									if($wpbusdirmanfieldtagsarr)
									{
										$wpbusdirman_last_field_tag=end($wpbusdirmanfieldtagsarr);
										foreach($wpbusdirmanfieldtagsarr as $wpbusdirman_field_tag)
										{
											$wpbusdirman_field_value.="$wpbusdirman_field_tag";
											if($wpbusdirman_last_field_tag != $wpbusdirman_field_tag )
											{
												$wpbusdirman_field_value.=",";
											}
										}
									}
									break;
								default:
									$wpbusdirman_field_value=get_post_meta($wpbdmlistingid, $wpbusdirman_field_label, $single = true);
									break;
							}
						}
					}
					switch ($wpbusdirman_field_type)
					{
						case 1:
							$html .= "<p class=\"wpbdmp\"><label for=\"wpbusdirman_field_label$wpbusdirman_field_label_association\">$wpbusdirman_field_label_name</label><br/>";
							$wpbusdirman_field_validation=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val);
							if($wpbusdirman_field_validation == 'date')
							{
								$html .= __("Format 01/31/1969","WPBDM");
							}
							$html .= "</p><input type=\"text\" id=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" class=\"intextbox" . $class_required . "\" value=\"$wpbusdirman_field_value\">";
							break;
						case 2:
							if($wpbusdirman_field_association == 'category')
							{
								if(is_array($wpbusdirman_field_value))
								{
									$wpbusdirman_field_value_selected=$wpbusdirman_field_value[0];
								}
								else
								{
									$wpbusdirman_field_value_selected=$wpbusdirman_field_value;
								}
								$html .= "<p class=\"wpbdmp\"><label for=\"cat\">$wpbusdirman_field_label_name</label></p>";
								$html .= wp_dropdown_categories(array('taxonomy' => $wpbdmposttypecategory, 'show_option_none' => $wpbusdirman_selectcattext, 'orderby' => 'name', 'selected' => $wpbusdirman_field_value_selected, 'order' => 'ASC', 'hide_empty' => 0, 'hierarchical' => 1, 'echo' => 0, 'class' => $class_required));
							}
							else
							{
								$html .= "<p class=\"wpbdmp\"><label for=\"wpbusdirman_field_label$wpbusdirman_field_label_association\">$wpbusdirman_field_label_name</label></p><select class=\"inselect" . $class_required . "\" id=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\">";
								$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
								$wpbusdirman_formselop=array();
								for ($i=0;isset($wpbusdirman_formselops[$i]);++$i)
								{
									$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
								}
								if($wpbusdirman_formselop)
								{
									foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
									{
										$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
										if($wpbusdirman_field_value == $wpbusdirman_formseloption)
										{
											$wpbusdirmanselected="selected";
										}
										else
										{
											$wpbusdirmanselected='';
										}
										$html .= "<option $wpbusdirmanselected value=\"$wpbusdirman_formseloption\" $wpbusdirmanselected>$wpbusdirman_formseloption</option>";
									}
								}
								$html .= "</select>";
							}
							break;
						case 3:
							$wpbusdirman_field_value=stripslashes($wpbusdirman_field_value);
							$html .= "<p class=\"wpbdmp\"><label for=\"wpbusdirman_field_label$wpbusdirman_field_label_association\">$wpbusdirman_field_label_name</label></p><textarea id=\"\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" class=\"intextarea" . $class_required . "\">$wpbusdirman_field_value</textarea>";
							break;
						case 4:
							$html .= "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p>";
							$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
							$wpbusdirman_formselop=array();
							for ($i=0;isset($wpbusdirman_formselops[$i]);++$i)
							{
								$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
							}
							if($wpbusdirman_formselop)
							{
								foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
								{
									$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
									if($wpbusdirman_formseloption == $wpbusdirman_field_value)
									{
										$wpbusdirmanchecked="checked";
									}
									else
									{
										$wpbusdirmanchecked='';
									}
									$html .= "<span style=\"padding-right:10px;\"><input type=\"radio\" class=\"" . $class_required . "\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" value=\"$wpbusdirman_formseloption\" $wpbusdirmanchecked />$wpbusdirman_formseloption</span>";
								}
							}
							break;
						case 5:
							$html .= "<p class=\"wpbdmp\"><label for=\"\">$wpbusdirman_field_label_name</label></p><select class=\"inselectmultiple" . $class_required . "\" id=\"wpbusdirman_field_label" . $wpbusdirman_field_label_association . "\" name=\"wpbusdirman_field_label".$wpbusdirman_field_label_association."[]\" multiple=\"multiple\">";
							$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
							$wpbusdirman_formselop=array();
							for ($i=0;isset($wpbusdirman_formselops[$i]);++$i)
							{
								$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
							}
							$wpbusdirmanmultivals=explode("\t",$wpbusdirman_field_value);
							$wpbusdirmanmultivalsarr=array();
							for ($a=0;isset($wpbusdirmanmultivals[$a]);++$a)
							{
								$wpbusdirmanmultivalsarr[]=trim($wpbusdirmanmultivals[$a]);
							}
							if($wpbusdirman_formselop)
							{
								foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
								{
									$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
									$html .= "<option ";
									if(in_array($wpbusdirman_formseloption,$wpbusdirmanmultivalsarr))
									{
										$html .= $wpbusdirmanselectedword;
									}
									$html .= "  value=\"$wpbusdirman_formseloption\">$wpbusdirman_formseloption</option>";
								}
							}
							$html .= "</select>";
							break;
						case 6:
							$html .= "<p class=\"wpbdmp\"><label for=\"wpbusdirman_field_label" . $wpbusdirman_field_label_association . "\">$wpbusdirman_field_label_name</label></p>";
							if($wpbusdirman_field_association == 'category')
							{
								$mywpbdmcatlist = get_terms($wpbdmposttypecategory);
								if($mywpbdmcatlist)
								{
									foreach($mywpbdmcatlist as $wpbusdirman_formseloption)
									{
										$mywpbdmcattermid=$wpbusdirman_formseloption->term_id;
										$mywpbdmcattermname=$wpbusdirman_formseloption->name;
										$html .= "<div id=\"wpbdmcheckboxclass\"><input type=\"checkbox\" class=\"" . $class_required . "\" id=\"wpbusdirman_field_label" . $wpbusdirman_field_label_association . "\" name=\"wpbusdirman_field_label".$wpbusdirman_field_label_association."[]\" value=\"$mywpbdmcattermid\"";

										if ( (is_array($wpbusdirman_field_value))
										&& (in_array($mywpbdmcattermid,$wpbusdirman_field_value)) )
										{
										$html .= $wpbusdirmancheckedword;
										}

										$html .= "/>" . $mywpbdmcattermname . "</div>";
									}
								}
							}
							else
							{
								$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
								$wpbusdirman_formselop=array();
								for ($i=0;isset($wpbusdirman_formselops[$i]);++$i)
								{
									$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
								}
								$wpbusdirmancboxvals=explode("\t",$wpbusdirman_field_value);
								$wpbusdirmanxboxvalsarr=array();
								for ($a=0;isset($wpbusdirmancboxvals[$a]);++$a)
								{
									$wpbusdirmanxboxvalsarr[]=trim($wpbusdirmancboxvals[$a]);
								}
								if($wpbusdirman_formselop)
								{
									foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
									{
										$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
										$html .= "<div id=\"wpbdmcheckboxclass\"><input type=\"checkbox\" class=\"" . $class_required . "\" name=\"wpbusdirman_field_label".$wpbusdirman_field_label_association."[]\" value=\"$wpbusdirman_formseloption\"";
										if(in_array($wpbusdirman_formseloption,$wpbusdirmanxboxvalsarr))
										{
											$html .= $wpbusdirmancheckedword;
										}
										$html .= "/>$wpbusdirman_formseloption</div>";
									}
								}

							}
							$html .= "<div style=\"clear:both;\"></div>";
							break;
					}
				}
			}
			$html .= "<p><input type=\"submit\" class=\"insubmitbutton\" value=\"" . __("Submit","WPBDM") . "\" /></p></form>";
		}
	}

	return $html;
}

function wpbusdirman_uninstall()
{
	global $message;
	$dirname="wpbdm";
	$html = '';

	if( isset($_REQUEST['action'])
		&& !empty($_REQUEST['action']) )
	{
		if($_REQUEST['action'] == 'wpbusdirman_d_install')
		{
			$html .= wpbusdirman_d_install();
		}
	}
	if( !isset($_REQUEST['action'])
		|| empty($_REQUEST['action']) )
	{
		$html .= wpbusdirman_admin_head();
		$html .= "<h3 style=\"padding:10px;\">" . __("Uninstall","WPBDM") . "</h3>";
		if(isset($message)
			&& !empty($message))
		{
			$html .= $message;
		}
		$html .= "<p>" . __("You have arrived at this page by clicking the Uninstall link. If you are certain you wish to uninstall the plugin, please click the link below to proceed. Please note that all your data related to the plugin, your ads, images and everything else created by the plugin will be destroyed","WPBDM") . "<p><b>" . __("Important Information","WPBDM") . "</b></p><blockquote><p>1." . __("If you want to keep your user uploaded images, please download the folder $dirname, which you will find inside your uploads directory, to your local drive for later use or rename the folder to something else so the uninstaller can bypass it","WPBDM") . "</p></blockquote>: <a href=\"?page=wpbdman_m1&action=wpbusdirman_d_install\">" . __("Proceed with Uninstalling WP Business Directory Manager Uninstall","WPBDM") . "</a>";
		$html .= wpbusdirman_admin_foot();
	}

	echo $html;
}

function wpbusdirman_d_install()
{
	global $wpdb,$wpbusdirman_plugin_path,$table_prefix,$wpbusdirman_plugin_dir,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix,$wpbdmposttype;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbdmdraftortrash=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_47'];
	$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');
	$html = '';


		$wpbusdirman_catcat=get_posts('post_type='.$wpbdmposttype);

		if($wpbusdirman_catcat)
		{
			foreach($wpbusdirman_catcat as $wpbusdirman_cat)
			{
				$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
			}
		}
		if($wpbusdirman_postsposts)
		{
			foreach($wpbusdirman_postsposts as $wpbusdirman_post)
			{
				$wpbusdirman_unints_postarr = array();
				$wpbusdirman_unints_postarr['ID'] = $wpbusdirman_post;
				$wpbusdirman_unints_postarr['post_type'] = $wpbdmposttype;
				$wpbusdirman_unints_postarr['post_status'] = $wpbdmdraftortrash;
				wp_update_post( $wpbusdirman_unints_postarr );
			}
		}

	$wpbusdirman_query="DELETE FROM $wpdb->options WHERE option_name LIKE '%wpbusdirman_%'";
	@mysql_query($wpbusdirman_query);
	wp_clear_scheduled_hook('wpbusdirman_listingexpirations_hook');
	$wpbdm_pluginfile=$wpbusdirman_plugin_dir."/wpbusdirman.php";
	$wpbusdirman_current = get_option('active_plugins');
	array_splice($wpbusdirman_current, array_search( $wpbdm_pluginfile, $wpbusdirman_current), 1 );
	update_option('active_plugins', $wpbusdirman_current);
	do_action('deactivate_' . $wpbdm_pluginfile );
	$html .= "<div style=\"padding:50px;font-weight:bold;\"><p>" . __("Almost done...","WPBDM") . "</p><h1>" . __("One More Step","WPBDM") . "</h1><a href=\"plugins.php?plugin=$wpbusdirman_plugin_dir&deactivate=true\">" . __("Please click here to complete the uninstallation process","WPBDM") . "</a></h1></div>";
//Mike Bronner: is this needed?
//	die;

	return $html;
}

function wpbusdirman_opsconfig_categories()
{
}

function wpbusdirmanui_homescreen ()
{
	$html = '';

	$html .= apply_filters('wpbdm_show-directory', null);

	return $html;
}

function wpbusdirmanui_directory_screen()
{
	global $wpbdmimagesurl,$wpbusdirman_imagesurl,$wpbusdirman_plugin_path,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix,$wpbdmposttype;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_contact_errors=false;
 	$args=array('hide_empty' => 0);
 	$wpbusdirman_postcats=get_terms( $wpbdmposttypecategory, $args);
	$html = '';

	if(!isset($wpbusdirman_postcats) || empty($wpbusdirman_postcats))
	{
 		if(is_user_logged_in() && current_user_can('install_plugins'))
 		{
			$html .= "<p>" . __("There are no categories assigned to the business directory yet. You need to assign some categories to the business directory. Only admins can see this message. Regular users are seeing a message that there are currently no listings in the directory. Listings cannot be added until you assign categories to the business directory. ","WPBDM") . "</p>";
 		}
 		else
 		{
			$html .= "<p>" . __("There are currently no listings in the directory","WPBDM") . "</p>";
		}
	}
	else
	{
		$wpbusdirmanaction='';
		if(isset($_REQUEST['action'])
			&& !empty($_REQUEST['action']))
		{
			$wpbusdirmanaction=$_REQUEST['action'];
		}
		elseif(isset($_REQUEST['do'])
			&& !empty ($_REQUEST['do']))
		{
			$wpbusdirmanaction=$_REQUEST['do'];
		}

		if($wpbusdirmanaction == 'submitlisting')
		{
			$html .= apply_filters('wpbdm_show-add-listing-form', '1', '', 'new', '');
		}
		elseif($wpbusdirmanaction == 'viewlistings')
		{
			$html .= wpbusdirman_viewlistings();
		}
		elseif($wpbusdirmanaction == 'renewlisting')
		{
			$wpbdmgpid=wpbusdirman_gpid();
			$wpbusdirman_permalink=get_permalink($wpbdmgpid);
			$neworedit="renew";
			if(isset($_REQUEST['id'])
				&& !empty($_REQUEST['id']))
			{
				$wpbdmidtorenew=$_REQUEST['id'];
				$html .= wpbusdirman_renew_listing($wpbdmidtorenew,$wpbusdirman_permalink,$neworedit);
			}
		}
		elseif($wpbusdirmanaction == 'renewlisting_step_2')
		{
			$wpbusdirmanlistingtermlength=array();
			$wpbusdirmanfeeoption=array();

			if(isset($_REQUEST['wpbusdirmanlistingpostid'])
				&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
			{
				$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
			}
			if(isset($_REQUEST['whichfeeoption'])
				&& !empty($_REQUEST['whichfeeoption']))
			{
				$wpbusdirmanfeeoption=$_REQUEST['whichfeeoption'];
			}
			if(isset($_REQUEST['wpbusdirmanlistingtermlength'])
				&& !empty($_REQUEST['wpbusdirmanlistingtermlength']))
			{
				$wpbusdirmanlistingtermlength=$_REQUEST['wpbusdirmanlistingtermlength'];
			}
			if(isset($_REQUEST['wpbusdirmanpermalink'])
				&& !empty($_REQUEST['wpbusdirmanpermalink']))
			{
				$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
			}
			if(isset($_REQUEST['neworedit'])
				&& !empty($_REQUEST['neworedit']))
			{
				$neworedit=$_REQUEST['neworedit'];
			}


			/*$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);

			$wpbusdirmannumimagesallowed=$myimagesallowedleft['imagesallowed'];
			$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];
			$totalexistingimages=$myimagesallowedleft['totalexisting'];*/

			$wpbusdirmanthisfeetopay=wpbusdirman_calculate_fee_to_pay($wpbusdirmanfeeoption);

			$wpbusdirman_my_renew_post = array();
			$wpbusdirman_my_renew_post['ID'] = $wpbusdirmanlistingpostid;
			$wpbusdirman_my_renew_post['post_status'] = 'pending';
			$html .= wp_update_post( $wpbusdirman_my_renew_post );

				if($wpbusdirmanthisfeetopay > 0)
				{
					$html .= wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$wpbusdirmanlistingtermlength,$wpbusdirmanthisfeetopay);
				}
				else
				{
					// There is no fee to pay so skip to end of process. Nothing left to do
					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
					{
						$html .= "<p>" . __("Your submission has been received and is currently pending review","WPBDM") . "</p>";
					}
					elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'publish')
					{
						$html .= "<p>" . __("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
					}
					else
					{
						$html .= "<p>" . __("You are finished with your listing.","WPBDM") . "</p>";
						$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></form>";
					}
				}

		}
		elseif($wpbusdirmanaction == 'post')
		{
			$html .= apply_filters('wpbdm_process-form-post', null);
		}
		elseif($wpbusdirmanaction == 'editlisting')
		{
			if(isset($_REQUEST['wpbusdirmanlistingid'])
				&& !empty($_REQUEST['wpbusdirmanlistingid']))
			{
				$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingid'];
			}
			$html .= apply_filters('wpbdm_show-add-listing-form', '', '', 'edit', $wpbdmlistingid);
		}
		elseif($wpbusdirmanaction == 'deletelisting')
		{
			$wpbusdirman_config_options=get_wpbusdirman_config_options();
			$wpbdmdraftortrash=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_47'];
			if(isset($_REQUEST['wpbusdirmanlistingid'])
				&& !empty($_REQUEST['wpbusdirmanlistingid']))
			{
				$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingid'];
			}
			if(isset($wpbdmlistingid) && !empty($wpbdmlistingid))
			{
				$wpbusdirman_del_postarr = array();
				$wpbusdirman_del_postarr['ID'] = $wpbdmlistingid;
				$wpbusdirman_del_postarr['post_type'] = $wpbdmposttype;
				$wpbusdirman_del_postarr['post_status'] = $wpbdmdraftortrash;
				$html .= wp_update_post( $wpbusdirman_del_postarr );
				$html .= "<p>" . __("The listing has been deleted.","WPBDM") . "</p>";
				$html .= wpbusdirman_managelistings();
			}
			else
			{
				$html .= "<p>" . __("The system could not determine which listing you want to delete so nothing has been deleted.","WPBDM") . "</p>";
				$html .= wpbusdirman_managelistings();
			}
		}
		elseif($wpbusdirmanaction == 'upgradetostickylisting')
		{
			if(isset($_REQUEST['wpbusdirmanlistingid'])
				&& !empty($_REQUEST['wpbusdirmanlistingid']))
			{
				$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingid'];
			}
			$html .= wpbusdirman_upgradetosticky($wpbdmlistingid);
		}
		elseif($wpbusdirmanaction == 'sendcontactmessage')
		{
			$commentauthormessage='';
			$commentauthorname='';
			$commentauthoremail='';
			$commentauthorwebsite='';
			if(isset($_REQUEST['wpbusdirmanlistingpostid'])
				&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
			{
				$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
			}
			if(isset($_REQUEST['wpbusdirmanpermalink'])
				&& !empty($_REQUEST['wpbusdirmanpermalink']))
			{
				$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
			}
			if(isset($_REQUEST['commentauthormessage'])
				&& !empty($_REQUEST['commentauthormessage']))
			{
				$commentauthormessage=$_REQUEST['commentauthormessage'];
			}
			global $post, $current_user, $user_identity;
			global $wpbusdirman_contact_form_values, $wpbusdirman_contact_form_errors;
			$wpbusdirman_contact_form_errors = '';
			if(is_user_logged_in())
			{
				$commentauthorname=$user_identity;
				$commentauthoremail=$current_user->data->user_email;
				$commentauthorwebsite=$current_user->data->user_url;
			}
			else
			{
				if(isset($_REQUEST['commentauthorname'])
					&& !empty($_REQUEST['commentauthorname']))
				{
					$commentauthorname=htmlspecialchars( $_REQUEST['commentauthorname'] );
				}
				if(isset($_REQUEST['commentauthoremail'])
					&& !empty($_REQUEST['commentauthoremail']))
				{
					$commentauthoremail=$_REQUEST['commentauthoremail'];
				}
				if(isset($_REQUEST['commentauthorwebsite'])
					&& !empty($_REQUEST['commentauthorwebsite']))
				{
					$commentauthorwebsite=$_REQUEST['commentauthorwebsite'];
				}

			}
			if ( !isset($commentauthorname)
				|| empty($commentauthorname) )
			{
				$wpbusdirman_contact_errors=true;
				$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_contact_form_errors.=__("Please enter your name.","WPBDM");
				$wpbusdirman_contact_form_errors.="</li>";
			}
			if(strlen($commentauthorname) < 3)
			{
				$wpbusdirman_contact_errors=true;
				$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_contact_form_errors.=__("Name needs to be at least 3 characters in length to be considered valid.","WPBDM");
				$wpbusdirman_contact_form_errors.="</li>";
			}
			if ( !isset($commentauthoremail)
				|| empty($commentauthoremail) )
			{
				$wpbusdirman_contact_errors=true;
				$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_contact_form_errors.=__("Please enter your email.","WPBDM");
				$wpbusdirman_contact_form_errors.="</li>";
			}
			if ( !wpbusdirman_isValidEmailAddress($commentauthoremail) )
			{
				$wpbusdirman_contact_errors=true;
				$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_contact_form_errors.=__("Please enter a valid email.","WPBDM");
				$wpbusdirman_contact_form_errors.="</li>";
			}
			if( isset($commentauthorwebsite)
				&& !empty($commentauthorwebsite)
				&& !(wpbusdirman_isValidURL($commentauthorwebsite)) )
			{
				$wpbusdirman_contact_errors=true;
				$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_contact_form_errors.=__("Please enter a valid URL.","WPBDM");
				$wpbusdirman_contact_form_errors.="</li>";
			}
			$commentauthormessage = stripslashes($commentauthormessage);
			$commentauthormessage = trim(wp_kses( $commentauthormessage, array() ));
			if ( !isset($commentauthormessage )
				|| empty($commentauthormessage))
			{
				$wpbusdirman_contact_errors=true;
				$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_contact_form_errors.=__("You did not enter a message.","WPBDM");
				$wpbusdirman_contact_form_errors.="</li>";
			}
			if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_30'] == "yes")
			{
				$privatekey = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_29'];
				if(isset($privatekey) && !empty($privatekey))
				{
					require_once('recaptcha/recaptchalib.php');
					$resp = recaptcha_check_answer ($privatekey,
					$_SERVER["REMOTE_ADDR"],
					$_POST["recaptcha_challenge_field"],
					$_POST["recaptcha_response_field"]);
					if (!$resp->is_valid)
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("The reCAPTCHA wasn't entered correctly: ","WPBDM");
						$wpbusdirman_contact_form_errors.=" . $resp->error . ";
						$wpbusdirman_contact_form_errors.="</li>";
					}
				}
			}
			if($wpbusdirman_contact_errors)
			{
				$html .= wpbusdirman_contactform($wpbusdirmanpermalink,$wpbusdirmanlistingpostid,$commentauthorname,$commentauthoremail,$commentauthorwebsite,$commentauthormessage,$wpbusdirman_contact_form_errors);
			}
			else
			{
				$post_author = get_userdata( $post->post_author );
				$headers =	"MIME-Version: 1.0\n" .
						"From: $commentauthorname <$commentauthoremail>\n" .
						"Reply-To: $commentauthoremail\n" .
						"Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
				$subject = "[" . get_option( 'blogname' ) . "] " . wp_kses( get_the_title($wpbusdirmanlistingpostid), array() );
				$wpbdmsendtoemail=wpbusdirman_get_the_business_email($wpbusdirmanlistingpostid);
				if(!isset($wpbdmsendtoemail) || empty($wpbdmsendtoemail))
				{
					$wpbdmsendtoemail=$post_author->user_email;
				}
				$time = date_i18n( __('l F j, Y \a\t g:i a'), current_time( 'timestamp' ) );
				$message = "Name: $commentauthorname
				Email: $commentauthoremail
				Website: $commentauthorwebsite

				$commentauthormessage

				Time: $time

				";
				if(wp_mail( $wpbdmsendtoemail, $subject, $message, $headers ))
				{
					$html .= "<p>" . __("Your message has been sent","WPBDM") . "</p>";
				}
				else
				{
					$html .= "<p>" . __("There was a problem encountered. Your message has not been sent","WPBDM") . "</p>";
				}
			}
		}
		elseif($wpbusdirmanaction == 'deleteimage')
		{
			$wpbdmlistingid='';
			$wpbdmimagetodelete='';
			$wpbusdirmannumimgsallowed='';
			$wpbusdirmannumimgsleft='';
			$wpbusdirmanlistingtermlength=array();
			$wpbusdirmanpermalink='';
			$neworedit='';
			if(isset($_REQUEST['wpbusdirmanlistingpostid'])
				&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
			{
				$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingpostid'];
			}
			if(isset($_REQUEST['wpbusdirmanimagetodelete']) && !empty($_REQUEST['wpbusdirmanimagetodelete']))
			{
				$wpbdmimagetodelete=$_REQUEST['wpbusdirmanimagetodelete'];
			}
			if(isset($_REQUEST['wpbusdirmannumimgsallowed']) && !empty($_REQUEST['wpbusdirmannumimgsallowed']))
			{
				$wpbusdirmannumimgsallowed=$_REQUEST['wpbusdirmannumimgsallowed'];
			}
			if(isset($_REQUEST['wpbusdirmannumimgsleft']) && !empty($_REQUEST['wpbusdirmannumimgsleft']))
			{
				$wpbusdirmannumimgsleft=$_REQUEST['wpbusdirmannumimgsleft'];
			}
			if(isset($_REQUEST['wpbusdirmanlistingtermlength']) && !empty($_REQUEST['wpbusdirmanlistingtermlength']))
			{
				$wpbusdirmanlistingtermlength=$_REQUEST['wpbusdirmanlistingtermlength'];
			}
			if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink']))
			{
				$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
			}
			if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit']))
			{
				$neworedit=$_REQUEST['neworedit'];
			}
			$html .= wpbusdirman_deleteimage($imagetodelete=$wpbdmimagetodelete,$wpbdmlistingid,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanpermalink,$neworedit);
		}
		elseif($wpbusdirmanaction == 'payment_step_1')
		{
			$wpbusdirmanfeeoptions=array();

			if(isset($_REQUEST['wpbusdirmanlistingpostid'])
				&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
			{
				$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
			}
			if(isset($_REQUEST['inpost_category'])
				&& !empty($_REQUEST['inpost_category']))
			{
				$uscats=$_REQUEST['inpost_category'];
			}

			foreach($uscats as $uscat)
			{
					if(isset($_REQUEST['whichfeeoption_'.$uscat])
						&& !empty($_REQUEST['whichfeeoption_'.$uscat]))
					{
						$wpbusdirmanfeeoption=$_REQUEST['whichfeeoption_'.$uscat];
						$wpbusdirmanfeeoptions[]=$wpbusdirmanfeeoption;
						$myfeecatobj[]=array('catid' => $uscat, 'feeopid' => $wpbusdirmanfeeoption);
					}
			} // End foreach uscats


			foreach($myfeecatobj as $fcobj)
			{
				$cat=$fcobj['catid'];
				$feeid=$fcobj['feeopid'];

				$listingincr=get_option('wpbusdirman_settings_fees_increment_'.$feeid);

				$catdur=$cat;
				$catdur.="_";
				$catdur.=$listingincr;
				$wpbusdirmanlistingtermlength[]=$catdur;

				$mycatobj[]=array('listingcat' => $uscat,'listingduration' => $listingincr);
			}


			if(isset($_REQUEST['wpbusdirmanpermalink'])
				&& !empty($_REQUEST['wpbusdirmanpermalink']))
			{
				$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
			}
			if(isset($_REQUEST['neworedit'])
				&& !empty($_REQUEST['neworedit']))
			{
				$neworedit=$_REQUEST['neworedit'];
			}

			$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirmanlistingpostid,$wpbusdirmanfeeoptions);

			$wpbusdirmannumimagesallowed=$myimagesallowedleft['imagesallowed'];
			$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];
			$totalexistingimages=$myimagesallowedleft['totalexisting'];


			if($wpbusdirmanlistingtermlength)
			{
				foreach($wpbusdirmanlistingtermlength as $catdur)
				{
					$existingtermlengths=get_post_meta($wpbusdirmanlistingpostid, "termlength", false);

						if(!in_array($catdur,$existingtermlengths))
						{
								add_post_meta($wpbusdirmanlistingpostid, "termlength", $catdur, false);
						}
				}
			}

			if($wpbusdirmanfeeoptions)
			{
				foreach($wpbusdirmanfeeoptions as $feeopid)
				{
					$wpbusdirmanlistingcost=get_option('wpbusdirman_settings_fees_amount_'.$feeopid);
					add_post_meta($wpbusdirmanlistingpostid, "costoflisting", $wpbusdirmanlistingcost, false) or update_post_meta($wpbusdirmanlistingpostid, "costoflisting", $wpbusdirmanlistingcost);
					add_post_meta($wpbusdirmanlistingpostid, "listingfeeid", $feeopid, false) or update_post_meta($wpbusdirmanlistingpostid, "costoflisting", $feeopid);
				}
			}

			$html .= apply_filters('wpbdm_show-image-upload-form', $wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimagesallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror='',$neworedit,$wpbusdirmanfeeoptions);

		}
		elseif($wpbusdirmanaction == 'payment_step_2')
		{
			$wpbusdirmanfeeoptions=array();
			$wpbusdirmanlistingtermlength=array();

			if(isset($_REQUEST['wpbusdirmanlistingpostid'])
				&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
			{
				$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
			}
			if(isset($_REQUEST['wpbusdirmanfeeoption'])
				&& !empty($_REQUEST['wpbusdirmanfeeoption']))
			{
				$wpbusdirmanfeeoption=$_REQUEST['wpbusdirmanfeeoption'];
			}elseif(isset($_REQUEST['whichfeeoption'])
				&& !empty($_REQUEST['whichfeeoption'])){$wpbusdirmanfeeoption=$_REQUEST['whichfeeoption'];}

			if(isset($_REQUEST['wpbusdirmanlistingtermlength'])
				&& !empty($_REQUEST['wpbusdirmanlistingtermlength']))
			{
				$wpbusdirmanlistingtermlength=$_REQUEST['wpbusdirmanlistingtermlength'];
			}
			if(isset($_REQUEST['wpbusdirmanpermalink'])
				&& !empty($_REQUEST['wpbusdirmanpermalink']))
			{
				$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
			}
			if(isset($_REQUEST['neworedit'])
				&& !empty($_REQUEST['neworedit']))
			{
				$neworedit=$_REQUEST['neworedit'];
			}

			$wpbusdirmancostoflisting=wpbusdirman_calculate_fee_to_pay($wpbusdirmanfeeoption);

				if($wpbusdirmancostoflisting > 0)
				{
					$html .= wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$wpbusdirmanlistingtermlength,$wpbusdirmancostoflisting);
				}
				else
				{
					// There is no fee to pay so skip to end of process. Nothing left to do
					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
					{
						$html .= "<p>" . __("Your submission has been received and is currently pending review","WPBDM") . "</p>";
					}
					elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'publish')
					{
						$html .= "<p>" . __("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
					}
					else
					{
						$html .= "<p>" . __("You are finished with your listing.","WPBDM") . "</p>";
						$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></form>";
					}
				}
		}
		elseif($wpbusdirmanaction == 'wpbusdirmanuploadfile')
		{
			$html .= wpbusdirman_doupload();
		}
		else
		{
			global $wpbusdirman_gpid,$permalinkstructure;
			$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
			$querysymbol="?";
			if(!isset($permalinkstructure)
				|| empty($permalinkstructure))
			{
				$querysymbol="&amp";
			}
			if(file_exists(get_template_directory() . '/single/wpbusdirman-index-categories.php'))
			{
				include get_template_directory() . '/single/wpbusdirman-index-categories.php';
			}
			elseif(file_exists(get_stylesheet_directory() . '/single/wpbusdirman-index-categories.php'))
			{
				include get_stylesheet_directory() . '/single/wpbusdirman-index-categories.php';
			}
			elseif(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-categories.php'))
			{
				include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-categories.php';
			}
			else
			{
				include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-categories.php';
			}
			if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_44'] == "yes")
			{
				if(file_exists(get_template_directory() . '/single/wpbusdirman-index-listings.php'))
				{
					include get_template_directory() . '/single/wpbusdirman-index-listings.php';
				}
				elseif(file_exists(get_stylesheet_directory() . '/single/wpbusdirman-index-listings.php'))
				{
					include get_stylesheet_directory() . '/single/wpbusdirman-index-listings.php';
				}
				elseif(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php'))
				{
					include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';
				}
				else
				{
					include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';
				}
			}
		}
	}

	return $html;
}

function wpbusdirman_get_the_business_email($wpbusdirmanlistingpostid)
{

	$wpbdm_the_email='';
	wp_reset_query();
	$mypost=get_post($wpbusdirmanlistingpostid);
	$thepostid=$mypost->ID;
	$wpbdm_the_emailsarr=array();

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val):


			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);


			if($wpbusdirman_field_association == 'meta')
			{
				$wpbdm_meta_fields[]=$wpbusdirman_field_label;
			}

		endforeach;


		foreach($wpbdm_meta_fields as $wpbdm_meta_field)
		{

			$wpbdm_field_value=get_post_meta($thepostid, $wpbdm_meta_field, true);

				if(isset($wpbdm_field_value) && !empty($wpbdm_field_value) && (wpbusdirman_isValidEmailAddress($wpbdm_field_value)))
				{
					$wpbdm_the_emailsarr[]=$wpbdm_field_value;
				}

		}

	}

	$wpbdm_the_email=$wpbdm_the_emailsarr[0];
	return $wpbdm_the_email;
}

function wpbusdirman_the_image($wpbusdirman_pID,$size = 'medium' , $class = '')
{

	//setup the attachment array
	$att_array = array(
	'post_parent' => $wpbusdirman_pID,
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'order_by' => 'menu_order'
	);

	//get the post attachments
	$attachments = get_children($att_array);

	//make sure there are attachments
	if (is_array($attachments))
	{
		//loop through them
		foreach($attachments as $att)
		{
			//find the one we want based on its characteristics
			if ( $att->menu_order == 0)
			{
				$image_src_array = wp_get_attachment_image_src($att->ID, $size);

				//get url - 1 and 2 are the x and y dimensions
				$url = $image_src_array[0];
				$caption = $att->post_excerpt;
				$image_html = '%s';

				//combine the data
				$wpbusdirman_img_html = sprintf($image_html,$url,$caption,$class);

				$wpbusdirman_image_url=$url;

			}

			return $wpbusdirman_image_url;
		}
	}
}

function wpbusdirman_do_post()
{
	global $wpbusdirman_gpid,$wpbdmposttype,$wpbdmposttypecategory,$wpbdmposttypetags,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
	$html = '';
	$makeactive='';
	$neworedit='';
	$wpbdmlistingid='';
	$mycatobj=array();

	if(isset($_REQUEST['formmode'])
		&& ($_REQUEST['formmode'] == -1))
	{
		$makeactive=$_REQUEST['formmode'];
	}
	if(isset($_REQUEST['neworedit'])
		&& !empty($_REQUEST['neworedit']))
	{
		$neworedit=$_REQUEST['neworedit'];
	}
	if(isset($_REQUEST['wpbdmlistingid'])
		&& !empty($_REQUEST['wpbdmlistingid']))
	{
		$wpbdmlistingid=$_REQUEST['wpbdmlistingid'];
	}
	if($makeactive == -1)
	{
		$html .= "<h3 style=\"padding:10px;\">" . __("Information Not Saved","WPBDM") . "</h3><p>" . __("You are trying to submit the form in preview mode. You cannot save while in preview mode","WPBDM") . " <a href=\"javascript:history.go(-1)\">" . __("Go Back","WPBDM") . "</a></p>";
	}
	else
	{
		if (!(is_user_logged_in()) )
		{
			if($wpbusdirman_field_vals)
			{
				foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
				{
					$wpbusdirman_validation_op=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val);
					if($wpbusdirman_validation_op == 'email')
					{
						$wpbusdirman_email_numval=$wpbusdirman_field_val;
					}
					$wpbusdirman_association_op=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
					if($wpbusdirman_association_op == 'title')
					{
						$wpbusdirman_title_numval=$wpbusdirman_field_val;
					}
				}
			}
			$wpbusdirman_email_field=$_REQUEST['wpbusdirman_field_label_meta'.$wpbusdirman_email_numval];
			$guestrand=$wpbusdirman_user_pass=wpbusdirman_generatePassword(5,2);
			$wpbusdirman_display_name='Guest';
			$wpbusdirman_display_name.=" $guestrand";
			$wpbusdirman_user_login='guest_';
			$wpbusdirman_user_login.=" $guestrand";
			if(email_exists($wpbusdirman_email_field))
			{
				$wpbusdirman_UID_get=get_user_by_email($wpbusdirman_email_field);
				$wpbusdirman_UID=$wpbusdirman_UID_get->ID;
			}
			else
			{
				$wpbusdirman_user_pass=wpbusdirman_generatePassword(7,2);
				$wpbusdirman_UID=wp_insert_user(array('display_name'=>$wpbusdirman_display_name,'user_login'=>$wpbusdirman_user_login,'user_email'=>$wpbusdirman_email_field,'user_pass'=>$wpbusdirman_user_pass));
			}
		}
		elseif(is_user_logged_in())
		{
			global $current_user;
			get_currentuserinfo();
			$wpbusdirman_UID=$current_user->ID;
		}

		if(!isset($wpbusdirman_UID) || empty($wpbusdirman_UID))
		{
			$wpbusdirman_UID=1;
		}
		$wpbusdirmanposterrors = wpbusdirman_validate_data();
		if($wpbusdirmanposterrors)
		{
			$html .= apply_filters('wpbdm_show-add-listing-form', $makeactive,$wpbusdirmanposterrors,$neworedit,$wpbdmlistingid);
		}
		else
		{
			$post_title=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_title']);
			$post_excerpt=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_excerpt']);
			$post_content=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_description']);
			$post_tags=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_tags']);
			global $wpbusdirman_gpid,$permalinkstructure;
			$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
			$querysymbol="?";
			if(!isset($permalinkstructure)
				|| empty($permalinkstructure))
			{
				$querysymbol="&amp";
			}
			if(isset($_REQUEST['cat'])
				&& !empty($_REQUEST['cat']))
			{
				$post_category_item= $_REQUEST['cat'];
				$inpost_category=array("$post_category_item");
			}
			elseif(isset($_REQUEST['wpbusdirman_field_label_category'])
				&& !empty($_REQUEST['wpbusdirman_field_label_category']))
			{
				$inpost_category=$_REQUEST['wpbusdirman_field_label_category'];
			}
			if(isset($neworedit)
				&& ($neworedit == 'edit'))
			{
				$post_status=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'];
				if($post_status == 'pending2')
				{
					$post_status="pending";
				}
				elseif($post_status == 'publish2')
				{
					$post_status="publish";
				}
			}
			else
			{
				$post_status=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'];
			}
			if(!isset($post_status)
				|| empty($post_status))
			{
				$post_status='pending';
			}
			if ( empty($inpost_category)
				|| 0 == count($inpost_category)
				|| !is_array($inpost_category) )
			{
				$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');
				if($wpbusdirman_myterms)
				{
					foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
					{
						$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
					}
				}
				$post_category=$wpbusdirman_postcatitems[0];
			}
			else
			{
				$post_category = $inpost_category;
			}
			$post_tag=explode(",",$post_tags);
			$tags_input=array();
			for ($i=0;isset($post_tag[$i]);++$i)
			{
				$tags_input[]=$post_tag[$i];
			}
			$wpbusdirman_postID = wp_insert_post( array(
				'post_author'	=> $wpbusdirman_UID,
				'post_title'	=> $post_title,
				'post_content'	=> $post_content,
				'post_excerpt'	=> $post_excerpt,
				'post_status' 	=> $post_status,
				'post_type' 	=> $wpbdmposttype,
				'ID'	=> $wpbdmlistingid
			));
			wp_set_post_terms( $wpbusdirman_postID , $tags_input, $wpbdmposttypetags, false );
			wp_set_post_terms( $wpbusdirman_postID , $post_category, $wpbdmposttypecategory, false );
			$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
			if($wpbusdirman_field_vals)
			{
				foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
				{
					$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
					$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
					$wpbusdirman_field_type=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);
					if($wpbusdirman_field_association == 'meta')
					{
						$wpbusdirman_fieldmeta_set="wpbusdirman_field_label_meta$wpbusdirman_field_val";
						if($wpbusdirman_field_type == 6)
						{
							$wpbusdirman_the_fieldmeta=$_REQUEST[$wpbusdirman_fieldmeta_set];
							$wpbusdirmanfieldmeta='';
							if($wpbusdirman_the_fieldmeta)
							{
								foreach($wpbusdirman_the_fieldmeta as $wpbusdirman_thefieldmeta)
								{
									$wpbusdirmanfieldmeta.="$wpbusdirman_thefieldmeta\t";
								}
							}
						}
						elseif($wpbusdirman_field_type == 5)
						{
							$wpbusdirman_the_fieldmeta=$_REQUEST[$wpbusdirman_fieldmeta_set];
							$wpbusdirmanfieldmeta='';
							if (count($wpbusdirman_the_fieldmeta) > 0)
							{
								for ($i=0;$i<count($wpbusdirman_the_fieldmeta);$i++)
								{
									$wpbusdirmanfieldmeta.="$wpbusdirman_the_fieldmeta[$i]\t";
								}
							}
						}
						else
						{
							$wpbusdirmanfieldmeta=$_REQUEST[$wpbusdirman_fieldmeta_set];
						}
						add_post_meta($wpbusdirman_postID, $wpbusdirman_field_label, $wpbusdirmanfieldmeta, true) or update_post_meta($wpbusdirman_postID, $wpbusdirman_field_label, $wpbusdirmanfieldmeta);

					}
				}

						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no")
						{
							if(isset($neworedit)
								&& (!($neworedit == 'edit')) )
							{
								$wpbusdirmantermduration=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];
								foreach($inpost_category as $mypostcategory)
								{
									$wpbusdirmanlengthofterm=$mypostcategory;
									$wpbusdirmanlengthofterm.="_";
									$wpbusdirmanlengthofterm.=$wpbusdirmantermduration;

									add_post_meta($wpbusdirman_postID, "termlength", $wpbusdirmanlengthofterm, false) or update_post_meta($wpbusdirman_postID, "termlength", $wpbusdirmanlengthofterm);
								}
							}
						}

			}

			global $wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule;

			if(!($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no"))
			{
				/* Payments are activated */

				if(( $wpbusdirman_haspaypalmodule == 1) || ($wpbusdirman_hastwocheckoutmodule == 1) || ($wpbusdirman_hasgooglecheckoutmodule == 1))
				{
					if(!($neworedit == 'edit'))
					{
						/* This is not an edit so payment options need to be setup */


						$html .= "<h2>" . __("Step 2","WPBDM") . "</h2>";
						$wpbusdirman_fee_to_pay_li=wpbusdirman_feepay_configure($inpost_category);

						if(isset($wpbusdirman_fee_to_pay_li) && !empty($wpbusdirman_fee_to_pay_li))
						{
							/* There is a fee to be paid so proceed with setting up the fee selection page to display to the user */

							global $wpbusdirman_gpid,$permalinkstructure;
							$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
							$wpbusdirman_fee_to_pay="<div id=\"wpbusdirmanpaymentoptionslist\">";
							$wpbusdirman_fee_to_pay.=$wpbusdirman_fee_to_pay_li;
							$wpbusdirman_fee_to_pay.="</div>";
							$neworedit='new';
							$html .= "<label>" . __("Select Listing Payment Option","WPBDM") . "</label><br /><p>";
							$usercatstotal=count($inpost_category);
							if($usercatstotal > 1){
							$html .="<p>";
							$html .= __("You have selected more than one category. Each category you to which you elect to submit your listing incurs a separate fee.", "WPBDM");
							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
							{
								$html .=__(" The number of images attached to your listing will be set according to option you choose that has the most images. So if for one category you chose an option with 2 images but for another category you chose an option with 4 images your listing will be allotted 4 image slots", "WPBDM");
							}
							$html .="</p>";
							}
							$html .= "<form method=\"post\" action=\"$wpbusdirman_permalink\">";
							$html .= "<input type=\"hidden\" name=\"action\" value=\"payment_step_1\" />";
							foreach ($inpost_category as $key => $value)
							{
							 $html.='<input type=hidden name="inpost_category[]" value="'.htmlspecialchars($value).'"';
							}
							$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirman_postID\" />";
							$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirman_permalink\" />";
							$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\" />";
							$html .= $wpbusdirman_fee_to_pay;
							$html .= "<br /><input type=\"submit\" class=\"insubmitbutton\" value=\"" . __("Next","WPBDM") . "\" /></form></p>";
						}
						else
						{

							/* wpbusdirman_fee_to_pay_li value is missing so move on and setup the image upload form to display to the user */

							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
							{
								$wpbusdirmanlistingtermlength=array();
								if(!isset($wpbusdirmanlistingtermlength) || empty($wpbusdirmanlistingtermlength))
								{
									$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];
								}

								$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirman_postID,$wpbusdirmanfeeoption='');

								$wpbusdirmannumimgsallowed=$myimagesallowedleft['imagesallowed'];
								$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];


									foreach($inpost_category as $mycatid)
									{
											$listingincr=$mycatid;
											$listingincr="_";
											$listingincr=$wpbusdirmanlistingtermlength;

											$mycatobj[]=array('listingcat' => $mycatid,'listingduration' => $listingincr);

									} // End foreach wpbusdirmanlistingtermlength

								$html .= apply_filters('wpbdm_show-image-upload-form', $wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');

							}
							else
							{
								$html .= "<h3 style=\"padding:10px;\">" . __("Submission received","WPBDM") . "</h3><p>" . __("Your submission has been received.","WPBDM") .  "</p>";
							}
						}
					}
					else
					{
						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
						{
							$html .= "<h3>" . __("Step 2","WPBDM") . "</h3>";

							$wpbusdirmanlistingtermlength=get_post_meta($wpbusdirman_postID, "termlength", $single=false);

							if($wpbusdirmanlistingtermlength)
							{

								foreach($wpbusdirmanlistingtermlength as $catdur)
								{
									// potential issue for users with listings submitted via pre 1.9.3 versions because termlength is saved as single digit value whereas in 1.9.3+ term length saves as XXX_xx where XXX is the category ID and xx is the term duration with _ acting as a delimiter

									$mycatdurvals=explode("_",$catdur);
									$mycatid=$mycatdurvals[0];
									$listingincr=$mycatdurvals[1];

										$mycatobj[]=array('listingcat' => $mycatid,'listingduration' => $listingincr);

								} // End foreach wpbusdirmanlistingtermlength
							}
							else
							{
								$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];

								foreach($inpost_category as $uscat)
								{
									$mycatobj[]=array('listingcat' => $uscat,'listingduration' => $wpbusdirmanlistingtermlength);
								}
							}

								$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirman_postID,$wpbusdirmanfeeoption='');

								$wpbusdirmannumimgsallowed=$myimagesallowedleft['imagesallowed'];
								$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];

							$html .= apply_filters('wpbdm_show-image-upload-form', $wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');

						}
						else
						{
							$html .= "<h3 style=\"padding:10px;\">" . __("Submission received","WPBDM") . "</h3><p>" . __("Your submission has been received.","WPBDM") . "</p>";
						}
					}
				}
				else
				{
					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
					{
						$html .= "<h3>" . __("Step 2","WPBDM") . "</h3>";
							$wpbusdirmanlistingtermlength=get_post_meta($wpbusdirman_postID, "termlength", $single=false);

							if($wpbusdirmanlistingtermlength)
							{

								foreach($wpbusdirmanlistingtermlength as $catdur)
								{
									// potential issue for users with listings submitted via pre 1.9.3 versions because termlength is saved as single digit value whereas in 1.9.3+ term length saves as XXX_xx where XXX is the category ID and xx is the term duration with _ acting as a delimiter

									$mycatdurvals=explode("_",$catdur);
									$mycatid=$mycatdurvals[0];
									$listingincr=$mycatdurvals[1];

										$mycatobj[]=array('listingcat' => $mycatid,'listingduration' => $listingincr);

								} // End foreach wpbusdirmanlistingtermlength
							}
							else
							{
								$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];

								foreach($inpost_category as $uscat)
								{
									$mycatobj[]=array('listingcat' => $uscat,'listingduration' => $wpbusdirmanlistingtermlength);
								}
							}

								$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirman_postID,$wpbusdirmanfeeoption='');

								$wpbusdirmannumimgsallowed=$myimagesallowedleft['imagesallowed'];
								$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];

								$html .= apply_filters('wpbdm_show-image-upload-form', $wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror,$neworedit,$whichfeeoption);

					}
					else
					{
						$html .= "<h3 style=\"padding:10px;\">" . __("Submission received","WPBDM") . "</h3><p>" . __("Your submission has been received.","WPBDM") . "</p>";
					}
				}
			}
			else
			{
				/* Payments are not activated */

				if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
				{
					$html .= "<h3>" . __("Step 2","WPBDM") . "</h3>";
					if(isset($neworedit)
						&& !empty($neworedit)
						&& ($neworedit == 'edit'))
					{
						$wpbusdirmanlistingtermlength=get_post_meta($wpbusdirman_postID, "termlength", $single=false);

							if($wpbusdirmanlistingtermlength)
							{

								foreach($wpbusdirmanlistingtermlength as $catdur)
								{
									// potential issue for users with listings submitted via pre 1.9.3 versions because termlength is saved as single digit value whereas in 1.9.3+ term length saves as XXX_xx where XXX is the category ID and xx is the term duration with _ acting as a delimiter

									$mycatdurvals=explode("_",$catdur);
									$mycatid=$mycatdurvals[0];
									$listingincr=$mycatdurvals[1];

										$mycatobj[]=array('listingcat' => $mycatid,'listingduration' => $listingincr);

								} // End foreach wpbusdirmanlistingtermlength
							}
							else
							{
								$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];

								foreach($inpost_category as $uscat)
								{
									$mycatobj[]=array('listingcat' => $uscat,'listingduration' => $wpbusdirmanlistingtermlength);
								}
							}


							$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirman_postID,$wpbusdirmanfeeoption='');

							$wpbusdirmannumimgsallowed=$myimagesallowedleft['imagesallowed'];
							$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];

							$html .= apply_filters('wpbdm_show-image-upload-form', $wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');

					}
					else
					{
						$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];

						foreach($inpost_category as $uscat)
						{
								$mycatobj[]=array('listingcat' => $uscat,'listingduration' => $wpbusdirmanlistingtermlength);
						}

							$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirman_postID,$wpbusdirmanfeeoption='');

							$wpbusdirmannumimgsallowed=$myimagesallowedleft['imagesallowed'];
							$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];

							$html .= apply_filters('wpbdm_show-image-upload-form', $wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');

					}
				}
				else
				{
					$html .= "<h3 style=\"padding:10px;\">" . __("Submission received","WPBDM") . "</h3><p>" . __("Your submission has been received.","WPBDM") . "</p>";
				}
			}
		}
	}

	return $html;
}

function wpbusdirman_image_upload_form($wpbusdirmanlistingpostid, $wpbusdirmanpermalink, $wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft, $mycatobj, $wpbusdirmanuerror, $neworedit, $whichfeeoption)
{
	global $wpbdmimagesurl,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

		$mycatduration=array();
		$feeoptionsarr=array();

		if($mycatobj && is_array($mycatobj)){

			foreach($mycatobj as $mycatobject)
			{
				$catduration=$mycatobject['listingcat'];
				$catduration.="_";
				$catduration.=$mycatobject['listingduration'];
				$mycatduration[]=$catduration;

			}
		}

		if($whichfeeoption)
		{
			foreach($whichfeeoption as $feeoption)
			{
				$feeoptionsarr[]=get_option('wpbusdirman_settings_fees_amount_'.$feeoption);
			}
		}

		$feepayval=array_sum($feeoptionsarr);

	if(isset($wpbusdirmanuerror) && !empty($wpbusdirmanuerror))
	{
		$html .= "<p>";
		foreach($wpbusdirmanuerror as $wpbusdirmanuerror)
		{
			$html .= $wpbusdirmanuerror;
		}
		$html .= "</p>";
	}
	if(isset($wpbusdirmanuerror)
		&& !empty($wpbusdirmanuerror))
	{
		$html .= "<p class=\"wpbusdirmaerroralert\">$wpbusdirmanuerror</p>";
	}


	$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirmanlistingpostid,$whichfeeoption);
	$wpbusdirmannumimagesallowed=$myimagesallowedleft['imagesallowed'];
	$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];
	$totalexistingimages=$myimagesallowedleft['totalexisting'];

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
	{

				if( ($totalexistingimages > 0) && ( $wpbusdirmannumimgsleft <= 0) )
				{
					$wpbusdirmanimagesinpost=get_post_meta($wpbusdirmanlistingpostid, "image", $single = false);

					$html .= "<p>" . __("It appears you do not have the ability to upload additional images at this time.","WPBDM") . "</p>";
					if(get_post_meta($wpbusdirmanlistingpostid, "image", $single = true))
					{
						$html .= "<p>" . __("You can manage your current images below","WPBDM") . "</p>";
						if($wpbusdirmanimagesinpost)
						{
							foreach($wpbusdirmanimagesinpost as $wpbusdirmanimage)
							{
								$html .= "<div style=\"float:left;margin-right:10px;margin-bottom:10px;\"><img src=\"$wpbdmimagesurl/thumbnails/$wpbusdirmanimage\" border=\"0\" height=\"100\" alt=\"$wpbusdirmanimage\"><br/>";
								$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
								$html .= "<input type=\"hidden\" name=\"action\" value=\"deleteimage\" />";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\" />";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanimagetodelete\" value=\"$wpbusdirmanimage\" />";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmannumimgsallowed\" value=\"$wpbusdirmannumimgsallowed\" />";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmannumimgsleft\" value=\"$wpbusdirmannumimgsleft\" />";
								//$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength\" value=\"$wpbusdirmanlistingtermlength\" />";
								foreach ($mycatduration as $key => $value)
								{
									$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength[]\" value=\"$value\" />";
								}

								$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\" />";
								$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\" />";
								//$html .= "<input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\" />";
								if($whichfeeoption)
								{
									foreach ($whichfeeoption as $key => $value)
									{
										$html .= "<input type=\"hidden\" name=\"whichfeeoption[]\" value=\"$value\" />";
									}
								}
								$html .= "<input type=\"submit\" class=\"deletelistingbutton\" value=\"" . __("Delete Image","WPBDM") . "\" /></form></div>";
							}
						}
						$html .= "<div style=\"clear:both;\"></div>";
						if(isset($neworedit)
							&& !empty($neworedit)
							&& ($neworedit == 'edit'))
						{
							$html .= "<p>" . __("If you are not updating your images you can click the exit now button.","WPBDM") . "</p>";
							$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
							$html .= "<p>";
							$html .= "<input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\"></p></form>";
						}
					}
					else
					{
						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
						{
							$html .= "<p>" . __("Your submission has been received and is currently pending review","WPBDM") . "</p>";
						}
						elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1']=='publish')
						{
							$html .= "<p>" . __("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
						}
						$html .= "<p>" . __("You are finished with your listing.","WPBDM") . "</p>";
						$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\"></form>";
					}
				}
				else
				{
					$html .= "<p>If you would like to include an image with your listing please upload the image of your choice. You are allowed [$wpbusdirmannumimgsallowed] images and have [$wpbusdirmannumimgsleft] image slots still available.</p>";
					$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\" ENCTYPE=\"Multipart/form-data\">";
					$html .= "<input type=\"hidden\" name=\"action\" value=\"wpbusdirmanuploadfile\" />";
					$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\" />";
					$html .= "<input type=\"hidden\" name=\"wpbusdirmannumimgsallowed\" value=\"$wpbusdirmannumimgsallowed\" />";
					$html .= "<input type=\"hidden\" name=\"wpbusdirmannumimgsleft\" value=\"$wpbusdirmannumimgsleft\" />";

						foreach ($mycatduration as $key => $value)
						{
							$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength[]\" value=\"$value\" />";
						}
					$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\" />";
					if($whichfeeoption)
					{
						foreach ($whichfeeoption as $key => $value)
						{
							$html .= "<input type=\"hidden\" name=\"whichfeeoption[]\" value=\"$value\" />";
						}
					}
					$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\" />";
					for ($i=0;$i<$wpbusdirmannumimgsleft;$i++)
					{
						$html .= "<p><input name=\"wpbusdirmanuploadpic$i\"type=\"file\"></p>";
					}
					$html .= "<p><input class=\"insubmitbutton\" value=\"" . __("Upload File","WPBDM") . "\" type=\"submit\"></p></form>";
					if($totalexistingimages >= 1)
					{
						if(get_post_meta($wpbusdirmanlistingpostid, "image", $single = true))
						{
							$wpbusdirmanimagesinpost=get_post_meta($wpbusdirmanlistingpostid, "image", $single = false);
							$html .= "<p>" . __("You can manage your current images below","WPBDM") . "</p>";
							if($wpbusdirmanimagesinpost)
							{
								foreach($wpbusdirmanimagesinpost as $wpbusdirmanimage)
								{
									$html .= "<div style=\"float:left;margin-right:10px;margin-bottom:10px;\"><img src=\"$wpbdmimagesurl/thumbnails/$wpbusdirmanimage\" border=\"0\" height=\"100\" alt=\"$wpbusdirmanimage\"><br/>";
									$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
									$html .= "<input type=\"hidden\" name=\"action\" value=\"deleteimage\"/>";
									$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
									$html .= "<input type=\"hidden\" name=\"wpbusdirmanimagetodelete\" value=\"$wpbusdirmanimage\"/>";
									$html .= "<input type=\"hidden\" name=\"wpbusdirmannumimgsallowed\" value=\"$wpbusdirmannumimgsallowed\"/>";
									$html .= "<input type=\"hidden\" name=\"wpbusdirmannumimgsleft\" value=\"$wpbusdirmannumimgsleft\"/>";
									//$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength\" value=\"$wpbusdirmanlistingtermlength\"/>";
									foreach ($mycatduration as $key => $value)
									{
										$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength[]\" value=\"$value\" />";
									}
									$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/>";
									$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
									//$html .= "<input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
									if($whichfeeoption)
									{
										foreach ($whichfeeoption as $key => $value)
										{
											$html .= "<input type=\"hidden\" name=\"whichfeeoption[]\" value=\"$value\" />";
										}
									}
									$html .= "<input type=\"submit\" class=\"deletelistingbutton\" value=\"" . __("Delete Image","WPBDM") . "\" /></form></div>";
								}
							}
							$html .= "<div style=\"clear:both;\"></div>";
						}
					}
					if(isset($neworedit) && !empty($neworedit) && ($neworedit == 'edit'))
					{
						$html .= "<p>" . __("If you prefer not to add an image or you are otherwise finished managing your images you can click the exit now button.","WPBDM") . "</p>";
						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'] == 'pending2')
						{
							$html .= "<p>" . __("Your updated listing will be submitted for review.","WPBDM") . "</p>";
						}
						elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19']=='publish2')
						{
							$html .= "<p>" . __("Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
						}
						$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
						$html .= "<p>";
						$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
									if($whichfeeoption)
									{
										foreach ($whichfeeoption as $key => $value)
										{
											$html .= "<input type=\"hidden\" name=\"whichfeeoption[]\" value=\"$value\" />";
										}
									}
						$html .= "<input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></p></form>";
					}
					else
					{
						if(!($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no"))
						{

							if($feepayval > 0)
							{
								$html .= "<p>" . __("If you prefer not to add any images please click Next to proceed to the next step.","WPBDM") . "</p>";
								$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
								$html .= "<p><input type=\"hidden\" name=\"action\" value=\"payment_step_2\"/>";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
									foreach ($mycatduration as $key => $value)
									{
										$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength[]\" value=\"$value\" />";
									}
									foreach ($whichfeeoption as $key => $value)
									{
										$html .= "<input type=\"hidden\" name=\"whichfeeoption[]\" value=\"$value\" />";
									}
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/>";
								$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
								$html .= "<input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Next","WPBDM") . "\" /></p></form>";
							}
							else
							{
								$html .= "<p>" . __("If you prefer not to add an image click exit now. Your listing will be submitted for review.","WPBDM") . "</p>";
								$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><p>";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\" />";
								$html .= "<input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></p></form>";
							}
						}
						else
						{
							$submitactionword =__("submit your listing.","WPBDM");
							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
							{
								$submitactionword =__("submit your listing for review","WPBDM");
							}
							elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1']=='publish')
							{
								$submitactionword =__("publish your listing","WPBDM");
							}
							$html .= "<p>" . __("If you prefer not to upload an image at this time you can click the Exit now Button. Clicking the button will $submitactionword.","WPBDM") . "</p>";
							$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><p>";
							$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\" /><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></p></form>";
						}
					}
				}


		}
		else
		{

						if(!($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no"))
						{

							if($feepayval > 0)
							{
								$html .= "<p>" . __("Click Next to pay your listing fee. Your listing will not be published until your listing fee payment has been received and processed.","WPBDM") . "</p>";
								$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
								$html .= "<p><input type=\"hidden\" name=\"action\" value=\"payment_step_2\"/>";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
									foreach ($mycatduration as $key => $value)
									{
										$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength[]\" value=\"$value\" />";
									}
									foreach ($whichfeeoption as $key => $value)
									{
										$html .= "<input type=\"hidden\" name=\"whichfeeoption[]\" value=\"$value\" />";
									}
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/>";
								$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
								$html .= "<input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Next","WPBDM") . "\" /></p></form>";
							}
							else
							{
								$html .= "<p>" . __("If you prefer not to add an image click exit now. Your listing will be submitted for review.","WPBDM") . "</p>";
								$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><p>";
								$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\" />";
								$html .= "<input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></p></form>";
							}
						}
						else
						{
							$submitactionword =__("submit your listing.","WPBDM");
							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
							{
								$submitactionword =__("submit your listing for review","WPBDM");
							}
							elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1']=='publish')
							{
								$submitactionword =__("publish your listing","WPBDM");
							}
							$html .= "<p>" . __("If you prefer not to upload an image at this time you can click the Exit now Button. Clicking the button will $submitactionword.","WPBDM") . "</p>";
							$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><p>";
							$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\" /><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></p></form>";
						}

		}

	return $html;

}

function wpbusdirman_doupload()
{
	global $wpbusdirmanimagesdirectory,$wpbusdirmanthumbsdirectory,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirmanpermalink='';
	$wpbusdirmannumimgsallowed='';
	$wpbusdirmannumimgsleft='';
	$wpbusdirmanlistingpostid='';
	$neworedit='';
	$html = '';
	$mycatobj=array();
	$wpbusdirmanfeeoption=array();
	$wpbusdirmanlistingtermlength=array();

	if(isset($_REQUEST['wpbusdirmanlistingpostid'])
		&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
	{
		$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
	}


	if(isset($_REQUEST['wpbusdirmanlistingpostid'])
		&& !empty($_REQUEST['wpbusdirmanlistingpostid']))
	{
		$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
	}

	if(isset($_REQUEST['wpbusdirmannumimgsallowed'])
		&& !empty($_REQUEST['wpbusdirmannumimgsallowed']))
	{
		$wpbusdirmannumimgsallowed=$_REQUEST['wpbusdirmannumimgsallowed'];
	}

	if(isset($_REQUEST['wpbusdirmanlistingtermlength'])
		&& !empty($_REQUEST['wpbusdirmanlistingtermlength']))
	{
		$wpbusdirmanlistingtermlength=$_REQUEST['wpbusdirmanlistingtermlength'];
	}

	if(isset($_REQUEST['wpbusdirmannumimgsleft'])
		&& !empty($_REQUEST['wpbusdirmannumimgsleft']))
	{
		$wpbusdirmannumimgsleft=$_REQUEST['wpbusdirmannumimgsleft'];
	}

	if(isset($_REQUEST['wpbusdirmanpermalink'])
		&& !empty($_REQUEST['wpbusdirmanpermalink']))
	{
		$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
	}
	if(isset($_REQUEST['neworedit'])
		&& !empty($_REQUEST['neworedit']))
	{
		$neworedit=$_REQUEST['neworedit'];
	}
	if(isset($_REQUEST['wpbusdirmanfeeoption'])
		&& !empty($_REQUEST['wpbusdirmanfeeoption']))
	{
		$wpbusdirmanfeeoption=$_REQUEST['wpbusdirmanfeeoption'];
	}elseif(isset($_REQUEST['whichfeeoption'])
		&& !empty($_REQUEST['whichfeeoption']))
	{
		$wpbusdirmanfeeoption=$_REQUEST['whichfeeoption'];
	}

/*	print_r($wpbusdirmanfeeoption);
	echo "<br/>";

	print_r($wpbusdirmanlistingtermlength);

	echo "<p>Images allowed: $wpbusdirmannumimgsallowed</p>";
	echo "Images left: $wpbusdirmannumimgsleft";
	echo "Listing ID: $wpbusdirmanlistingpostid";
	die;*/

		//Rebuild mycatobj

		foreach($wpbusdirmanlistingtermlength as $catdur)
		{

			$mycatdurvals=explode("_",$catdur);
			$mycatid=$mycatdurvals[0];
			$listingincr=$mycatdurvals[1];

				$mycatobj[]=array('listingcat' => $mycatid,'listingduration' => $listingincr);

		} // End foreach wpbusdirmanlistingtermlength


	if ( !is_dir($wpbusdirmanimagesdirectory) )
	{
		@umask(0);
		@mkdir($wpbusdirmanimagesdirectory, 0777);
	}
	if ( !is_dir($wpbusdirmanthumbsdirectory) )
	{
		@umask(0);
		@mkdir($wpbusdirmanthumbsdirectory, 0777);
	}
	$wpbusdirmanimgmaxsize = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_13'];
	$wpbusdirmanimgminsize = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_14'];
	$wpbusdirmanimgmaxwidth = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_15'];
	$wpbusdirmanimgmaxheight = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_16'];
	$wpbusdirmanthumbnailwidth = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_17'];
	$wpbusdirmanallowedextensions = array(".jpg", ".gif", ".png");
	$wpbusdirmanerrornofiles=true;
	$wpbusdirmanuerror=array();
	for ($i=0;$i<$wpbusdirmannumimgsleft;$i++)
	{
		$wpbusdirmantheuploadedfilename = $_FILES['wpbusdirmanuploadpic'. $i]['name'];
		if(!empty($wpbusdirmantheuploadedfilename))
		{
			$wpbusdirmanerrornofiles=false;
		}
	}
	if ($wpbusdirmanerrornofiles)
	{
		$wpbusdirmanuerror[]="<p class=\"wpbusdirmanerroralert\">";
		$wpbusdirmanuerror[].=__("No file was selected","wpbusdirman");
		$wpbusdirmanuerror[].="</p>";

		$wpbusdirmanuploadformshow=apply_filters('wpbdm_show-image-upload-form', $wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror,$neworedit,$wpbusdirmanfeeoption);

		$html .= $wpbusdirmanuploadformshow;
	}
	else
	{
		$html .= wpbusdirmanuploadimages($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanimgmaxsize,$wpbusdirmanimgminsize,$wpbusdirmanthumbnailwidth,$wpbusdirmanuploaded_actual_field_name='wpbusdirmanuploadpic',$required=false,$neworedit,$wpbusdirmanfeeoption);
	}

	return $html;
}

function wpbusdirman_calculate_fee_to_pay($wpbusdirmanfeeoption)
{

	$wpbusdirmanthisfeetopay='';
	$wpbusdirmanthisfeetopayarr=array();

		if($wpbusdirmanfeeoption)
		{
			foreach($wpbusdirmanfeeoption as $feeopid)
			{
				$wpbusdirmanlistingcost=get_option('wpbusdirman_settings_fees_amount_'.$feeopid);
				$wpbusdirmanthisfeetopayarr[]=$wpbusdirmanlistingcost;
			}
		}

		if($wpbusdirmanthisfeetopayarr)
		{
			$wpbusdirmanthisfeetopay=array_sum($wpbusdirmanthisfeetopayarr);
		}
	return $wpbusdirmanthisfeetopay;

}

function wpbusdirman_validate_data()
{
	$wpbusdirman_field_item_array=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
	$wpbusdirman_field_errors='';

	if($wpbusdirman_field_item_array)
	{
		foreach($wpbusdirman_field_item_array as $wpbusdirman_field_x_field)
		{
			$wpbusdirman_field_name=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_x_field);
			$wpbusdirman_field_validation=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_x_field);
			$wpbusdirman_field_type=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_x_field);
			$wpbusdirman_field_options=get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_field_x_field);
			$wpbusdirman_field_required=get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_field_x_field);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_x_field);
			if($wpbusdirman_field_association == 'title')
			{
				$wpbusdirman_field_label_association="_title";
			}
			elseif($wpbusdirman_field_association == 'category')
			{
				$wpbusdirman_field_label_association="_category";
			}
			elseif($wpbusdirman_field_association == 'excerpt')
			{
				$wpbusdirman_field_label_association="_excerpt";
			}
			elseif($wpbusdirman_field_association == 'description')
			{
				$wpbusdirman_field_label_association="_description";
			}
			elseif($wpbusdirman_field_association == 'tags')
			{
				$wpbusdirman_field_label_association="_tags";
			}
			else
			{
				$wpbusdirman_field_label_association="_meta$wpbusdirman_field_x_field";
			}

			if($wpbusdirman_field_association == 'category')
			{
				if($wpbusdirman_field_type == 2){$wpbusdirman_field_inputname="cat";}
				elseif($wpbusdirman_field_type == 6){$wpbusdirman_field_inputname="wpbusdirman_field_label_category";}
			}
			else
			{
				$wpbusdirman_field_inputname="wpbusdirman_field_label";
				$wpbusdirman_field_inputname.=$wpbusdirman_field_label_association;
			}
			if (($wpbusdirman_field_required == 'yes')
				&& ((!isset($_REQUEST[$wpbusdirman_field_inputname])
					|| empty($_REQUEST[$wpbusdirman_field_inputname]))))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is required","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
			if ((($wpbusdirman_field_validation == 'missing')
				&& ($wpbusdirman_field_required == 'yes'))
				&& (!isset($_REQUEST[$wpbusdirman_field_inputname])
					|| empty($_REQUEST[$wpbusdirman_field_inputname])))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is required","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
			elseif (($wpbusdirman_field_validation == 'url')
				&& (isset($_REQUEST[$wpbusdirman_field_inputname]))
				&& (!empty($_REQUEST[$wpbusdirman_field_inputname]))
				&& (!wpbusdirman_isValidURL($_REQUEST[$wpbusdirman_field_inputname])))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is badly formatted. Valid URL format required. Include http://","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
			elseif (($wpbusdirman_field_validation == 'email')
				&& ($wpbusdirman_field_required == 'yes')
				&& (!wpbusdirman_isValidEmailAddress($_REQUEST[$wpbusdirman_field_inputname])))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is badly formatted. Valid Email format required.","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
			elseif (($wpbusdirman_field_validation == 'numericdeci')
				&& ($wpbusdirman_field_required == 'yes')
				&& (!is_numeric($_REQUEST[$wpbusdirman_field_inputname])))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name must be a number.","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
			elseif (($wpbusdirman_field_validation == 'numericwhole')
				&& ($wpbusdirman_field_required == 'yes')
				&& (!ctype_digit($_REQUEST[$wpbusdirman_field_inputname])))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name must be a number. Decimal values not allowed.","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
			elseif (($wpbusdirman_field_validation == 'date')
				&& ($wpbusdirman_field_required == 'yes')
				&& (!wpbusdirman_is_ValidDate($_REQUEST[$wpbusdirman_field_inputname])))
			{
				$error=true;
				$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
				$wpbusdirman_field_errors.=__("$wpbusdirman_field_name must be in the format 00/00/0000.","awpdb");
				$wpbusdirman_field_errors.="</li>";
			}
		}
	}

	return $wpbusdirman_field_errors;
}

function wpbusdirman_isValidURL($url)
{
	return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}

function wpbusdirman_isValidEmailAddress($email)
{
	if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email))
	{
		return false;
	}
	$email_array = explode("@", $email);
	$local_array = explode(".", $email_array[0]);
	for ($i = 0; $i < sizeof($local_array); $i++)
	{
    	if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i]))
    	{
			return false;
		}
	}
	if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
	{
		$domain_array = explode(".", $email_array[1]);
		if (sizeof($domain_array) < 2)
		{
			return false; // Not enough parts to domain
		}
		for ($i = 0; $i < sizeof($domain_array); $i++)
		{
			if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])| ?([A-Za-z0-9]+))$", $domain_array[$i]))
			{
				return false;
			}
		}
	}

	return true;
}

function wpbusdirman_is_ValidDate($date)
{
	list($themonth,$theday,$theyear)=explode("/",$date);
	$theday=(int)$theday;
	$themonth=(int)$themonth;
	$theyear=(int)$theyear;

	if ($theday!="" && $themonth!="" && $theyear!="")
	{
		if (is_numeric($theyear) && is_numeric($themonth) && is_numeric($theday))
   		{
       		 return checkdate($themonth,$theday,$theyear);
    	}
    }

	return false;
}

function wpbusdirmanuploadimages($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanimgmaxsize,$wpbusdirmanimgminsize,$wpbusdirmanthumbnailwidth,$wpbusdirmanuploaded_actual_field_name,$required,$neworedit,$wpbusdirmanfeeoption)
{

	//echo $wpbusdirmannumimgsleft;die;

	global $wpdb,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirmanwpbusdirmanerroralert=false;
	$wpbusdirmanfilesuploaded=true;
	$wpbusdirmanuerror=array();
	$uploaddir=get_option('upload_path');
	if(!isset($uploaddir) || empty($uploaddir))
	{
		$uploaddir=ABSPATH;
		$uploaddir.="wp-content/uploads";
		//$uploaddir = trim($uploaddir,'/');
	}
	$wpbusdirmanuploaddir=$uploaddir;
	$wpbusdirmanuploaddir.="/wpbdm";
	$wpbusdirmanuploadthumbsdir=$wpbusdirmanuploaddir;
	$wpbusdirmanuploadthumbsdir.="/thumbnails";
	$html = '';

	/* listing term length with cat id and term lenght for multi cat situations */
	$mycatduration=array();

	if($mycatobj && is_array($mycatobj))
	{

		foreach($mycatobj as $mycatobject)
		{
				$catduration=$mycatobject['listingcat'];
				$catduration.="_";
				$catduration.=$mycatobject['listingduration'];
				$mycatduration[]=$catduration;
		}
	}


	if ( !is_dir($wpbusdirmanuploaddir) )
	{
		umask(0);
		mkdir($wpbusdirmanuploaddir, 0777);
	}
	if ( !is_dir($wpbusdirmanuploadthumbsdir) )
	{
		umask(0);
		mkdir($wpbusdirmanuploadthumbsdir, 0777);
	}
	for($i=0;$i<$wpbusdirmannumimgsleft;$i++)
	{
		$wpbusdirmanuploadedfilename=addslashes($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['name']);
		$wpbusdirmanuploaded_ext=strtolower(substr(strrchr($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['name'],"."),1));
		$wpbusdirmanuploaded_ext_array=array('gif','jpg','jpeg','png');
		if (isset($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name'])
			&& is_uploaded_file($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']))
		{
			$wpbusdirman_imginfo = getimagesize($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']);
			$wpbusdirman_imgfilesizeval=filesize($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']);
			$wpbusdirmandesired_filename=mktime();
			$wpbusdirmandesired_filename.="_$i";
			if(isset($wpbusdirmanuploadedfilename)
				&& !empty($wpbusdirmanuploadedfilename))
			{
				if (!(in_array($wpbusdirmanuploaded_ext, $wpbusdirmanuploaded_ext_array)))
				{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("had an invalid file extension and was not uploaded","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
				}
				elseif(filesize($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']) <= $wpbusdirmanimgminsize)
				{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">";
					$wpbusdirmanuerror[].=__("The size of $wpbusdirmanuploadedfilename was too small. The file was not uploaded. File size must be greater than $wpbusdirmanimgminsize bytes","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
				}
				elseif($wpbusdirman_imginfo[0]< $wpbusdirmanthumbnailwidth)
				{
					// width is too short
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("did not meet the minimum width of [$wpbusdirmanthumbnailwidth] pixels. The file was not uploaded","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
				}
				elseif ($wpbusdirman_imginfo[1]< $wpbusdirmanthumbnailwidth)
				{
					// height is too short
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("did not meet the minimum height of [$wpbusdirmanthumbnailwidth] pixels. The file was not uploaded","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
				}
				elseif(!isset($wpbusdirman_imginfo[0])
					&& !isset($wpbusdirman_imginfo[1]))
				{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("does not appear to be a valid image file","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
				}
				elseif( $wpbusdirman_imgfilesizeval > $wpbusdirmanimgmaxsize )
				{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("was larger than the maximum allowed file size of [$wpbusdirmanimgmaxsize] bytes. The file was not uploaded");
					$wpbusdirmanuerror[].="</p>";
				}
				elseif(!empty($wpbusdirmandesired_filename))
				{
					$wpbusdirmanuploadedfilename="$wpbusdirmandesired_filename.$wpbusdirmanuploaded_ext";
					if (!move_uploaded_file($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name'],$wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename))
					{
						$wpbdmor=$wpbusdirmanuploadedfilename;
						$wpbusdirmanuploadedfilename='';
						$wpbusdirmanwpbusdirmanerroralert=true;
						$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbdmor]";
						$wpbusdirmanuerror[].=__("could not be moved to the destination directory $wpbusdirmanuploaddir","wpbusdirman");
						$wpbusdirmanuerror[].="</p>";
					}
					else
					{
						if(!wpbusdirmancreatethumb($wpbusdirmanuploadedfilename,$wpbusdirmanuploaddir,$wpbusdirmanthumbnailwidth))
						{
							$wpbusdirmanwpbusdirmanerroralert=true;
							$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">";
							$wpbusdirmanuerror[].=__("Could not create thumbnail image of [ $wpbusdirmanuploadedfilename ]","wpbusdirman");
							$wpbusdirmanuerror[].="</p>";
						}
						@chmod($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename,0644);

						add_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='image', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename, false) or update_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='image', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename);
						add_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='thumbnail', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename, false) or update_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='thumbnail', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename);
						add_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmannumimgsallowed, true) or update_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmannumimgsallowed);

					/*	if($mycatduration)
						{
							foreach($mycatduration as $catdur)
							{
								$existingtermlengths=get_post_meta($wpbusdirmanlistingpostid, "termlength", false);

								if(!in_array($catdur,$existingtermlengths))
								{
									add_post_meta($wpbusdirmanlistingpostid, "termlength", $catdur, false);
								}
							}
						}

						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
						{
							if($wpbusdirmanfeeoption)
							{
								foreach($wpbusdirmanfeeoption as $feeopid)
								{
									$wpbusdirmanlistingcost=get_option('wpbusdirman_settings_fees_amount_'.$feeopid);
									add_post_meta($wpbusdirmanlistingpostid, "costoflisting", $wpbusdirmanlistingcost, false) or update_post_meta($wpbusdirmanlistingpostid, "costoflisting", $wpbusdirmanlistingcost);
									add_post_meta($wpbusdirmanlistingpostid, "listingfeeid", $feeopid, false) or update_post_meta($wpbusdirmanlistingpostid, "costoflisting", $feeopid);
								}
							}
						} */
					}
				}
			}
			else
			{
				$wpbusdirmanwpbusdirmanerroralert=true;
				$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">";
				$wpbusdirmanuerror[].=__("Unknown error encountered uploading image","wpbusdirman");
				$wpbusdirmanuerror[].="</p>";
			}
		}
	} // Close for $i...
	if ($wpbusdirmanwpbusdirmanerroralert)
	{
		$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);

		$new_wpbusdirmannumimagesallowed=$myimagesallowedleft['imagesallowed'];
		$new_wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];

		$wpbusdirmanuploadformshow=apply_filters('wpbdm_show-image-upload-form', $wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$new_wpbusdirmannumimagesallowed,$new_wpbusdirmannumimgsleft,$mycatobj,$wpbusdirmanuerror,$neworedit,$wpbusdirmanfeeoption);
		$html .= $wpbusdirmanuploadformshow;
	}
	else
	{
		if(isset($neworedit)
			&& !empty($neworedit)
			&& ($neworedit == 'edit'))
		{
			if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'] == 'pending2')
			{
				$html .= "<p>" . __("Your listing has been updated. Your listing is currently pending re-review and will become accessible again once the administrator has reviewed it.","WPBDM") . "</p>";
			}
			elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'] == 'publish2')
			{
				$html .= "<p>" . __("Your listing has been updated. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
			}
			else
			{
				$html .= "<p>" . __("You are finished with your listing.","WPBDM") . "</p><form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></form>";
			}
		}
		else
		{
			if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
			{

				$wpbusdirmanthisfeetopay=wpbusdirman_calculate_fee_to_pay($wpbusdirmanfeeoption);

				if($wpbusdirmanthisfeetopay > 0)
				{
					$html .= wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$mycatduration,$wpbusdirmanthisfeetopay);
				}
				else
				{
					// There is no fee to pay so skip to end of process. Nothing left to do
					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
					{
						$html .= "<p>" . __("Your submission has been received and is currently pending review","WPBDM") . "</p>";
					}
					elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'publish')
					{
						$html .= "<p>" . __("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
					}
					else
					{
						$html .= "<p>" . __("You are finished with your listing.","WPBDM") . "</p>";
						$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></form>";
					}
				}
			}
			else
			{
				if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
				{
					$html .= "<p>" . __("Your submission has been received and is currently pending review","WPBDM") . "</p>";
				}
				elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'publish')
				{
					$html .= "<p>" . __("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM") . "</p>";
				}
				else
				{
					$html .= "<p>" . __("You are finished with your listing.","WPBDM") . "</p><form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"" . __("Exit Now","WPBDM") . "\" /></form>";
				}
			}
		}
	}

	return $html;
}

function wpbusdirmancreatethumb($wpbusdirmanuploadedfilename,$wpbusdirmanuploaddir,$wpbusdirmanthumbnailwidth)
{
		$wpbusdirman_show_all=true;
		$wpbusdirman_thumbs_width=$wpbusdirmanthumbnailwidth;
		$mynewimg='';
		if (extension_loaded('gd')) {
			if ($wpbusdirman_imginfo=getimagesize($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename)) {
				$width=$wpbusdirman_imginfo[0];
				$height=$wpbusdirman_imginfo[1];
				if ($width>$wpbusdirman_thumbs_width) {
					$newwidth=$wpbusdirman_thumbs_width;
					$newheight=$height*($wpbusdirman_thumbs_width/$width);
					if ($wpbusdirman_imginfo[2]==1) {		//gif
					} elseif ($wpbusdirman_imginfo[2]==2) {		//jpg
						if (function_exists('imagecreatefromjpeg')) {
							$myimg=@imagecreatefromjpeg($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename);
						}
					} elseif ($wpbusdirman_imginfo[2]==3) {	//png
						$myimg=@imagecreatefrompng($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename);
					}
					if (isset($myimg) && !empty($myimg)) {
						$gdinfo=wpbusdirman_GD();
						if (stristr($gdinfo['GD Version'], '2.')) {	// if we have GD v2 installed
							$mynewimg=@imagecreatetruecolor($newwidth,$newheight);
							if (imagecopyresampled($mynewimg,$myimg,0,0,0,0,$newwidth,$newheight,$width,$height)) {
								$wpbusdirman_show_all=false;
							}
						} else {	// GD 1.x here
							$mynewimg=@imagecreate($newwidth,$newheight);
							if (@imagecopyresized($mynewimg,$myimg,0,0,0,0,$newwidth,$newheight,$width,$height)) {
								$wpbusdirman_show_all=false;
							}
						}
					}
				}
			}
		}
		if (!is_writable($wpbusdirmanuploaddir.'/thumbnails')) {
			@chmod($wpbusdirmanuploaddir.'/thumbnails',0755);
			if (!is_writable($wpbusdirmanuploaddir.'/thumbnails')) {
				@chmod($wpbusdirmanuploaddir.'/thumbnails',0777);
			}
		}
		if ($wpbusdirman_show_all) {
			$myreturn=@copy($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename,$wpbusdirmanuploaddir.'/thumbnails/'.$wpbusdirmanuploadedfilename);
		} else {
			$myreturn=@imagejpeg($mynewimg,$wpbusdirmanuploaddir.'/thumbnails/'.$wpbusdirmanuploadedfilename,100);
		}
		@chmod($wpbusdirmanuploaddir.'/thumbnails/'.$wpbusdirmanuploadedfilename,0644);
	return $myreturn;
}



		function wpbusdirman_GD()
		{
			$myreturn=array();
			if (function_exists('gd_info'))
			{
				$myreturn=gd_info();
			} else
			{
				$myreturn=array('GD Version'=>'');
				ob_start();
				phpinfo(8);
				$info=ob_get_contents();
				ob_end_clean();
				foreach (explode("\n",$info) as $line)
				{
					if (strpos($line,'GD Version')!==false)
					{
						$myreturn['GD Version']=trim(str_replace('GD Version', '', strip_tags($line)));
					}
				}
			}
			return $myreturn;
		}

function wpbusdirman_imagesallowed_left($wpbusdirmanlistingpostid,$wpbusdirmanfeeoptions)
{

	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	global $wpbusdirmanconfigoptionsprefix;

	$imagesalloweleftobj="";

	if($wpbusdirmanlistingpostid)
	{
		$existingfeeids=get_post_meta($wpbusdirmanlistingpostid,'listingfeeid',false);

			if($existingfeeids)
			{
				foreach($existingfeeids as $existingfeeid)
				{
					$wpbusdirmannumimgsallowedarr[]=get_option('wpbusdirman_settings_fees_images_'.$existingfeeid);
				}

				$wpbusdirmannumimagesallowed=max($wpbusdirmannumimgsallowedarr);
			}
			else
			{
				if($wpbusdirmanfeeoptions)
				{
					foreach($wpbusdirmanfeeoptions as $wpbusdirmanfeeoption)
					{
						$wpbusdirmannumimgsallowed=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirmanfeeoption);
						$wpbusdirmannumimgsallowedarr[]=$wpbusdirmannumimgsallowed;
					}

						$wpbusdirmannumimagesallowed=max($wpbusdirmannumimgsallowedarr);
				}
				else
				{
					$wpbusdirmannumimagesallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
				}
			}
	}
	else
	{
		if($wpbusdirmanfeeoptions)
		{
			foreach($wpbusdirmanfeeoptions as $wpbusdirmanfeeoption)
			{
				$wpbusdirmannumimgsallowed=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirmanfeeoption);
				$wpbusdirmannumimgsallowedarr[]=$wpbusdirmannumimgsallowed;
			}

				$wpbusdirmannumimagesallowed=max($wpbusdirmannumimgsallowedarr);
		}
		else
		{
				$wpbusdirmannumimagesallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
		}
	}

		$existingimages=get_post_meta($wpbusdirmanlistingpostid,'image',false);
		if($existingimages){ $totalexistingimages=count($existingimages); } else { $totalexistingimages = 0;}

		if($totalexistingimages > 0){$wpbusdirmannumimgsleft=($wpbusdirmannumimagesallowed - $totalexistingimages );}else{$wpbusdirmannumimgsleft = $wpbusdirmannumimagesallowed;}

		$imagesalloweleftobj=array('listingid' => $wpbusdirmanlistingpostid, 'imagesallowed' => $wpbusdirmannumimagesallowed, 'imagesleft' =>  $wpbusdirmannumimgsleft,'totalexisting' => $totalexistingimages );

	return $imagesalloweleftobj;
}

function wpbusdirman_managelistings()
{
	global $siteurl,$wpbdmimagesurl,$wpbusdirman_gpid,$permalinkstructure,$wpbdmposttype,$wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	if(!(is_user_logged_in()))
	{
		$wpbusdirmanloginurl=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_4'];
		if(!isset($wpbusdirmanloginurl) || empty($wpbusdirmanloginurl))
		{
			$wpbusdirmanloginurl=$siteurl.'/wp-login.php';
		}
		$html .= "<p>" . __("You are not currently logged in. Please login or register first. When registering, you will receive an activation email. Be sure to check your spam if you don't see it in your email with 60 mintues.","WPBDM") . "</p>";
		$html .= "<form method=\"post\" action=\"$wpbusdirmanloginurl\"><input type=\"submit\" class=\"insubmitbutton\" value=\"" . __("Login Now","WPBDM") . "\" /></form>";
	}
	else
	{
		$args=array('hide_empty' => 0);
		$wpbusdirman_postcats=get_terms( $wpbdmposttypecategory, $args);
		if(!isset($wpbusdirman_postcats) || empty($wpbusdirman_postcats))
		{
			if(is_user_logged_in() && current_user_can('install_plugins'))
			{
				$html .= "<p>" . __("There are no categories assigned to the business directory yet. You need to assign some categories to the business directory. Only admins can see this message. Regular users are seeing a message that they do not currently have any listings to manage. Listings cannot be added until you assign categories to the business directory. ","WPBDM") . "</p>";
			}
			else
			{
				$html .= "<p>" . __("You do not currently have any listings to manage","WPBDM") . "</p>";
			}
		}
		else
		{
			global $current_user;
			$html .= get_currentuserinfo();
			$wpbusdirman_CUID=$current_user->ID;
			wp_reset_query();
			$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
			query_posts('author='.$wpbusdirman_CUID.'&post_type='.$wpbdmposttype);
			if ( have_posts() )
			{
				$html .= '<p>' . __("Your current listings are shown below. To edit a listing click the edit button. To delete a listing click the delete button.","WPBDM") . "</p>";
				while (have_posts())
				{
					$html .= the_post();
					$html .= wpbusdirman_post_excerpt();
				}
				$html .= '<div class="navigation">';
				if(function_exists('wp_pagenavi'))
				{
					$html .= wp_pagenavi();
				}
				else
				{
					$html .= '<div class="alignleft">' . next_posts_link('&laquo; Older Entries') . '</div><div class="alignright">' . previous_posts_link('Newer Entries &raquo;') . '</div>';
				}
				$html .= '</div>';
			}
			else
			{
				 $html .= "<p>" . __("You do not currently have any listings in the directory","WPBDM") . "</p>";
			}
			wp_reset_query();
		}
	}

	return $html;
}

function wpbusdirman_deleteimage($imagetodelete,$wpbdmlistingid,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanpermalink,$neworedit)
{
	global $wpbusdirmanimagesdirectory,$wpbusdirmanthumbsdirectory;
	$html = '';

	if(isset($imagetodelete)
		&& !empty($imagetodelete))
	{
		if(isset($wpbdmlistingid)
			&& !empty($wpbdmlistingid))
		{
			delete_post_meta($wpbdmlistingid, "image", $imagetodelete);
			delete_post_meta($wpbdmlistingid, "thumbnail", $imagetodelete);
			if (file_exists($wpbusdirmanimagesdirectory.'/'.$imagetodelete))
			{
				@unlink($wpbusdirmanimagesdirectory.'/'.$imagetodelete);
			}
			if (file_exists($wpbusdirmanthumbsdirectory.'/'.$imagetodelete))
			{
				@unlink($wpbusdirmanthumbsdirectory.'/'.$imagetodelete);
			}
			$wpbusdirmannumimgsleft=($wpbusdirmannumimgsleft + 1);
		}
	}
	$html .= apply_filters('wpbdm_show-image-upload-form', $wpbdmlistingid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');

	return $html;
}

function wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$wpbusdirmanlengthofterm,$wpbusdirmanlistingcost)
{
	global $wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule,$wpbusdirman_gpid,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirman_get_currency_symbol=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_12'];

	if(!isset($wpbusdirman_get_currency_symbol)
		|| empty($wpbusdirman_get_currency_symbol))
	{
		$wpbusdirman_get_currency_symbol="$";
	}
	$wpbusdirman_googlecheckout_button='';
	$wpbusdirman_paypal_button='';
	$wpbusdirman_twocheckout_button='';
	$html = '';


	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
	{

		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
		{
			$myimagesallowedleft=wpbusdirman_imagesallowed_left($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);

			$wpbusdirmannumimagesallowed=$myimagesallowedleft['imagesallowed'];
			$wpbusdirmannumimgsleft=$myimagesallowedleft['imagesleft'];

			add_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmannumimagesallowed, true) or update_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmannumimagesallowed);
		}
	}
	$html .= "<h3>" . __("Step 3","WPBDM") . "</h3><br />";
	global $wpbusdirman_imagesurl;
	if(($wpbusdirman_hasgooglecheckoutmodule == 1)
		&& ($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_40'] != "yes"))
	{ 	$html .= "<h4 class=\"paymentheader\">" . __("Pay ", "WPBDM");
		$html .= $wpbusdirman_get_currency_symbol;
		$html .= $wpbusdirmanlistingcost;
		$html .= __(" listing fee via Google Checkout","WPBDM") . "</h4>";
		$wpbusdirman_googlecheckout_button=wpbusdirman_googlecheckout_button($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$wpbusdirmanlistingcost);
		$html .= "<div class=\"paymentbuttondiv\">" . $wpbusdirman_googlecheckout_button . "</div>";
	}
	if(($wpbusdirman_haspaypalmodule == 1)
		&& ($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_41'] != "yes"))
	{
		$html .= "<h4 class=\"paymentheader\">" . __("Pay ","WPBDM");
		$html .= $wpbusdirman_get_currency_symbol;
		$html .= $wpbusdirmanlistingcost;
		$html .= __(" listing Fee via PayPal","WPBDM") . "</h4>";
		$wpbusdirman_paypal_button=wpbusdirman_paypal_button($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$wpbusdirman_imagesurl,$wpbusdirmanlistingcost);
		$html .= "<div class=\"paymentbuttondiv\">" . $wpbusdirman_paypal_button . "</div>";
	}

	if(($wpbusdirman_hastwocheckoutmodule == 1)
		&& ($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_43'] != "yes"))
	{
		$html .= "<h4 class=\"paymentheader\">" . __("Pay ", "WPBDM");
		$html .= $wpbusdirman_get_currency_symbol;
		$html .= $wpbusdirmanlistingcost;
		$html .= __(" listing fee via 2Checkout","WPBDM") . "</h4>";
		$wpbusdirman_twocheckout_button=wpbusdirman_twocheckout_button($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption,$wpbusdirman_gpid,$wpbusdirmanlistingcost);
		$html .= "<div class=\"paymentbuttondiv\">" . $wpbusdirman_twocheckout_button . "</div>";
	}

	return $html;
}

function wpbusdirman_feepay_configure($post_category_item)
{

	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_get_currency_symbol=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_12'];

	if(!isset($wpbusdirman_get_currency_symbol)
		|| empty($wpbusdirman_get_currency_symbol))
	{
		$wpbusdirman_get_currency_symbol="$";
	}



	$wpbusdirman_settings_fees_ops=wpbusdirman_retrieveoptions($whichoptionvalue='wpbusdirman_settings_fees_label_');
	$wpbusdirman_fee_to_pay_li = '';
	$html = '';

	global $wpbusdirman_hastwocheckoutmodule,$wpbusdirman_haspaypalmodule,$wpbusdirman_hasgooglecheckoutmodule;
	if( $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_40'] == "yes"
		&& $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_41'] == "yes"
			&& $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_43'] == "yes"
				&& $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes" )
						{
						$wpbusdirman_fee_to_pay_li='';
						return $wpbusdirman_fee_to_pay_li;
						}

						else
						{


	if($wpbusdirman_settings_fees_ops)
	{

		foreach($wpbusdirman_settings_fees_ops as $wpbusdirman_settings_fees_op)
		{
			// Retrieve the categories that are saved under this fee,check if the posted category is one and if so add $wpbusdirman_settings_fees_op to array with category and fee objects

			// Retrieve the categories
			$wpbusdirman_categories_under=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirman_settings_fees_op);


				$temp = explode(',',$wpbusdirman_categories_under);
				foreach ($temp as $categ_id)
				{

					$wpbusdirman_savedcatid=trim($categ_id);

					$wpbusdirman_get_fee=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_get_fee_arr[]=$wpbusdirman_get_fee;

					$wpbusdirman_fee_op_name=get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_fee_op_images=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_fee_op_increment=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_settings_fees_op);

					$wpbusdirman_settings_fees_and_cats[]=array('category'=> $wpbusdirman_savedcatid,'feeop'=> $wpbusdirman_settings_fees_op,'feeamount' => $wpbusdirman_get_fee, 'feelabelname' => $wpbusdirman_fee_op_name,'feeimages' => $wpbusdirman_fee_op_images,'feeincrement' =>$wpbusdirman_fee_op_increment  );

				} // End foreach

		} // End foreach



				foreach( $post_category_item as $mypostcategory )
				{

					$term = get_term($mypostcategory,$wpbdmposttypecategory,'','');
					$thecategoryname=$term->name;

					$wpbusdirman_fee_to_pay_li.='<h4 class="feecategoriesheader">';
					$wpbusdirman_fee_to_pay_li.=$thecategoryname;
					$wpbusdirman_fee_to_pay_li.=__(" fee options", "WPBDM");
					$wpbusdirman_fee_to_pay_li.="</h4>";

					foreach($wpbusdirman_settings_fees_and_cats as $wpbusdirmansettingsfeeandcat)
					{
						$catid=$wpbusdirmansettingsfeeandcat['category'];
						if($catid == 0){$catid=$mypostcategory;}

						$feeid=$wpbusdirmansettingsfeeandcat['feeop'];
						$feeamt=$wpbusdirmansettingsfeeandcat['feeamount'];
						$feelname=$wpbusdirmansettingsfeeandcat['feelabelname'];
						$feeimages=$wpbusdirmansettingsfeeandcat['feeimages'];
						$feeduration=$wpbusdirmansettingsfeeandcat['feeincrement'];


						if( $mypostcategory == $catid )
						{

							$checked='';
							$myfeamt='';

							$wpbusdirman_fee_to_pay_li.="<p><input type=\"radio\" name=\"whichfeeoption_$catid\" value=\"$feeid\" checked />$feelname $wpbusdirman_get_currency_symbol$feeamt (";
								$wpbusdirman_fee_to_pay_li.=__(" Listing will run for ","WPBDM");
								$wpbusdirman_fee_to_pay_li.= $feeduration;
								$wpbusdirman_fee_to_pay_li.=__(" days","WPBDM");

								if( ($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes") && ($feeimages > 0))
								{
									$wpbusdirman_fee_to_pay_li.=__(" - listing includes ","WPBDM");
									$wpbusdirman_fee_to_pay_li.= $feeimages;
									$wpbusdirman_fee_to_pay_li.=__(" images","WPBDM");
								}

								$wpbusdirman_fee_to_pay_li.=")</p>";
						}

					} // End foreach

				} // End foreach


		}

	}


	return $wpbusdirman_fee_to_pay_li;
}

function wpbusdirman_msort($array, $id="id", $sort_ascending=true) {
        $temp_array = array();
        while(count($array)>0) {
            $lowest_id = 0;
            $index=0;
            foreach ($array as $item) {
                if (isset($item[$id])) {
                                    if ($array[$lowest_id][$id]) {
                    if ($item[$id]<$array[$lowest_id][$id]) {
                        $lowest_id = $index;
                    }
                    }
                                }
                $index++;
            }
            $temp_array[] = $array[$lowest_id];
            $array = array_merge(array_slice($array, 0,$lowest_id), array_slice($array, $lowest_id+1));
        }
                if ($sort_ascending) {
            return $temp_array;
                } else {
                    return array_reverse($temp_array);
                }
    }

function wpbusdirman_contactform($wpbusdirmanpermalink,$wpbusdirmanlistingpostid,$commentauthorname,$commentauthoremail,$commentauthorwebsite,$commentauthormessage,$wpbusdirmancontacterrors)
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	if(!isset($wpbusdirmanpermalink) || empty($wpbusdirmanpermalink))
	{
		global $wpbusdirman_gpid,$wpbdmimagesurl;
		$wpbusdirmanpermalink=get_permalink($wpbusdirman_gpid);
	}
	$html = '';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_27'] == "yes")
	{
		if(isset($wpbusdirmancontacterrors)
			&& !empty($wpbusdirmancontacterrors))
		{
			$html .= "<ul id=\"wpbusdirmanerrors\">$wpbusdirmancontacterrors</ul>";
		}
		$html .= "<h4>" . __("Send Message to listing owner","WPBDM") . "</h4><p><label>" . __("Listing Title: ","WPBDM") . "</label>" . get_the_title($wpbusdirmanlistingpostid) . "</p>";
		$html .= "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
		if(!is_user_logged_in())
		{
			$html .= "<p><label style=\"width:4em;\">" . __("Your Name ","WPBDM") . "</label><input type=\"text\" class=\"intextbox\" name=\"commentauthorname\" value=\"$commentauthorname\" /></p><p><label style=\"width:4em;\">" . __("Your Email ","WPBDM") . "</label><input type=\"text\" class=\"intextbox\" name=\"commentauthoremail\" value=\"$commentauthoremail\" /></p>";
			$html .= "<p><label style=\"width:4em;\">" . __("Website url ","WPBDM") . "</label><input type=\"text\" class=\"intextbox\" name=\"commentauthorwebsite\" value=\"$commentauthorwebsite\" /></p>";
		}
		elseif(is_user_logged_in())
		{
			if(!isset($commentauthorname) || empty($commentauthorname))
			{
				global $post, $current_user;
				get_currentuserinfo();
				$commentauthorname = $current_user->user_login;
			}
			$html .= "<p>" . __("You are currently logged in as ","WPBDM") . $commentauthorname . "." . __(" Your message will be sent using your logged in contact email.","WPBDM") . "</p>";
		}
		$html .= "<p><label style=\"width:4em;\">" . __("Message","WPBDM") . "</label><br/><br/><textarea name=\"commentauthormessage\" class=\"intextarea\">$commentauthormessage</textarea></p>";
		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_30'] == "yes")
		{
			$publickey = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_28'];
			if(isset($publickey)
				&& !empty($publickey))
			{
				require_once('recaptcha/recaptchalib.php');
				$wpbdmrecaptcha=recaptcha_get_html($publickey);
				$html .= recaptcha_get_html($publickey);
			}
		}
		$html .= "<p><input type=\"hidden\" name=\"action\" value=\"sendcontactmessage\" />";
		$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\" />";
		$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\" />";
		$html .= "<input type=\"submit\" class=\"insubmitbutton\" value=\"Send\" /></p></form>";
	}

	return $html;
}


function wpbusdirman_upgradetosticky($wpbdmlistingid)
{
 	global $wpbusdirman_imagesurl,$wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule,$wpbusdirman_gpid,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_get_currency_symbol=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_12'];

	if(!isset($wpbusdirman_get_currency_symbol)
		|| empty($wpbusdirman_get_currency_symbol))
	{
		$wpbusdirman_get_currency_symbol="$";
	}

	$html = '';
 	$html .= "<h4>" . __("Upgrade listing","WPBDM") . "</h4>";
 	$wpbdmstickydetailtext=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_33'];
 	if(isset($wpbdmstickydetailtext)
 		&& !empty($wpbdmstickydetailtext))
 	{
 		$html .= "<p>$wpbdmstickydetailtext</p>";
 	}
 	$wpbusdirman_stickylistingprice=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_32'];
 	add_post_meta($wpbdmlistingid, "sticky", "not paid", true) or update_post_meta($wpbdmlistingid, "sticky", "not paid");
	if($wpbusdirman_hasgooglecheckoutmodule == 1)
	{
		$html .= "<h4 class=\"paymentheader\">" . __("Pay ", "WPBDM");
		$html .= $wpbusdirman_get_currency_symbol;
		$html .= $wpbusdirman_stickylistingprice;
		$html .= __(" upgrade fee via Google Checkout","WPBDM") . "</h4>";
		$wpbusdirman_googlecheckout_button=wpbusdirman_googlecheckout_button($wpbdmlistingid,$wpbusdirmanfeeoption='32',$wpbusdirman_stickylistingprice);
		$html .= "<div class=\"paymentbuttondiv\">" . $wpbusdirman_googlecheckout_button . "</div>";
	}

	if($wpbusdirman_haspaypalmodule == 1)
	{
		$html .= "<h4 class=\"paymentheader\">" . __("Pay ", "WPBDM");
		$html .= $wpbusdirman_get_currency_symbol;
		$html .= $wpbusdirman_stickylistingprice;
		$html .= __(" upgrade fee via PayPal","WPBDM") . "</h4>";
		$wpbusdirman_paypal_button=wpbusdirman_paypal_button($wpbdmlistingid,$wpbusdirmanfeeoption='32',$wpbusdirman_imagesurl,$wpbusdirman_stickylistingprice);
		$html .= "<div class=\"paymentbuttondiv\">" . $wpbusdirman_paypal_button . "</div>";
	}

	if($wpbusdirman_hastwocheckoutmodule == 1)
	{
		$html .= "<h4 class=\"paymentheader\">" . __("Pay ", "WPBDM");
		$html .= $wpbusdirman_get_currency_symbol;
		$html .= $wpbusdirman_stickylistingprice;
		$html .= __(" upgrade fee via 2Checkout","WPBDM") . "</h4>";
		$wpbusdirman_twocheckout_button=wpbusdirman_twocheckout_button($wpbdmlistingid,$wpbusdirmanfeeoption='32',$wpbusdirman_gpid,$wpbusdirman_stickylistingprice);
		$html .= "<div class=\"paymentbuttondiv\">" . $wpbusdirman_twocheckout_button . "</div>";
	}

	return $html;
}

function wpbusdirman_featured_pending()
{
	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	$html .= wpbusdirman_admin_head();
	if( isset($_REQUEST['action'])
		&& !empty($_REQUEST['action'])
		&& ($_REQUEST['action'] == 'upgradefeatured'))
	{
		if(isset($_REQUEST['id'])
			&& !empty($_REQUEST['id']))
		{
			$wpbdmposttofeature=$_REQUEST['id'];
			update_post_meta($wpbdmposttofeature, "sticky", "approved");
			$html .= "<p>" . __("The listing has been upgraded.","WPBDM") . "</p>";
		}
		else
		{
			$html .= "<p>" . __("No ID was provided. Please try again","WPBDM") . "</p>";
		}
	}
	if( isset($_REQUEST['action'])
		&& !empty($_REQUEST['action'])
		&& ($_REQUEST['action'] == 'cancelfeatured'))
	{
		if(isset($_REQUEST['id'])
			&& !empty($_REQUEST['id']))
		{
			$wpbdmposttofeature=$_REQUEST['id'];
			delete_post_meta($wpbdmposttofeature, "sticky","pending");
			$html .= "<p>" . __("The listing has been downgraded.","WPBDM") . "</p>";
		}
		else
		{
			$html .= "<p>" . __("No ID was provided. Please try again","WPBDM") . "</p>";
		}
	}
	$html .= "<h3 style=\"padding:10px;\">" . __("Manage Featured Listings pending manual upgrade", "WPBDM") . "</h3>";
	$wpbusdirman_pending='';
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_31'] == "no")
	{
		$html .= "<p>" . __("You are not currently allowing sticky (featured) listings. To allow sticky listings check that option in the manage options page under the Featured/Sticky listing settings.","WPBDM") . "</p>";
	}
	else
	{
		global $wpbusdirman_valuetext, $wpbusdirman_labeltext,$wpbusdirman_actiontext,$wpbdmposttypecategory,$wpbdmposttype;
		$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

		$wpbusdirman_catcat=get_posts('post_type='.$wpbdmposttype);
		if($wpbusdirman_catcat)
		{
			foreach($wpbusdirman_catcat as $wpbusdirman_cat)
			{
				$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
			}
		}
		if($wpbusdirman_postsposts)
		{
			foreach($wpbusdirman_postsposts as $wpbusdirman_post)
			{
				$wpbdmsticky=get_post_meta($wpbusdirman_post, "sticky",$single=true);

				if($wpbdmsticky == 'pending')
				{
					$wpbusdirman_pendingfeatured[]=$wpbusdirman_post;
				}
			}
		}
		if(empty($wpbusdirman_pendingfeatured))
		{
			$html .= "<p>" . __("Currently there are no listings waiting to be upgraded to sticky(featured) status","WPBDM") . "</p>";
		}
		else
		{
			$html .= "<table class=\"widefat\" cellspacing=\"0\"><thead><tr>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Post Title","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Post ID","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
			$html .= "</tr></thead><tfoot><tr>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Post Title","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Post ID","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
			$html .= "</tr></tfoot><tbody>";
			if($wpbusdirman_pendingfeatured)
			{
				foreach($wpbusdirman_pendingfeatured as $wpbusdirman_pendingfeatureditem)
				{
					$html .= "<tr><td><a href=\"" . get_permalink($wpbusdirman_pendingfeatureditem) . "\">" . get_the_title($wpbusdirman_pendingfeatureditem)."</a></td>";
					$html .= "<td>$wpbusdirman_pendingfeatureditem</td><td><a href=\"?page=wpbdman_c4&action=upgradefeatured&id=$wpbusdirman_pendingfeatureditem\">" . __("Upgrade","WPBDM") . "</a> | <a href=\"?page=wpbdman_c4&action=cancelfeatured&id=$wpbusdirman_pendingfeatureditem\">" . __("Downgrade","WPBDM") . "</td></tr>";
				}
			}
		}
		$html .= "</tbody></table>";
	}
	$html .= wpbusdirman_admin_foot();

	echo $html;
}

function wpbusdirman_manage_paid()
{
	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	$html .= wpbusdirman_admin_head();
	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'setaspaid'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbdmposttosetaspaid=$_REQUEST['id'];
			update_post_meta($wpbdmposttosetaspaid, "paymentstatus", "paid");
			$html .= "<p>" . __("The listing status has been set as paid.","WPBDM") . "</p>";
		}
		else
		{
			$html .= "<p>" . __("No ID was provided. Please try again","WPBDM") . "</p>";
		}
	}
	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'setasnotpaid'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbdmposttosetasnotpaid=$_REQUEST['id'];
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","pending");
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","refunded");
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","unknown");
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","cancelled");
			$html .= "<p>" . __("The listing status has been changed non-paying.","WPBDM") . "</p>";
		}
		else
		{
			$html .= "<p>" . __("No ID was provided. Please try again","WPBDM") . "</p>";
		}
	}
	$html .= "<h3 style=\"padding:10px;\">" . __("Manage Paid Listings", "WPBDM") . "</h3>";
	$wpbusdirman_pending='';
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no")
	{
		$html .= "<p>" . __("You are not currently charging any payment fees. To charge fees for listings check that option in the manage options page.","WPBDM") . "</p>";
	}
	else
	{
		global $wpbusdirman_valuetext, $wpbusdirman_labeltext,$wpbusdirman_actiontext,$wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbdmposttype;

		$wpbusdirman_catcat=get_posts('post_type='.$wpbdmposttype);

		if($wpbusdirman_catcat)
		{
			foreach($wpbusdirman_catcat as $wpbusdirman_cat)
			{
				$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
			}
		}
		if($wpbusdirman_postsposts)
		{
			foreach($wpbusdirman_postsposts as $wpbusdirman_post)
			{
				$wpbdmisapaid=get_post_meta($wpbusdirman_post, "paymentstatus",$single=true);
				if(isset($wpbdmisapaid) && ($wpbdmisapaid <> ''))
				{
					$wpbusdirman_paidlistings[]=$wpbusdirman_post;
				}
			}
		}
		if(empty($wpbusdirman_paidlistings))
		{
			$html .= "<p>" . __("Currently there are no paid listings","WPBDM") . "</p>";
		}
		else
		{
			$html .= "<p style=\"float:right\"><a href=\"http://businessdirectoryplugin.com/wp-business-directory-manager/manage-paid-listings\">" . __("Get info on managing paid listings","WPBDM") . "</a></p>";
			$html .= "<table class=\"widefat\" cellspacing=\"0\"><thead><tr>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Title","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("ID","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Status","WPBDM") . "</th>";
			if($wpbusdirman_haspaypalmodule == 1)
			{
				$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Flag","WPBDM") . "</th>";
			}
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Gateway","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Buyer","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Payment Email","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
			$html .= "</tr></thead><tfoot><tr>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Title","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("ID","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Status","WPBDM") . "</th>";
			if($wpbusdirman_haspaypalmodule == 1)
			{
				$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Flag","WPBDM") . "</th>";
			}
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Gateway","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Buyer","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . __("Payment Email","WPBDM") . "</th>";
			$html .= "<th scope=\"col\" class=\"manage-column\">" . $wpbusdirman_actiontext . "</th>";
			$html .= "</tr></tfoot><tbody>";
			if($wpbusdirman_paidlistings)
			{
				foreach($wpbusdirman_paidlistings as $wpbusdirman_paidlistingsitem)
				{
					$bfn=get_post_meta($wpbusdirman_paidlistingsitem, "buyerfirstname", true);
					$bln=get_post_meta($wpbusdirman_paidlistingsitem, "buyerlastname", true);
					$pflagged=get_post_meta($wpbusdirman_paidlistingsitem, "paymentflag", true);
					$pstat=get_post_meta($wpbusdirman_paidlistingsitem, "paymentstatus", true);
					if(!isset($pflagged)
						|| empty($pflagged))
					{
						$pflagged="None";
					}
					$pemail=get_post_meta($wpbusdirman_paidlistingsitem, "payeremail", true);
					if(!isset($pemail)
						|| empty($pemail))
					{
						$pemail="Unavailable";
					}
					$html .= "<tr><td><a href=\"" . get_permalink($wpbusdirman_paidlistingsitem) . "\">".get_the_title($wpbusdirman_paidlistingsitem)."</a></td>";
					$html .= "<td>$wpbusdirman_paidlistingsitem</td>";
					$html .= "<td>".get_post_meta($wpbusdirman_paidlistingsitem, "paymentstatus", true)."</td>";
					if($wpbusdirman_haspaypalmodule == 1)
					{
						$html .= "<td>$pflagged</td>";
					}
					$html .= "<td>".get_post_meta($wpbusdirman_paidlistingsitem, "paymentgateway", true)."</td>";
					$html .= "<td>$bfn $bln</td>";
					$html .= "<td>$pemail</td>";

					$html .= "<td>" . __("Set as","WPBDM") . ": ";
					if(isset($pstat) && !empty($pstat) && ($pstat != 'paid'))
					{
						$html .= "<a href=\"?page=wpbdman_c5&action=setaspaid&id=$wpbusdirman_paidlistingsitem\">" . __("Paid","WPBDM") . "</a>";
					}
					$html .= "<a href=\"?page=wpbdman_c5&action=setasnotpaid&id=$wpbusdirman_paidlistingsitem\">" . __("Not paid","WPBDM") . "</a></td></tr>";
				}
			}
		}
		$html .= "</tbody></table>";
	}
	$html .= wpbusdirman_admin_foot();

	echo $html;
}

function wpbusdirman_payment_thankyou()
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_payment_thankyou_message=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_37'];
	$html = '';

	$html .= "<h3>" . __("Listing Sumitted","WPBDM") . "</h3>";
	if(isset($wpbusdirman_payment_thankyou_message)
		&& !empty($wpbusdirman_payment_thankyou_message))
	{
		$html .= "<p>$wpbusdirman_payment_thankyou_message</p>";
	}

	return $html;
}

function wpbudirman_sticky_payment_thankyou()
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_payment_thankyou_message=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_37'];
	$html = '';

	$html .= "<h3>" . __("Listing Upgraded to featured","WPBDM") . "</h3>";
	if(isset($wpbusdirman_payment_thankyou_message)
		&& !empty($wpbusdirman_payment_thankyou_message))
	{
		$html .= "<p>$wpbusdirman_payment_thankyou_message</p>";
	}

	return $html;
}

function wpbusdirmanfindpage($shortcode)
{
	global $wpdb,$table_prefix;
	$myreturn=false;
	$query="SELECT count(post_name) FROM {$table_prefix}posts WHERE post_content='$shortcode' AND post_type='page'";

	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}

function wpbusdirmanmakepagemain($wpbdmpagename,$wpbdmpagecontent)
{

	$wpbusdirman_gpid='';
	if(!(wpbusdirmanfindpage($wpbdmpagecontent)))
	{

		// Create the main business directory page
		  $wpbusdirman_gpid=wp_insert_post(  $wpbusdirman_my_page );

			$wpbusdirman_gpid = wp_insert_post( array(
			'post_author'	=> 1,
			'post_title'	=> $wpbdmpagename,
			'post_content'	=> $wpbdmpagecontent,
			'post_status' 	=> 'publish',
			'post_type' 	=> 'page',
		));
  	}

  	return $wpbusdirman_gpid;

}

function wpbusdirmanmakepage($wpbusdirman_gpid,$wpbdmpagename,$wpbdmpagecontent)
{
	if(!(wpbusdirmanfindpage($wpbdmpagecontent)))
	{

		// Create the child pages
		if(!isset($wpbusdirman_gpid) || empty($wpbusdirman_gpid)){$wpbusdirman_gpid=wpbusdirman_gpid();}

			$wpbusdirman_gpid = wp_insert_post( array(
			'post_author'	=> 1,
			'post_title'	=> $wpbdmpagename,
			'post_content'	=> $wpbdmpagecontent,
			'post_status' 	=> 'publish',
			'post_type' 	=> 'page',
			'post_parent' => $wpbusdirman_gpid,
		));
	}

}

function wpbusdirman_sticky_payment_thankyou()
{
	$html = '';

	$html .= "<h3>" . __("Listing Upgrade Payment Status","WPBDM") . "</h3>";
	$html .= "<p>" . __("Thank you for your payment. Your listing upgrade request and payment notification have been sent. Contact the administrator if your listing is not upgraded within 24 hours.","WPBDM") . "</p>";

	return $html;
}

function wpbusdirman_listings_expirations()
{
	global $wpbusdirman_gpid,$permalinkstructure,$nameofsite,$thisadminemail,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');
	if($wpbusdirman_myterms)
	{
		foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
		{
			$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
		}
	}
	if($wpbusdirman_postcatitems)
	{
		foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
		{
			$args = array(
				'post_status' => 'publish',
				'meta_key' => 'termlength',
				'post_type' => $wpbdmposttype,
				'meta_compare=>meta_value=0'
				);
			$wpbusdirman_catcat = get_posts($args);
			if ($wpbusdirman_catcat)
			{
				foreach ($wpbusdirman_catcat as $wpbusdirman_cat)
				{
					$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
				}
			}
		}
	}
	if(!empty($wpbusdirman_postsposts))
	{

		foreach($wpbusdirman_postsposts as $listingwithtermlengthset)
		{
			$wpbusdirmantermlength=get_post_meta($listingwithtermlengthset, "termlength", true);
			$wpbusdirmanpostdataarr=get_post( $listingwithtermlengthset );
			$wpbusdirmanpoststartdatebase=$wpbusdirmanpostdataarr->post_date;
			$wpbusdirmanpostauthorid=$wpbusdirmanpostdataarr->post_author;
			$wpbusdirmanpostauthoremail=get_the_author_meta( 'user_email', $wpbusdirmanpostauthorid );
			$wpbusdirmanstartdate = strtotime($wpbusdirmanpoststartdatebase);
			$wpbusdirmanexpiredate= date('Y-m-d', strtotime('+'.$wpbusdirmantermlength.' days', $wpbusdirmanstartdate));
			$wpbusdirmanlistingtitle=get_the_title($listingwithtermlengthset);
			$todaysdatestart=date('Y-m-d');
			$wpbusdirmantodaysdate=strtotime($todaysdatestart);
			$wpbusdirmanexpiredatestrt = strtotime($wpbusdirmanexpiredate);
			if ($wpbusdirmanexpiredatestrt < $wpbusdirmantodaysdate)
			{
				$wpbusdirman_my_expired_post = array();
				$wpbusdirman_my_expired_post['ID'] = $listingwithtermlengthset;
				$wpbusdirman_my_expired_post['post_status'] = 'wpbdmexpired';
				wp_update_post( $wpbusdirman_my_expired_post );
				$listingexpirationtext=__("has expired","WPBDM");
				$headers =	"MIME-Version: 1.0\n" .
						"From: $nameofsite <$thisadminemail>\n" .
						"Reply-To: $thisadminemail\n" .
						"Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";
				$subject = "[" . get_option( 'blogname' ) . "] " . wp_kses( $wpbusdirmanlistingtitle, array() );
				$time = date_i18n( __('l F j, Y \a\t g:i a'), current_time( 'timestamp' ) );
				if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_38'] == "yes")
				{
					$wpbusdirmanrenewlistingtext="To renew your listing click the link below";
					$wpbusdirmanrenewlistinglink=get_permalink($wpbusdirman_gpid);
					if(isset($permalinkstructure) && !empty($permalinkstructure))
					{
						$wpbusdirmanrenewlistinglink.="?do=renewlisting&id=$listingwithtermlengthset";
					}
					else
					{
						$wpbusdirmanrenewlistinglink.="&do=renewlisting&id=$listingwithtermlengthset";
					}
				}
				else
				{
					$wpbusdirmanrenewlistingtext="";
					$wpbusdirmanrenewlistinglink="";
				}
				$message = "

				$wpbusdirmanlistingtitle $listingexpirationtext

				$wpbusdirmanrenewlistingtext

				$wpbusdirmanrenewlistinglink

				Time: $time

				";
				@wp_mail( $wpbusdirmanpostauthoremail, $subject, $message, $headers );
			}
		}
	}
}

function wpbusdirman_renew_listing($wpbdmidtorenew,$wpbusdirman_permalink,$neworedit)
{
	global $wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule;
	$html = '';

	if(isset($wpbdmidtorenew)
		&& !empty($wpbdmidtorenew))
	{
		$wpbdmrenewingtitle=get_the_title($wpbdmidtorenew);
		$wpbdmrenewingcat=get_the_category($wpbdmidtorenew);
		if($wpbdmrenewingcat)
		{
			foreach($wpbdmrenewingcat as $wpbdmrenewingcategory)
			{
				$wpbdmrenewingcatID=$wpbdmrenewingcategory->cat_ID;
			}
		}
		if(( $wpbusdirman_haspaypalmodule == 1)
			|| ($wpbusdirman_hastwocheckoutmodule == 1)
			|| ($wpbusdirman_hasgooglecheckoutmodule == 1))
		{
			$html .= "<h3>" . __("Renew Listing","WPBDM") . "</h3>";
			$wpbusdirman_fee_to_pay_li=wpbusdirman_feepay_configure($wpbdmrenewingcatID);
			$html .= "<p>" . __("You are about to renew","WPBDM") . ": $wpbdmrenewingtitle" . "</p>";
			if(isset($wpbusdirman_fee_to_pay_li) && !empty($wpbusdirman_fee_to_pay_li))
			{
				global $wpbusdirman_gpid,$permalinkstructure;
				$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
				$wpbusdirman_fee_to_pay="<ul id=\"wpbusdirmanpaymentoptionslist\">";
				$wpbusdirman_fee_to_pay.=$wpbusdirman_fee_to_pay_li;
				$wpbusdirman_fee_to_pay.="</ul>";
				$neworedit='new';
				$html .= "<label>" . __("Select Listing Payment Option","WPBDM") . "</label><br /><p>";
				$html .= "<form method=\"post\" action=\"$wpbusdirman_permalink\">";
				$html .= "<input type=\"hidden\" name=\"action\" value=\"renewlisting_step_2\" />";
				$html .= "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbdmidtorenew\" />";
				$html .= "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirman_permalink\" />";
				$html .= "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\" />" . $wpbusdirman_fee_to_pay . "<br/><input type=\"submit\" class=\"insubmitbutton\" value=\"" . __("Next","WPBDM") . "\" /></form></p>";
			}
		}
	}
	else
	{
		$html .= "<p>" . __("There was no ID supplied. Cannot complete renewal. Please contact administrator","WPBDM") . "</p>";
	}

	return $html;
}

function wpbusdirman_viewlistings()
{
	global $wpbusdirman_plugin_path;

	if(file_exists(get_template_directory() . '/single/wpbusdirman-index-listings.php'))
	{
		include get_template_directory() . '/single/wpbusdirman-index-listings.php';
	}
	elseif(file_exists(get_stylesheet_directory() . '/single/wpbusdirman-index-listings.php'))
	{
		include get_stylesheet_directory() . '/single/wpbusdirman-index-listings.php';
	}
	elseif(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php'))
	{
		include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';
	}
	else
	{
		include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';
	}
}


//Display the listing thumbnail
function wpbusdirman_display_the_thumbnail()
{
	global $wpbdmimagesurl,$post,$wpbusdirmanconfigoptionsprefix,$wpbusdirman_imagesurl;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_11'] == "yes")
	{
		$tpostimg2=get_post_meta($post->ID, "image", true);
		if(isset($tpostimg2)
			&& !empty($tpostimg2))
		{
			$wpbusdirman_theimg2=$tpostimg2;
		}
		else
		{
			$wpbusdirman_theimg2='';
		}
		$wpbdmusedef=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_39'];
		$wpbdmimgwidth=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_17'];
		if(!isset($wpbdmimgwidth)
			|| empty($wpbdmimgwidth))
		{
			$wpbdmimgwidth="120";
		}
		if(isset($wpbusdirman_theimg2)
			&& !empty($wpbusdirman_theimg2))
		{
			$html .= '<a href="' . get_permalink() . '"><img class="wpbdmthumbs" src="' . $wpbdmimagesurl . '/thumbnails/' . $wpbusdirman_theimg2 . '" width="' . $wpbdmimgwidth . '" alt="' . the_title(null, null, false) . '" title="' . the_title(null, null, false) . '" border="0"></a>';
		}
		else
		{
			if(!isset($wpbdmusedef)
				|| empty($wpbdmusedef)
				|| ($wpbdmusedef == "yes"))
			{
				$html .= '<a href="' . get_permalink() . '"><img class="wpbdmthumbs" src="' . $wpbusdirman_imagesurl . '/default.png" width="' . $wpbdmimgwidth . '" alt="' .  the_title(null, null, false) . '" title="' . the_title(null, null, false) . '" border="0"></a>';
			}
		}
	}

	return $html;
}

function wpbusdirman_catpage_title()
{
	echo wpbusdirman_post_catpage_title();
}

function wpbusdirman_post_catpage_title()
{
	global $post,$wpbdmposttypecategory;
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	$html = '';

	$html .= '<h1>' . $term->name . '</h1>';

	return $html;
}

function wpbusdirman_menu_buttons()
{
	echo wpbusdirman_post_menu_buttons();
}

function wpbusdirman_post_menu_buttons()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	$html .= '<div id="wpbusdirmancats">' . wpbusdirman_post_menu_button_submitlisting() . wpbusdirman_menu_button_directory() . '</div><div style="clear:both;"></div><br />';

	return $html;
}

function wpbusdirman_menu_button_submitlisting()
{
	echo wpbusdirman_post_menu_button_submitlisting();
}

function wpbusdirman_post_menu_button_submitlisting()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	$html .= '<form method="post" action="' . $wpbusdirman_permalink . '"><input type="hidden" name="action" value="submitlisting" /><input type="submit" class="submitlistingbutton" value="' . __("Submit A Listing","WPBDM") . '" /></form>';

	return $html;
}

function wpbusdirman_menu_button_viewlistings()
{
	echo wpbusdirman_post_menu_button_viewlistings();
}

function wpbusdirman_post_menu_button_viewlistings()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	$html .= '<form method="post" action="' . $wpbusdirman_permalink . '"><input type="hidden" name="action" value="viewlistings" /><input type="submit" class="viewlistingsbutton" style="margin-right:10px;" value="' . __("View Listings","WPBDM") . '" /></form>';

	return $html;
}

function wpbusdirman_menu_button_directory()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	$html .= '<form method="post" action="' . $wpbusdirman_permalink . '"><input type="submit" class="viewlistingsbutton" style="margin-right:10px;" value="' . __("Directory","WPBDM") . '" /></form>';
}

function wpbusdirman_menu_button_editlisting()
{
	global $post;
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	if(is_user_logged_in())
	{
		global $current_user;
		get_currentuserinfo();
		$wpbusdirmanloggedinuseremail=$current_user->user_email;
		$wpbusdirmanauthoremail=get_the_author_meta('user_email');
		if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
		{
			$html .= '<form method="post" action="' . $wpbusdirman_permalink . '"><input type="hidden" name="action" value="editlisting" /><input type="hidden" name="wpbusdirmanlistingid" value="' . $post->ID . '" /><input type="submit" class="editlistingbutton" value="' . __("Edit Listing","WPBDM") . '" /></form>';
		}
	}

	return $html;
}

function wpbusdirman_menu_button_upgradelisting()
{
	global $post,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	if(is_user_logged_in())
	{
		global $current_user;
		get_currentuserinfo();
		$wpbusdirmanloggedinuseremail=$current_user->user_email;
		$wpbusdirmanauthoremail=get_the_author_meta('user_email');
		$wpbdmpostissticky=get_post_meta($post->ID, "sticky", $single=true);
		if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
		{
			if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_31'] == "yes")
			{
				if( (!isset($wpbdmpostissticky) || empty($wpbdmpostissticky) || ($wpbdmpostissticky == 'not paid')) && ( $post->post_status == 'publish') )
				{
					$html .= '<form method="post" action="' . $wpbusdirman_permalink . '"><input type="hidden" name="action" value="upgradetostickylisting" /><input type="hidden" name="wpbusdirmanlistingid" value="' . $post->ID . '" /><input type="submit" class="updradetostickylistingbutton" value="' . __("Upgrade Listing","WPBDM") . '" /></form>';
				}
			}
		}
	}

	return $html;
}

function wpbusdirman_list_categories()
{
	echo wpbusdirman_post_list_categories();
}

function wpbusdirman_post_list_categories()
{
	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbdm_hide_empty=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_10'];
	$html = '';

	if(isset($wpbdm_hide_empty)
		&& !empty($wpbdm_hide_empty)
		&& ($wpbdm_hide_empty == "yes"))
	{
		$wpbdm_hide_empty=1;
	}
	elseif(isset($wpbdm_hide_empty)
		&& !empty($wpbdm_hide_empty)
		&& ($wpbdm_hide_empty == "no"))
	{
		$wpbdm_hide_empty=0;
	}
	$wpbdm_show_count=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_9'];
	if(isset($wpbdm_show_count)
		&& !empty($wpbdm_show_count)
		&& ($wpbdm_show_count == "yes"))
	{
		$wpbdm_show_count=1;
	}
	elseif(isset($wpbdm_show_count)
		&& !empty($wpbdm_show_count)
		&& ($wpbdm_show_count == "no"))
	{
		$wpbdm_show_count=0;
	}
	$wpbdm_show_parent_categories_only=$wpbusdirmanconfigoptionsprefix."_settings_config_48";
	$wpbdm_show_parent_categories_only=1;
	if(isset($wpbdm_show_parent_categories_only)
		&& !empty($wpbdm_show_parent_categories_only)
		&& ($wpbdm_show_parent_categories_only == "yes"))
	{
		$wpbdm_show_parent_categories_only=0;
	}
	elseif(isset($wpbdm_show_parent_categories_only)
		&& !empty($wpbdm_show_parent_categories_only)
		&& ($wpbdm_show_parent_categories_only == "no"))
	{
		$wpbdm_show_parent_categories_only=1;
	}

	$taxonomy     = $wpbdmposttypecategory;
	$orderby      = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_7'];
	$show_count   = $wpbdm_show_count;      // 1 for yes, 0 for no
	$pad_counts   = 0;      // 1 for yes, 0 for no
	$hierarchical = $wpbdm_show_parent_categories_only;      // 1 for yes, 0 for no
	$title        = '';
	$order=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_8'];
	$hide_empty=$wpbdm_hide_empty;
	$args = array(
		'taxonomy'     => $taxonomy,
		'orderby'      => $orderby,
		'show_count'   => $show_count,
		'pad_counts'   => $pad_counts,
		'hierarchical' => $hierarchical,
		'title_li'     => $title,
		'order' =>$order,
		'hide_empty' => $hide_empty
	);
	$html .= wp_list_categories($args);

	return $html;
}

function wpbusdirman_dropdown_categories()
{
	global $post,$wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$wpbdm_hide_empty=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_10'];
	$html = '';

	if(isset($wpbdm_hide_empty)
		&& !empty($wpbdm_hide_empty)
		&& ($wpbdm_hide_empty == "yes"))
	{
		$wpbdm_hide_empty=1;
	}
	elseif(isset($wpbdm_hide_empty)
		&& !empty($wpbdm_hide_empty)
		&& ($wpbdm_hide_empty == "no"))
	{
		$wpbdm_hide_empty=0;
	}
	$wpbdm_show_count=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_9'];
	if(isset($wpbdm_show_count)
		&& !empty($wpbdm_show_count)
		&& ($wpbdm_show_count == "yes"))
	{
		$wpbdm_show_count=1;
	}
	elseif(isset($wpbdm_show_count)
		&& !empty($wpbdm_show_count)
		&& ($wpbdm_show_count == "no"))
	{
		$wpbdm_show_count=0;
	}
	$wpbdm_show_parent_categories_only=$wpbusdirmanconfigoptionsprefix."_settings_config_48";
	$wpbdm_show_parent_categories_only=1;
	if(isset($wpbdm_show_parent_categories_only)
		&& !empty($wpbdm_show_parent_categories_only)
		&& ($wpbdm_show_parent_categories_only == "yes"))
	{
		$wpbdm_show_parent_categories_only=0;
	}
	$wpbusdirman_postvalues=get_the_terms(get_the_ID(), $wpbdmposttypecategory);
	if($wpbusdirman_postvalues)
	{
		foreach($wpbusdirman_postvalues as $wpbusdirman_postvalue)
		{
			$wpbusdirman_field_value_selected=$wpbusdirman_postvalue->term_id;
		}
	}
	$html .= '<form action="' . bloginfo('url') . '" method="get">';
	$taxonomies = array($wpbdmposttypecategory);
	$args = array('echo'=>0,'show_option_none'=>$wpbusdirman_selectcattext,'orderby'=>$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_7'],'selected'=>$wpbusdirman_field_value_selected,'order'=>$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_8'],'hide_empty'=>$wpbdm_hide_empty,'hierarchical'=>$wpbdm_show_parent_categories_only);
	$select = get_terms_dropdown($taxonomies, $args);
	$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
	$html .= $select;
	$html .= '<noscript><div><input type="submit" value="N�yt�" /></div></noscript></form>';

	return $html;
}

function get_terms_dropdown($taxonomies, $args)
{
	global $wpbdmposttypecategory;
	$myterms = get_terms($taxonomies, $args);
	$output ="<select name='".$wpbdmposttypecategory."'>";

	if($myterms)
	{
		foreach($myterms as $term){
			$root_url = get_bloginfo('url');
			$term_taxonomy=$term->taxonomy;
			$term_slug=$term->slug;
			$term_name =$term->name;
			$link = $term_slug;
			$output .="<option value='".$link."'>".$term_name."</option>";
		}
	}
	$output .="</select>";

	return $output;
}


function wpbusdirman_catpage_query()
{
	global $wpbdmposttype,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

	if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_52']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_52']))
	{
		$wpbdm_order_listings_by=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_52'];
	}

	if(isset($wpbdm_order_listings_by) && !empty($wpbdm_order_listings_by)){$wpbdmorderlistingsby=$wpbdm_order_listings_by;}
	else { $wpbdmorderlistingsby='date';}

	if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_53']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_53']))
	{
		$wpbdm_sort_order_listings=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_53'];
	}

	if(isset($wpbdm_sort_order_listings) && !empty($wpbdm_sort_order_listings)){$wpbdmsortorderlistings=$wpbdm_sort_order_listings;}
	else { $wpbdmsortorderlistings='ASC';}


	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args=array(
	  $wpbdmposttypecategory => $term->name,
	  'post_type' => $wpbdmposttype,
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	'paged'=>$paged,
	'orderby'=>$wpbdmorderlistingsby. ' meta_key=sticky&meta_value=approved',
	'order'=> $wpbdmsortorderlistings,

	);
	//$my_query = null;
	//$my_query = new WP_Query($args);

	query_posts($args);
	$wpbusdirman_stickyids=array();
}

function wpbusdirman_indexpage_query()
{
	global $wpbdmposttype,$wpbusdirmanconfigoptionsprefix;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_52']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_52']))
	{
		$wpbdm_order_listings_by=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_52'];
	}

	if(isset($wpbdm_order_listings_by) && !empty($wpbdm_order_listings_by)){$wpbdmorderlistingsby=$wpbdm_order_listings_by;}
	else { $wpbdmorderlistingsby='date';}

	if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_53']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_53']))
	{
		$wpbdm_sort_order_listings=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_53'];
	}

	if(isset($wpbdm_sort_order_listings) && !empty($wpbdm_sort_order_listings)){$wpbdmsortorderlistings=$wpbdm_sort_order_listings;}
	else { $wpbdmsortorderlistings='ASC';}


	$args=array(
	  'post_type' => $wpbdmposttype,
	  'post_status' => 'publish',
	'paged'=>$paged,
	'orderby'=>$wpbdmorderlistingsby. ' meta_key=sticky&meta_value=approved',
	'order'=>$wpbdmsortorderlistings
	);
	query_posts($args);
	$wpbusdirman_stickyids=array();
}

// Display the listing fields in excerpt view
function wpbusdirman_display_the_listing_fields()
{
	global $post,$wpbdmposttypecategory,$wpbdmposttypetags;
	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
	$html = '';

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
		{
			if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val) == 'yes')
			{
				$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
				$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
				if($wpbusdirman_field_association == 'title')
				{
					$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: <a href="' .  get_permalink() . '">' .  the_title(null, null, false) . '</a></p>';
				}
				elseif($wpbusdirman_field_association == 'category')
				{
					$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' ) . '</p>';
				}
				elseif($wpbusdirman_field_association == 'meta')
				{
					$wpbusdirman_field_value=get_post_meta($post->ID, $wpbusdirman_field_label, $single = true);
					$wpbusdirman_field_value=preg_replace("/(http:\/\/[^\s]+)/","<a rel=\"no follow\" href=\"\$1\">\$1</a>",$wpbusdirman_field_value);
					$wpbusdirman_field_value=str_replace("\t",", ",$wpbusdirman_field_value);
					if(isset($wpbusdirman_field_value)
						&& !empty($wpbusdirman_field_value)
						&& (!wpbusdirman_isValidEmailAddress($wpbusdirman_field_value)))
					{
						$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . $wpbusdirman_field_value . '</p>';
					}
				}
				elseif (($wpbusdirman_field_association == 'excerpt')
					&& (has_excerpt($post->ID)))
				{
					$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: <a href="' . get_permalink() . '">' . the_excerpt() . '</a></p>';
				}
				elseif($wpbusdirman_field_association == 'description')
				{
					$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: <a href="' . get_permalink() . '">' . the_content(' ') . '</a></p>';
				}
				elseif (($wpbusdirman_field_association == 'tags')
					&& (get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' )))
				{
					$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' ) . '</p>';
				}
			}
		}
	}

	return $html;
}

function wpbusdirman_view_edit_delete_listing_button()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	$html .= '<div style="clear:both;"></div><div class="vieweditbuttons"><div class="vieweditbutton"><a href="' . get_permalink() . '">' . __("View","WPBDM") . '</a></div>';
	if(is_user_logged_in())
	{
		global $current_user;
		get_currentuserinfo();
		$wpbusdirmanloggedinuseremail=$current_user->user_email;
		$wpbusdirmanauthoremail=get_the_author_meta('user_email');
		if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
		{
			$html .= '<div class="vieweditbutton"><form method="post" action="' . $wpbusdirman_permalink . '"><input type="hidden" name="action" value="editlisting" /><input type="hidden" name="wpbusdirmanlistingid" value="' . get_the_id() . '" /><input type="submit" value="' . __("Edit","WPBDM") . '" /></form></div><div class="vieweditbutton"><form method="post" action="' . $wpbusdirman_permalink . '"><input type="hidden" name="action" value="deletelisting" /><input type="hidden" name="wpbusdirmanlistingid" value="' . get_the_id() . '" /><input type="submit" value="' . __("Delete","WPBDM") . '" /></form></div>';
		}
	}
	$html .= '</div>';

	return $html;
}

function wpbusdirman_display_excerpt()
{
	echo wpbusdirman_post_excerpt();
}

function wpbusdirman_post_excerpt()
{ 	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

	$html = '';

	$html .= '<div id="wpbdmlistings"><div class="listingthumbnail">' . wpbusdirman_display_the_thumbnail() . '</div><div class="listingdetails">';
	$html .= wpbusdirman_display_the_listing_fields();
	$html .= wpbusdirman_view_edit_delete_listing_button();
	$html .= '</div><div style="clear:both;"></div></div>';

	return $html;
}

function wpbusdirman_display_ac()
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_34'] == "yes")
	{
		$html .= '<div class="wpbdmac">Directory powered by <a href="http://wpbusinessdirectorymanager.businessdirectoryplugin.com/">WP Business Directory Manager</a> available via <a href="http://www.businessdirectoryplugin.com">Themes Town</a></div>';
	}

	return $html;
}

function wpbusdirman_display_main_image()
{
	echo wpbusdirman_post_main_image();
}

function wpbusdirman_post_main_image()
{
	global $post,$wpbdmimagesurl,$wpbusdirman_imagesurl,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$html = '';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_11'] == "yes")
	{
		$usingdefault=0;
		$wpbusdirmanpostimages=get_post_meta($post->ID, "thumbnail", $single=false);
		$wpbusdirmanpostimagestotal=count($wpbusdirmanpostimages);
		$wpbusdirmanpostimagefeature='';
		if($wpbusdirmanpostimagestotal >=1)
		{
			$wpbusdirmanpostimagefeature=$wpbusdirmanpostimages[0];
		}
		$wpbdmusedef=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_39'];
		if(!isset($wpbdmusedef)
			|| empty($wpbdmusedef)
			|| ($wpbdmusedef == "yes"))
		{
			if(!isset($wpbusdirmanpostimagefeature)
				|| empty($wpbusdirmanpostimagefeature))
			{
				$usingdefault=1;
				$wpbusdirmanpostimagefeature=$wpbusdirman_imagesurl.'/default-image-big.gif';
			}
		}
		if(isset($wpbusdirmanpostimagefeature)
			&& !empty($wpbusdirmanpostimagefeature))
		{
			$html .= '<a href="' . get_permalink() . '"><img src="';
			if($usingdefault != 1)
			{
				$html .= $wpbdmimagesurl;
				$html .= '/';
			}
			$html .= $wpbusdirmanpostimagefeature . '" alt="' . the_title(null, null, false) . '" title="' . the_title(null, null, false) . '" border="0"></a><br />';
		}
	}

	return $html;
}

function wpbusdirman_display_extra_thumbnails()
{
	echo wpbusdirman_post_extra_thumbnails();
}

function wpbusdirman_post_extra_thumbnails()
{
	global $post,$wpbdmimagesurl;
	$wpbusdirmanpostimages=get_post_meta($post->ID, "thumbnail", $single=false);
	$wpbusdirmanpostimagestotal=count($wpbusdirmanpostimages);
	$wpbusdirmanpostimagefeature='';
	$html = '';

	if($wpbusdirmanpostimagestotal >=1)
	{
		$wpbusdirmanpostimagefeature=$wpbusdirmanpostimages[0];
	}
	if($wpbusdirmanpostimagestotal > 1)
	{
		$html .= '<div class="extrathumbnails">';
		foreach($wpbusdirmanpostimages as $wpbusdirmanpostimage)
		{
			if(!($wpbusdirmanpostimage == $wpbusdirmanpostimagefeature))
			{
				$html .= '<a class="thickbox" href="' . $wpbdmimagesurl . '/' . $wpbusdirmanpostimage . '"><img class="wpbdmthumbs" src="' . $wpbdmimagesurl . '/thumbnails/' . $wpbusdirmanpostimage . '" alt="' . the_title(null, null, false) . '" title="' . the_title(null, null, false) . '" border="0"></a>';
			}
		}
		$html .= '</div>';
	}

	return $html;
}

function wpbusdirman_single_listing_details()
{
	echo wpbusdirman_post_single_listing_details();
}

function wpbusdirman_post_single_listing_details()
{
	global $post,$wpbusdirman_gpid,$wpbdmimagesurl,$wpbusdirman_imagesurl,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
	$html = '';

	if(is_user_logged_in())
	{
		global $current_user;
		$html .= get_currentuserinfo();
		$wpbusdirmanloggedinuseremail=$current_user->user_email;
		$wpbusdirmanauthoremail=get_the_author_meta('user_email');
		$wpbdmpostissticky=get_post_meta($post->ID, "sticky", $single=true);
		if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
		{
			$html .= '<div class="editlistingsingleview">' . wpbusdirman_menu_button_editlisting() . wpbusdirman_menu_button_upgradelisting() . '</div><div style="clear:both;"></div>';
		}
	}
	 if(isset($wpbdmpostissticky)
	 	&& !empty($wpbdmpostissticky)
	 	&& ($wpbdmpostissticky  == 'approved') )
	 {
	 	$html .= '<span class="featuredlisting"><img src="' . $wpbusdirman_imagesurl . '/featuredlisting.png" alt="' . __("Featured Listing","WPBDM") . '" border="0" title="' . the_title(null, null, false) . '"></span>';
	}
	$html .= wpbusdirman_the_listing_title();
	$html .= wpbusdirman_the_listing_category();
	$html .= wpbusdirman_the_listing_meta('single');
	$html .= wpbusdirman_the_listing_excerpt();
	$html .= wpbusdirman_the_listing_content();
	$html .= wpbusdirman_the_listing_tags();
	$html .= wpbusdirman_contactform($wpbusdirman_permalink,$post->ID,$commentauthorname='',$commentauthoremail='',$commentauthorwebsite='',$commentauthormessage='',$wpbusdirman_contact_form_errors='');

	return $html;
}

function wpbusdirman_the_listing_title()
{
	global $wpbusdirman_field_vals_pfl;
	$html = '';

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val)
		{
			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
			if($wpbusdirman_field_association == 'title')
			{
				$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: <a href="' . get_permalink() . '">' . the_title(null, null, false) . '</a></p>';
			}
		}
	}

	return $html;
}

function wpbusdirman_the_listing_tags()
{
	global $wpbdmposttypetags,$wpbusdirman_field_vals_pfl,$post;
	$html = '';

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val)
		{
			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
			if (($wpbusdirman_field_association == 'tags')
				&& (get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' )))
			{
				$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' ) . '</p>';
			}
		}
	}

	return $html;
}

function wpbusdirman_the_listing_excerpt()
{
	global $wpbusdirman_field_vals_pfl,$post;
	$html = '';

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val)
		{
			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
			if (($wpbusdirman_field_association == 'excerpt')
				&& (has_excerpt($post->ID)))
			{
				$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . get_the_excerpt() . '</p>';
			}
		}
	}

	return $html;
}

function wpbusdirman_the_listing_content()
{
	global $wpbusdirman_field_vals_pfl;
	$html = '';

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val)
		{
			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
			if($wpbusdirman_field_association == 'description')
			{
				$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . get_the_content() . '</p>';
			}
		}
	}

	return $html;
}

function wpbusdirman_the_listing_category()
{
	global $wpbdmposttypecategory,$wpbusdirman_field_vals_pfl,$post;
	$html = '';

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val)
		{
			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
			if($wpbusdirman_field_association == 'category')
			{
				$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' ) . '</p>';
			}
		}
	}

	return $html;
}

function wpbusdirman_the_listing_meta($excerptorsingle)
{
	global $post,$wpbusdirmanconfigoptionsprefix,$wpbusdirman_field_vals_pfl;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$overrideemailblocking=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_45'];
	$html = '';

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val)
		{
			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
			if($wpbusdirman_field_association == 'meta')
			{
				$wpbusdirman_field_value=get_post_meta(get_the_ID(), $wpbusdirman_field_label, $single = true);
				$wpbusdirman_field_value=preg_replace("/(http:\/\/[^\s]+)/","<a rel=\"no follow\" href=\"\$1\">\$1</a>",$wpbusdirman_field_value);
				$wpbusdirman_field_value=str_replace("\t",", ",$wpbusdirman_field_value);
				$wpbusdireman_field_hide = get_option('wpbusdirman_postform_field_hide_' . $wpbusdirman_field_val);
				if ("yes" == $wpbusdireman_field_hide)
				{
					continue;
				}
				if( isset($overrideemailblocking)
					&& !empty($overrideemailblocking)
					&& ($overrideemailblocking == "yes") )
				{
					if( isset($wpbusdirman_field_value)
						&& !empty($wpbusdirman_field_value) )
					{
						if(isset($excerptorsingle)
							&& !empty($excerptorsingle)
							&& ($excerptorsingle == 'excerpt'))
						{
							if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val) == 'yes')
							{
								$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . $wpbusdirman_field_value . '</p>';
							}
						}
						else
						{
							$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . $wpbusdirman_field_value . '</p>';
						}
					}
				}
				elseif($overrideemailblocking == "no")
				{
					if( isset($wpbusdirman_field_value)
						&& !empty($wpbusdirman_field_value)
						&&  !wpbusdirman_isValidEmailAddress($wpbusdirman_field_value) )
					{
						if(isset($excerptorsingle)
							&& !empty($excerptorsingle)
							&& ($excerptorsingle == 'excerpt'))
						{
							if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val) == 'yes')
							{
								$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . $wpbusdirman_field_value . '</p>';
							}
						}
						else
						{
							$html .= '<p><label>' . $wpbusdirman_field_label . '</label>: ' . $wpbusdirman_field_value . '</p>';
						}
					}
				}
			}
		}
	}

	return $html;
}

function wpbusdirman_latest_listings($numlistings)
{
	global $wpbdmposttype;
	$wpbdmpostheadline='';
	$args = array(
		'post_status' => 'publish',
		'post_type' => $wpbdmposttype,
		'numberposts' => $numlistings,
		'orderby' => 'date'
	);
	$wpbusdirman_theposts = get_posts($args);

	if($wpbusdirman_theposts)
	{
		foreach($wpbusdirman_theposts as $wpbusdirman_thepost)
		{
			$wpbdmpostheadline.="<li><a href=\"";
			$wpbdmpostheadline.=get_permalink($wpbusdirman_thepost->ID);
			$wpbdmpostheadline.="\">$wpbusdirman_thepost->post_title</a></li>";
		}
	}

	return $wpbdmpostheadline;
}

function get_wpbusdirman_config_options()
{
	$mywpbusdirman_config_options=array();
	global $wpbusdirmanconfigoptionsprefix;

	$pstandwpbusdirman_config_options=get_option($wpbusdirmanconfigoptionsprefix.'_settings_config');

	if(isset($pstandwpbusdirman_config_options) && !empty($pstandwpbusdirman_config_options))
	{
		foreach ($pstandwpbusdirman_config_options as $pstandoption)
		{
			if(isset($pstandoption['id']) && !empty($pstandoption['id']))
			{
				$mywpbusdirman_config_options[$pstandoption['id']]=$pstandoption['std'];
			}

		}
	}

	return $mywpbusdirman_config_options;
}

function wpbusdirman_config_check_for_wpbusdirman_config_options()
{
	global $wpbusdirmanconfigoptionsprefix,$def_wpbusdirman_config_options,$poststatusoptions,$yesnooptions,$categoryorderoptions,$categorysortoptions;
	$wpbusdirmanconfigoptions=$wpbusdirmanconfigoptionsprefix.'_settings_config';
	$mysavedthemewpbusdirman_config_options=get_option($wpbusdirmanconfigoptions);

		$wpbusdirman_config_options = $mysavedthemewpbusdirman_config_options;

		if (!isset($wpbusdirman_config_options) || empty($wpbusdirman_config_options) || !is_array($wpbusdirman_config_options))
		{
			$wpbusdirman_config_options = $def_wpbusdirman_config_options;

			if($wpbusdirman_config_options)
			{
				foreach ($wpbusdirman_config_options as $optionvalue)
				{
					if(!isset($optionvalue['id']) || empty($optionvalue['id']))
					{
						$optionvalue['id']='';
					}
					if(!isset($optionvalue['wpbusdirman_config_options']) || empty($optionvalue['wpbusdirman_config_options']))
					{
						$optionvalue['wpbusdirman_config_options']='';
					}
					if(!isset($optionvalue['std']) || empty($optionvalue['std']))
					{
						$optionvalue['std']='';
					}

						$setmywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
						"id" => $optionvalue['id'],
						"std" => $optionvalue['std'],
						"type" => $optionvalue['type'],
						"options" => $optionvalue['options']);

				}
			}

			update_option($wpbusdirmanconfigoptions,$setmywpbusdirman_config_options);
		}
}

function wpbusdirman_config_reconcile_options()
{
	global $wpbusdirmanconfigoptionsprefix,$def_wpbusdirman_config_options,$poststatusoptions,$yesnooptions,$categoryorderoptions,$categorysortoptions;
	$wpbusdirmanconfigoptions=$wpbusdirmanconfigoptionsprefix.'_settings_config';
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

			$setmywpbusdirman_config_options=array();

				if($def_wpbusdirman_config_options)
				{
					foreach ($def_wpbusdirman_config_options as $optionvalue)
					{

						if(!isset($optionvalue['id']) || empty($optionvalue['id']))
						{
							$optionvalue['id']='';
						}
						if(!isset($optionvalue['wpbusdirman_config_options']) || empty($optionvalue['wpbusdirman_config_options']))
						{
							$optionvalue['wpbusdirman_config_options']='';
						}
						if(!isset($optionvalue['name']) || empty($optionvalue['name']))
						{
							$optionvalue['name']='';
						}
						if(!isset($optionvalue['std']) || empty($optionvalue['std']))
						{
							$optionvalue['std']='';
						}
						if(!isset($optionvalue['options']) || empty($optionvalue['options']))
						{
							$optionvalue['options']='';
						}


						if(isset($wpbusdirman_config_options[$optionvalue['id']]) && !empty($wpbusdirman_config_options[$optionvalue['id']]))
						{
							$savedoptionvalue=$wpbusdirman_config_options[$optionvalue['id']];
						}
						elseif(isset($optionvalue['std']) && !empty($optionvalue['std']))
						{
							$savedoptionvalue=$optionvalue['std'];
						}
						else
						{
							$savedoptionvalue='';
						}
						$setmywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
						"id" => $optionvalue['id'],
						"std" => $savedoptionvalue,
						"type" => $optionvalue['type'],
						"options" => $optionvalue['options']);
					}
				}

				update_option($wpbusdirmanconfigoptions,$setmywpbusdirman_config_options);

}

function wpbusdirman_config_admin()
{
	global $wpbusdirmanconfigoptionsprefix, $def_wpbusdirman_config_options,$poststatusoptions,$yesnooptions,$categoryorderoptions,$categorysortoptions;
	$html = '';

	$html .= wpbusdirman_config_reconcile_options();
	$wpbusdirmanconfigoptions=$wpbusdirmanconfigoptionsprefix.'_settings_config';
	$mysavedthemewpbusdirman_config_options=get_option($wpbusdirmanconfigoptions);
	$wpbusdirman_config_options = $mysavedthemewpbusdirman_config_options;
	if (!isset($wpbusdirman_config_options)
		|| empty($wpbusdirman_config_options)
		|| !is_array($wpbusdirman_config_options))
	{
		$wpbusdirman_config_options = $def_wpbusdirman_config_options;
		if($wpbusdirman_config_options)
		{
			foreach ($wpbusdirman_config_options as $optionvalue)
			{
				if(isset($optionvalue['id'])
					&& !empty($optionvalue['id']))
				{
					$savedoptionvalue=get_option($optionvalue['id']);
					if(!isset($savedoptionvalue)
						|| empty ($savedoptionvalue))
					{
						$savedoptionvalue=$optionvalue['std'];
					}
					$setmywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
					"id" => $optionvalue['id'],
					"std" => $savedoptionvalue,
					"type" => $optionvalue['type'],
					"options" => $optionvalue['options']);
					delete_option($optionvalue['id']);
				}
			}
		}
		update_option($wpbusdirmanconfigoptions,$setmywpbusdirman_config_options);
	}
	if( isset($_REQUEST['action'])
		&& ( 'updatewpbusdirman_config_options' == $_REQUEST['action'] ))
	{
		$myoptionvalue='';
		if($wpbusdirman_config_options)
		{
			foreach ($wpbusdirman_config_options as $optionvalue)
			{
				if ((isset($optionvalue['id'])
					&& !empty($optionvalue['id']))
					&& ( isset( $_REQUEST[ $optionvalue['id'] ])))
				{
					$myoptionvalue = $_REQUEST[ $optionvalue['id'] ];
				}
				if(!isset($optionvalue['options']) || empty($optionvalue['options']))
				{
					$optionvalue['options']='';
				}
				if(!isset($optionvalue['id']) || empty($optionvalue['id']))
				{
					$optionvalue['id']='';
				}
				if(!isset($optionvalue['std']) || empty($optionvalue['std'] ))
				{
					$optionvalue['std']='';
				}
				$mywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
				"id" => $optionvalue['id'],
				"std" => $myoptionvalue,
				"type" => $optionvalue['type'],
				"options" => $optionvalue['options']);
			}
		}
		update_option($wpbusdirmanconfigoptions,$mywpbusdirman_config_options);
		$wpbusdirman_config_optionsupdated=true;
	}
	else if( isset($_REQUEST['action']) && ( 'reset' == $_REQUEST['action'] ))
	{
		update_option($wpbusdirmanconfigoptions,$def_wpbusdirman_config_options);
		$wpbusdirman_config_optionsreset=true;
	}
	if( isset($_REQUEST['saved'])
		&& !empty( $_REQUEST['saved'] ))
	{
		$html .= '<div id="message" class="updated fade"><p><strong>'.$myasfwpname.' settings saved.</strong></p></div>';
	}
	if ( isset($_REQUEST['reset'])
		&& !empty( $_REQUEST['reset'] ))
	{
		$html .= '<div id="message" class="updated fade"><p><strong>'.$myasfwpname.' settings reset.</strong></p></div>';
	}
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_config_saved_options = get_option($wpbusdirmanconfigoptionsprefix.'_settings_config');
	if (!isset($wpbusdirman_config_saved_options) || empty($wpbusdirman_config_saved_options) || !is_array($wpbusdirman_config_saved_options))
	{
		$wpbusdirman_config_options = $def_wpbusdirman_config_options;
	}
	else
	{
		$wpbusdirman_config_options=$wpbusdirman_config_saved_options;
	}
	$html .= '<div class="wrap"><h2>' . __('WP Business Directory Main Settings','WPBDM') . '</h2><form method="post">';
	foreach ($wpbusdirman_config_options as $value)
	{
		if ($value['type'] == "text")
		{
			$html .= '<div style="float: left; width: 880px; background-color:#E4F2FD; border-left: 1px solid #C2D6E6; border-right: 1px solid #C2D6E6;  border-bottom: 1px solid #C2D6E6; padding: 10px;"><div style="width: 200px; float: left;">' . $value['name'] . '</div><div style="width: 680px; float: left;"><input name="' . $value['id'] . '" id="' . $value['id'] . '" style="width: 400px;" type="' . $value['type'] . '" value="';
			if ( isset($wpbusdirman_config_options[ $value['id'] ]) && $wpbusdirman_config_options[ $value['id'] ] != "")
			{
				$html .= stripslashes($wpbusdirman_config_options[ $value['id'] ]);
			}
			else
			{
				$html .= $value['std'];
			}
			$html .= '" /></div></div>';
		}
		elseif ($value['type'] == "text2")
		{
			$html .= '<div style="float: left; width: 880px; background-color:#E4F2FD; border-left: 1px solid #C2D6E6; border-right: 1px solid #C2D6E6;  border-bottom: 1px solid #C2D6E6; padding: 10px;"><div style="width: 200px; float: left;">' . $value['name'] . '</div><div style="width: 680px; float: left;"><textarea name="' . $value['id'] . '" id="' . $value['id'] . '" style="width: 400px; height: 200px;" type="' . $value['type'] . '">';
			if ( $wpbusdirman_config_options[ $value['id'] ] != "")
			{
				$html .= stripslashes($wpbusdirman_config_options[ $value['id'] ]);
			}
			else
			{
				$html .= $value['std'];
			}
			$html .= '</textarea></div></div>';
		}
		elseif ($value['type'] == "select")
		{
			$html .= '<div style="float: left; width: 880px; background-color:#E4F2FD; border-left: 1px solid #C2D6E6; border-right: 1px solid #C2D6E6;  border-bottom: 1px solid #C2D6E6; padding: 10px;"><div style="width: 200px; float: left;">' . $value['name'] . '</div><div style="width: 680px; float: left;"><select name="' . $value['id'] . '" id="' . $value['id'] . '" style="width: 400px;">';
			foreach ($value['options'] as $option)
			{
				$html .= '<option';
				if ( isset($wpbusdirman_config_options[ $value['id'] ]) && $wpbusdirman_config_options[ $value['id'] ] == $option)
				{
					$html .= ' selected="selected"';
				}
				elseif ($option == $value['std'])
				{
					$html .= ' selected="selected"';
				}
				$html .= '>' . $option . '</option>';
			}
			$html .= '</select></div></div>';
		}
		elseif ($value['type'] == "titles")
		{
			$html .= '<div style="float: left; width: 870px; padding: 15px; background-color:#2583AD; border: 1px solid #2583AD; color: #fff; font-size: 16px; font-weight: bold; margin-top: 25px;">' . $value['name'] . '</div>';
		}
	}
	$html .= '<div style="clear: both;"></div><p style="float: left;" class="submit"><input name="save" type="submit" value="Save changes" /><input type="hidden" name="action" value="updatewpbusdirman_config_options" /></p></form><form method="post"><p style="float: left;" class="submit"><input name="reset" type="submit" value="Reset" /><input type="hidden" name="action" value="reset" /></p></form>';

	echo $html;
}
