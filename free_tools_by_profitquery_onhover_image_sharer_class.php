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
* @category Class
* @package  Wordpress_Plugin
* @author   ShemOtechnik Profitquery Team <support@profitquery.com>
* @license  http://www.php.net/license/3_01.txt  PHP License 3.01
* @version  SVN: 1.0
*/

class FreeProfitQueryToolsImageSharerClass
{
	/** LitePQ Share Image Settings **/
    var $_options;
	function FreeProfitQueryToolsImageSharerClass(){
		$this->__construct();
	}
	/**
     * Initializes the plugin.
     *
     * @param null     
     * @return null
     * */
    function __construct()
    {
		$this->_options = $this->getSettings();			
        add_action('admin_menu', array($this, 'LPQImageShareMenu'));		
    }
	
	/**
     * Adds sub menu page to the WP settings menu
     * 
     * @return null
     */
    function LPQImageShareMenu()
    {
        add_options_page(
            'Free Profitquery | Image Sharer', 'Free Profitquery | Image Sharer',
            'manage_options', FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PAGE_NAME,
            array($this, 'LPQImageShareOptions')
        );
    }
	
	 /**
     * Get the plugin's settings page url
     * 
     * @return string
     */
    function getSettingsPageUrl()
    {
        return admin_url("options-general.php?page=" . FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PAGE_NAME);
    }
	
	/**
     *  Get LitePQ Share Image settings array
     * 
     *  @return string
     */
    function getSettings()
    {
        return get_option('free_pq_image_sharer_option');
    }
	
