<?php
/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) ShemOtechnik Profitquery Team shemotechnik@profitquery.com |
* +--------------------------------------------------------------------------+
* | This program is free software; you can redistribute it and/or modify     |
* | it under the terms of the GNU General Public License as published by     |
* | the Free Software Foundation; either version 2 of the License, or        |
* | (at your option) any later version.                                      |
* |                                                                          |
* | This program is distributed in the hope that it will be useful,          |
* | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
* | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
* | GNU General Public License for more details.                             |
* |                                                                          |
* | You should have received a copy of the GNU General Public License        |
* | along with this program; if not, write to the Free Software              |
* | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA |
* +--------------------------------------------------------------------------+
*/
/**
* Plugin Name: Free Profitquery Tools | Image Sharer
* Plugin URI: http://profitquery.com/image_sharer_wordpress.html
* Description: On Hover Image Sharer By Profitquery for traffic growth 3x, many design options, mobile responsive, without any social network apps. Use this plugin you automatically get access to the Profitquery marketing intelligence platform - Marketing Bar, Contact Form, MailChimp Integration, Social Login, Follow Buttons, Sharing Sidebar, Floating Popup, Call Me Back, Contact Verifier etc.
* Version: 1.0
*
* Author: Profitquery Team <support@profitquery.com>
* Author URI: http://profitquery.com/?utm_campaign=wordpress_plugin
*/

$free_pq_image_sharer_option = get_option('free_pq_image_sharer_option');

if (!defined('FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PLUGIN_NAME'))
	define('FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PAGE_NAME'))
	define('FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PAGE_NAME', 'free_tools_by_profitquery_onhover_image_sharer');

if (!defined('FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_ADMIN_CSS'))
	define('FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_ADMIN_CSS', 'css/wp.css');

require_once 'free_tools_by_profitquery_onhover_image_sharer_class.php';
new FreeProfitQueryToolsImageSharerClass();

add_action('init', 'free_pq_image_sharer_init');

function free_pq_image_sharer_init(){
	global $free_pq_image_sharer_option;	
	if ( !is_admin() && $free_pq_image_sharer_option[apiKey]){
		wp_enqueue_script( 'lite_profitquery_lib',plugins_url().'/'.FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PLUGIN_NAME.'/js/lite.profitquery.min.js?apiKey='.$free_pq_image_sharer_option[apiKey]);		
		add_action('wp_footer', 'free_pq_image_sharer_set_options');
	}
}

/* Adding action links on plugin list*/
function free_tools_by_profitquery_image_sharer_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="options-general.php?page=free_tools_by_profitquery_onhover_image_sharer">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

add_filter('plugin_action_links', 'free_tools_by_profitquery_image_sharer_action_links', 10, 2);

function free_pq_image_sharer_set_options(){
	global $free_pq_image_sharer_option;	
	$content= '<script type="application/javascript">liteprofitquery.productOptions.imageShareOptions = {}; ';
	if((int)$free_pq_image_sharer_option[min_share_image_width_size]>0){
		$content .= 'liteprofitquery.productOptions.imageShareOptions.minWidth = '.intval($free_pq_image_sharer_option[min_share_image_width_size]).'; ';
	}
	if(trim($free_pq_image_sharer_option[type_color])!='' || trim($free_pq_image_sharer_option[type_design])!=''){
		$className = 'x30';
		if(trim($free_pq_image_sharer_option[type_design]) == '' || trim($free_pq_image_sharer_option[type_design]) == 'square') $className = 'x30';
		elseif(trim($free_pq_image_sharer_option[type_design]) == 'rounded') $className = 'x30rounded';
		elseif(trim($free_pq_image_sharer_option[type_design]) == 'circle') $className = 'x30circle';				
		
		$className = $className.' '.$free_pq_image_sharer_option[type_color].' '.trim($free_pq_image_sharer_option[type_background]).' '.trim($free_pq_image_sharer_option[type_inline]);
		$content .= 'liteprofitquery.productOptions.imageShareOptions.typeDesign = "'.$className.'"; ';
	}
	$array_active_socnet_text = '';
	foreach((array)$free_pq_image_sharer_option[social_network] as $k => $v){
		if((int)$v == 1){
			$array_active_socnet_text .= $k.":1,";
		}
	}
	if($array_active_socnet_text) {
		$array_active_socnet_text = substr($array_active_socnet_text, 0, strlen($array_active_socnet_text)-1);
		$content .= 'liteprofitquery.productOptions.imageShareOptions.activeSocnet = {'.$array_active_socnet_text.'}; ';
	}	
	$content.='</script>';
	echo $content;
}