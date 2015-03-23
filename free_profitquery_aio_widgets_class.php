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
* @version  SVN: 1.1
*/

class ProfitQuerySmartWidgetsClass
{
	/** Profitquery Settings **/
    var $_options;
	function ProfitQuerySmartWidgetsClass(){
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
		/*get Options From Old Image Sharer and delete them*/
		$this->getOldSettings();
		add_action( 'admin_init', array($this, 'wpbootstrap_scripts_with_jquery') );
        add_action('admin_menu', array($this, 'ProfitquerySmartWidgetsMenu'));		
		
    }
	
	function wpbootstrap_scripts_with_jquery(){				
		wp_register_script('jquery-2.1.3', 'https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', '','2.1.3');		
		wp_register_script('bootstrap-min', plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/js/bootstrap.min.js', array('jquery-2.1.3'));		
		wp_register_script('bootstrap-switch', plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/js/bootstrap-switch.js', array('bootstrap-min'));
		
		wp_enqueue_script('jquery-2.1.3');
		wp_enqueue_script('bootstrap-min');
		wp_enqueue_script('bootstrap-switch');
		
		
		wp_enqueue_style('google_fonts','http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&amp;subset=latin,cyrillic');
		wp_enqueue_style('profitquery_smart_widgets_bootstrap',plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH.'bootstrap.css');
		wp_enqueue_style('profitquery_smart_widgets_bootstrap_theme',plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH.'bootstrap-theme.css');
		wp_enqueue_style('profitquery_smart_widgets_bootstrap_switch',plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH.'bootstrap-switch.css');
		wp_enqueue_style('profitquery_smart_widgets_main',plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH.'profitquery_smart_widgets_wordpress.css');
		wp_enqueue_style('profitquery_smart_widgets_icons',plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH.'icons.css');				
	}
	
	/**
     * Adds sub menu page to the WP settings menu
     * 
     * @return null
     */
    function ProfitquerySmartWidgetsMenu()
    {		
        add_options_page(
            'AIO Widgets | Profitquery', 'Share + Subscribe + Contact',
            'manage_options', PROFITQUERY_SMART_WIDGETS_PAGE_NAME,
            array($this, 'ProfitquerySmartWidgetsOptions')
        );
    }
	
	 /**
     * Get the plugin's settings page url
     * 
     * @return string
     */
    function getSettingsPageUrl()
    {
        return admin_url("options-general.php?page=" . PROFITQUERY_SMART_WIDGETS_PAGE_NAME);
    }
	
	function setDefaultProductData(){
		//Other default params
		$this->_options[sharingSideBar][disabled] = 0;				
		$this->_options[subscribeBar][disabled] = 1;
		$this->_options[subscribeExit][disabled] = 1;
		$this->_options[thankPopup][disabled] = 1;
		$this->_options[follow][disabled] = 1;
		$this->_options[callMe][disabled] = 1;
																		
		$this->_options[sharingSideBar][socnet] = array('FB'=>1, 'GP'=>1, 'TW'=>1, 'LI'=>1, 'MailTo'=>1);
		$this->_options[sharingSideBar][position] = 'pq_left pq_middle';
		$this->_options[sharingSideBar][design][color] = 'c4';
		$this->_options[sharingSideBar][design][size] = 'x40';

		$this->_options[contactUs][disabled] = 0;
		$this->_options[contactUs][position] = 'pq_right pq_bottom';
		$this->_options['contactUs']['typeWindow'] = 'pq_medium';				
		$this->_options['contactUs']['background'] = 'bg_grey';
		$this->_options['contactUs']['button_color'] = 'btn_lightblue';				
		$this->_options['contactUs']['title'] = 'Contact Us';
		$this->_options['contactUs']['buttonTitle'] = 'Send';				
		$this->_options['contactUs']['loader_background'] = 'bg_black';
		$this->_options['contactUs']['afterProceed'][thank] = 1;
		
		$this->_options['thankPopup']['title'] = 'Thank You';
		$this->_options['thankPopup']['buttonTitle'] = 'Close';
		$this->_options['thankPopup']['background'] = 'bg_grey';
		$this->_options['thankPopup']['img'] = 'img_10.png';								
		
		$this->_options[subscribeBar][background] = 'bg_grey';
		$this->_options[subscribeBar][button_color] = 'btn_lightblue invert';
		$this->_options[subscribeExit][background] = 'bg_grey';
		$this->_options[subscribeExit][button_color] = 'btn_lightblue invert';
		$this->_options[subscribeExit][typeWindow] = 'pq_medium';
		
		$this->_options['adminEmail'] = get_settings('admin_email');
		$this->_options[aio_widgets_loaded] = 1;
		update_option('profitquery', $this->_options);
	}	
	
	function getOldSettings(){
		$oldImageOptions = get_option('free_pq_image_sharer_option');
		
		//IF non apiKey and Non old image sharer
		if(!$this->_options[apiKey]){
			if($oldImageOptions[apiKey]){
				$this->_options[apiKey] = $oldImageOptions[apiKey];
				$this->_options[aio_widgets_loaded] = 1;
				$this->_options[imageSharer][disabled] = 0;
				$this->_options[imageSharer][socnet] = $oldImageOptions['social_network'];
				$this->_options[imageSharer][design][form] = $oldImageOptions['type_design'];
				$this->_options[imageSharer][design][size] = 'x30';
				$this->_options[imageSharer][design][color] = $oldImageOptions['type_color'];
				$this->_options[imageSharer][design][shadow] = $oldImageOptions['type_background'];
				$this->_options[imageSharer][position] = $oldImageOptions['type_inline'];
				if((int)$oldImageOptions['min_share_image_width_size'] > 0)
					$this->_options[imageSharer][minWidth] = (int)$oldImageOptions['min_share_image_width_size'];
				else
					$this->_options[imageSharer][minWidth] = 100;
				
				update_option('profitquery', $this->_options);
				update_option('free_pq_image_sharer_option', array());
				
				$this->setDefaultProductData();
			}
		//IF not yet Loaded
		} else if((int)$this->_options[aio_widgets_loaded] == 0) {
			//DEFAULT OPTIONS
			$this->_options[imageSharer][disabled] = 0;
			$this->_options[imageSharer][socnet] = array('FB'=>1, 'GP'=>1, 'TW'=>1, 'PI'=>1);				
			$this->_options[imageSharer][design][color] = 'c4';
			$this->_options[imageSharer][design][size] = 'x30';
			$this->_options[imageSharer][design][shadow] = 'sh6';
			$this->_options[imageSharer][minWidth] = 100;
			update_option('profitquery', $this->_options);
			
			$this ->setDefaultProductData();
		}
	}
	
	/**
     *  Get LitePQ Share Image settings array
     * 
     *  @return string
     */
    function getSettings()
    {
        return get_option('profitquery');
    }
	
	 /**
     * Manages the WP settings page
     * 
     * @return null
     */
    function ProfitquerySmartWidgetsOptions()
    {
        if (!current_user_can('manage_options')) {
            wp_die(
                __('You do not have sufficient permissions to access this page.')
            );
        }
		echo '
		<noscript>				
				<p>Please enable JavaScript in your browser.</p>				
		</noscript>
		';		
		
		if($_POST[action] == 'editAdditionalData'){
			//follow
			if($_POST[follow]){				
				if(trim($_POST[follow][title])) $this->_options['follow']['title'] = sanitize_text_field($_POST[follow][title]); else $this->_options['follow']['title'] = '';
				if(trim($_POST[follow][sub_title])) $this->_options['follow']['sub_title'] = sanitize_text_field($_POST[follow][sub_title]); else $this->_options['follow']['sub_title'] = '';
				if(trim($_POST[follow][background])) $this->_options['follow']['background'] = sanitize_text_field($_POST[follow][background]); else $this->_options['follow']['background'] = '';
				if($_POST[follow][follow_socnet]){
					if(trim($_POST[follow][follow_socnet][FB]) != '') $this->_options[follow][follow_socnet][FB] = sanitize_text_field($_POST[follow][follow_socnet][FB]); else $this->_options[follow][follow_socnet][FB] = '';
					if(trim($_POST[follow][follow_socnet][TW]) != '') $this->_options[follow][follow_socnet][TW] = sanitize_text_field($_POST[follow][follow_socnet][TW]); else $this->_options[follow][follow_socnet][TW] = '';
					if(trim($_POST[follow][follow_socnet][GP]) != '') $this->_options[follow][follow_socnet][GP] = sanitize_text_field($_POST[follow][follow_socnet][GP]); else $this->_options[follow][follow_socnet][GP] = '';
					if(trim($_POST[follow][follow_socnet][PI]) != '') $this->_options[follow][follow_socnet][PI] = sanitize_text_field($_POST[follow][follow_socnet][PI]); else $this->_options[follow][follow_socnet][PI] = '';
					if(trim($_POST[follow][follow_socnet][VK]) != '') $this->_options[follow][follow_socnet][VK] = sanitize_text_field($_POST[follow][follow_socnet][VK]); else $this->_options[follow][follow_socnet][VK] = '';
					if(trim($_POST[follow][follow_socnet][OD]) != '') $this->_options[follow][follow_socnet][OD] = sanitize_text_field($_POST[follow][follow_socnet][OD]); else $this->_options[follow][follow_socnet][OD] = '';
				}
			}
			
			//thankPopup
			if($_POST[thankPopup]){
				if(trim($_POST[thankPopup][title])) $this->_options['thankPopup']['title'] = sanitize_text_field($_POST[thankPopup][title]); else $this->_options['thankPopup']['title'] = '';
				if(trim($_POST[thankPopup][sub_title])) $this->_options['thankPopup']['sub_title'] = sanitize_text_field($_POST[thankPopup][sub_title]); else $this->_options['thankPopup']['sub_title'] = '';
				if(trim($_POST[thankPopup][buttonTitle])) $this->_options['thankPopup']['buttonTitle'] = sanitize_text_field($_POST[thankPopup][buttonTitle]); else $this->_options['thankPopup']['buttonTitle'] = '';
				if(trim($_POST[thankPopup][background])) $this->_options['thankPopup']['background'] = sanitize_text_field($_POST[thankPopup][background]); else $this->_options['thankPopup']['background'] = '';
				if(trim($_POST[thankPopup][img])) $this->_options['thankPopup']['img'] = sanitize_text_field($_POST[thankPopup][img]); else $this->_options['thankPopup']['img'] = '';
				if(trim($_POST[thankPopup][imgUrl])) $this->_options['thankPopup']['imgUrl'] = sanitize_text_field($_POST[thankPopup][imgUrl]); else $this->_options['thankPopup']['imgUrl'] = '';				
			}						
			
			//sharingSideBar
			if($_POST[sharingSideBar][afterProceed][follow] == 'on'){
				$this->_options['sharingSideBar']['afterProceed']['follow'] = 1;
				$this->_options['sharingSideBar']['afterProceed']['thank'] = 0;
			} elseif($_POST[sharingSideBar][afterProceed][thank] == 'on'){
				$this->_options['sharingSideBar']['afterProceed']['follow'] = 0;
				$this->_options['sharingSideBar']['afterProceed']['thank'] = 1;
			} else {
				$this->_options['sharingSideBar']['afterProceed']['follow'] = 0;
				$this->_options['sharingSideBar']['afterProceed']['thank'] = 0;
			}
			
			//imageSharer
			if($_POST[imageSharer][afterProceed][follow] == 'on'){
				$this->_options['imageSharer']['afterProceed']['follow'] = 1;
				$this->_options['imageSharer']['afterProceed']['thank'] = 0;
			} elseif($_POST[imageSharer][afterProceed][thank] == 'on'){
				$this->_options['imageSharer']['afterProceed']['follow'] = 0;
				$this->_options['imageSharer']['afterProceed']['thank'] = 1;
			} else {
				$this->_options['imageSharer']['afterProceed']['follow'] = 0;
				$this->_options['imageSharer']['afterProceed']['thank'] = 0;
			}
			
			//subscribeBar
			if($_POST[subscribeBar][afterProceed][follow] == 'on'){
				$this->_options['subscribeBar']['afterProceed']['follow'] = 1;
				$this->_options['subscribeBar']['afterProceed']['thank'] = 0;
			} elseif($_POST[subscribeBar][afterProceed][thank] == 'on'){
				$this->_options['subscribeBar']['afterProceed']['follow'] = 0;
				$this->_options['subscribeBar']['afterProceed']['thank'] = 1;
			} else {
				$this->_options['subscribeBar']['afterProceed']['follow'] = 0;
				$this->_options['subscribeBar']['afterProceed']['thank'] = 0;
			}
			
			//callMe
			if($_POST[callMe][afterProceed][follow] == 'on'){
				$this->_options['callMe']['afterProceed']['follow'] = 1;
				$this->_options['callMe']['afterProceed']['thank'] = 0;
			} elseif($_POST[callMe][afterProceed][thank] == 'on'){
				$this->_options['callMe']['afterProceed']['follow'] = 0;
				$this->_options['callMe']['afterProceed']['thank'] = 1;
			} else {
				$this->_options['callMe']['afterProceed']['follow'] = 0;
				$this->_options['callMe']['afterProceed']['thank'] = 0;
			}
			
			//contactUs
			if($_POST[contactUs][afterProceed][follow] == 'on'){
				$this->_options['contactUs']['afterProceed']['follow'] = 1;
				$this->_options['contactUs']['afterProceed']['thank'] = 0;
			} elseif($_POST[contactUs][afterProceed][thank] == 'on'){
				$this->_options['contactUs']['afterProceed']['follow'] = 0;
				$this->_options['contactUs']['afterProceed']['thank'] = 1;
			} else {
				$this->_options['contactUs']['afterProceed']['follow'] = 0;
				$this->_options['contactUs']['afterProceed']['thank'] = 0;
			}
			
			//subscribeExit
			if($_POST[subscribeExit][afterProceed][follow] == 'on'){
				$this->_options['subscribeExit']['afterProceed']['follow'] = 1;
				$this->_options['subscribeExit']['afterProceed']['thank'] = 0;
			} elseif($_POST[subscribeExit][afterProceed][thank] == 'on'){
				$this->_options['subscribeExit']['afterProceed']['follow'] = 0;
				$this->_options['subscribeExit']['afterProceed']['thank'] = 1;
			} else {
				$this->_options['subscribeExit']['afterProceed']['follow'] = 0;
				$this->_options['subscribeExit']['afterProceed']['thank'] = 0;
			}			
			
			update_option('profitquery', $this->_options);
			echo '
			<div id="successPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(151, 255, 0, 0.5); text-align: center;">
					<p style="color: rgb(104, 174, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">Data changed!</p>
			</div>
			<script>
				setTimeout(function(){document.getElementById("successPQBlock").style.display="none";}, 5000);
				</script>
			';
		}
		
		if($_POST[action] == 'edit'){
			//sharingSideBar
			if($_POST[sharingSideBar]){
				if($_POST[sharingSideBar][enabled] == 'on') $this->_options['sharingSideBar']['disabled'] = 0; else $this->_options['sharingSideBar']['disabled'] = 1;
				if(trim($_POST[sharingSideBar][position])) $this->_options['sharingSideBar']['position'] = sanitize_text_field($_POST[sharingSideBar][position]); else $this->_options['sharingSideBar']['position'] = '';
				if($_POST[sharingSideBar][socnet]){
					if($_POST[sharingSideBar][socnet][FB] == 'on') $this->_options[sharingSideBar][socnet][FB] = 1; else $this->_options[sharingSideBar][socnet][FB] = 0;
                    if($_POST[sharingSideBar][socnet][TW] == 'on') $this->_options[sharingSideBar][socnet][TW] = 1; else $this->_options[sharingSideBar][socnet][TW] = 0;
                    if($_POST[sharingSideBar][socnet][GP] == 'on') $this->_options[sharingSideBar][socnet][GP] = 1; else $this->_options[sharingSideBar][socnet][GP] = 0;
                    if($_POST[sharingSideBar][socnet][PI] == 'on') $this->_options[sharingSideBar][socnet][PI] = 1; else $this->_options[sharingSideBar][socnet][PI] = 0;
                    if($_POST[sharingSideBar][socnet][TR] == 'on') $this->_options[sharingSideBar][socnet][TR] = 1; else $this->_options[sharingSideBar][socnet][TR] = 0;
                    if($_POST[sharingSideBar][socnet][LI] == 'on') $this->_options[sharingSideBar][socnet][LI] = 1; else $this->_options[sharingSideBar][socnet][LI] = 0;
                    if($_POST[sharingSideBar][socnet][VK] == 'on') $this->_options[sharingSideBar][socnet][VK] = 1; else $this->_options[sharingSideBar][socnet][VK] = 0;
                    if($_POST[sharingSideBar][socnet][OD] == 'on') $this->_options[sharingSideBar][socnet][OD] = 1; else $this->_options[sharingSideBar][socnet][OD] = 0;
                    if($_POST[sharingSideBar][socnet][MW] == 'on') $this->_options[sharingSideBar][socnet][MW] = 1; else $this->_options[sharingSideBar][socnet][MW] = 0;
                    if($_POST[sharingSideBar][socnet][LJ] == 'on') $this->_options[sharingSideBar][socnet][LJ] = 1; else $this->_options[sharingSideBar][socnet][LJ] = 0;
                    if($_POST[sharingSideBar][socnet][MailTo] == 'on') $this->_options[sharingSideBar][socnet][MailTo] = 1; else $this->_options[sharingSideBar][socnet][MailTo] = 0;
                    if($_POST[sharingSideBar][socnet]['Print'] == 'on') $this->_options[sharingSideBar][socnet]['Print'] = 1; else $this->_options[sharingSideBar][socnet]['Print'] = 0;
				}
				
				if(trim($_POST[sharingSideBar][design][color])) $this->_options['sharingSideBar']['design']['color'] = sanitize_text_field($_POST[sharingSideBar][design][color]); else $this->_options['sharingSideBar']['design']['color'] = 'c4';
				if(trim($_POST[sharingSideBar][design][form])) $this->_options['sharingSideBar']['design']['form'] = sanitize_text_field($_POST[sharingSideBar][design][form]); else $this->_options['sharingSideBar']['design']['form'] = '';
				if(trim($_POST[sharingSideBar][design][size])) $this->_options['sharingSideBar']['design']['size'] = sanitize_text_field($_POST[sharingSideBar][design][size]); else $this->_options['sharingSideBar']['design']['size'] = 'x30';
				
				if($_POST[sharingSideBar][afterProceed]){
					if($_POST[sharingSideBar][afterProceed][follow] == 'on'){
						$this->_options['sharingSideBar']['afterProceed']['follow'] = 1;
						$this->_options['sharingSideBar']['afterProceed']['thank'] = 0;
					} elseif($_POST[sharingSideBar][afterProceed][thank] == 'on'){
						$this->_options['sharingSideBar']['afterProceed']['follow'] = 0;
						$this->_options['sharingSideBar']['afterProceed']['thank'] = 1;
					} else {
						$this->_options['sharingSideBar']['afterProceed']['follow'] = 0;
						$this->_options['sharingSideBar']['afterProceed']['thank'] = 0;
					}									
				} else {
					$this->_options['sharingSideBar']['afterProceed']['follow'] = 0;
					$this->_options['sharingSideBar']['afterProceed']['thank'] = 0;
				}
			}
			
			//imageSharer
			if($_POST[imageSharer]){
				if($_POST[imageSharer][enabled] == 'on') $this->_options['imageSharer']['disabled'] = 0; else $this->_options['imageSharer']['disabled'] = 1;
				if(trim($_POST[imageSharer][position])) $this->_options['imageSharer']['position'] = sanitize_text_field($_POST[imageSharer][position]); else $this->_options['imageSharer']['position'] = '';
				if($_POST[imageSharer][socnet]){					
					if($_POST[imageSharer][socnet][FB] == 'on') $this->_options[imageSharer][socnet][FB] = 1; else $this->_options[imageSharer][socnet][FB] = 0;
                    if($_POST[imageSharer][socnet][TW] == 'on') $this->_options[imageSharer][socnet][TW] = 1; else $this->_options[imageSharer][socnet][TW] = 0;
                    if($_POST[imageSharer][socnet][GP] == 'on') $this->_options[imageSharer][socnet][GP] = 1; else $this->_options[imageSharer][socnet][GP] = 0;
                    if($_POST[imageSharer][socnet][PI] == 'on') $this->_options[imageSharer][socnet][PI] = 1; else $this->_options[imageSharer][socnet][PI] = 0;
                    if($_POST[imageSharer][socnet][TR] == 'on') $this->_options[imageSharer][socnet][TR] = 1; else $this->_options[imageSharer][socnet][TR] = 0;
                    if($_POST[imageSharer][socnet][LI] == 'on') $this->_options[imageSharer][socnet][LI] = 1; else $this->_options[imageSharer][socnet][LI] = 0;
                    if($_POST[imageSharer][socnet][VK] == 'on') $this->_options[imageSharer][socnet][VK] = 1; else $this->_options[imageSharer][socnet][VK] = 0;
                    if($_POST[imageSharer][socnet][OD] == 'on') $this->_options[imageSharer][socnet][OD] = 1; else $this->_options[imageSharer][socnet][OD] = 0;
                    if($_POST[imageSharer][socnet][LJ] == 'on') $this->_options[imageSharer][socnet][LJ] = 1; else $this->_options[imageSharer][socnet][LJ] = 0;                    
				}
				if(trim($_POST[imageSharer][design][color])) $this->_options['imageSharer']['design']['color'] = sanitize_text_field($_POST[imageSharer][design][color]); else $this->_options['imageSharer']['design']['color'] = 'c4';
				if(trim($_POST[imageSharer][design][form])) $this->_options['imageSharer']['design']['form'] = sanitize_text_field($_POST[imageSharer][design][form]); else $this->_options['imageSharer']['design']['form'] = '';
				if(trim($_POST[imageSharer][design][size])) $this->_options['imageSharer']['design']['size'] = sanitize_text_field($_POST[imageSharer][design][size]); else $this->_options['imageSharer']['design']['size'] = 'x30';
				if(trim($_POST[imageSharer][design][shadow])) $this->_options['imageSharer']['design']['shadow'] = sanitize_text_field($_POST[imageSharer][design][shadow]); else $this->_options['imageSharer']['design']['shadow'] = 'sh1';
				
				if(intval($_POST[imageSharer][minWidth]) >= 0) $this->_options['imageSharer']['minWidth'] = intval($_POST[imageSharer][minWidth]);								
				
				if($_POST[imageSharer][afterProceed]){
					if($_POST[imageSharer][afterProceed][follow] == 'on'){
						$this->_options['imageSharer']['afterProceed']['follow'] = 1;
						$this->_options['imageSharer']['afterProceed']['thank'] = 0;
					} elseif($_POST[imageSharer][afterProceed][thank] == 'on'){
						$this->_options['imageSharer']['afterProceed']['follow'] = 0;
						$this->_options['imageSharer']['afterProceed']['thank'] = 1;
					} else {
						$this->_options['imageSharer']['afterProceed']['follow'] = 0;
						$this->_options['imageSharer']['afterProceed']['thank'] = 0;
					}									
				} else {
					$this->_options['imageSharer']['afterProceed']['follow'] = 0;
					$this->_options['imageSharer']['afterProceed']['thank'] = 0;
				}
			}
						
			if(trim($_POST[imageSharer][position])) $this->_options['imageSharer']['position'] = sanitize_text_field($_POST[imageSharer][position]); else $this->_options['imageSharer']['position'] = '';
			
			
			//Subscribe Design Block
			if(trim($_POST[subscribeDesign]) != '') $this->_options['subscribeDesign'] = sanitize_text_field($_POST[subscribeDesign]);
			if(trim($_POST[subscribeProviderUrl]) != '') $this->_options['subscribeProviderUrl'] = sanitize_text_field($_POST[subscribeProviderUrl]);
			
			//subscribeBar
			if($_POST[subscribeBar]){
				if($_POST[subscribeBar][enabled] == 'on') $this->_options['subscribeBar']['disabled'] = 0; else $this->_options['subscribeBar']['disabled'] = 1;
				if(trim($_POST[subscribeBar][position])) $this->_options['subscribeBar']['position'] = sanitize_text_field($_POST[subscribeBar][position]); else $this->_options['subscribeBar']['position'] = '';
				if(trim($_POST[subscribeBar][typeWindow])) $this->_options['subscribeBar']['typeWindow'] = sanitize_text_field($_POST[subscribeBar][typeWindow]); else $this->_options['subscribeBar']['typeWindow'] = '';
				if(trim($_POST[subscribeBar][title])) $this->_options['subscribeBar']['title'] = sanitize_text_field($_POST[subscribeBar][title]); else $this->_options['subscribeBar']['title'] = '';
				if(trim($_POST[subscribeBar][inputEmailTitle])) $this->_options['subscribeBar']['inputEmailTitle'] = sanitize_text_field($_POST[subscribeBar][inputEmailTitle]); else $this->_options['subscribeBar']['inputEmailTitle'] = '';
				if(trim($_POST[subscribeBar][buttonTitle])) $this->_options['subscribeBar']['buttonTitle'] = sanitize_text_field($_POST[subscribeBar][buttonTitle]); else $this->_options['subscribeBar']['buttonTitle'] = '';
				if(trim($_POST[subscribeBar][background])) $this->_options['subscribeBar']['background'] = sanitize_text_field($_POST[subscribeBar][background]); else $this->_options['subscribeBar']['background'] = '';
				if(trim($_POST[subscribeBar][button_color])) $this->_options['subscribeBar']['button_color'] = sanitize_text_field($_POST[subscribeBar][button_color]); else $this->_options['subscribeBar']['button_color'] = '';
				if(trim($_POST[subscribeBar][size])) $this->_options['subscribeBar']['size'] = sanitize_text_field($_POST[subscribeBar][size]); else $this->_options['subscribeBar']['size'] = '';								
				
				if($_POST[subscribeBar][afterProceed]){
					if($_POST[subscribeBar][afterProceed][follow] == 'on'){
						$this->_options['subscribeBar']['afterProceed']['follow'] = 1;
						$this->_options['subscribeBar']['afterProceed']['thank'] = 0;
					} elseif($_POST[subscribeBar][afterProceed][thank] == 'on'){
						$this->_options['subscribeBar']['afterProceed']['follow'] = 0;
						$this->_options['subscribeBar']['afterProceed']['thank'] = 1;
					} else {
						$this->_options['subscribeBar']['afterProceed']['follow'] = 0;
						$this->_options['subscribeBar']['afterProceed']['thank'] = 0;
					}									
				} else {
					$this->_options['subscribeBar']['afterProceed']['follow'] = 0;
					$this->_options['subscribeBar']['afterProceed']['thank'] = 0;
				}
			}
			
			//subscribeExit
			if($_POST[subscribeExit]){
				if($_POST[subscribeExit][enabled] == 'on') $this->_options['subscribeExit']['disabled'] = 0; else $this->_options['subscribeExit']['disabled'] = 1;
				if(trim($_POST[subscribeExit][position])) $this->_options['subscribeExit']['position'] = sanitize_text_field($_POST[subscribeExit][position]); else $this->_options['subscribeExit']['position'] = '';
				if(trim($_POST[subscribeExit][typeWindow])) $this->_options['subscribeExit']['typeWindow'] = sanitize_text_field($_POST[subscribeExit][typeWindow]); else $this->_options['subscribeExit']['typeWindow'] = '';
				if(trim($_POST[subscribeExit][title])) $this->_options['subscribeExit']['title'] = sanitize_text_field($_POST[subscribeExit][title]); else $this->_options['subscribeExit']['title'] = '';
				if(trim($_POST[subscribeExit][sub_title])) $this->_options['subscribeExit']['sub_title'] = sanitize_text_field($_POST[subscribeExit][sub_title]); else $this->_options['subscribeExit']['sub_title'] = '';
				if(trim($_POST[subscribeExit][inputEmailTitle])) $this->_options['subscribeExit']['inputEmailTitle'] = sanitize_text_field($_POST[subscribeExit][inputEmailTitle]); else $this->_options['subscribeExit']['inputEmailTitle'] = '';
				if(trim($_POST[subscribeExit][buttonTitle])) $this->_options['subscribeExit']['buttonTitle'] = sanitize_text_field($_POST[subscribeExit][buttonTitle]); else $this->_options['subscribeExit']['buttonTitle'] = '';
				if(trim($_POST[subscribeExit][background])) $this->_options['subscribeExit']['background'] = sanitize_text_field($_POST[subscribeExit][background]); else $this->_options['subscribeExit']['background'] = '';
				if(trim($_POST[subscribeExit][button_color])) $this->_options['subscribeExit']['button_color'] = sanitize_text_field($_POST[subscribeExit][button_color]); else $this->_options['subscribeExit']['button_color'] = '';
				if(trim($_POST[subscribeExit][img])) $this->_options['subscribeExit']['img'] = sanitize_text_field($_POST[subscribeExit][img]); else $this->_options['subscribeExit']['img'] = '';
				if(trim($_POST[subscribeExit][imgUrl])) $this->_options['subscribeExit']['imgUrl'] = sanitize_text_field($_POST[subscribeExit][imgUrl]); else $this->_options['subscribeExit']['imgUrl'] = '';				
				if($_POST[subscribeExit][afterProceed]){
					if($_POST[subscribeExit][afterProceed][follow] == 'on'){
						$this->_options['subscribeExit']['afterProceed']['follow'] = 1;
						$this->_options['subscribeExit']['afterProceed']['thank'] = 0;
					} elseif($_POST[subscribeExit][afterProceed][thank] == 'on'){
						$this->_options['subscribeExit']['afterProceed']['follow'] = 0;
						$this->_options['subscribeExit']['afterProceed']['thank'] = 1;
					} else {
						$this->_options['subscribeExit']['afterProceed']['follow'] = 0;
						$this->_options['subscribeExit']['afterProceed']['thank'] = 0;
					}									
				}else{
					$this->_options['subscribeExit']['afterProceed']['follow'] = 0;
					$this->_options['subscribeExit']['afterProceed']['thank'] = 0;
				}
			}
			
			
			//contactUs Design Block
			if(trim($_POST[contactsDesign]) != '') $this->_options['contactsDesign'] = sanitize_text_field($_POST[contactsDesign]);
			if(trim($_POST[adminEmail]) != '') $this->_options['adminEmail'] = sanitize_text_field($_POST[adminEmail]);
			
			//contactUs
			if($_POST[contactUs]){
				if($_POST[contactUs][enabled] == 'on') $this->_options['contactUs']['disabled'] = 0; else $this->_options['contactUs']['disabled'] = 1;
				if(trim($_POST[contactUs][position])) $this->_options['contactUs']['position'] = sanitize_text_field($_POST[contactUs][position]); else $this->_options['contactUs']['position'] = '';
				if(trim($_POST[contactUs][typeWindow])) $this->_options['contactUs']['typeWindow'] = sanitize_text_field($_POST[contactUs][typeWindow]); else $this->_options['contactUs']['typeWindow'] = '';
				if(trim($_POST[contactUs][title])) $this->_options['contactUs']['title'] = sanitize_text_field($_POST[contactUs][title]); else $this->_options['contactUs']['title'] = '';
				if(trim($_POST[contactUs][sub_title])) $this->_options['contactUs']['sub_title'] = sanitize_text_field($_POST[contactUs][sub_title]); else $this->_options['contactUs']['sub_title'] = '';
				if(trim($_POST[contactUs][buttonTitle])) $this->_options['contactUs']['buttonTitle'] = sanitize_text_field($_POST[contactUs][buttonTitle]); else $this->_options['contactUs']['buttonTitle'] = '';
								
				if(trim($_POST[contactUs][background])) $this->_options['contactUs']['background'] = sanitize_text_field($_POST[contactUs][background]); else $this->_options['contactUs']['background'] = '';
				if(trim($_POST[contactUs][loader_background])) $this->_options['contactUs']['loader_background'] = sanitize_text_field($_POST[contactUs][loader_background]); else $this->_options['contactUs']['loader_background'] = '';
				if(trim($_POST[contactUs][button_color])) $this->_options['contactUs']['button_color'] = sanitize_text_field($_POST[contactUs][button_color]); else $this->_options['contactUs']['button_color'] = '';
				if(trim($_POST[contactUs][img])) $this->_options['contactUs']['img'] = sanitize_text_field($_POST[contactUs][img]); else $this->_options['contactUs']['img'] = '';
				if(trim($_POST[contactUs][imgUrl])) $this->_options['contactUs']['imgUrl'] = sanitize_text_field($_POST[contactUs][imgUrl]); else $this->_options['contactUs']['imgUrl'] = '';				
				if($_POST[contactUs][afterProceed]){
					if($_POST[contactUs][afterProceed][follow] == 'on'){
						$this->_options['contactUs']['afterProceed']['follow'] = 1;
						$this->_options['contactUs']['afterProceed']['thank'] = 0;
					} elseif($_POST[contactUs][afterProceed][thank] == 'on'){
						$this->_options['contactUs']['afterProceed']['follow'] = 0;
						$this->_options['contactUs']['afterProceed']['thank'] = 1;
					} else {
						$this->_options['contactUs']['afterProceed']['follow'] = 0;
						$this->_options['contactUs']['afterProceed']['thank'] = 0;
					}									
				}else{
					$this->_options['contactUs']['afterProceed']['follow'] = 0;
					$this->_options['contactUs']['afterProceed']['thank'] = 0;
				}
			}
			
			//callMe
			if($_POST[callMe]){
				if($_POST[callMe][enabled] == 'on') $this->_options['callMe']['disabled'] = 0; else $this->_options['callMe']['disabled'] = 1;
				if(trim($_POST[callMe][position])) $this->_options['callMe']['position'] = sanitize_text_field($_POST[callMe][position]); else $this->_options['callMe']['position'] = '';
				if(trim($_POST[callMe][typeWindow])) $this->_options['callMe']['typeWindow'] = sanitize_text_field($_POST[callMe][typeWindow]); else $this->_options['callMe']['typeWindow'] = '';
				if(trim($_POST[callMe][title])) $this->_options['callMe']['title'] = sanitize_text_field($_POST[callMe][title]); else $this->_options['callMe']['title'] = '';
				if(trim($_POST[callMe][sub_title])) $this->_options['callMe']['sub_title'] = sanitize_text_field($_POST[callMe][sub_title]); else $this->_options['callMe']['sub_title'] = '';				
				if(trim($_POST[callMe][buttonTitle])) $this->_options['callMe']['buttonTitle'] = sanitize_text_field($_POST[callMe][buttonTitle]); else $this->_options['callMe']['buttonTitle'] = '';
								
				if(trim($_POST[callMe][background])) $this->_options['callMe']['background'] = sanitize_text_field($_POST[callMe][background]); else $this->_options['callMe']['background'] = '';
				if(trim($_POST[callMe][loader_background])) $this->_options['callMe']['loader_background'] = sanitize_text_field($_POST[callMe][loader_background]); else $this->_options['callMe']['loader_background'] = '';
				if(trim($_POST[callMe][button_color])) $this->_options['callMe']['button_color'] = sanitize_text_field($_POST[callMe][button_color]); else $this->_options['callMe']['button_color'] = '';
				if(trim($_POST[callMe][img])) $this->_options['callMe']['img'] = sanitize_text_field($_POST[callMe][img]); else $this->_options['callMe']['img'] = '';
				if(trim($_POST[callMe][imgUrl])) $this->_options['callMe']['imgUrl'] = sanitize_text_field($_POST[callMe][imgUrl]); else $this->_options['callMe']['imgUrl'] = '';				
				if($_POST[callMe][afterProceed]){
					if($_POST[callMe][afterProceed][follow] == 'on'){
						$this->_options['callMe']['afterProceed']['follow'] = 1;
						$this->_options['callMe']['afterProceed']['thank'] = 0;
					} elseif($_POST[callMe][afterProceed][thank] == 'on'){
						$this->_options['callMe']['afterProceed']['follow'] = 0;
						$this->_options['callMe']['afterProceed']['thank'] = 1;
					} else {
						$this->_options['callMe']['afterProceed']['follow'] = 0;
						$this->_options['callMe']['afterProceed']['thank'] = 0;
					}									
				}else{
					$this->_options['callMe']['afterProceed']['follow'] = 0;
					$this->_options['callMe']['afterProceed']['thank'] = 0;
				}
			}						
						
						
			update_option('profitquery', $this->_options);
			echo '
			<div id="successPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(151, 255, 0, 0.5); text-align: center;">
					<p style="color: rgb(104, 174, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">Data changed!</p>
			</div>
			<script>
				setTimeout(function(){document.getElementById("successPQBlock").style.display="none";}, 5000);
				</script>
			';
		}
		
		//update_option('profitquery', '');
				
		
		//save api key
		if(trim($_POST[apiKey]) != '' || trim($_GET[apiKey]) != ''){
			if(trim($_POST[apiKey]) != '') $this->_options['apiKey'] = sanitize_text_field($_POST[apiKey]);
			if(trim($_GET[apiKey]) != '') $this->_options['apiKey'] = sanitize_text_field($_GET[apiKey]);
			if(!trim($this->_options['apiKey'])){
				//DEFAULT OPTIONS
				$this->_options[imageSharer][disabled] = 0;
				$this->_options[imageSharer][socnet] = array('FB'=>1, 'GP'=>1, 'TW'=>1, 'PI'=>1);				
				$this->_options[imageSharer][design][color] = 'c4';
				$this->_options[imageSharer][design][size] = 'x30';
				$this->_options[imageSharer][design][shadow] = 'sh6';
				$this->_options[imageSharer][minWidth] = 100;
				update_option('profitquery', $this->_options);
				
				$this ->setDefaultProductData();
			}															
			echo '			
				<div id="successPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(151, 255, 0, 0.5); text-align: center;">
					<p style="color: rgb(104, 174, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">API Key Was Saved!</p>
				</div>
				<script>
				setTimeout(function(){document.getElementById("successPQBlock").style.display="none";}, 5000);
				</script>
			';			
		} else {
			echo '			
				<div style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(151, 255, 0, 0.5); text-align: center;">
					<p style="color: rgb(104, 174, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;"><a href="'.$this->getSettingsPageUrl().'&action=changeApiKey">Edit Api Key</a></p>
				</div>				
			';	
		}

		//printr($this->_options);
			//die();
		
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
			  <h1 class="pq" style="font-family: pt sans narrow; font-size: 30px; color: #7A7A7A; font-weight: normal; display: inline-block; float: left; margin: 0; line-height: 40px;">Start to use AIO Widgets by Profitquery</h1>
			  <p style="font-family: arial; font-size: 16px; color: #929292; display: inline-block; float: right; margin: 0; height: 40px; padding: 10px 0 0; box-sizing: border-box;">Need help? <a style="color: #222222; text-decoration: none;" href="http://profitquery.com/aio_widgets_wordpress.html" target="_pq_image_sharer_wordpress">Check instructions <img src="'.plugins_url('images/icon.png', __FILE__).'" style="margin: 0 0 -5px;" /></a></p>
			 </div>				
				<p style="font-family: arial; font-size: 16px; color: #A9A9A9; margin: 16px 0 50px;">To start using the AIO Widgets By Profitquery, we first need your Profitquery Lite API Key.</p>
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
			if(profitquery_is_subscribe_enabled($this->_options)){
				if(trim($this->_options[subscribeProviderUrl]) == ''){
					echo '
						<div style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(242, 20, 67, 0.5); text-align: center;">
						 <p style="color: rgb(174, 0, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">For complete install Subscribe tools please set up Subscribe Form action option <a href="'.$this->getSettingsPageUrl().'#setupFormAction" style="text-decoration: none;" >Complete setup</a></p>
						</div>						
					';
				}
			}
			
			if(profitquery_is_follow_enabled_and_not_setup($this->_options)){
				echo '
						<div style="display: block;width: auto; margin: 0 15px 0 5px; background: rgba(242, 20, 67, 0.5); text-align: center;">
						 <p style="color: rgb(174, 0, 0); font-size: 16px; font-family: arial; padding: 5px; margin: 0px;">For complete install follow popup after proceed, please set up any follow address <a href="'.$this->getSettingsPageUrl().'#setupFollow" style="text-decoration: none;" >Complete setup</a></p>
						</div>						
					';
			}
			?>
			<div style="width: 100%; overflow: hidden; background: white;">
				<div class="container-fluid" id="free_profitquery">
				<script>
					var photoPath = "<?php echo plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PATH;?>";
					var previewPath = "<?php echo plugins_url().'/'.PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME.'/'.PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PATH.PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PREVIEW_PATH;?>";
					function chagnePopupImg(img, id, custom_photo_block_id){						
						try{							
							if(img == 'custom'){								
								document.getElementById(id).style.display = 'none';
								document.getElementById(custom_photo_block_id).style.display = 'block';
							}else if(img != ''){								
								document.getElementById(id).style.display = 'block';
								document.getElementById(id).src = photoPath+img;
								document.getElementById(custom_photo_block_id).style.display = 'none';
							} else {
								document.getElementById(id).style.display = 'none';
								document.getElementById(custom_photo_block_id).style.display = 'none';
							}
						}catch(err){};
					}
				  </script>
				  <div style="margin-top: 35px; background: #fffde1; padding: 45px 0;">
					<h4 class="panel-title">Share Tools</h4>											
					<div id="collapseOne" class="panel-collapse collapse in">
					<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
					<input type="hidden" name="action" value="edit">
					  <div class="panel-body">
					  <p>Get more beautiful shares, referrals, virality, without any social app.</p>
						
						
						<div class="col-sm-6">
							<img id="sharingSideBar_IMG" src="<?php echo plugins_url('images/sharing.png', __FILE__);?>" />
							<h5>Sharing Sidebar</h5>
							<div class="col-sm-10">							
								
								<label>
									<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-small bootstrap-switch-success">
										<input type="checkbox" name="sharingSideBar[enabled]" <?php if((int)$this->_options[sharingSideBar][disabled] == 0) echo 'checked';?> >
									</div>
								</label>
								
								<label>
									<select id="sharingSideBar_position" onchange="changeShareBlockImg();" name="sharingSideBar[position]">
										<option value="pq_left pq_middle" <?php if($this->_options[sharingSideBar][position] == 'pq_left pq_middle') echo 'selected';?> >Left side</option>
										<option value="pq_right pq_middle" <?php if($this->_options[sharingSideBar][position] == 'pq_right pq_middle') echo 'selected';?> >Right side</option>
									</select>
								</label>
								<script>
									function changeShareBlockImg(){
										if(document.getElementById('sharingSideBar_position').value == 'pq_left pq_middle'){
											document.getElementById('sharingSideBar_IMG').src = previewPath+'sharing_left.png';
										}
										if(document.getElementById('sharingSideBar_position').value == 'pq_right pq_middle'){
											document.getElementById('sharingSideBar_IMG').src = previewPath+'sharing_right.png';
										}
									}
									changeShareBlockImg();
								</script>
								<a href="#Sharing_Sidebar" onclick="document.getElementById('Sharing_Sidebar').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>
						</div>
						<div class="col-sm-6">
							<img id="imageSharer_IMG" src="<?php echo plugins_url('images/image_sharer.png', __FILE__);?>" />
							<h5>Image Sharer</h5>
							<div class="col-sm-10">							
								<label>
									<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-small bootstrap-switch-success">
										<input type="checkbox" name="imageSharer[enabled]" <?php if((int)$this->_options[imageSharer][disabled] == 0) echo 'checked';?>>
									</div>
								</label>
								<label>	
									<select id="imageSharer_design_position" onchange="changeImageSharerBlockImg();" name="imageSharer[position]">
										<option value="" <?php if($this->_options[imageSharer][position] == '') echo 'selected';?>>Vertically</option>
										<option value="inline" <?php if($this->_options[imageSharer][position] == 'inline') echo 'selected';?>>Horizontally</option>
									</select>
									<script>
									function changeImageSharerBlockImg(){
										if(document.getElementById('imageSharer_design_position').value == ''){
											document.getElementById('imageSharer_IMG').src = previewPath+'on_hover_vert.png';
										}
										if(document.getElementById('imageSharer_design_position').value == 'inline'){
											document.getElementById('imageSharer_IMG').src = previewPath+'on_hover_horiz.png';
										}
									}
									changeImageSharerBlockImg();
								</script>
								</label>	
									<a href="#Image_Sharer" onclick="document.getElementById('Image_Sharer').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>
						</div>
						<script>
							$("[name='sharingSideBar[enabled]']").bootstrapSwitch();
							$("[name='imageSharer[enabled]']").bootstrapSwitch();
						</script>
										
					</div>
					<div class="panel-body">
						<a name="Sharing_Sidebar"></a>
						<div class="col-sm-10 more" id="Sharing_Sidebar" style="display:none;">
							<h5>More Options Sharing Sidebar</h5>
							<div class="col-sm-10">
								
								<div class="x30" style="margin-top: 15px; overflow: hidden;  margin: 15px auto 0;">
									<label><div class="pq_fb"></div>
									<input type="checkbox" name="sharingSideBar[socnet][FB]" <?php if((int)$this->_options[sharingSideBar][socnet][FB] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_tw"></div>
									<input type="checkbox" name="sharingSideBar[socnet][TW]" <?php if((int)$this->_options[sharingSideBar][socnet][TW] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_gp"></div>
									<input type="checkbox" name="sharingSideBar[socnet][GP]" <?php if((int)$this->_options[sharingSideBar][socnet][GP] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_pi"></div>
									<input type="checkbox" name="sharingSideBar[socnet][PI]" <?php if((int)$this->_options[sharingSideBar][socnet][PI] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_tr"></div>
									<input type="checkbox" name="sharingSideBar[socnet][TR]" <?php if((int)$this->_options[sharingSideBar][socnet][TR] == 1) echo 'checked';?>></label>
									
									<label><div class="pq_li"></div>
									<input type="checkbox" name="sharingSideBar[socnet][LI]" <?php if((int)$this->_options[sharingSideBar][socnet][LI] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_vk"></div>
									<input type="checkbox" name="sharingSideBar[socnet][VK]" <?php if((int)$this->_options[sharingSideBar][socnet][VK] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_od"></div>
									<input type="checkbox" name="sharingSideBar[socnet][OD]" <?php if((int)$this->_options[sharingSideBar][socnet][OD] == 1) echo 'checked';?>></label>
									
									<label><div class="pq_mw"></div>
									<input type="checkbox" name="sharingSideBar[socnet][MW]" <?php if((int)$this->_options[sharingSideBar][socnet][MW] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_lj"></div>
									<input type="checkbox" name="sharingSideBar[socnet][LJ]" <?php if((int)$this->_options[sharingSideBar][socnet][LJ] == 1) echo 'checked';?>></label>
													
									<label><div class="pq_em"></div>
									<input type="checkbox" name="sharingSideBar[socnet][MailTo]" <?php if((int)$this->_options[sharingSideBar][socnet][MailTo] == 1) echo 'checked';?>></label>
									
									<label><div class="pq_pr"></div>
									<input type="checkbox" name="sharingSideBar[socnet][Print]" <?php if((int)$this->_options[sharingSideBar][socnet]['Print'] == 1) echo 'checked';?>></label>
								</div>
								
								<div class="col-sm-12 icons" style="padding: 0; margin: 20px 0 0;">
									<label><select id="sharingSideBar_design_color" name="sharingSideBar[design][color]">
										<option value="c4" <?php if($this->_options[sharingSideBar][design][color] == 'c4') echo 'selected';?>>Color</option>
										<option value="c1" <?php if($this->_options[sharingSideBar][design][color] == 'c1') echo 'selected';?>>Color light</option>
										<option value="c2" <?php if($this->_options[sharingSideBar][design][color] == 'c2') echo 'selected';?>>Color volume</option>
										<option value="c3" <?php if($this->_options[sharingSideBar][design][color] == 'c3') echo 'selected';?>>Color dark</option>
										<option value="c5" <?php if($this->_options[sharingSideBar][design][color] == 'c5') echo 'selected';?>>Black</option>
										<option value="c6" <?php if($this->_options[sharingSideBar][design][color] == 'c6') echo 'selected';?>>Black volume</option>
										<option value="c7" <?php if($this->_options[sharingSideBar][design][color] == 'c7') echo 'selected';?>>White volume</option>
										<option value="c8" <?php if($this->_options[sharingSideBar][design][color] == 'c8') echo 'selected';?>>White</option>
									</select></label>
								</div>
								<div class="col-sm-6 icons" style="padding-left: 0; margin: 10px 0;">
									<label><select id="sharingSideBar_design_form" name="sharingSideBar[design][form]">
										<option value="" <?php if($this->_options[sharingSideBar][design][form] == '') echo 'selected';?>>Square</option>
										<option value="circle" <?php if($this->_options[sharingSideBar][design][form] == 'circle') echo 'selected';?>>Circle</option>
										<option value="rounded" <?php if($this->_options[sharingSideBar][design][form] == 'rounded') echo 'selected';?>>Rounded</option>
									</select></label>
								</div>
								<div class="col-sm-6 icons" style="padding-right: 0; margin: 10px 0;">
									<label><select id="sharingSideBar_design_size" name="sharingSideBar[design][size]">
										<option value="x30" <?php if($this->_options[sharingSideBar][design][size] == 'x30') echo 'selected';?>>Size M</option>
										<option value="x40" <?php if($this->_options[sharingSideBar][design][size] == 'x40') echo 'selected';?>>Size L</option>
										<option value="x20" <?php if($this->_options[sharingSideBar][design][size] == 'x20') echo 'selected';?>>Size S</option>
									</select></label>
									<label>
								</div>
								
								<div style="clear: both;"></div>
								<label>
								<div class="box">
								<p>Follow Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="sharingSideBar[afterProceed][follow]" <?php if((int)$this->_options[sharingSideBar][afterProceed][follow] == 1) echo 'checked';?>></div>
								</div>
								</label>
								<label>
								<div class="box">
								<p>Thank Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="sharingSideBar[afterProceed][thank]" <?php if((int)$this->_options[sharingSideBar][afterProceed][thank] == 1) echo 'checked';?>></div>
								</div>
								</label>
								
							<script>
								$("[name='sharingSideBar[afterProceed][follow]']").bootstrapSwitch();
								$("[name='sharingSideBar[afterProceed][thank]']").bootstrapSwitch();
								function sharingSideBarPreview(){									
									var designIcons = 'pq-social-block '+document.getElementById('sharingSideBar_design_size').value+document.getElementById('sharingSideBar_design_form').value+' '+document.getElementById('sharingSideBar_design_color').value;
									var position = 'pq_icons '+document.getElementById('sharingSideBar_position').value;
									window.open("http://profitquery.com/aio_widgets_demo.html?p=sidebarShare&position="+position+'&typeBlock='+designIcons, "product_view");							
								}
							</script>
							<div class="clear"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="sharingSideBarPreview();">Preview</button>
							
							</div>
						<div class="pq_tooltip" data-toggle="tooltip" data-placement="left" title="For enable Follow Popup must be Off" style="bottom: 80px;"></div>
						<a href="javascript:void(0)" onclick="document.getElementById('Sharing_Sidebar').style.display='none';"><div class="close"></div></a>
						</div>
						<a name="Image_Sharer"></a>
						<div class="col-sm-10 more" id="Image_Sharer" style="display:none;">
							<h5>More options Image Sharer</h5>
						<div class="col-md-10">
						
						<label>
						<div style="position: relative; overflow: hidden; max-width: 403px; min-height: 200px; margin: 20px auto 10px;">
							<img src="<?php echo plugins_url('images/capture.png', __FILE__);?>" style="position: absolute; top: 0; right: 0; width: 100%;" />
							<input type="text" name="imageSharer[minWidth]" style="position: absolute; top: 86px; width: 80px; font-size: 16px; font-family: arial; color: #9A9A9A; box-sizing: border-box; text-align: center; margin-left: -40px;" value="<?php echo (int)$this->_options[imageSharer][minWidth];?>">
							<p style="position: absolute; top: 88px; font-size: 16px; font-family: arial; color: #9A9A9A; width: 50%; right: 0; padding-left: 49px;">px</p>
						</div>
						</label>
						<label>
						<div class="box">
							<p>Follow Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
							<input type="checkbox" name="imageSharer[afterProceed][follow]" <?php if((int)$this->_options[imageSharer][afterProceed][follow] == 1) echo 'checked';?>></div>
						</div>
						</label>
						<label>
						<div class="box">
							<p>Thank Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
							<input type="checkbox" name="imageSharer[afterProceed][thank]" <?php if((int)$this->_options[imageSharer][afterProceed][thank] == 1) echo 'checked';?>></div>
						</div></label>
						<script>
							$("[name='imageSharer[afterProceed][follow]']").bootstrapSwitch();
							$("[name='imageSharer[afterProceed][thank]']").bootstrapSwitch();
						</script>
						
						</div><div class="clear"></div>
							
						<a href="javascript:void(0)" onclick="document.getElementById('Image_Sharer').style.display='none';"><div class="close"></div></a>
						<div class="col-sm-12 x30" style="padding-top: 25px;">											
							<label><div class="pq_fb"></div>
							<input type="checkbox" name="imageSharer[socnet][FB]" <?php if((int)$this->_options[imageSharer][socnet][FB] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_tw"></div>
							<input type="checkbox" name="imageSharer[socnet][TW]" <?php if((int)$this->_options[imageSharer][socnet][TW] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_gp"></div>
							<input type="checkbox" name="imageSharer[socnet][GP]" <?php if((int)$this->_options[imageSharer][socnet][GP] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_pi"></div>
							<input type="checkbox" name="imageSharer[socnet][PI]" <?php if((int)$this->_options[imageSharer][socnet][PI] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_tr"></div>
							<input type="checkbox" name="imageSharer[socnet][TR]" <?php if((int)$this->_options[imageSharer][socnet][TR] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_vk"></div>
							<input type="checkbox" name="imageSharer[socnet][VK]" <?php if((int)$this->_options[imageSharer][socnet][VK] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_od"></div>
							<input type="checkbox" name="imageSharer[socnet][OD]" <?php if((int)$this->_options[imageSharer][socnet][OD] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_lj"></div>
							<input type="checkbox" name="imageSharer[socnet][LJ]" <?php if((int)$this->_options[imageSharer][socnet][LJ] == 1) echo 'checked';?>></label>
							
							<label><div class="pq_li"></div>
							<input type="checkbox" name="imageSharer[socnet][LI]" <?php if((int)$this->_options[imageSharer][socnet][LI] == 1) echo 'checked';?>></label>
						</div>
						<div class="col-md-10">
						<div class="col-md-6 icons" style="padding-left: 0; margin: 20px 0;">
							<label><select id="imageSharer_design_color" name="imageSharer[design][color]">
								<option value="c4" <?php if($this->_options[imageSharer][design][color] == 'c4') echo 'selected';?>>Color</option>
								<option value="c1" <?php if($this->_options[imageSharer][design][color] == 'c1') echo 'selected';?>>Color light</option>
								<option value="c2" <?php if($this->_options[imageSharer][design][color] == 'c2') echo 'selected';?>>Color volume</option>
								<option value="c3" <?php if($this->_options[imageSharer][design][color] == 'c3') echo 'selected';?>>Color dark</option>
								<option value="c5" <?php if($this->_options[imageSharer][design][color] == 'c5') echo 'selected';?>>Black</option>
								<option value="c6" <?php if($this->_options[imageSharer][design][color] == 'c6') echo 'selected';?>>Black volume</option>
								<option value="c7" <?php if($this->_options[imageSharer][design][color] == 'c7') echo 'selected';?>>White volume</option>
								<option value="c8" <?php if($this->_options[imageSharer][design][color] == 'c8') echo 'selected';?>>White</option>
							</select></label>
							<label><select id="imageSharer_design_form" name="imageSharer[design][form]">
								<option value="" <?php if($this->_options[imageSharer][design][form] == '') echo 'selected';?>>Square</option>
								<option value="circle" <?php if($this->_options[imageSharer][design][form] == 'circle') echo 'selected';?>>Circle</option>
								<option value="rounded" <?php if($this->_options[imageSharer][design][form] == 'rounded') echo 'selected';?>>Rounded</option>
							</select></label>
						</div>
						<div class="col-md-6 icons" style="padding-right: 0; margin: 20px 0;">
							<label><select id="imageSharer_design_size" name="imageSharer[design][size]">
								<option value="x30" <?php if($this->_options[imageSharer][design][size] == 'x30') echo 'selected';?>>Size M</option>
								<option value="x40" <?php if($this->_options[imageSharer][design][size] == 'x40') echo 'selected';?>>Size L</option>
								<option value="x20" <?php if($this->_options[imageSharer][design][size] == 'x20') echo 'selected';?>>Size S</option>
							</select></label>
							<label><select id="imageSharer_design_shadow" name="imageSharer[design][shadow]">
								<option value="sh_1" <?php if($this->_options[imageSharer][design][shadow] == 'sh1') echo 'selected';?>>Shadow1</option>
								<option value="sh_2" <?php if($this->_options[imageSharer][design][shadow] == 'sh2') echo 'selected';?>>Shadow2</option>
								<option value="sh_3" <?php if($this->_options[imageSharer][design][shadow] == 'sh3') echo 'selected';?>>Shadow3</option>
								<option value="sh_4" <?php if($this->_options[imageSharer][design][shadow] == 'sh4') echo 'selected';?>>Shadow4</option>
								<option value="sh_5" <?php if($this->_options[imageSharer][design][shadow] == 'sh5') echo 'selected';?>>Shadow5</option>
								<option value="sh_6" <?php if($this->_options[imageSharer][design][shadow] == 'sh6') echo 'selected';?>>Shadow6</option>
							</select></label>
						</div>																									
						
						<button type="button" onclick="imageSharerPreview();" class="btn btn-link btn-lg btn-m">Preview</button>
						
						<script>								
								function imageSharerPreview(){									
									var design = document.getElementById('imageSharer_design_size').value+document.getElementById('imageSharer_design_form').value+' '+document.getElementById('imageSharer_design_color').value+' '+document.getElementById('imageSharer_design_shadow').value+' '+document.getElementById('imageSharer_design_position').value;									
									window.open("http://profitquery.com/aio_widgets_demo.html?p=imageSharer&design="+design, "product_view");							
								}
						</script>
						
						</div>
						<div class="pq_tooltip" data-toggle="tooltip" data-placement="left" title="For enable Follow Popup must be Off" style="bottom: 297px;"></div>
						</div>												
						
					</div>
					<input type="submit" class="btn btn_m_red" value="Save changes">
					</form> 
					</div>
				  </div>
				  <a name="EmailBlock"></a>
					<div style="margin-top: 35px; background: #f4ffe4; padding: 45px 0;">
					
						<h4 class="panel-title">Subscribe Tools</h4>
						
					<div id="collapseTwo" class="panel-collapse collapse in">
					<form action="<?php echo $this->getSettingsPageUrl();?>#EmailBlock" method="post">
					<input type="hidden" name="action" value="edit">
					  <div class="panel-body">
					  <p>Get more subscribers, simply mailchimp integration.</p>						
						
						<div class="col-sm-6">
							<img id="subscribeBar_IMG" src="<?php echo plugins_url('images/bar.png', __FILE__);?>" />
							<h5>Marketing Bar</h5>
							<div class="col-sm-10">							
							
							<label>
								<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-small bootstrap-switch-success">
									<input type="checkbox" name="subscribeBar[enabled]" <?php if((int)$this->_options[subscribeBar][disabled] == 0) echo 'checked';?>>
								</div>
							</label>
							
							<label>
							<select id="subscribeBar_position" onchange="changeSubscribeBarBlockImg();" name="subscribeBar[position]">
								<option value="pq_top"  <?php if($this->_options[subscribeBar][position] == 'pq_top' || $this->_options[subscribeBar][position] == '') echo 'selected';?>>Top</option>
								<option value="pq_bottom" <?php if($this->_options[subscribeBar][position] == 'pq_bottom') echo 'selected';?>>Bottom</option>
							</select>
							<script>
								function changeSubscribeBarBlockImg(){
									if(document.getElementById('subscribeBar_position').value == 'pq_top'){
										document.getElementById('subscribeBar_IMG').src = previewPath+'bar_top.png';
									}
									if(document.getElementById('subscribeBar_position').value == 'pq_bottom'){
										document.getElementById('subscribeBar_IMG').src = previewPath+'bar_bottom.png';
									}
								}
								changeSubscribeBarBlockImg();
							</script>
							</label>
							<a href="#Marketing_Bar" onclick="document.getElementById('Marketing_Bar').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>
						</div>
						<div class="col-sm-6">
							<img id="subscribeExit_IMG" src="<?php echo plugins_url('images/subscribe.png', __FILE__);?>" />
							<h5>Exit Popup</h5>
							<div class="col-sm-10">							
							<label>
								<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-small bootstrap-switch-success">
									<input type="checkbox" name="subscribeExit[enabled]" <?php if((int)$this->_options[subscribeExit][disabled] == 0) echo 'checked';?>>
								</div>
							</label>
							
							<label>
							<select id="subscribeExit_typeWindow" onchange="changeSubscribeExitBlockImg();" name="subscribeExit[typeWindow]">
								<option value="pq_large" <?php if($this->_options[subscribeExit][typeWindow] == 'pq_large' || $this->_options[subscribeExit][typeWindow] == '') echo 'selected';?>>Size L</option>
								<option value="pq_medium" <?php if($this->_options[subscribeExit][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
								<option value="pq_mini" <?php if($this->_options[subscribeExit][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
							</select>
							<script>
								function changeSubscribeExitBlockImg(){
									if(document.getElementById('subscribeExit_typeWindow').value == 'pq_large'){
										document.getElementById('subscribeExit_IMG').src = previewPath+'mail_l.png';
									}
									if(document.getElementById('subscribeExit_typeWindow').value == 'pq_medium'){
										document.getElementById('subscribeExit_IMG').src = previewPath+'mail_m.png';
									}
									if(document.getElementById('subscribeExit_typeWindow').value == 'pq_mini'){
										document.getElementById('subscribeExit_IMG').src = previewPath+'mail_s.png';
									}
								}
								changeSubscribeBarBlockImg();
							</script>
							</label>
							<a href="#Exit_Popup" onclick="document.getElementById('Exit_Popup').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>							
						</div>
						<script>
							$("[name='subscribeExit[enabled]']").bootstrapSwitch();
							$("[name='subscribeBar[enabled]']").bootstrapSwitch();
						</script>												
						</div>					
					<div class="panel-body">
						<a name="Marketing_Bar"></a><div class="col-sm-10 more" id="Marketing_Bar" style="display:none;">
							<h5>More options Marketing Bar</h5>
							<div class="col-sm-10" style="width: 83.333333%;">
							
							<label style="display: block;"><p>Heading</p><input name="subscribeBar[title]" value="<?php echo stripslashes($this->_options[subscribeBar][title]);?>"></label>	
							<label style="display: block;"><p>Input email text</p><input name="subscribeBar[inputEmailTitle]" value="<?php echo stripslashes($this->_options[subscribeBar][inputEmailTitle]);?>"></label>
							<label style="display: block;"><p>Button</p><input name="subscribeBar[buttonTitle]" value="<?php echo stripslashes($this->_options[subscribeBar][buttonTitle]);?>"></label>
							
							<div class="col-sm-6" style="padding-left: 0; margin: 10px 0;">
							<label><select id="subscribeBar_background" name="subscribeBar[background]">
								    <option value="bg_grey" <?php if($this->_options[subscribeBar][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="" <?php if($this->_options[subscribeBar][background] == '') echo 'selected';?>>Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[subscribeBar][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[subscribeBar][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[subscribeBar][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[subscribeBar][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[subscribeBar][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[subscribeBar][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[subscribeBar][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[subscribeBar][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[subscribeBar][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[subscribeBar][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
							</select></label>
							</div>
							<div class="col-sm-6" style="padding-right: 0; margin: 10px 0;">
							<label><select id="subscribeBar_button_color" name="subscribeBar[button_color]">
								    <option value="btn_lightblue" <?php if($this->_options[subscribeBar][button_color] == 'btn_lightblue') echo 'selected';?>>Button - Lightblue</option>
									<option value="btn_lightblue invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_lightblue invert' || $this->_options[subscribeBar][button_color] == '') echo 'selected';?>>Button - Lightblue Transparent</option>
									<option value="btn_blue" <?php if($this->_options[subscribeBar][button_color] == 'btn_blue') echo 'selected';?>>Button - Blue</option>
									<option value="btn_blue invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_blue invert') echo 'selected';?>>Button - Blue Transparent</option>
									<option value="btn_black" <?php if($this->_options[subscribeBar][button_color] == 'btn_black') echo 'selected';?>>Button - Black</option>
									<option value="btn_black invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_black invert') echo 'selected';?>>Button - Black Transparent</option>
									<option value="btn_green" <?php if($this->_options[subscribeBar][button_color] == 'btn_green') echo 'selected';?>>Button - Green</option>
									<option value="btn_green invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_green invert') echo 'selected';?>>Button - Green Transparent</option>
									<option value="btn_violet" <?php if($this->_options[subscribeBar][button_color] == 'btn_violet') echo 'selected';?>>Button - Violet</option>
									<option value="btn_violet invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_violet invert') echo 'selected';?>>Button - Violet Transparent</option>
									<option value="btn_orange" <?php if($this->_options[subscribeBar][button_color] == 'btn_orange') echo 'selected';?>>Button - Orange</option>
									<option value="btn_orange invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_orange invert') echo 'selected';?>>Button - Orange Transparent</option>
									<option value="btn_red" <?php if($this->_options[subscribeBar][button_color] == 'btn_red') echo 'selected';?>>Button - Red</option>
									<option value="btn_red invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_red invert') echo 'selected';?>>Button - Red Transparent</option>
									<option value="btn_lilac" <?php if($this->_options[subscribeBar][button_color] == 'btn_lilac') echo 'selected';?>>Button - Lilac</option>
									<option value="btn_lilac invert" <?php if($this->_options[subscribeBar][button_color] == 'btn_lilac invert') echo 'selected';?>>Button - Lilac Transparent</option>
							</select></label>
							</div>
							<div class="clear"></div>
							<label>
							<select id="subscribeBar_size" name="subscribeBar[size]">
								<option value="" <?php if($this->_options[subscribeBar][size] == '') echo 'selected';?>>Size M</option>
								<option value="pq_mini" <?php if($this->_options[subscribeBar][size] == 'pq_mini') echo 'selected';?>>Size S</option>
							</select>
							</label>
							<label>
							<div class="box">
								<p>Follow Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeBar[afterProceed][follow]" <?php if((int)$this->_options[subscribeBar][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div>
							</label>
							<label>
							<div class="box">
								<p>Thank Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeBar[afterProceed][thank]" <?php if((int)$this->_options[subscribeBar][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div>
							</label>
							<script>
								$("[name='subscribeBar[afterProceed][follow]']").bootstrapSwitch();
								$("[name='subscribeBar[afterProceed][thank]']").bootstrapSwitch();
							</script>
							<div class="clear"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="subscribeBarPreview();">Preview</button>
							
							<script>								
								function subscribeBarPreview(){									
									var design = document.getElementById('subscribeBar_size').value+' '+document.getElementById('subscribeBar_position').value+' '+document.getElementById('subscribeBar_background').value+' '+document.getElementById('imageSharer_design_color').value+' '+document.getElementById('subscribeBar_button_color').value;
									window.open("http://profitquery.com/aio_widgets_demo.html?p=subscribeBar&design="+design, "product_view");							
								}
							</script>
							
							</div>
							<div class="pq_tooltip" data-toggle="tooltip" data-placement="left" title="For enable Follow Popup must be Off" style="bottom: 80px;"></div>
						<a href="javascript:void(0)" onclick="document.getElementById('Marketing_Bar').style.display='none';"><div class="close"></div></a>
						</div>
						<a name="Exit_Popup"></a><div class="col-sm-10 more" id="Exit_Popup" style="display:none;">
							<h5>More options Exit Popup</h5>
							<div class="col-sm-10" style="width: 83.333333%;">
							
							<label style="display: block;"><p>Heading</p><input name="subscribeExit[title]" value="<?php echo stripslashes($this->_options[subscribeExit][title]);?>"></label>
							<label style="display: block;"><p>Text</p><input name="subscribeExit[sub_title]" value="<?php echo stripslashes($this->_options[subscribeExit][sub_title]);?>"></label>
							<label style="display: block;"><p>Button</p><input name="subscribeExit[buttonTitle]" value="<?php echo stripslashes($this->_options[subscribeExit][buttonTitle]);?>"></label>
							<label style="display: block;"><p>Input email text</p><input name="subscribeExit[inputEmailTitle]" value="<?php echo stripslashes($this->_options[subscribeExit][inputEmailTitle]);?>"></label>
							
							<div class="col-sm-6" style="padding-left: 0; margin: 10px 0;">
							<label>
							<select id="subscribeExit_background" name="subscribeExit[background]">
								    <option value="bg_grey" <?php if($this->_options[subscribeExit][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="" <?php if($this->_options[subscribeExit][background] == '') echo 'selected';?>>Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[subscribeExit][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[subscribeExit][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[subscribeExit][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[subscribeExit][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[subscribeExit][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[subscribeExit][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[subscribeExit][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[subscribeExit][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[subscribeExit][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[subscribeExit][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
							</select></label>
							</div>
							<div class="col-sm-6" style="padding-right: 0; margin: 10px 0;">
							<label>
							<select id="subscribeExit_button_color" name="subscribeExit[button_color]">
								    <option value="btn_lightblue" <?php if($this->_options[subscribeExit][button_color] == 'btn_lightblue') echo 'selected';?>>Button - Lightblue</option>
									<option value="btn_lightblue invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_lightblue invert' || $this->_options[subscribeExit][button_color] == '') echo 'selected';?>>Button - Lightblue Transparent</option>
									<option value="btn_blue" <?php if($this->_options[subscribeExit][button_color] == 'btn_blue') echo 'selected';?>>Button - Blue</option>
									<option value="btn_blue invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_blue invert') echo 'selected';?>>Button - Blue Transparent</option>
									<option value="btn_black" <?php if($this->_options[subscribeExit][button_color] == 'btn_black') echo 'selected';?>>Button - Black</option>
									<option value="btn_black invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_black invert') echo 'selected';?>>Button - Black Transparent</option>
									<option value="btn_green" <?php if($this->_options[subscribeExit][button_color] == 'btn_green') echo 'selected';?>>Button - Green</option>
									<option value="btn_green invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_green invert') echo 'selected';?>>Button - Green Transparent</option>
									<option value="btn_violet" <?php if($this->_options[subscribeExit][button_color] == 'btn_violet') echo 'selected';?>>Button - Violet</option>
									<option value="btn_violet invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_violet invert') echo 'selected';?>>Button - Violet Transparent</option>
									<option value="btn_orange" <?php if($this->_options[subscribeExit][button_color] == 'btn_orange') echo 'selected';?>>Button - Orange</option>
									<option value="btn_orange invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_orange invert') echo 'selected';?>>Button - Orange Transparent</option>
									<option value="btn_red" <?php if($this->_options[subscribeExit][button_color] == 'btn_red') echo 'selected';?>>Button - Red</option>
									<option value="btn_red invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_red invert') echo 'selected';?>>Button - Red Transparent</option>
									<option value="btn_lilac" <?php if($this->_options[subscribeExit][button_color] == 'btn_lilac') echo 'selected';?>>Button - Lilac</option>
									<option value="btn_lilac invert" <?php if($this->_options[subscribeExit][button_color] == 'btn_lilac invert') echo 'selected';?>>Button - Lilac Transparent</option>
							</select></label>
							</div>
							<div class="clear"></div>
							<label>
							<select id="subscribeExit_img" name="subscribeExit[img]" onchange="chagnePopupImg(this.value, 'subscribeExitFotoBlock', 'subscribeExitCustomFotoBlock');">
								<option value="" selected >No picture</option>
								<option value="img_01.png" <?php if($this->_options[subscribeExit][img] == 'img_01.png') echo 'selected';?>>Question</option>
								<option value="img_02.png" <?php if($this->_options[subscribeExit][img] == 'img_02.png') echo 'selected';?>>Attention</option>
								<option value="img_03.png" <?php if($this->_options[subscribeExit][img] == 'img_03.png') echo 'selected';?>>Info</option>
								<option value="img_04.png" <?php if($this->_options[subscribeExit][img] == 'img_04.png') echo 'selected';?>>Knowledge</option>
								<option value="img_05.png" <?php if($this->_options[subscribeExit][img] == 'img_05.png') echo 'selected';?>>Idea</option>
								<option value="img_06.png" <?php if($this->_options[subscribeExit][img] == 'img_06.png') echo 'selected';?>>Talk</option>
								<option value="img_07.png" <?php if($this->_options[subscribeExit][img] == 'img_07.png') echo 'selected';?>>News</option>
								<option value="img_08.png" <?php if($this->_options[subscribeExit][img] == 'img_08.png') echo 'selected';?>>Megaphone</option>
								<option value="img_09.png" <?php if($this->_options[subscribeExit][img] == 'img_09.png') echo 'selected';?>>Gift</option>
								<option value="img_10.png" <?php if($this->_options[subscribeExit][img] == 'img_10.png') echo 'selected';?>>Success</option>
								<option value="custom" <?php if($this->_options[subscribeExit][img] == 'custom') echo 'selected';?>>Your custom image ...</option>
							</select></label>
							<label><div class="img"><img id="subscribeExitFotoBlock" />
							<input name="subscribeExit[imgUrl]"  style="display:none;" id="subscribeExitCustomFotoBlock" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[subscribeExit][imgUrl]);?>">
							</div></label>							
							<label><div class="box">
								<p>Follow Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeExit[afterProceed][follow]" <?php if((int)$this->_options[subscribeExit][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>Thank Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeExit[afterProceed][thank]" <?php if((int)$this->_options[subscribeExit][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<?php
								echo "
								<script>
									chagnePopupImg('".$this->_options[subscribeExit][img]."', 'subscribeExitFotoBlock', 'subscribeExitCustomFotoBlock');
								</script>
								";
							?>
							<script>								
								$("[name='subscribeExit[afterProceed][follow]']").bootstrapSwitch();
								$("[name='subscribeExit[afterProceed][thank]']").bootstrapSwitch();
								
								function subscribeExitPreview(){									
									var design = document.getElementById('subscribeExit_typeWindow').value+' '+document.getElementById('subscribeExit_background').value+' '+document.getElementById('subscribeExit_button_color').value;
									var img = document.getElementById('subscribeExit_img').value;
									var imgUrl = document.getElementById('subscribeExitCustomFotoBlock').value;
									window.open("http://profitquery.com/aio_widgets_demo.html?p=subscribeExit&design="+design+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl), "product_view");							
								}							
							</script>
							<div class="clear"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="subscribeExitPreview();">Preview</button>
							</div>
						<div class="pq_tooltip" data-toggle="tooltip" data-placement="left" title="For enable Follow Popup must be Off" style="bottom: 80px;"></div>
						<a href="javascript:void(0)" onclick="document.getElementById('Exit_Popup').style.display='none';"><div class="close"></div></a>
						</div>
					</div>
						<div class="col-sm-10" style="overflow: hidden; padding: 0; margin: 0 auto 10px;">
						
						<div class="panel-body" style="background: #F3F3F3; padding: 20px 0 5px; margin: 0 15px;">
							<div class="col-sm-12">
								<div class="col-sm-6 col-sm-offset-3" style="margin-top: 0; margin-bottom: 0;">
									<label><select style="width: 98%; box-sizing: border-box; padding: 4px; margin: 10px 0;">
										<option value="mailchimp">MailChimp</option>										
									</select></label>
								</div>
							</div>
						</div>
						<div class="panel-body" style="background: #F3F3F3; padding: 0; margin: 0 15px;">
							<div class="col-sm-12">
								<a data-toggle="collapse" href="#step1"><div class="col-xs-4">
									<img src="<?php echo plugins_url('images/1.png', __FILE__);?>" />
									<p style="color: rgb(162, 162, 162); padding: 12px 0 3px;">Click for the list and choose ‘Signup Forms’</p>
									
								</div></a>
								<a data-toggle="collapse" href="#step2"><div class="col-xs-4">
									<img src="<?php echo plugins_url('images/2.png', __FILE__);?>" />
									<p style="color: rgb(162, 162, 162); padding: 12px 0 3px;">Click the Embedded forms option</p>
									
								</div></a>
								<a data-toggle="collapse" href="#step3"><div class="col-xs-4">
									<img src="<?php echo plugins_url('images/3.png', __FILE__);?>" />
									<p style="color: rgb(162, 162, 162); padding: 12px 0 3px;">Paste the Copy/Paste code  Form Action="... ”</p>
									
								</div></a>
							</div>
						</div>						
						<a name="setupFormAction"></a>
						<div class="panel-body" style="  background: #F3F3F3; padding: 0 0 25px; margin: 0 15px;">							
							<div class="col-sm-12 panel-collapse collapse" id="step1">
								<div><img src="<?php echo plugins_url('images/mailchimp_1.png', __FILE__);?>" /><a data-toggle="collapse" href="#step1"><div class="close"></div></a></div>
							
							</div>
							<div class="col-sm-12 panel-collapse collapse" id="step2">
								<div><img src="<?php echo plugins_url('images/mailchimp_2.png', __FILE__);?>" /><a data-toggle="collapse" href="#step2"><div class="close"></div></a></div>
							</div>							
							<div class="col-sm-12 panel-collapse collapse" id="step3">
								<div><img src="<?php echo plugins_url('images/mailchimp_3.png', __FILE__);?>" /><a data-toggle="collapse" href="#step3"><div class="close"></div></a></div>
							</div>
							
							<div class="col-sm-12" style="margin-top: 25px;">
								<div class="col-sm-3" style="padding: 4px 0 0;">
									<p>Form Action=</p>
								</div>
								<div class="col-sm-9" style="margin: 0 0 10px;">
									<label style="display: block;"><input name="subscribeProviderUrl" value="<?php echo stripslashes($this->_options[subscribeProviderUrl]);?>"></label>
								</div>
								
							</div>							
						</div>
						</div><input type="submit" class="btn btn_m_red" value="Save changes">
					  </form>	
					</div>
				  </div>
				  <a name="ContactBlock"></a>
				  <div style="margin-top: 35px; background: #e4fff9; padding: 45px 0;">					
						<h4 class="panel-title">Contact Form's</h4>
						
					<div id="collapseFour" class="panel-collapse collapse in">
					<form action="<?php echo $this->getSettingsPageUrl();?>#ContactBlock" method="post">
					<input type="hidden" name="action" value="edit">
					  <div class="panel-body">
					   <p>Get more contact information, visitors feedback.</p>
												
						<div class="col-sm-6">
							<img id="contactUs_IMG" src="<?php echo plugins_url('images/contact_us.png', __FILE__);?>" />
							<h5>Contact Form</h5>
							<div class="col-sm-10">							
							
							<label>
								<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-small bootstrap-switch-success">
									<input type="checkbox" name="contactUs[enabled]" <?php if((int)$this->_options[contactUs][disabled] == 0) echo 'checked';?>>
								</div>
							</label>
							
							<label>
							<select id="contactUs_typeWindow" onchange="changeContactUsBlockImg();" name="contactUs[typeWindow]">
								<option value="pq_large" <?php if($this->_options[contactUs][typeWindow] == 'pq_large' || $this->_options[contactUs][typeWindow] == '') echo 'selected';?>>Size L</option>
								<option value="pq_medium" <?php if($this->_options[contactUs][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
								<option value="pq_mini" <?php if($this->_options[contactUs][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
							</select>
							<script>
								function changeContactUsBlockImg(){
									if(document.getElementById('contactUs_typeWindow').value == 'pq_large'){
										document.getElementById('contactUs_IMG').src = previewPath+'contact_l.png';
									}
									if(document.getElementById('contactUs_typeWindow').value == 'pq_medium'){
										document.getElementById('contactUs_IMG').src = previewPath+'contact_m.png';
									}
									if(document.getElementById('contactUs_typeWindow').value == 'pq_mini'){
										document.getElementById('contactUs_IMG').src = previewPath+'contact_s.png';
									}
								}
								changeContactUsBlockImg();
							</script>
							</label>
							<a href="#Contact_Form" onclick="document.getElementById('Contact_Form').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>
						</div>
						<div class="col-sm-6">
							<img id="callMe_IMG" src="<?php echo plugins_url('images/call_me_back.png', __FILE__);?>" />
							<div class="clear"></div>
							<div class="col-sm-10">
							<h5>Call Me Back</h5>							
							
							<label>
								<div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-small bootstrap-switch-success">
									<input type="checkbox" name="callMe[enabled]" <?php if((int)$this->_options[callMe][disabled] == 0) echo 'checked';?>>
								</div>
							</label>
							
							<label>
							<select id="callMe_typeWindow" onchange="changeCallMeBlockImg();" name="callMe[typeWindow]">
								<option value="pq_large" <?php if($this->_options[callMe][typeWindow] == 'pq_large' || $this->_options[callMe][typeWindow] == '') echo 'selected';?>>Size L</option>
								<option value="pq_medium" <?php if($this->_options[callMe][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
								<option value="pq_mini" <?php if($this->_options[callMe][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
							</select>
							<script>
								function changeCallMeBlockImg(){
									if(document.getElementById('callMe_typeWindow').value == 'pq_large'){
										document.getElementById('callMe_IMG').src = previewPath+'call_l.png';
									}
									if(document.getElementById('callMe_typeWindow').value == 'pq_medium'){
										document.getElementById('callMe_IMG').src = previewPath+'call_m.png';
									}
									if(document.getElementById('callMe_typeWindow').value == 'pq_mini'){
										document.getElementById('callMe_IMG').src = previewPath+'call_s.png';
									}
								}
								changeCallMeBlockImg();
							</script>
							</label>
							<a href="#Call_Me_Back" onclick="document.getElementById('Call_Me_Back').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>
							
						</div>
						<script>
								$("[name='callMe[enabled]']").bootstrapSwitch();
								$("[name='contactUs[enabled]']").bootstrapSwitch();
						</script>
					</div>
					<div class="clear"></div>
					
					<div class="panel-body">
						<a name="Contact_Form"></a><div class="col-sm-10 more" id="Contact_Form" style="display:none">
							<h5>More options Contact Form</h5>
							<div class="col-sm-10" style="width: 83.333333%;">
							
							<label style="display: block;"><p>Heading</p><input name="contactUs[title]" value="<?php echo stripslashes($this->_options[contactUs][title]);?>"></label>
							<label style="display: block;"><p>Text</p><input name="contactUs[sub_title]" value="<?php echo stripslashes($this->_options[contactUs][sub_title]);?>"></label>							
							<label style="display: block;"><p>Button Title</p><input name="contactUs[buttonTitle]" value="<?php echo stripslashes($this->_options[contactUs][buttonTitle]);?>"></label>							
							
							<label style="padding-left:0; margin: 20px 0;">
							<select id="contactUs_position" name="contactUs[position]">
							    <option value="pq_right pq_bottom" <?php if($this->_options[contactUs][position] == 'pq_right pq_bottom' || $this->_options[contactUs][position] == '') echo 'selected';?>>Loader - Right/Bottom</option>
								<option value="pq_right pq_top" <?php if($this->_options[contactUs][position] == 'pq_right pq_top') echo 'selected';?>>Loader - Right/Top</option>		
								<option value="pq_left pq_bottom" <?php if($this->_options[contactUs][position] == 'pq_left pq_bottom') echo 'selected';?>>Loader - Left/Bottom</option>
								<option value="pq_left pq_top" <?php if($this->_options[contactUs][position] == 'pq_left pq_top') echo 'selected';?>>Loader - Left/Top</option>
							</select>
							</label>
							<label><select id="contactUs_loader_background" name="contactUs[loader_background]">
								    <option value="bg_grey" <?php if($this->_options[contactUs][loader_background] == 'bg_grey') echo 'selected';?>>Loader - Background - Grey</option>
									<option value="" <?php if($this->_options[contactUs][loader_background] == '') echo 'selected';?>>Loader - Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[contactUs][loader_background] == 'bg_yellow') echo 'selected';?>>Loader - Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[contactUs][loader_background] == 'bg_wormwood') echo 'selected';?>>Loader - Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[contactUs][loader_background] == 'bg_blue') echo 'selected';?>>Loader - Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[contactUs][loader_background] == 'bg_green') echo 'selected';?>>Loader - Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[contactUs][loader_background] == 'bg_beige') echo 'selected';?>>Loader - Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[contactUs][loader_background] == 'bg_red') echo 'selected';?>>Loader - Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[contactUs][loader_background] == 'bg_iceblue') echo 'selected';?>>Loader - Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[contactUs][loader_background] == 'bg_black') echo 'selected';?>>Loader - Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[contactUs][loader_background] == 'bg_skyblue') echo 'selected';?>>Loader - Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[contactUs][loader_background] == 'bg_lilac') echo 'selected';?>>Loader - Background - Lilac</option>
							</select></label>
							<div class="clear"></div>	
							<div class="col-sm-6" style="padding-left:0; margin: 10px 0 0;">                          						
							<label><select id="contactUs_background" name="contactUs[background]">
								    <option value="bg_grey" <?php if($this->_options[contactUs][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="" <?php if($this->_options[contactUs][background] == '') echo 'selected';?>>Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[contactUs][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[contactUs][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[contactUs][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[contactUs][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[contactUs][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[contactUs][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[contactUs][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[contactUs][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[contactUs][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[contactUs][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
							</select><label>
							</div>
							<div class="col-sm-6" style="padding-right:0; margin: 10px 0 0;">
							<label><select id="contactUs_button_color" name="contactUs[button_color]">
								    <option value="btn_lightblue" <?php if($this->_options[contactUs][button_color] == 'btn_lightblue') echo 'selected';?>>Button - Lightblue</option>
									<option value="btn_lightblue invert" <?php if($this->_options[contactUs][button_color] == 'btn_lightblue invert' || $this->_options[contactUs][button_color] == '') echo 'selected';?>>Button - Lightblue Transparent</option>
									<option value="btn_blue" <?php if($this->_options[contactUs][button_color] == 'btn_blue') echo 'selected';?>>Button - Blue</option>
									<option value="btn_blue invert" <?php if($this->_options[contactUs][button_color] == 'btn_blue invert') echo 'selected';?>>Button - Blue Transparent</option>
									<option value="btn_black" <?php if($this->_options[contactUs][button_color] == 'btn_black') echo 'selected';?>>Button - Black</option>
									<option value="btn_black invert" <?php if($this->_options[contactUs][button_color] == 'btn_black invert') echo 'selected';?>>Button - Black Transparent</option>
									<option value="btn_green" <?php if($this->_options[contactUs][button_color] == 'btn_green') echo 'selected';?>>Button - Green</option>
									<option value="btn_green invert" <?php if($this->_options[contactUs][button_color] == 'btn_green invert') echo 'selected';?>>Button - Green Transparent</option>
									<option value="btn_violet" <?php if($this->_options[contactUs][button_color] == 'btn_violet') echo 'selected';?>>Button - Violet</option>
									<option value="btn_violet invert" <?php if($this->_options[contactUs][button_color] == 'btn_violet invert') echo 'selected';?>>Button - Violet Transparent</option>
									<option value="btn_orange" <?php if($this->_options[contactUs][button_color] == 'btn_orange') echo 'selected';?>>Button - Orange</option>
									<option value="btn_orange invert" <?php if($this->_options[contactUs][button_color] == 'btn_orange invert') echo 'selected';?>>Button - Orange Transparent</option>
									<option value="btn_red" <?php if($this->_options[contactUs][button_color] == 'btn_red') echo 'selected';?>>Button - Red</option>
									<option value="btn_red invert" <?php if($this->_options[contactUs][button_color] == 'btn_red invert') echo 'selected';?>>Button - Red Transparent</option>
									<option value="btn_lilac" <?php if($this->_options[contactUs][button_color] == 'btn_lilac') echo 'selected';?>>Button - Lilac</option>
									<option value="btn_lilac invert" <?php if($this->_options[contactUs][button_color] == 'btn_lilac invert') echo 'selected';?>>Button - Lilac Transparent</option>
							</select><label>
							</div>
							<div class="clear"></div>
							<label><select id="contactUs_img" name="contactUs[img]" onchange="chagnePopupImg(this.value, 'contactUsFotoBlock', 'contactUsCustomFotoBlock');">
								<option value="" selected >No picture</option>
								<option value="img_01.png" <?php if($this->_options[contactUs][img] == 'img_01.png') echo 'selected';?>>Question</option>
								<option value="img_02.png" <?php if($this->_options[contactUs][img] == 'img_02.png') echo 'selected';?>>Attention</option>
								<option value="img_03.png" <?php if($this->_options[contactUs][img] == 'img_03.png') echo 'selected';?>>Info</option>
								<option value="img_04.png" <?php if($this->_options[contactUs][img] == 'img_04.png') echo 'selected';?>>Knowledge</option>
								<option value="img_05.png" <?php if($this->_options[contactUs][img] == 'img_05.png') echo 'selected';?>>Idea</option>
								<option value="img_06.png" <?php if($this->_options[contactUs][img] == 'img_06.png') echo 'selected';?>>Talk</option>
								<option value="img_07.png" <?php if($this->_options[contactUs][img] == 'img_07.png') echo 'selected';?>>News</option>
								<option value="img_08.png" <?php if($this->_options[contactUs][img] == 'img_08.png') echo 'selected';?>>Megaphone</option>
								<option value="img_09.png" <?php if($this->_options[contactUs][img] == 'img_09.png') echo 'selected';?>>Gift</option>
								<option value="img_10.png" <?php if($this->_options[contactUs][img] == 'img_10.png') echo 'selected';?>>Success</option>
								<option value="custom" <?php if($this->_options[contactUs][img] == 'custom') echo 'selected';?>>Your custom image ...</option>
							</select></label>
							<label><div class="img"><img id="contactUsFotoBlock" src="" />
							<input name="contactUs[imgUrl]" style="display:none;" id="contactUsCustomFotoBlock" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[contactUs][imgUrl]);?>">
							</div></label>
							<label><div class="box">
								<p>Follow Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="contactUs[afterProceed][follow]" <?php if((int)$this->_options[contactUs][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>Thank Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="contactUs[afterProceed][thank]" <?php if((int)$this->_options[contactUs][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<?php
								echo "
								<script>
									chagnePopupImg('".$this->_options[contactUs][img]."', 'contactUsFotoBlock', 'contactUsCustomFotoBlock');
								</script>
								";
							?>
							<script>
								$("[name='contactUs[afterProceed][follow]']").bootstrapSwitch();
								$("[name='contactUs[afterProceed][thank]']").bootstrapSwitch();
								
								function contactUsPreview(){									
									var design = document.getElementById('contactUs_typeWindow').value+' '+document.getElementById('contactUs_background').value+' '+document.getElementById('contactUs_button_color').value;
									var img = document.getElementById('contactUs_img').value;
									var imgUrl = document.getElementById('contactUsCustomFotoBlock').value;
									var loaderBackground = document.getElementById('contactUs_loader_background').value;
									var position = document.getElementById('contactUs_position').value;
									window.open("http://profitquery.com/aio_widgets_demo.html?p=contactUs&design="+design+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl)+'&loaderDesign='+loaderBackground+'&position='+position, "product_view");							
								}
							</script>
							
							<div class="clear"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="contactUsPreview()">Preview</button>
							
							
							</div>
							<div class="pq_tooltip" data-toggle="tooltip" data-placement="left" title="For enable Follow Popup must be Off" style="bottom: 80px;"></div>
						<a href="javascript:void(0)" onclick="document.getElementById('Contact_Form').style.display='none';"><div class="close"></div></a>
						</div>
						<a name="Call_Me_Back"></a><div class="col-sm-10 more" id="Call_Me_Back" style="display:none;">
							<h5>More options Call Me Back</h5>
							<div class="col-sm-10" style="width: 83.333333%;">
							
							<label style="display: block;"><p>Heading</p><input name="callMe[title]" value="<?php echo stripslashes($this->_options[callMe][title])?>"></label>
							<label style="display: block;"><p>Text</p><input name="callMe[sub_title]" value="<?php echo stripslashes($this->_options[callMe][sub_title])?>"></label>
							<label style="display: block;"><p>Button</p><input name="callMe[buttonTitle]" value="<?php echo stripslashes($this->_options[callMe][buttonTitle])?>"></label>
						    
                            <label style="padding-left:0; margin: 20px 0;">
							<select id="callMe_position" name="callMe[position]">
							    <option value="pq_right pq_bottom" <?php if($this->_options[callMe][position] == 'pq_right pq_bottom' ) echo 'selected';?>>Loader - Right/Bottom</option>
								<option value="pq_right pq_top" <?php if($this->_options[callMe][position] == 'pq_right pq_top') echo 'selected';?>>Loader - Right/Top</option>		
								<option value="pq_left pq_bottom" <?php if($this->_options[callMe][position] == 'pq_left pq_bottom' || $this->_options[callMe][position] == '') echo 'selected';?>>Loader - Left/Bottom</option>
								<option value="pq_left pq_top" <?php if($this->_options[callMe][position] == 'pq_left pq_top') echo 'selected';?>>Loader - Left/Top</option>
							</select>
							</label>
							<label><select id="callMe_loader_background" name="callMe[loader_background]">
								    <option value="bg_grey" <?php if($this->_options[callMe][loader_background] == 'bg_grey') echo 'selected';?>>Loader - Background - Grey</option>
									<option value="" <?php if($this->_options[callMe][loader_background] == '') echo 'selected';?>>Loader - Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[callMe][loader_background] == 'bg_yellow') echo 'selected';?>>Loader - Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[callMe][loader_background] == 'bg_wormwood') echo 'selected';?>>Loader - Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[callMe][loader_background] == 'bg_blue') echo 'selected';?>>Loader - Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[callMe][loader_background] == 'bg_green') echo 'selected';?>>Loader - Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[callMe][loader_background] == 'bg_beige') echo 'selected';?>>Loader - Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[callMe][loader_background] == 'bg_red') echo 'selected';?>>Loader - Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[callMe][loader_background] == 'bg_iceblue') echo 'selected';?>>Loader - Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[callMe][loader_background] == 'bg_black') echo 'selected';?>>Loader - Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[callMe][loader_background] == 'bg_skyblue') echo 'selected';?>>Loader - Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[callMe][loader_background] == 'bg_lilac') echo 'selected';?>>Loader - Background - Lilac</option>
							</select></label>
							
							<div class="clear"></div>
							<div class="col-sm-6" style="padding-left:0; margin-top: 10px;">
							<label><select id="callMe_background" name="callMe[background]">
								    <option value="bg_grey" <?php if($this->_options[callMe][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="" <?php if($this->_options[callMe][background] == '') echo 'selected';?>>Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[callMe][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[callMe][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[callMe][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[callMe][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[callMe][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[callMe][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[callMe][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[callMe][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[callMe][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[callMe][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
							</select><label>
							</div>
							<div class="col-sm-6" style="padding-right:0; margin-top: 10px;">
							<label><select id="callMe_button_color" name="callMe[button_color]">
								    <option value="btn_lightblue" <?php if($this->_options[callMe][button_color] == 'btn_lightblue') echo 'selected';?>>Button - Lightblue</option>
									<option value="btn_lightblue invert" <?php if($this->_options[callMe][button_color] == 'btn_lightblue invert' || $this->_options[callMe][button_color] == '') echo 'selected';?>>Button - Lightblue Transparent</option>
									<option value="btn_blue" <?php if($this->_options[callMe][button_color] == 'btn_blue') echo 'selected';?>>Button - Blue</option>
									<option value="btn_blue invert" <?php if($this->_options[callMe][button_color] == 'btn_blue invert') echo 'selected';?>>Button - Blue Transparent</option>
									<option value="btn_black" <?php if($this->_options[callMe][button_color] == 'btn_black') echo 'selected';?>>Button - Black</option>
									<option value="btn_black invert" <?php if($this->_options[callMe][button_color] == 'btn_black invert') echo 'selected';?>>Button - Black Transparent</option>
									<option value="btn_green" <?php if($this->_options[callMe][button_color] == 'btn_green') echo 'selected';?>>Button - Green</option>
									<option value="btn_green invert" <?php if($this->_options[callMe][button_color] == 'btn_green invert') echo 'selected';?>>Button - Green Transparent</option>
									<option value="btn_violet" <?php if($this->_options[callMe][button_color] == 'btn_violet') echo 'selected';?>>Button - Violet</option>
									<option value="btn_violet invert" <?php if($this->_options[callMe][button_color] == 'btn_violet invert') echo 'selected';?>>Button - Violet Transparent</option>
									<option value="btn_orange" <?php if($this->_options[callMe][button_color] == 'btn_orange') echo 'selected';?>>Button - Orange</option>
									<option value="btn_orange invert" <?php if($this->_options[callMe][button_color] == 'btn_orange invert') echo 'selected';?>>Button - Orange Transparent</option>
									<option value="btn_red" <?php if($this->_options[callMe][button_color] == 'btn_red') echo 'selected';?>>Button - Red</option>
									<option value="btn_red invert" <?php if($this->_options[callMe][button_color] == 'btn_red invert') echo 'selected';?>>Button - Red Transparent</option>
									<option value="btn_lilac" <?php if($this->_options[callMe][button_color] == 'btn_lilac') echo 'selected';?>>Button - Lilac</option>
									<option value="btn_lilac invert" <?php if($this->_options[callMe][button_color] == 'btn_lilac invert') echo 'selected';?>>Button - Lilac Transparent</option>
							</select><label>
							</div>
							<div class="clear"></div>
						
							<label><select id="callMe_img" name="callMe[img]" onchange="chagnePopupImg(this.value, 'callMeFotoBlock', 'callMeCustomFotoBlock');">
								<option value="" selected >No picture</option>
								<option value="img_01.png" <?php if($this->_options[callMe][img] == 'img_01.png') echo 'selected';?>>Question</option>
								<option value="img_02.png" <?php if($this->_options[callMe][img] == 'img_02.png') echo 'selected';?>>Attention</option>
								<option value="img_03.png" <?php if($this->_options[callMe][img] == 'img_03.png') echo 'selected';?>>Info</option>
								<option value="img_04.png" <?php if($this->_options[callMe][img] == 'img_04.png') echo 'selected';?>>Knowledge</option>
								<option value="img_05.png" <?php if($this->_options[callMe][img] == 'img_05.png') echo 'selected';?>>Idea</option>
								<option value="img_06.png" <?php if($this->_options[callMe][img] == 'img_06.png') echo 'selected';?>>Talk</option>
								<option value="img_07.png" <?php if($this->_options[callMe][img] == 'img_07.png') echo 'selected';?>>News</option>
								<option value="img_08.png" <?php if($this->_options[callMe][img] == 'img_08.png') echo 'selected';?>>Megaphone</option>
								<option value="img_09.png" <?php if($this->_options[callMe][img] == 'img_09.png') echo 'selected';?>>Gift</option>
								<option value="img_10.png" <?php if($this->_options[callMe][img] == 'img_10.png') echo 'selected';?>>Success</option>
								<option value="custom" <?php if($this->_options[callMe][img] == 'custom') echo 'selected';?>>Your custom image ...</option>
							</select></label>
							<label><div class="img"><img id="callMeFotoBlock" src="" />
							<input name="callMe[imgUrl]" style="display:none;" id="callMeCustomFotoBlock" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[callMe][imgUrl])?>">
							</div></label>
							<label><div class="box">
								<p>Follow Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="callMe[afterProceed][follow]" <?php if((int)$this->_options[callMe][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>Thank Popup After Success</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="callMe[afterProceed][thank]" <?php if((int)$this->_options[callMe][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<?php
								echo "
								<script>
									chagnePopupImg('".$this->_options[callMe][img]."', 'callMeFotoBlock', 'callMeCustomFotoBlock');
								</script>
								";
							?>
							<script>
								$("[name='callMe[afterProceed][follow]']").bootstrapSwitch();
								$("[name='callMe[afterProceed][thank]']").bootstrapSwitch();
								
								function callMePreview(){									
									var design = document.getElementById('callMe_typeWindow').value+' '+document.getElementById('callMe_background').value+' '+document.getElementById('callMe_button_color').value;
									var img = document.getElementById('callMe_img').value;
									var imgUrl = document.getElementById('callMeCustomFotoBlock').value;
									var loaderBackground = document.getElementById('callMe_loader_background').value;
									var position = document.getElementById('callMe_position').value;
									window.open("http://profitquery.com/aio_widgets_demo.html?p=callMe&design="+design+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl)+'&loaderDesign='+loaderBackground+'&position='+position, "product_view");							
								}
							</script>
							<div class="clear"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="callMePreview()">Preview</button>
							
							
							</div>
						<div class="pq_tooltip" data-toggle="tooltip" data-placement="left" title="For enable Follow Popup must be Off" style="bottom: 80px;"></div>
						<a href="javascript:void(0)" onclick="document.getElementById('Call_Me_Back').style.display='none';"><div class="close"></div></a>
						</div>
					</div>
						<div class="panel-body">
						<div class="col-sm-10" style="background: #F3F3F3; overflow: hidden; padding: 20px 0 10px; margin: 0 auto 25px; width: 83.333333%!important;">
							
							<div class="col-sm-12">
								<div class="col-sm-3" style="padding: 4px 0 0;">
									<p>Send Mail To</p>
								</div>
								<div class="col-sm-9" style="margin: 0 0 10px;">
									<label style="display: block;"><input name="adminEmail" value="<?php echo stripslashes($this->_options[adminEmail])?>"></label>
								</div>
								
							</div>							
						</div>
						</div><input type="submit" class="btn btn_m_red" value="Save changes">						
					</form>
					</div>					
				</div>
				<a name="AfterSuccessBlock"></a>
				<div style="margin-top: 35px; background: #ffeafe; padding: 45px 0;">					
						<h4 class="panel-title">After Success</h4>
						
					<div id="collapseThree" class="panel-collapse collapse in">
					<form action="<?php echo $this->getSettingsPageUrl();?>#AfterSuccessBlock" method="post">
					<input type="hidden" name="action" value="editAdditionalData">
					  <div class="panel-body">
					   <p>Get more social network follower's as after proceed bonus.</p>
						
						
						<div class="col-sm-6">
							<img id="follow_IMG" src="<?php echo plugins_url('images/follow.png', __FILE__);?>" />
							<div class="col-sm-10">
							<h5>Follow Popup</h5>							
							<label><select id="follow_background" onchange="changeFollowBlockImg()" name="follow[background]">
								    <option value="bg_grey" <?php if($this->_options[follow][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="" <?php if($this->_options[follow][background] == '') echo 'selected';?>>Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[follow][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[follow][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[follow][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[follow][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[follow][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[follow][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[follow][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[follow][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[follow][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[follow][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
							</select><label>
							<script>
								function changeFollowBlockImg(){
									if(document.getElementById('follow_background').value == 'bg_grey'){
										document.getElementById('follow_IMG').src = previewPath+'follow_7_m.png';
									}
									if(document.getElementById('follow_background').value == ''){
										document.getElementById('follow_IMG').src = previewPath+'follow_1_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_yellow'){
										document.getElementById('follow_IMG').src = previewPath+'follow_6_m.png';
									}									
									if(document.getElementById('follow_background').value == 'bg_wormwood'){
										document.getElementById('follow_IMG').src = previewPath+'follow_5_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_blue'){
										document.getElementById('follow_IMG').src = previewPath+'follow_10_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_green'){
										document.getElementById('follow_IMG').src = previewPath+'follow_11_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_beige'){
										document.getElementById('follow_IMG').src = previewPath+'follow_3_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_red'){
										document.getElementById('follow_IMG').src = previewPath+'follow_8_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_iceblue'){
										document.getElementById('follow_IMG').src = previewPath+'follow_2_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_black'){
										document.getElementById('follow_IMG').src = previewPath+'follow_12_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_skyblue'){
										document.getElementById('follow_IMG').src = previewPath+'follow_9_m.png';
									}
									if(document.getElementById('follow_background').value == 'bg_lilac'){
										document.getElementById('follow_IMG').src = previewPath+'follow_4_m.png';
									}
								}
								changeFollowBlockImg();
							</script>
							<a href="#After_Sharing" onclick="document.getElementById('After_Sharing').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>
						</div>
						<div class="col-sm-6">
							<img id="thank_IMG" src="<?php echo plugins_url('images/thank.png', __FILE__);?>" />
							<div class="col-sm-10">
							<h5>Thankyou Popup</h5>							
							
							<label><select id="thankPopup_background" onchange="changeThankBlockImg();" name="thankPopup[background]">
								    <option value="bg_grey" <?php if($this->_options[thankPopup][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="" <?php if($this->_options[thankPopup][background] == '') echo 'selected';?>>Background - White</option>
									<option value="bg_yellow" <?php if($this->_options[thankPopup][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_wormwood" <?php if($this->_options[thankPopup][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_blue" <?php if($this->_options[thankPopup][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_green" <?php if($this->_options[thankPopup][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_beige" <?php if($this->_options[thankPopup][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_red" <?php if($this->_options[thankPopup][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_iceblue" <?php if($this->_options[thankPopup][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_black" <?php if($this->_options[thankPopup][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_skyblue" <?php if($this->_options[thankPopup][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_lilac" <?php if($this->_options[thankPopup][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
							</select></label>
							<script>
								function changeThankBlockImg(){
									if(document.getElementById('thankPopup_background').value == 'bg_grey'){
										document.getElementById('thank_IMG').src = previewPath+'thank_7_m.png';
									}
									if(document.getElementById('thankPopup_background').value == ''){
										document.getElementById('thank_IMG').src = previewPath+'thank_1_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_yellow'){
										document.getElementById('thank_IMG').src = previewPath+'thank_6_m.png';
									}									
									if(document.getElementById('thankPopup_background').value == 'bg_wormwood'){
										document.getElementById('thank_IMG').src = previewPath+'thank_5_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_blue'){
										document.getElementById('thank_IMG').src = previewPath+'thank_10_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_green'){
										document.getElementById('thank_IMG').src = previewPath+'thank_11_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_beige'){
										document.getElementById('thank_IMG').src = previewPath+'thank_3_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_red'){
										document.getElementById('thank_IMG').src = previewPath+'thank_8_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_iceblue'){
										document.getElementById('thank_IMG').src = previewPath+'thank_2_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_black'){
										document.getElementById('thank_IMG').src = previewPath+'thank_12_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_skyblue'){
										document.getElementById('thank_IMG').src = previewPath+'thank_9_m.png';
									}
									if(document.getElementById('thankPopup_background').value == 'bg_lilac'){
										document.getElementById('thank_IMG').src = previewPath+'thank_4_m.png';
									}
								}
								changeThankBlockImg();
							</script>							
							
							<a href="#Thankyou_Popup" onclick="document.getElementById('Thankyou_Popup').style.display='block';"><button type="button" class="btn btn-link btn-bg">More Option</button></a>							
							</div>							
						</div>						
					</div>
					
					
					
					<div class="panel-body">
						<a name="After_Sharing"></a><div class="col-sm-10 more" id="After_Sharing" style="display:none;">
							<h5>More options Follow Us After Sharing</h5>
							<div class="col-sm-10" style="width: 83.333333%;">
							
							<label style="display: block;"><p>Heading</p><input name="follow[title]" value="<?php echo stripslashes($this->_options[follow][title])?>"></label>					
							<label style="display: block;"><p>Text</p><input name="follow[sub_title]" value="<?php echo stripslashes($this->_options[follow][sub_title])?>"></label>					
							
							<div class="col-sm-6" style="padding-left: 0; margin: 20px 0;">
							<label><div class="box">
								<p>After Sharing Sidebar</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="sharingSideBar[afterProceed][follow]" <?php if((int)$this->_options[sharingSideBar][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Image Sharer</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="imageSharer[afterProceed][follow]" <?php if((int)$this->_options[imageSharer][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Marketing Bar</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeBar[afterProceed][follow]" <?php if((int)$this->_options[subscribeBar][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							</div>
							<div class="col-sm-6" style="padding-right: 0; margin: 20px 0;">
							<label><div class="box">
								<p>After Exit Popup</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeExit[afterProceed][follow]" <?php if((int)$this->_options[subscribeExit][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Contact Form</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="contactUs[afterProceed][follow]" <?php if((int)$this->_options[contactUs][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Call Me Back</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="callMe[afterProceed][follow]" <?php if((int)$this->_options[callMe][afterProceed][follow] == 1) echo 'checked';?>></div>
							</div><label>
							</div>
							<script>
								$("[name='sharingSideBar[afterProceed][follow]']").bootstrapSwitch();
								$("[name='imageSharer[afterProceed][follow]']").bootstrapSwitch();
								$("[name='subscribeBar[afterProceed][follow]']").bootstrapSwitch();
								$("[name='subscribeExit[afterProceed][follow]']").bootstrapSwitch();
								$("[name='contactUs[afterProceed][follow]']").bootstrapSwitch();
								$("[name='callMe[afterProceed][follow]']").bootstrapSwitch();
								
								function followPreview(){									
									var design = document.getElementById('follow_background').value;									
									window.open("http://profitquery.com/aio_widgets_demo.html?p=follow&design="+design, "product_view");
								}
							</script>
							<div style="clear: both;"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="followPreview();">Preview</button>
							</div>
						<a href="javascript:void(0)" onclick="document.getElementById('After_Sharing').style.display='none';"><div class="close"></div></a>
						</div>
						<a name="Thankyou_Popup"></a><div class="col-sm-10 more" id="Thankyou_Popup" style="display:none;">
							<h5>More options Thankyou Popup</h5>
							<div class="col-sm-10" style="width: 83.333333%;">
							<label style="display: block;"><p>Heading</p><input name="thankPopup[title]" value="<?php echo stripslashes($this->_options[thankPopup][title])?>"></label>
							<label style="display: block;"><p>Text</p><input name="thankPopup[sub_title]" value="<?php echo stripslashes($this->_options[thankPopup][sub_title])?>"></label>							
							<label style="display: block;"><p>Button Title</p><input name="thankPopup[buttonTitle]" value="<?php echo stripslashes($this->_options[thankPopup][buttonTitle])?>"></label>							
							<div class="clear"></div>							
							<label style="margin: 10px 0;">
							<select id="thankPopup_img" name="thankPopup[img]" onchange="chagnePopupImg(this.value, 'thankPopupFotoBlock', 'thankPopupCustomFotoBlock');">
								<option value="img_01.png" <?php if($this->_options[thankPopup][img] == 'img_01.png') echo 'selected';?>>Question</option>
								<option value="img_02.png" <?php if($this->_options[thankPopup][img] == 'img_02.png') echo 'selected';?>>Attention</option>
								<option value="img_03.png" <?php if($this->_options[thankPopup][img] == 'img_03.png') echo 'selected';?>>Info</option>
								<option value="img_04.png" <?php if($this->_options[thankPopup][img] == 'img_04.png') echo 'selected';?>>Knowledge</option>
								<option value="img_05.png" <?php if($this->_options[thankPopup][img] == 'img_05.png') echo 'selected';?>>Idea</option>
								<option value="img_06.png" <?php if($this->_options[thankPopup][img] == 'img_06.png') echo 'selected';?>>Talk</option>
								<option value="img_07.png" <?php if($this->_options[thankPopup][img] == 'img_07.png') echo 'selected';?>>News</option>
								<option value="img_08.png" <?php if($this->_options[thankPopup][img] == 'img_08.png') echo 'selected';?>>Megaphone</option>
								<option value="img_09.png" <?php if($this->_options[thankPopup][img] == 'img_09.png') echo 'selected';?>>Gift</option>
								<option value="img_10.png" <?php if($this->_options[thankPopup][img] == 'img_10.png') echo 'selected';?>>Success</option>
								<option value="custom" <?php if($this->_options[thankPopup][img] == 'custom') echo 'selected';?>>Your custom image ...</option>
							</select>
							</label>
							<label style="margin: 10px 0;">
							<div class="img">
								<img id="thankPopupFotoBlock" src="" />
							<input name="thankPopup[imgUrl]" style="display:none; margin-top: 10px;" id="thankPopupCustomFotoBlock" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[thankPopup][imgUrl])?>">
							</div></label>
							<?php
								echo "
								<script>
									chagnePopupImg('".$this->_options[thankPopup][img]."', 'thankPopupFotoBlock', 'thankPopupCustomFotoBlock');
								</script>
								";
							?>
							<div class="clear"></div>
							<div class="col-sm-6" style="padding-left: 0; margin: 20px 0;">
							<label><div class="box">
								<p>After Sharing Sidebar</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="sharingSideBar[afterProceed][thank]" <?php if((int)$this->_options[sharingSideBar][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Image Sharer</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="imageSharer[afterProceed][thank]" <?php if((int)$this->_options[imageSharer][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Marketing Bar</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeBar[afterProceed][thank]" <?php if((int)$this->_options[subscribeBar][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							</div>
							<div class="col-sm-6" style="padding-right: 0; margin: 20px 0;">
							<label><div class="box">
								<p>After Exit Popup</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="subscribeExit[afterProceed][thank]" <?php if((int)$this->_options[subscribeExit][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Contact Form</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="contactUs[afterProceed][thank]" <?php if((int)$this->_options[contactUs][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div></label>
							<label><div class="box">
								<p>After Call Me Back</p><div class="bootstrap-switch bootstrap-switch-wrapper bootstrap-switch-on bootstrap-switch-id-switch-size bootstrap-switch-animate bootstrap-switch-mini bootstrap-switch-success">
								<input type="checkbox" name="callMe[afterProceed][thank]" <?php if((int)$this->_options[callMe][afterProceed][thank] == 1) echo 'checked';?>></div>
							</div><label>
							</div>
							<script>
								$("[name='sharingSideBar[afterProceed][thank]']").bootstrapSwitch();
								$("[name='imageSharer[afterProceed][thank]']").bootstrapSwitch();
								$("[name='subscribeBar[afterProceed][thank]']").bootstrapSwitch();
								$("[name='subscribeExit[afterProceed][thank]']").bootstrapSwitch();
								$("[name='contactUs[afterProceed][thank]']").bootstrapSwitch();
								$("[name='callMe[afterProceed][thank]']").bootstrapSwitch();
								
								function thankPopupPreview(){									
									var design = document.getElementById('thankPopup_background').value;
									var img = document.getElementById('thankPopup_img').value;
									var imgUrl = document.getElementById('thankPopupCustomFotoBlock').value;									
									window.open("http://profitquery.com/aio_widgets_demo.html?p=thankPopup&design="+design+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl), "product_view");							
								}
							</script>
							<div class="clear"></div>
							<button type="button" class="btn btn-link btn-lg btn-m" onclick="thankPopupPreview();">Preview</button>							
							</div>
						<a href="javascript:void(0)" onclick="document.getElementById('Thankyou_Popup').style.display='none';"><div class="close"></div></a>
						</div>
					</div>
					<a name="setupFollow"></a>
					<div class="panel-body">
						<div class="col-md-10 services" style="background: #F3F3F3; overflow: hidden; padding: 40px 0 20px; margin: 0 auto 25px; width: 83.333333%!important;" id="pq_input">							
							<label style="display: block;"><div class="col-sm-10 x30">
								<div class="pq_fb"></div>
									<p>facebook.com/</p><input type="text" name="follow[follow_socnet][FB]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][FB]);?>">
							</div></label>
							<label style="display: block;"><div class="col-sm-10 x30">
								<div class="pq_tw"></div>
									<p>twitter.com/</p><input type="text" name="follow[follow_socnet][TW]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][TW]);?>">
										
							</div></label>
							<div id="collapseservices" class="panel-collapse collapse">
							<label style="display: block;"><div class="col-sm-10 x30">
								<div class="pq_gp"></div>
									<p>plus.google.com/</p><input type="text" name="follow[follow_socnet][GP]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][GP]);?>">
										
							</div></label>
							<label style="display: block;"><div class="col-sm-10 x30">
								<div class="pq_pi"></div>
									<p>pinterest.com/</p><input type="text" name="follow[follow_socnet][PI]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][PI]);?>">
										
							</div></label>
							<label style="display: block;"><div class="col-sm-10 x30">
								<div class="pq_vk"></div>
									<p>vk.com/</p><input type="text" name="follow[follow_socnet][VK]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][VK]);?>">
										
							</div></label>
							<label style="display: block;"><div class="col-sm-10 x30">
								<div class="pq_od"></div>
									<p>ok.ru/</p><input type="text" name="follow[follow_socnet][OD]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][OD]);?>">
							</div></label>
							</div>
							<button type="button" class="btn btn-link btn-bg" data-toggle="collapse" href="#collapseservices" >More Services</button>
						</div>
						
					  </div><input type="submit" class="btn btn_m_red" value="Save changes">
					  </form>
					</div>
				  </div>				  
			</div>
<div class="container-fluid" id="free_profitquery" style="padding-top: 150px;">
	<div class="col-sm-12">
		<h4>More Tools from Profitquery</h4>
		<div class="col-sm-12 items">
			<div class="col-sm-5">
					<img src="<?php echo plugins_url('images/referral_system.png', __FILE__);?>" />
					<h5>Refferal System</h3>
					<a href="http://profitquery.com/referral_system.html" target="_blank"><input type="button" class="btn btn_m_red" style="width: initial; margin: 20px auto 8px;" value="Learn more"></a>
			</div>
			<div class="col-sm-5">
					<img src="<?php echo plugins_url('images/social_login.png', __FILE__);?>" />
					<h5>Social Login</h5>
					<a href="http://profitquery.com/social_login.html" target="_blank"><input type="button" class="btn btn_m_red" style="width: initial; margin: 20px auto 8px;" value="Learn more"></a>
			</div>
			<div class="col-sm-5">
					<img src="<?php echo plugins_url('images/trigger_mail.png', __FILE__);?>" />
					<h5>Trigger Mail</h3>
					<a href="http://profitquery.com/trigger_mail.html" target="_blank"><input type="button" class="btn btn_m_red" style="width: initial; margin: 20px auto 8px;" value="Learn more"></a>
			</div>
			<div class="col-sm-5">
					<img src="<?php echo plugins_url('images/product_discount.png', __FILE__);?>" />
					<h5>Product Discount</h5>
					<a href="http://profitquery.com/product_discount.html" target="_blank"><input type="button" class="btn btn_m_red" style="width: initial; margin: 20px auto 8px;" value="Learn more"></a>
			</div>
			
		</div>
		<div class="col-sm-10" style="overflow: hidden; padding: 20px 0; margin: 30px 0 25px; background: rgb(244, 244, 244);">
			<img src="<?php echo plugins_url('images/ecom.png', __FILE__);?>" />
			
			<h5>Free Profitquery Widgets for Ecommerce</h5>
			<a href="http://profitquery.com/ecom.html" target="_blank"><input type="button" class="btn btn_m_red" style="width: initial; margin: 20px auto 8px; background: transparent; color: red;  border: 1px solid red;" value="Learn more"></a>
		</div>
		<div class="col-sm-10" style="overflow: hidden; padding: 20px 0; margin: 10px 0 25px; background: #f8dde3;">
			<h5 style="color: white; background: #008AFF; width: 100px; margin: 0 auto; line-height: 35px; font-size: 26px;">PRO</h5>
			<h5>Get Profitquery Pro version</h5>
			<a href="http://profitquery.com/promo.html" target="_blank"><input type="button" class="btn btn_m_red" style="width: initial; margin: 20px auto 8px;" value="Learn more"></a>
		</div>
	</div>
</div>
</div>			
			<?php
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