	 /**
     * Manages the WP settings page
     * 
     * @return null
     */
    function LPQImageShareOptions()
    {
        if (!current_user_can('manage_options')) {
            wp_die(
                __('You do not have sufficient permissions to access this page.')
            );
        }
		wp_enqueue_style('free_profitquery_image_sharer_style',plugins_url().'/'.FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_PLUGIN_NAME.'/'.FREE_TOOLS_BY_PROFITQUERY_ONHOVER_IMAGE_SHARER_ADMIN_CSS);
		
		echo '
		<noscript>
				<div id="errorJSPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(242, 20, 67, 0.5); text-align: center;">
					 <p style="color: rgb(174, 0, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">Please enable JavaScript in your browser.</p>
				</div>
			</noscript>
		';
		
		if($_POST[action] == 'change'){
			if(intval($_POST[min_image_width]) >= 0) $this->_options['min_share_image_width_size'] = intval($_POST[min_image_width]);
			if($_POST[social_network]){
				if($_POST[social_network][FB] == 'on') $this->_options['social_network'][FB] = 1; else $this->_options['social_network'][FB] = 0;
				if($_POST[social_network][TW] == 'on') $this->_options['social_network'][TW] = 1; else $this->_options['social_network'][TW] = 0;
				if($_POST[social_network][PI] == 'on') $this->_options['social_network'][PI] = 1; else $this->_options['social_network'][PI] = 0;
				if($_POST[social_network][GP] == 'on') $this->_options['social_network'][GP] = 1; else $this->_options['social_network'][GP] = 0;
				if($_POST[social_network][TR] == 'on') $this->_options['social_network'][TR] = 1; else $this->_options['social_network'][TR] = 0;
				if($_POST[social_network][VK] == 'on') $this->_options['social_network'][VK] = 1; else $this->_options['social_network'][VK] = 0;
				if($_POST[social_network][OD] == 'on') $this->_options['social_network'][OD] = 1; else $this->_options['social_network'][OD] = 0;
			}
			
			if(trim($_POST['type_color'])) $this->_options['type_color'] = sanitize_text_field($_POST[type_color]);
			if(trim($_POST['type_design'])) $this->_options['type_design'] = sanitize_text_field($_POST[type_design]);
			$this->_options['type_background'] = sanitize_text_field($_POST['type_background']);
			$this->_options['type_inline'] = sanitize_text_field($_POST['type_inline']);
			
						
			update_option('free_pq_image_sharer_option', $this->_options);
			echo '
			<div id="successPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(151, 255, 0, 0.5); text-align: center;">
					<p style="color: rgb(104, 174, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">Data changed!</p>
			</div>
			<script>
				setTimeout(function(){document.getElementById("successPQBlock").style.display="none";}, 5000);
				</script>
			';
		}
		
		//save api key
		if(trim($_POST[apiKey]) != '' || trim($_GET[apiKey]) != ''){
			if(!trim($this->_options['apiKey'])){
				$this->_options['min_share_image_width_size'] = 100;				
				$this->_options['type_design'] = '';
				$this->_options['type_color'] = '';
				$this->_options['social_network'][FB] = 1;
				$this->_options['social_network'][TW] = 1;				
				$this->_options['social_network'][GP] = 1;				
				
			}
						
			
			if(trim($_POST[apiKey]) != '') $this->_options['apiKey'] = sanitize_text_field($_POST[apiKey]);
			if(trim($_GET[apiKey]) != '') $this->_options['apiKey'] = sanitize_text_field($_GET[apiKey]);
			update_option('free_pq_image_sharer_option', $this->_options);
			echo '			
				<div id="successPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(151, 255, 0, 0.5); text-align: center;">
					<p style="color: rgb(104, 174, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">API Key Was Saved!</p>
				</div>
				<script>
				setTimeout(function(){document.getElementById("successPQBlock").style.display="none";}, 5000);
				</script>
			';			
		}				
		if(!trim($this->_options['apiKey']) || $_GET[action] == 'changeApiKey'){
			$redirect_url = str_replace(".", "%2E", urlencode($this->getSettingsPageUrl().'&action=changeApiKey'));
			if((int)$_GET[is_error] == 1){
				echo '
					<div id="errorPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(242, 20, 67, 0.5); text-align: center;">
					 <p style="color: rgb(174, 0, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">Wrong Lite Profitquery API Key. <a href="http://litelib.profitquery.com/cms-sign-in/?domain='.$this->getDomain().'&cms=wp&redirect='.
                     str_replace(".", "%2E", urlencode($this->getSettingsPageUrl())).'" style="text-decoration: none;" target="_getLitePQApiKey">Get API Key</a></p>
					</div>					
					<script>
					setTimeout(function(){document.getElementById("errorPQBlock").style.display="none";}, 10000);
					</script>
				';
			}
			echo '			
			<div style="text-align: center; margin: 0 auto;">			
			<section style="margin: 20px auto 100px; width: 60%; ">
			<div style="overflow: hidden; margin: 0 0 40px;">
			  <h1 class="pq" style="font-family: pt sans narrow; font-size: 30px; color: #7A7A7A; font-weight: normal; display: inline-block; float: left; margin: 0; line-height: 40px;">Start to use Profitquery</h1>
			  <p style="font-family: arial; font-size: 16px; color: #929292; display: inline-block; float: right; margin: 0; height: 40px; padding: 10px 0 0; box-sizing: border-box;">Need help? <a style="color: #222222; text-decoration: none;" href="http://profitquery.com/image_sharer_wordpress.html" target="_pq_image_sharer_wordpress">Check instructions <img src="'.plugins_url('images/icon.png', __FILE__).'" style="margin: 0 0 -5px;" /></a></p>
			 </div>				
				<p style="font-family: arial; font-size: 16px; color: #A9A9A9; margin: 16px 0 50px;">To start using the On Hover Image Sharer plugin, we first need your Profitquery Lite API Key.</p>
				<img src="'.plugins_url('images/logo.png', __FILE__).'" style="display: block; margin: 0px auto;" />
				<form action="'.$this->getSettingsPageUrl().'" method="post" onsubmit="checkApiKey();return true;">
					<label><p style="font-family: arial; font-size: 16px; color: #A9A9A9; margin: 30px 0 5px;">Lite Profitquery API Key</p>
						<input type="text" name="apiKey" id="lPQApiKeyInput" value="'.$this->_options['apiKey'].'"  style="display: block; margin: 0 auto; padding:7px 15px; width: 70%; min-width: 200px;">
					</label>
					<a style="color: rgb(242, 20, 67); font-family: arial; font-size: 16px; display: block;margin: 10px; text-decoration: none;" href="http://litelib.profitquery.com/cms-sign-in/?domain='.$this->getDomain().'&cms=wp&redirect='.
                     str_replace(".", "%2E", urlencode($this->getSettingsPageUrl())).'" target="_getLitePQApiKey">Get API Key</a>
					<input type="submit" value="Confirm and save" style="font-family: pt sans narrow; color: white; background: #F21443; border: none; font-size: 20px; padding: 10px 40px; margin: 20px auto 0; border-radius: 3px; ">	
					 
				</form>
				<script>
					function checkApiKey(){						
						var	winParamString = "menubar=0,toolbar=0,resizable=1,scrollbars=1,width=400,height=200";											
						var clonWinParamString = winParamString;
						try {
							var e = winParamString.split("width=")[1].split(",")[0],
								f = winParamString.split("height=")[1].split(",")[0],
								g = (screen.width - e) / 2,
								h = (screen.height - f) / 2;
							g < 0 && (g = 0);
							h < 0 && (h = 0);
							clonWinParamString = clonWinParamString + (",top=" + h + ",left=" + g)
						} catch (i) {}
						try {							
							wopen = window.open("http://litelib.profitquery.com/cms-check-key/?domain='.$this->getDomain().'&cms=wp&redirect='.$redirect_url.'&apiKey="+encodeURIComponent(document.getElementById("lPQApiKeyInput").value), "Lite_Profitquery_API_Key_Check", clonWinParamString);							
						}catch(err){}						
					}
				</script>
			</section>
			</div>
			';	
		} else {
			echo '				
				<div style="text-align: center; margin: 0 auto;">
				<section style="margin: 20px auto 100px; width: 60%;">
				<div style="overflow: hidden; margin: 0 0 40px;">
					<h1 class="pq" style="font-family: pt sans narrow; font-size: 30px; color: #7A7A7A; font-weight: normal; display: inline-block; float: left; margin: 0; line-height: 40px;">Configure Image Share Options</h1>
					<p style="font-family: arial; font-size: 16px; color: #929292; display: inline-block; float: right; padding: 10px 0 0 0; height: 40px; box-sizing: border-box; margin: 0;">Need help? <a style="color: #222222; text-decoration: none;" href="http://profitquery.com/image_sharer_wordpress.html" target="_pq_image_sharer_wordpress">Check instructions <img src="'.plugins_url('images/icon.png', __FILE__).'" style="margin: 0 0 -5px;" /></a></p>
				</div>				
				
				<form action="'.$this->getSettingsPageUrl().'" method="post">
				<input type="hidden" name="action" value="change">
				<div style="display:block;">
					<div style="position: relative; overflow: hidden; width: 260px; height: 250px; display: inline-block; margin: 0 2%; min-width: 260px;">
						<img src="'.plugins_url('images/capture.png', __FILE__).'" style="position: absolute; top: 0; right: 0;" />
						<input type="text" name="min_image_width" value="'.$this->_options['min_share_image_width_size'].'" style="position: absolute; top: 85px; width: 100px; right: 80px; font-size: 16px; font-family: arial; color: #9A9A9A; padding: 0; box-sizing: border-box; text-align: center;" placeholder="500">
						<p style="position: absolute; top: 72px; right: 55px; font-size: 16px; font-family: arial; color: #9A9A9A;">px</p>
					</div>
					<div style="position: relative; overflow: hidden; width: 260px; height: 250px; display: inline-block; margin: 0 2%;">
						<div class="x30 c4" id="LPQ_icons_bar_id" style="text-align: center; height: 64px;">
									
												<label style="overflow: hidden; display: inline-block; "><div class="pq_fb"></div>
												<input type="checkbox"'; if($this->_options['social_network'][FB] == '1') { echo 'checked="checked"'; } echo' name="social_network[FB]" style="display: block; margin: 4px auto;"></label>
												
												<label style="overflow: hidden; display: inline-block; "><div class="pq_tw"></div>
												<input type="checkbox" '; if($this->_options['social_network'][TW] == '1') { echo 'checked="checked"'; } echo' name="social_network[TW]" style="display: block; margin: 4px auto;"></label>
												
												<label style="overflow: hidden; display: inline-block; "><div class="pq_gp"></div>
												<input type="checkbox" '; if($this->_options['social_network'][GP] == '1') { echo 'checked="checked"'; } echo' name="social_network[GP]" style="display: block; margin: 4px auto;"></label>
												
												<label style="overflow: hidden; display: inline-block; "><div class="pq_pi"></div>
												<input type="checkbox" '; if($this->_options['social_network'][PI] == '1') { echo 'checked="checked"'; } echo' name="social_network[PI]" style="display: block; margin: 4px auto;"></label>
												
												<label style="overflow: hidden; display: inline-block; "><div class="pq_tu"></div>
												<input type="checkbox" '; if($this->_options['social_network'][TR] == '1') { echo 'checked="checked"'; } echo' name="social_network[TR]" style="display: block; margin: 4px auto;"></label>
												
												<label style="overflow: hidden; display: inline-block; "><div class="pq_vk"></div>
												<input type="checkbox" '; if($this->_options['social_network'][VK] == '1') { echo 'checked="checked"'; } echo' name="social_network[VK]" style="display: block; margin: 4px auto;"></label>
												
												<label style="overflow: hidden; display: inline-block; "><div class="pq_od"></div>
												<input type="checkbox" '; if($this->_options['social_network'][OD] == '1') { echo 'checked="checked"'; } echo' name="social_network[OD]" style="display: block; margin: 4px auto;"></label>
												
												
									
								</div>
						<select name="type_color" id="LPQ_type_color_id" onchange="lpg_change_design();return false;" style="display: block; width: 100%; box-sizing: border-box; padding:0 4px; margin: 7px 0; color: #B1B1B1; font-size: 16px; height: 26px;">';
						if($this->_options['type_color'] == 'c4')	echo '<option value="c4" selected>Color</option>'; else echo '<option value="c4">Color</option>';
						if($this->_options['type_color'] == 'c1')   echo '<option value="c1" selected>Color light</option>'; else echo '<option value="c1">Color light</option>';
						if($this->_options['type_color'] == 'c2')	echo '<option value="c2" selected>Color volume</option>'; else echo '<option value="c2">Color volume</option>';
						if($this->_options['type_color'] == 'c3')	echo '<option value="c3" selected>Color dark</option>'; else echo '<option value="c3">Color dark</option>';
						if($this->_options['type_color'] == 'c5')	echo '<option value="c5" selected>Black</option>'; else echo '<option value="c5">Black</option>';
						if($this->_options['type_color'] == 'c6')	echo '<option value="c6" selected>Black volume</option>'; else echo '<option value="c6">Black volume</option>';
						if($this->_options['type_color'] == 'c7')	echo '<option value="c7" selected>White volume</option>'; else echo '<option value="c7">White volume</option>';
						if($this->_options['type_color'] == 'c8')	echo '<option value="c8" selected>White</option>'; else echo '<option value="c8">White</option>';
						echo '</select>
						<select name="type_design" id="LPQ_type_design_id" onchange="lpg_change_design();return false;" style="display: block; width: 100%; box-sizing: border-box; padding:0 4px; margin: 7px 0; color: #B1B1B1; font-size: 16px; height: 26px;">';
						if($this->_options['type_design'] == 'square' || trim($this->_options['type_design']) == '') echo '<option value="square" selected>Square</option>'; else echo '<option value="square">Square</option>';
						if($this->_options['type_design'] == 'circle') echo '<option value="circle" selected>Circle</option>'; else echo '<option value="circle">Circle</option>';
						if($this->_options['type_design'] == 'rounded') echo '<option value="rounded" selected>Rounded</option>'; else echo '<option value="rounded">Rounded</option>';
						echo '</select>
						<select name="type_background" id="LPQ_type_background_id" onchange="lpg_change_design();return false;" style="display: block; width: 100%; box-sizing: border-box; padding:0 4px; margin: 7px 0; color: #B1B1B1; font-size: 16px; height: 26px;">';
						if($this->_options['type_background'] == '' || trim($this->_options['type_background']) == '') echo '<option value="" selected>Default background</option>'; else echo '<option value="">Default background</option>';
						if($this->_options['type_background'] == 'sh1') echo '<option value="sh1" selected>Transparent</option>'; else echo '<option value="sh1">Transparent</option>';
						if($this->_options['type_background'] == 'sh2') echo '<option value="sh2" selected>White</option>'; else echo '<option value="sh2">White</option>';
						if($this->_options['type_background'] == 'sh3') echo '<option value="sh3" selected>Light</option>'; else echo '<option value="sh3">Light</option>';
						if($this->_options['type_background'] == 'sh4') echo '<option value="sh4" selected>Grey</option>'; else echo '<option value="sh4">Grey</option>';
						if($this->_options['type_background'] == 'sh5') echo '<option value="sh5" selected>Sharp</option>'; else echo '<option value="sh5">Sharp</option>';
						echo '</select>
						<select name="type_inline" id="LPQ_type_line_id" onchange="lpg_change_design();return false;" style="display: block; width: 100%; box-sizing: border-box; padding:0 4px; margin: 7px 0 0; color: #B1B1B1; font-size: 16px; height: 26px;">';
						if(trim($this->_options['type_inline']) == '') echo '<option value="" selected>In a column</option>'; else echo '<option value="">In a column</option>';
						if($this->_options['type_inline'] == 'inline') echo '<option value="inline" selected>Inline</option>'; else echo '<option value="inline">Inline</option>';
						echo '</select>
					</div>
				</div>	
					<input type="submit" value="Save changes" style="font-family: pt sans narrow; color: white; background: #F21443; border: none; font-size: 20px; padding: 10px 40px; margin: 12px; border-radius: 3px; ">
					<a style="font-family: arial; display: block;color: #313131;font-size: 16px;text-decoration: none; " href="http://profitquery.com/image_sharer_wordpress.html#additional_settings" target="_more">More options</a>
					<a href="'.$this->getSettingsPageUrl().'&action=changeApiKey" style="font-family: arial; display: block;color: #F21443;;font-size: 16px; text-decoration: none; margin: 20px auto 0;">Edit API Key</a>
					
				</form>
			</section>
			<section style="margin: 20px auto 100px; width: 90%;">
	<h2 style="font-family: pt sans narrow; font-size: 26px; color: #5A5A5A; font-weight: normal;">More Profitquery Tools</h2>

		<a href="http://profitquery.com/contact_form.html" target="_blank" target="_blank""><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/contact_form.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Contact Form</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/mailchimp_integration.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/mailchimp_integration.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">MailChimp Integration</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/smart_popup.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/smart_popup.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Smart Popup</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/referral_system.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/referral_system.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Referral System</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/marketing_bar.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/marketing_bar.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Marketing Bar</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/follow_buttons.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/follow_buttons.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Follow Buttons</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
				
		<a href="http://profitquery.com/floating_popup.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/floating_popup.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Floating Popup</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/exit_popup.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/exit_popup.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Exit Popup</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/social_login.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/social_login.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Social Login</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		
		<a href="http://profitquery.com/call_me_back.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/call_me_back.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Call Me Back</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/contact_verifier.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/contact_verifier.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Contact Verifier</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/cart_abandonment.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/cart_abandonment.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Cart Abandonment</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/social_reward.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/social_reward.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Social Reward</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/trigger_mail.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/trigger_mail.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Trigger Mail</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/secure_voting.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/secure_voting.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Secure Voting</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
				
		<a href="http://profitquery.com/sharing_sidebar.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/sharing_sidebar.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Sharing Sidebar</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/product_discount.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/product_discount.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Product Discount</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		<a href="http://profitquery.com/image_sharer.html"><div class="pq_wp_item">
			<div style="overflow: hidden;">
				<img src="'.plugins_url('images/image_sharer.png', __FILE__).'" />
			</div>
			<h3 style="color: #8A8A8A; font-family: arial; font-weight: normal; font-size: 16px; margin: 6px 0 10px;">Image Sharer</h3>
			<input type="submit" href="#" value="LEARN MORE" >	
		</div></a>
		
		
		
</section>
			</div>
			<script>
				function lpg_change_design(){
					var classN = "x30";
					if(document.getElementById("LPQ_type_design_id").value == "square"){
						classN = "x30";
					}else if(document.getElementById("LPQ_type_design_id").value == "rounded"){
						classN = "x30rounded";
					}else if(document.getElementById("LPQ_type_design_id").value == "circle"){
						classN = "x30circle";
					}
					
					classN = classN+" "+document.getElementById("LPQ_type_color_id").value;
					document.getElementById("LPQ_icons_bar_id").className = classN;					
				}
				lpg_change_design();
			</script>
			';
		}       
    }
	
	/**
     * Get the wp domain
     * 
     * @return string
     */
    function getDomain()
    {
        $url     = get_option('siteurl');
        $urlobj  = parse_url($url);
        $domain  = $urlobj['host'];
        return $domain;
    }
}