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
* @version  SVN: 3.1.1
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
        add_action('admin_menu', array($this, 'ProfitquerySmartWidgetsMenu'));		
		// Deactivation
        register_deactivation_hook(
            PROFITQUERY_SMART_WIDGETS_FILENAME,
            array($this, 'pluginDeactivation')
        );
		register_activation_hook(
            PROFITQUERY_SMART_WIDGETS_FILENAME,
            array($this, 'pluginActivation')
        );
    }
	
	/*
		IsPLuginPage
		return boolean
	*/
	function isPluginPage(){
		$ret = false;
		if(strstr($_SERVER[REQUEST_URI], 'wp-admin/plugins.php')){
			$ret = true;
		}		
		return $ret;
	}
	
	
	/**
     * Functions to execute on plugin activation
     * 
     * @return null
     */
    public function pluginActivation()
    {		
		$this->_options[aio_widgets_loaded] = 1;
		if((int)$this->_options[rateUs][timeActivation] == 0){			
			$this->_options[rateUs][timeActivation] = time();
		}		
		update_option('profitquery', $this->_options);    
		$this->setDefaultProductData();
    }
	
	
	 /**
     * Functions to execute on plugin deactivation
     * 
     * @return null
     */
    public function pluginDeactivation()
    {
        if (get_option('profitquery')) {			
			$this->_options[aio_widgets_loaded] = 0;
			update_option('profitquery', $this->_options);
        }
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
		
		if(!$this->_options[imageSharer]){
			$this->_options[imageSharer][disabled] = 0;
			$this->_options[imageSharer][socnet] = array('FB'=>1, 'TW'=>1, 'PI'=>1, 'TR'=>1);
			$this->_options[imageSharer][socnetOption] = array('FB'=>array('type'=>'pq'), 'TW'=>array('type'=>'pq'), 'PI'=>array('type'=>''), 'TR'=>array('type'=>''));
			$this->_options[imageSharer][design][color] = 'c7';
			$this->_options[imageSharer][design][size] = 'x30';
			$this->_options[imageSharer][design][form] = 'rounded';
			$this->_options[imageSharer][design][shadow] = 'sh4';
			$this->_options[imageSharer][minWidth] = 100;
		}	
				
		
		if(!$this->_options[follow]){
			$this->_options[follow][disabled] = 1;			
		}
		if(!$this->_options[callMe]){
			$this->_options[callMe][disabled] = 1;
		}
		
		if(!$this->_options[sharingSideBar]){
			$this->_options[sharingSideBar][disabled] = 0;		
			$this->_options[sharingSideBar][socnet] = array('FB'=>1, 'GP'=>1, 'TW'=>1, 'LI'=>1, 'Mail'=>1);
			$this->_options[sharingSideBar][position] = 'pq_left pq_middle';
			$this->_options[sharingSideBar][design][color] = 'c4';
			$this->_options[sharingSideBar][design][size] = 'x40';
		}
		
		if(!$this->_options[contactUs]){
			$this->_options[contactUs][disabled] = 1;
			$this->_options[contactUs][position] = 'pq_right pq_bottom';
			$this->_options['contactUs']['typeWindow'] = 'pq_medium';				
			$this->_options['contactUs']['background'] = 'bg_grey';
			$this->_options['contactUs']['button_color'] = 'btn_lightblue';				
			$this->_options['contactUs']['title'] = 'Contact Us';
			$this->_options['contactUs']['buttonTitle'] = 'Send';				
			$this->_options['contactUs']['loader_background'] = 'bg_black';
			$this->_options['contactUs']['afterProceed'][thank] = 1;
			$this->_options[contactUs][animation] = 'bounceInDown';
			$this->_options[contactUs][overlay] = 'over_iceblue_lt';
		}
		
		if(!$this->_options[thankPopup]){
			$this->_options[thankPopup][disabled] = 1;
			$this->_options['thankPopup']['title'] = 'Thank You';
			$this->_options['thankPopup']['buttonTitle'] = 'Close';
			$this->_options['thankPopup']['background'] = 'bg_grey';
			$this->_options['thankPopup']['img'] = 'img_10.png';
			$this->_options[thankPopup][animation] = 'bounceInDown';
			$this->_options[thankPopup][overlay] = 'over_white';
		}
		
		if(!$this->_options[subscribeBar]){
			$this->_options[subscribeBar][disabled] = 1;
			$this->_options[subscribeBar][background] = 'bg_red';
			$this->_options[subscribeBar][button_color] = 'btn_black';			
			$this->_options[subscribeBar][animation] = 'bounce';			
		}
		
		if(!$this->_options[subscribeExit]){
			$this->_options[subscribeExit][disabled] = 1;
			$this->_options[subscribeExit][background] = 'bg_red';
			$this->_options[subscribeExit][button_color] = 'btn_black invert';
			$this->_options[subscribeExit][typeWindow] = 'pq_medium';
			$this->_options[subscribeExit][animation] = 'tada';						
			$this->_options[subscribeExit][overlay] = 'over_black_lt';
		}
		
		if(!$this->_options['adminEmail']){
			$this->_options['adminEmail'] = get_settings('admin_email');
		}
		
		$this->_options[aio_widgets_loaded] = 1;
		$this->_options[proOptions]['proLoaderFilename'] = $this->getDomain().'.pq_pro_loader';
		$this->_options[proOptions]['mainPageUrl'] = 'http://'.$this->getDomain();
		update_option('profitquery', $this->_options);
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
	
	/*
	 *	parseSubscribeProviderForm
	 *	
	 *	@return array
	 */
	function parseSubscribeProviderForm()
	{		
		if($_POST[subscribeProvider] == 'mailchimp'){
			$return = $this->_parseMailchimpForm();
		}
		
		if($_POST[subscribeProvider] == 'acampaign'){
			$return = $this->_parseACampaignForm();
		}
		if($_POST[subscribeProvider] == 'aweber'){
			$return = $this->_parseAweberForm();
		}
		return $return;
	}
	
	
	function _parseMailchimpForm()
	{
		$txt = trim($_POST[subscribeProviderFormContent]);		
		$array = array();
		$matches = array();
		if($txt){
			$txt = stripslashes($txt);
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\"\'])(.*)([\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);
			if(!strstr($array[formAction], 'list-manage.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			}			
		}
		return $array;
	}
	
	function _parseACampaignForm()
	{
		$txt = trim($_POST[subscribeProviderFormContent]);		
		$array = array();
		$matches = array();
		$hiddenField = array();		
		if($txt){
			$txt = stripslashes($txt);
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\"\'])(.*)([\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);			
			if(!strstr($array[formAction], 'activehosted.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\"\'])(.*)([\"\'])(.*)(value=)(.*)([\"\'])(.*)([\"\'])(.*)(\>)/Ui', $txt, $matches);				
				foreach((array)$matches[10] as $k => $v){
					$hiddenField[$v] = $matches[16][$k];
				}				
				if($hiddenField[act]){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}
		}		
		return $array;
	}
	
	function _parseAweberForm()
	{
		$txt = trim($_POST[subscribeProviderFormContent]);		
		$array = array();
		$matches = array();
		$hiddenField = array();
		if($txt){
			$txt = stripslashes($txt);
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\"\'])(.*)([\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);
			if(!strstr($array[formAction], 'aweber.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\"\'])(.*)([\"\'])(.*)(value=)(.*)([\"\'])(.*)([\"\'])(.*)(\>)/Ui', $txt, $matches);
				foreach((array)$matches[10] as $k => $v){
					$hiddenField[$v] = $matches[16][$k];
				}
				if($hiddenField[meta_web_form_id]){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}
		}
		return $array;
	}
	
	function checkDisableExeptImageUrlMask(){
		$ret = 0;
		foreach((array)$this->_options[proOptions][imageSharer][disableExeptImageUrlMask] as $k => $v){
			if(trim($v)) {
				$ret = 1;
				break;
			}			
		}
		return $ret;
	}
	
	function checkDisableExeptExtensions(){
		$ret = 0;
		foreach((array)$this->_options[proOptions][imageSharer][disableExeptExtensions] as $k => $v){
			if(trim($v)) {
				$ret = 1;
				break;
			}			
		}
		return $ret;
	}
	
	function checkDisableExeptPageMask($name){
		$ret = 0;
		foreach((array)$this->_options[proOptions][$name][disableExeptPageMask] as $k => $v){			
			if(trim($v)) {
				$ret = 1;
				break;
			}			
		}
		return $ret;
	}
	
	function checkUseProAnimation($name){
		$ret = 0;
		if($this->_options[proOptions][$name]){
			foreach((array)$this->_options[proOptions][$name] as $k => $v){
				if(!is_array($v)){
					if(strstr($v, 'pq_pro_a_')){
						$ret = 1;
						break;
					}
				}
			}
		}
		return $ret;
	}
	
	function checkUseProHoverAnimation($name){
		$ret = 0;
		if($this->_options[proOptions][$name]){
			foreach((array)$this->_options[proOptions][$name] as $k => $v){
				if(!is_array($v)){
					if(strstr($v, 'pq_pro_ha_')){
						$ret = 1;
						break;
					}
				}
			}
		}
		return $ret;
	}
	
	function checkUseProDO($name){
		$ret = 0;
		if($this->_options[proOptions][$name]){
			if($this->_options[proOptions][$name][font]) $ret = 1;
			if($this->_options[proOptions][$name][head_font]) $ret = 1;
			if($this->_options[proOptions][$name][head_color]) $ret = 1;
			if($this->_options[proOptions][$name][head_size]) $ret = 1;
			if($this->_options[proOptions][$name][text_size]) $ret = 1;
			if($this->_options[proOptions][$name][text_color]) $ret = 1;
			if($this->_options[proOptions][$name][b_radius]) $ret = 1;
			if($this->_options[proOptions][$name][b_color]) $ret = 1;
			if($this->_options[proOptions][$name][b_opacity]) $ret = 1;
			if($this->_options[proOptions][$name][b_style]) $ret = 1;
			if($this->_options[proOptions][$name][b_shadow]) $ret = 1;
			if($this->_options[proOptions][$name][b_width]) $ret = 1;
			if($this->_options[proOptions][$name][b_c_color]) $ret = 1;			
		}
		return $ret;
	}
	
	function checkProOptions()
	{
		$ret = array();		
		if((int)$this->_options[proOptions][sharingSideBar][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][imageSharer][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][subscribeBar][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][subscribeExit][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][contactUs][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][callMe][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][thankPopup][disableMainPage]) $ret[disableMainPage]=1;
		if((int)$this->_options[proOptions][follow][disableMainPage]) $ret[disableMainPage]=1;
		
		if($this->checkDisableExeptPageMask('sharingSideBar')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('imageSharer')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('subscribeBar')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('subscribeExit')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('contactUs')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('callMe')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('thankPopup')) $ret[disableExeptPageMask]=1;
		if($this->checkDisableExeptPageMask('follow')) $ret[disableExeptPageMask]=1;
		
		if($this->checkDisableExeptExtensions()) $ret[ISdisableExeptExtensions]=1;
		if($this->checkDisableExeptImageUrlMask()) $ret[ISdisableExeptImageUrlMask]=1;		
		if((int)$this->_options[proOptions][imageSharer][minHeight]) $ret[ISminHeight]=1;
		
		if($this->checkUseProAnimation('sharingSideBar')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('imageSharer')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('subscribeBar')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('subscribeExit')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('contactUs')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('callMe')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('thankPopup')) $ret[useProAnimation]=1;
		if($this->checkUseProAnimation('follow')) $ret[useProAnimation]=1;
		
		if($this->checkUseProDO('sharingSideBar')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('imageSharer')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('subscribeBar')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('subscribeExit')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('contactUs')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('callMe')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('thankPopup')) $ret[useProDesignOptions]=1;
		if($this->checkUseProDO('follow')) $ret[useProDesignOptions]=1;
		
		if($this->checkUseProHoverAnimation('sharingSideBar')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('imageSharer')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('subscribeBar')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('subscribeExit')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('contactUs')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('callMe')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('thankPopup')) $ret[useProHover]=1;
		if($this->checkUseProHoverAnimation('follow')) $ret[useProHover]=1;
		
		return $ret;
	}
	
	function activatePluginVersion(){
		$this->_options[pluginRegistration] = 1;
		echo '
		//PQActivatePlugin
		<script>
			setTimeout(function(){
				try{
					if(document.getElementById("PQActivatePlugin")){
						document.getElementById("PQActivatePlugin").style.display="block";
					}
				}catch(err){};
			}, 2000);
		</script>
		<iframe src="http://api.profitquery.com/cms-sign-in/firstLoadPlugin/?domain='.$this->getDomain().'&cms=wp&ae='.get_settings('admin_email').'" style="width: 0px; height: 0px; position: fixed; bottom: -2px;display:none;"></iframe>
		';
		update_option('profitquery', $this->_options);
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
		echo "			
			<link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css'>
			<link rel='stylesheet'  href='".plugins_url()."/".PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME."/".PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH."pq_wordpress_v2.css' type='text/css' media='all' />			
		<noscript>				
				<p>Please enable JavaScript in your browser.</p>				
		</noscript>
		";
		
		//firstActivate
		if((int)$this->_options[pluginRegistration] == 0){
			if(!$this->_options[apiKey]){
				$this->activatePluginVersion();
			}
		}
		
		//deleteProOptions
		if($_GET[action] == 'deleteProOptions'){
			$proLoaderFilename = $this->_options['proOptions'][proLoaderFilename];
			$mainPageUrl = $this->_options['proOptions'][mainPageUrl];
			$this->_options['proOptions'] = array();
			$this->_options['proOptions'][proLoaderFilename] = $proLoaderFilename;
			$this->_options['proOptions'][mainPageUrl] = $mainPageUrl;
			update_option('profitquery', $this->_options);
		}
		
		//disableSharingSidebar
		if($_POST[action] == 'disableSharingSidebar'){
			$this->_options['sharingSideBar']['disabled'] = 1;
			update_option('profitquery', $this->_options);
		}
		//disableImageSharer
		if($_POST[action] == 'disableImageSharer'){
			$this->_options['imageSharer']['disabled'] = 1;
			update_option('profitquery', $this->_options);
		}
		//disableSubscribeBar
		if($_POST[action] == 'disableSubscribeBar'){
			$this->_options['subscribeBar']['disabled'] = 1;
			update_option('profitquery', $this->_options);
		}
		//disableSubscribeExit
		if($_POST[action] == 'disableSubscribeExit'){
			$this->_options['subscribeExit']['disabled'] = 1;
			update_option('profitquery', $this->_options);
		}
		//disableContactUs
		if($_POST[action] == 'disableContactUs'){
			$this->_options['contactUs']['disabled'] = 1;
			update_option('profitquery', $this->_options);
		}
		//disableCallMe
		if($_POST[action] == 'disableCallMe'){
			$this->_options['callMe']['disabled'] = 1;
			update_option('profitquery', $this->_options);
		}
	
		
		//savePro
		if($_POST[action] == 'savePro'){
			if(trim($_POST[apiKey]) != '') {
				$this->_options['apiKey'] = sanitize_text_field($_POST[apiKey]);
				$this->_options['errorApiKey'] = 0;
			}else{
				$this->_options['apiKey'] = '';
				$this->_options['errorApiKey'] = 1;
			}
			if(trim($_POST[proOptions][proLoaderFilename])) $this->_options['proOptions'][proLoaderFilename] = sanitize_text_field($_POST[proOptions][proLoaderFilename]); else $this->_options['proOptions'][proLoaderFilename] = '';
			if(trim($_POST[proOptions][mainPageUrl])) $this->_options['proOptions'][mainPageUrl] = sanitize_text_field($_POST[proOptions][mainPageUrl]); else $this->_options['proOptions'][mainPageUrl] = '';
			update_option('profitquery', $this->_options);
		}
		
		//save api key
		if(trim($_GET[apiKey]) != ''){			
			if(trim($_GET[apiKey]) != '') $this->_options['apiKey'] = sanitize_text_field($_GET[apiKey]);						
			$this->_options['errorApiKey'] = 0;				
			update_option('profitquery', $this->_options);			
		}
		
		//editGA
		if($_POST[action] == 'editGA'){
			if((int)$_POST[additionalOptions][enableGA] == 1) $this->_options[additionalOptions][enableGA] = 1; else $this->_options[additionalOptions][enableGA] = 0;			
			update_option('profitquery', $this->_options);			
		}
		
		//editLang
		if($_POST[action] == 'editLang'){
			$this->_options[additionalOptions][lang] = $_POST[additionalOptions][lang];
			update_option('profitquery', $this->_options);			
		}
		
		//setAdminEmail
		if($_POST[action] == 'setAdminEmail'){
			if(trim($_POST[adminEmail]) != '') $this->_options['adminEmail'] = sanitize_text_field($_POST[adminEmail]);
			update_option('profitquery', $this->_options);
		}
		
		//Providers Setup
		if($_POST[action] == 'subscribeProviderSetup'){
			if(isset($_POST[subscribeProvider])){				
				$this->_options['subscribeProvider'] = sanitize_text_field($_POST[subscribeProvider]);
				if($_POST[subscribeProviderFormContent]){
					unset($this->_options['subscribeProviderOption']);					
					$this->_options['subscribeProviderOption'][$this->_options['subscribeProvider']] = $this->parseSubscribeProviderForm();					
				}else{					
					$this->_options['subscribeProviderOption'][$this->_options['subscribeProvider']][is_error] = 1;
				}				
			}
			update_option('profitquery', $this->_options);			
		}
		
		if($_POST[action] == 'edit'){		
			//printr($this->_options['proOptions']);
			//die();
			//follow
			if($_POST[follow]){				
				if(trim($_POST[follow][title])) $this->_options['follow']['title'] = sanitize_text_field($_POST[follow][title]); else $this->_options['follow']['title'] = '';
				if(trim($_POST[follow][sub_title])) $this->_options['follow']['sub_title'] = sanitize_text_field($_POST[follow][sub_title]); else $this->_options['follow']['sub_title'] = '';
				if(trim($_POST[follow][background])) $this->_options['follow']['background'] = sanitize_text_field($_POST[follow][background]); else $this->_options['follow']['background'] = '';
				if(trim($_POST[follow][animation])) $this->_options['follow']['animation'] = sanitize_text_field($_POST[follow][animation]); else $this->_options['follow']['animation'] = '';
				if(trim($_POST[follow][overlay])) $this->_options['follow']['overlay'] = sanitize_text_field($_POST[follow][overlay]); else $this->_options['follow']['overlay'] = '';
				if(trim($_POST[follow][typeWindow])) $this->_options['follow']['typeWindow'] = sanitize_text_field($_POST[follow][typeWindow]); else $this->_options['follow']['typeWindow'] = '';				
				if($_POST[follow][follow_socnet]){
					if(trim($_POST[follow][follow_socnet][FB]) != '') $this->_options[follow][follow_socnet][FB] = sanitize_text_field($_POST[follow][follow_socnet][FB]); else $this->_options[follow][follow_socnet][FB] = '';
					if(trim($_POST[follow][follow_socnet][TW]) != '') $this->_options[follow][follow_socnet][TW] = sanitize_text_field($_POST[follow][follow_socnet][TW]); else $this->_options[follow][follow_socnet][TW] = '';
					if(trim($_POST[follow][follow_socnet][GP]) != '') $this->_options[follow][follow_socnet][GP] = sanitize_text_field($_POST[follow][follow_socnet][GP]); else $this->_options[follow][follow_socnet][GP] = '';
					if(trim($_POST[follow][follow_socnet][PI]) != '') $this->_options[follow][follow_socnet][PI] = sanitize_text_field($_POST[follow][follow_socnet][PI]); else $this->_options[follow][follow_socnet][PI] = '';
					if(trim($_POST[follow][follow_socnet][VK]) != '') $this->_options[follow][follow_socnet][VK] = sanitize_text_field($_POST[follow][follow_socnet][VK]); else $this->_options[follow][follow_socnet][VK] = '';
					if(trim($_POST[follow][follow_socnet][OD]) != '') $this->_options[follow][follow_socnet][OD] = sanitize_text_field($_POST[follow][follow_socnet][OD]); else $this->_options[follow][follow_socnet][OD] = '';
					if(trim($_POST[follow][follow_socnet][RSS]) != '') $this->_options[follow][follow_socnet][RSS] = sanitize_text_field($_POST[follow][follow_socnet][RSS]); else $this->_options[follow][follow_socnet][RSS] = '';
					if(trim($_POST[follow][follow_socnet][IG]) != '') $this->_options[follow][follow_socnet][IG] = sanitize_text_field($_POST[follow][follow_socnet][IG]); else $this->_options[follow][follow_socnet][IG] = '';					
				}
				if(trim($_POST[follow][design][color])) $this->_options['follow']['design']['color'] = sanitize_text_field($_POST[follow][design][color]); else $this->_options['sharingSideBar']['design']['color'] = 'c4';
				if(trim($_POST[follow][design][form])) $this->_options['follow']['design']['form'] = sanitize_text_field($_POST[follow][design][form]); else $this->_options['sharingSideBar']['design']['form'] = '';
				if(trim($_POST[follow][design][size])) $this->_options['follow']['design']['size'] = sanitize_text_field($_POST[follow][design][size]); else $this->_options['sharingSideBar']['design']['size'] = 'x30';
				if(trim($_POST[follow][design][shadow])) $this->_options['follow']['design']['shadow'] = sanitize_text_field($_POST[follow][design][shadow]); else $this->_options['sharingSideBar']['design']['shadow'] = '';
			}
			
			//thankPopup
			if($_POST[thankPopup]){
				if(trim($_POST[thankPopup][title])) $this->_options['thankPopup']['title'] = sanitize_text_field($_POST[thankPopup][title]); else $this->_options['thankPopup']['title'] = '';
				if(trim($_POST[thankPopup][sub_title])) $this->_options['thankPopup']['sub_title'] = sanitize_text_field($_POST[thankPopup][sub_title]); else $this->_options['thankPopup']['sub_title'] = '';
				if(trim($_POST[thankPopup][buttonTitle])) $this->_options['thankPopup']['buttonTitle'] = sanitize_text_field($_POST[thankPopup][buttonTitle]); else $this->_options['thankPopup']['buttonTitle'] = '';
				if(trim($_POST[thankPopup][background])) $this->_options['thankPopup']['background'] = sanitize_text_field($_POST[thankPopup][background]); else $this->_options['thankPopup']['background'] = '';
				if(trim($_POST[thankPopup][img])) $this->_options['thankPopup']['img'] = sanitize_text_field($_POST[thankPopup][img]); else $this->_options['thankPopup']['img'] = '';
				if(trim($_POST[thankPopup][imgUrl])) $this->_options['thankPopup']['imgUrl'] = sanitize_text_field($_POST[thankPopup][imgUrl]); else $this->_options['thankPopup']['imgUrl'] = '';				
				if(trim($_POST[thankPopup][animation])) $this->_options['thankPopup']['animation'] = sanitize_text_field($_POST[thankPopup][animation]); else $this->_options['thankPopup']['animation'] = '';				
				if(trim($_POST[thankPopup][overlay])) $this->_options['thankPopup']['overlay'] = sanitize_text_field($_POST[thankPopup][overlay]); else $this->_options['thankPopup']['overlay'] = '';				
				if(trim($_POST[thankPopup][button_color])) $this->_options['thankPopup']['button_color'] = sanitize_text_field($_POST[thankPopup][button_color]); else $this->_options['thankPopup']['button_color'] = '';				
				if(trim($_POST[thankPopup][typeWindow])) $this->_options['thankPopup']['typeWindow'] = sanitize_text_field($_POST[thankPopup][typeWindow]); else $this->_options['thankPopup']['typeWindow'] = '';				
			}
			
			if($_POST[sharingSideBar]){
				if($_POST[sharingSideBar][enabled] == 'on') $this->_options['sharingSideBar']['disabled'] = 0; else $this->_options['sharingSideBar']['disabled'] = 1;
				if(trim($_POST[sharingSideBar][top])) $this->_options['sharingSideBar']['top'] = sanitize_text_field($_POST[sharingSideBar][top]); else $this->_options['sharingSideBar']['top'] = '';
				if(trim($_POST[sharingSideBar][side])) $this->_options['sharingSideBar']['side'] = sanitize_text_field($_POST[sharingSideBar][side]); else $this->_options['sharingSideBar']['side'] = '';
				if(trim($_POST[sharingSideBar][mobile_title])) $this->_options['sharingSideBar']['mobile_title'] = sanitize_text_field($_POST[sharingSideBar][mobile_title]); else $this->_options['sharingSideBar']['position'] = '';
				if($_POST[sharingSideBar][socnet_with_pos]){
					unset($this->_options['sharingSideBar']['socnet']);//for old version
					foreach((array)$_POST[sharingSideBar][socnet_with_pos] as $pos => $socName){
						if($socName){
							$this->_options['sharingSideBar']['socnet_with_pos'][(int)$pos] = sanitize_text_field($socName);
						}else{
							$this->_options['sharingSideBar']['socnet_with_pos'][(int)$pos] = '';
						}
					}
				}
				
				if($_POST[sharingSideBar][galleryOption]){
					if((int)$_POST[sharingSideBar][galleryOption][disable] == 1) $this->_options['sharingSideBar']['galleryOption']['disable'] = 1; else $this->_options['sharingSideBar']['galleryOption']['disable'] = 0;
					if(trim($_POST[sharingSideBar][galleryOption][title])) $this->_options['sharingSideBar']['galleryOption']['title'] = sanitize_text_field($_POST[sharingSideBar][galleryOption][title]); else $this->_options['sharingSideBar']['galleryOption']['title'] = '';
					if(trim($_POST[sharingSideBar][galleryOption][button_color])) $this->_options['sharingSideBar']['galleryOption']['button_color'] = sanitize_text_field($_POST[sharingSideBar][galleryOption][button_color]); else $this->_options['sharingSideBar']['galleryOption']['button_color'] = '';
					if(trim($_POST[sharingSideBar][galleryOption][buttonTitle])) $this->_options['sharingSideBar']['galleryOption']['buttonTitle'] = sanitize_text_field($_POST[sharingSideBar][galleryOption][buttonTitle]); else $this->_options['sharingSideBar']['galleryOption']['buttonTitle'] = '';
					if(trim($_POST[sharingSideBar][galleryOption][minWidth])) $this->_options['sharingSideBar']['galleryOption']['minWidth'] = sanitize_text_field($_POST[sharingSideBar][galleryOption][minWidth]); else $this->_options['sharingSideBar']['galleryOption']['minWidth'] = '';
					if(trim($_POST[sharingSideBar][galleryOption][background_color])) $this->_options['sharingSideBar']['galleryOption']['background_color'] = sanitize_text_field($_POST[sharingSideBar][galleryOption][background_color]); else $this->_options['sharingSideBar']['galleryOption']['background_color'] = '';
				}
				
				if(trim($_POST[sharingSideBar][design][color])) $this->_options['sharingSideBar']['design']['color'] = sanitize_text_field($_POST[sharingSideBar][design][color]); else $this->_options['sharingSideBar']['design']['color'] = 'c4';
				if(trim($_POST[sharingSideBar][design][form])) $this->_options['sharingSideBar']['design']['form'] = sanitize_text_field($_POST[sharingSideBar][design][form]); else $this->_options['sharingSideBar']['design']['form'] = '';
				if(trim($_POST[sharingSideBar][design][size])) $this->_options['sharingSideBar']['design']['size'] = sanitize_text_field($_POST[sharingSideBar][design][size]); else $this->_options['sharingSideBar']['design']['size'] = 'x30';
				
				if($_POST[sharingSideBar][afterProceed]){
					if($_POST[sharingSideBar][afterProceed][follow] == '1'){
						$this->_options['sharingSideBar']['afterProceed']['follow'] = 1;
						$this->_options['sharingSideBar']['afterProceed']['thank'] = 0;
					} elseif($_POST[sharingSideBar][afterProceed][thank] == '1'){
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
				if($_POST[imageSharer][disableAfterClick] == 'on') $this->_options['imageSharer']['disableAfterClick'] = 1; else $this->_options['imageSharer']['disableAfterClick'] = 0;
				
				
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
                    if($_POST[imageSharer][socnet][WU] == 'on') $this->_options[imageSharer][socnet][WU] = 1; else $this->_options[imageSharer][socnet][WU] = 0;                    
                    if($_POST[imageSharer][socnet][Mail] == 'on') $this->_options[imageSharer][socnet][Mail] = 1; else $this->_options[imageSharer][socnet][Mail] = 0;                    
				}
				
				if($_POST[imageSharer][socnetOption]){
					if($_POST[imageSharer][socnetOption][FB]) $this->_options['imageSharer']['socnetOption'][FB] = $_POST[imageSharer][socnetOption][FB]; else unset($this->_options['imageSharer']['socnetOption'][FB]);
					if($_POST[imageSharer][socnetOption][TW]) $this->_options['imageSharer']['socnetOption'][TW] = $_POST[imageSharer][socnetOption][TW]; else unset($this->_options['imageSharer']['socnetOption'][TW]);
					if($_POST[imageSharer][socnetOption][PI]) $this->_options['imageSharer']['socnetOption'][PI] = $_POST[imageSharer][socnetOption][PI]; else unset($this->_options['imageSharer']['socnetOption'][PI]);
					if($_POST[imageSharer][socnetOption][GP]) $this->_options['imageSharer']['socnetOption'][GP] = $_POST[imageSharer][socnetOption][GP]; else unset($this->_options['imageSharer']['socnetOption'][GP]);
					if($_POST[imageSharer][socnetOption][TR]) $this->_options['imageSharer']['socnetOption'][TR] = $_POST[imageSharer][socnetOption][TR]; else unset($this->_options['imageSharer']['socnetOption'][TR]);
					if($_POST[imageSharer][socnetOption][VK]) $this->_options['imageSharer']['socnetOption'][VK] = $_POST[imageSharer][socnetOption][VK]; else unset($this->_options['imageSharer']['socnetOption'][VK]);
					if($_POST[imageSharer][socnetOption][OD]) $this->_options['imageSharer']['socnetOption'][OD] = $_POST[imageSharer][socnetOption][OD]; else unset($this->_options['imageSharer']['socnetOption'][OD]);
				}
				
				if(trim($_POST[imageSharer][design][color])) $this->_options['imageSharer']['design']['color'] = sanitize_text_field($_POST[imageSharer][design][color]); else $this->_options['imageSharer']['design']['color'] = 'c4';
				if(trim($_POST[imageSharer][design][form])) $this->_options['imageSharer']['design']['form'] = sanitize_text_field($_POST[imageSharer][design][form]); else $this->_options['imageSharer']['design']['form'] = '';
				if(trim($_POST[imageSharer][design][size])) $this->_options['imageSharer']['design']['size'] = sanitize_text_field($_POST[imageSharer][design][size]); else $this->_options['imageSharer']['design']['size'] = 'x30';
				if(trim($_POST[imageSharer][design][shadow])) $this->_options['imageSharer']['design']['shadow'] = sanitize_text_field($_POST[imageSharer][design][shadow]); else $this->_options['imageSharer']['design']['shadow'] = 'sh1';
				
				if(intval($_POST[imageSharer][minWidth]) >= 0) $this->_options['imageSharer']['minWidth'] = intval($_POST[imageSharer][minWidth]);								
				
				if($_POST[imageSharer][afterProceed]){
					if($_POST[imageSharer][afterProceed][follow] == '1'){
						$this->_options['imageSharer']['afterProceed']['follow'] = 1;
						$this->_options['imageSharer']['afterProceed']['thank'] = 0;
					} elseif($_POST[imageSharer][afterProceed][thank] == '1'){
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
			
			//subscribeBar
			if($_POST[subscribeBar]){
				if($_POST[subscribeBar][enabled] == 'on') $this->_options['subscribeBar']['disabled'] = 0; else $this->_options['subscribeBar']['disabled'] = 1;
				if(trim($_POST[subscribeBar][position])) $this->_options['subscribeBar']['position'] = sanitize_text_field($_POST[subscribeBar][position]); else $this->_options['subscribeBar']['position'] = '';
				if(trim($_POST[subscribeBar][typeWindow])) $this->_options['subscribeBar']['typeWindow'] = sanitize_text_field($_POST[subscribeBar][typeWindow]); else $this->_options['subscribeBar']['typeWindow'] = '';
				if(trim($_POST[subscribeBar][title])) $this->_options['subscribeBar']['title'] = sanitize_text_field($_POST[subscribeBar][title]); else $this->_options['subscribeBar']['title'] = '';
				if(trim($_POST[subscribeBar][mobile_title])) $this->_options['subscribeBar']['mobile_title'] = sanitize_text_field($_POST[subscribeBar][mobile_title]); else $this->_options['subscribeBar']['mobile_title'] = '';
				if(trim($_POST[subscribeBar][inputEmailTitle])) $this->_options['subscribeBar']['inputEmailTitle'] = sanitize_text_field($_POST[subscribeBar][inputEmailTitle]); else $this->_options['subscribeBar']['inputEmailTitle'] = '';
				if(trim($_POST[subscribeBar][inputNameTitle])) $this->_options['subscribeBar']['inputNameTitle'] = sanitize_text_field($_POST[subscribeBar][inputNameTitle]); else $this->_options['subscribeBar']['inputNameTitle'] = '';
				if(trim($_POST[subscribeBar][buttonTitle])) $this->_options['subscribeBar']['buttonTitle'] = sanitize_text_field($_POST[subscribeBar][buttonTitle]); else $this->_options['subscribeBar']['buttonTitle'] = '';
				if(trim($_POST[subscribeBar][background])) $this->_options['subscribeBar']['background'] = sanitize_text_field($_POST[subscribeBar][background]); else $this->_options['subscribeBar']['background'] = '';
				if(trim($_POST[subscribeBar][button_color])) $this->_options['subscribeBar']['button_color'] = sanitize_text_field($_POST[subscribeBar][button_color]); else $this->_options['subscribeBar']['button_color'] = '';
				if(trim($_POST[subscribeBar][size])) $this->_options['subscribeBar']['size'] = sanitize_text_field($_POST[subscribeBar][size]); else $this->_options['subscribeBar']['size'] = '';								
				if(trim($_POST[subscribeBar][animation])) $this->_options['subscribeBar']['animation'] = sanitize_text_field($_POST[subscribeBar][animation]); else $this->_options['subscribeBar']['animation'] = '';
				
				if($_POST[subscribeBar][afterProceed]){
					if($_POST[subscribeBar][afterProceed][follow] == '1'){
						$this->_options['subscribeBar']['afterProceed']['follow'] = 1;
						$this->_options['subscribeBar']['afterProceed']['thank'] = 0;
					} elseif($_POST[subscribeBar][afterProceed][thank] == '1'){
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
				if(trim($_POST[subscribeExit][inputNameTitle])) $this->_options['subscribeExit']['inputNameTitle'] = sanitize_text_field($_POST[subscribeExit][inputNameTitle]); else $this->_options['subscribeExit']['inputNameTitle'] = '';
				if(trim($_POST[subscribeExit][buttonTitle])) $this->_options['subscribeExit']['buttonTitle'] = sanitize_text_field($_POST[subscribeExit][buttonTitle]); else $this->_options['subscribeExit']['buttonTitle'] = '';
				if(trim($_POST[subscribeExit][background])) $this->_options['subscribeExit']['background'] = sanitize_text_field($_POST[subscribeExit][background]); else $this->_options['subscribeExit']['background'] = '';
				if(trim($_POST[subscribeExit][button_color])) $this->_options['subscribeExit']['button_color'] = sanitize_text_field($_POST[subscribeExit][button_color]); else $this->_options['subscribeExit']['button_color'] = '';
				if(trim($_POST[subscribeExit][img])) $this->_options['subscribeExit']['img'] = sanitize_text_field($_POST[subscribeExit][img]); else $this->_options['subscribeExit']['img'] = '';
				if(trim($_POST[subscribeExit][imgUrl])) $this->_options['subscribeExit']['imgUrl'] = sanitize_text_field($_POST[subscribeExit][imgUrl]); else $this->_options['subscribeExit']['imgUrl'] = '';				
				if(trim($_POST[subscribeExit][animation])) $this->_options['subscribeExit']['animation'] = sanitize_text_field($_POST[subscribeExit][animation]); else $this->_options['subscribeExit']['animation'] = '';				
				if(trim($_POST[subscribeExit][overlay])) $this->_options['subscribeExit']['overlay'] = sanitize_text_field($_POST[subscribeExit][overlay]); else $this->_options['subscribeExit']['overlay'] = '';				
				if($_POST[subscribeExit][afterProceed]){
					if($_POST[subscribeExit][afterProceed][follow] == '1'){
						$this->_options['subscribeExit']['afterProceed']['follow'] = 1;
						$this->_options['subscribeExit']['afterProceed']['thank'] = 0;
					} elseif($_POST[subscribeExit][afterProceed][thank] == '1'){
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
			
			//contactUs
			if($_POST[contactUs]){
				if($_POST[contactUs][enabled] == 'on') $this->_options['contactUs']['disabled'] = 0; else $this->_options['contactUs']['disabled'] = 1;
				if(trim($_POST[contactUs][top])) $this->_options['contactUs']['top'] = sanitize_text_field($_POST[contactUs][top]); else $this->_options['contactUs']['top'] = '';
				if(trim($_POST[contactUs][side])) $this->_options['contactUs']['side'] = sanitize_text_field($_POST[contactUs][side]); else $this->_options['contactUs']['side'] = '';
				if(trim($_POST[contactUs][typeWindow])) $this->_options['contactUs']['typeWindow'] = sanitize_text_field($_POST[contactUs][typeWindow]); else $this->_options['contactUs']['typeWindow'] = '';
				if(trim($_POST[contactUs][title])) $this->_options['contactUs']['title'] = sanitize_text_field($_POST[contactUs][title]); else $this->_options['contactUs']['title'] = '';
				if(trim($_POST[contactUs][sub_title])) $this->_options['contactUs']['sub_title'] = sanitize_text_field($_POST[contactUs][sub_title]); else $this->_options['contactUs']['sub_title'] = '';
				if(trim($_POST[contactUs][buttonTitle])) $this->_options['contactUs']['buttonTitle'] = sanitize_text_field($_POST[contactUs][buttonTitle]); else $this->_options['contactUs']['buttonTitle'] = '';
				if(trim($_POST[contactUs][enter_name_text])) $this->_options['contactUs']['enter_name_text'] = sanitize_text_field($_POST[contactUs][enter_name_text]); else $this->_options['contactUs']['enter_name_text'] = '';
				if(trim($_POST[contactUs][enter_email_text])) $this->_options['contactUs']['enter_email_text'] = sanitize_text_field($_POST[contactUs][enter_email_text]); else $this->_options['contactUs']['enter_email_text'] = '';
				if(trim($_POST[contactUs][enter_message_text])) $this->_options['contactUs']['enter_message_text'] = sanitize_text_field($_POST[contactUs][enter_message_text]); else $this->_options['contactUs']['enter_message_text'] = '';
				if(trim($_POST[contactUs][loaderText])) $this->_options['contactUs']['loaderText'] = sanitize_text_field($_POST[contactUs][loaderText]); else $this->_options['contactUs']['loaderText'] = '';
								
				if(trim($_POST[contactUs][overlay])) $this->_options['contactUs']['overlay'] = sanitize_text_field($_POST[contactUs][overlay]); else $this->_options['contactUs']['overlay'] = '';
				if(trim($_POST[contactUs][animation])) $this->_options['contactUs']['animation'] = sanitize_text_field($_POST[contactUs][animation]); else $this->_options['contactUs']['animation'] = '';
				if(trim($_POST[contactUs][background])) $this->_options['contactUs']['background'] = sanitize_text_field($_POST[contactUs][background]); else $this->_options['contactUs']['background'] = '';
				if(trim($_POST[contactUs][loader_background])) $this->_options['contactUs']['loader_background'] = sanitize_text_field($_POST[contactUs][loader_background]); else $this->_options['contactUs']['loader_background'] = '';
				if(trim($_POST[contactUs][button_color])) $this->_options['contactUs']['button_color'] = sanitize_text_field($_POST[contactUs][button_color]); else $this->_options['contactUs']['button_color'] = '';
				if(trim($_POST[contactUs][img])) $this->_options['contactUs']['img'] = sanitize_text_field($_POST[contactUs][img]); else $this->_options['contactUs']['img'] = '';
				if(trim($_POST[contactUs][imgUrl])) $this->_options['contactUs']['imgUrl'] = sanitize_text_field($_POST[contactUs][imgUrl]); else $this->_options['contactUs']['imgUrl'] = '';				
				if($_POST[contactUs][afterProceed]){
					if($_POST[contactUs][afterProceed][follow] == '1'){
						$this->_options['contactUs']['afterProceed']['follow'] = 1;
						$this->_options['contactUs']['afterProceed']['thank'] = 0;
					} elseif($_POST[contactUs][afterProceed][thank] == '1'){
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
				if(trim($_POST[callMe][top])) $this->_options['callMe']['top'] = sanitize_text_field($_POST[callMe][top]); else $this->_options['callMe']['top'] = '';
				if(trim($_POST[callMe][side])) $this->_options['callMe']['side'] = sanitize_text_field($_POST[callMe][side]); else $this->_options['callMe']['side'] = '';
				if(trim($_POST[callMe][typeWindow])) $this->_options['callMe']['typeWindow'] = sanitize_text_field($_POST[callMe][typeWindow]); else $this->_options['callMe']['typeWindow'] = '';
				if(trim($_POST[callMe][title])) $this->_options['callMe']['title'] = sanitize_text_field($_POST[callMe][title]); else $this->_options['callMe']['title'] = '';
				if(trim($_POST[callMe][sub_title])) $this->_options['callMe']['sub_title'] = sanitize_text_field($_POST[callMe][sub_title]); else $this->_options['callMe']['sub_title'] = '';				
				if(trim($_POST[callMe][buttonTitle])) $this->_options['callMe']['buttonTitle'] = sanitize_text_field($_POST[callMe][buttonTitle]); else $this->_options['callMe']['buttonTitle'] = '';
				
				if(trim($_POST[callMe][enter_name_text])) $this->_options['callMe']['enter_name_text'] = sanitize_text_field($_POST[callMe][enter_name_text]); else $this->_options['callMe']['enter_name_text'] = '';
				if(trim($_POST[callMe][enter_phone_text])) $this->_options['callMe']['enter_phone_text'] = sanitize_text_field($_POST[callMe][buttonTitle]); else $this->_options['callMe']['enter_phone_text'] = '';
				if(trim($_POST[callMe][loaderText])) $this->_options['callMe']['loaderText'] = sanitize_text_field($_POST[callMe][loaderText]); else $this->_options['callMe']['loaderText'] = '';
								
				if(trim($_POST[callMe][overlay])) $this->_options['callMe']['overlay'] = sanitize_text_field($_POST[callMe][overlay]); else $this->_options['callMe']['overlay'] = '';
				if(trim($_POST[callMe][animation])) $this->_options['callMe']['animation'] = sanitize_text_field($_POST[callMe][animation]); else $this->_options['callMe']['animation'] = '';
				if(trim($_POST[callMe][background])) $this->_options['callMe']['background'] = sanitize_text_field($_POST[callMe][background]); else $this->_options['callMe']['background'] = '';
				if(trim($_POST[callMe][loader_background])) $this->_options['callMe']['loader_background'] = sanitize_text_field($_POST[callMe][loader_background]); else $this->_options['callMe']['loader_background'] = '';
				if(trim($_POST[callMe][button_color])) $this->_options['callMe']['button_color'] = sanitize_text_field($_POST[callMe][button_color]); else $this->_options['callMe']['button_color'] = '';
				if(trim($_POST[callMe][img])) $this->_options['callMe']['img'] = sanitize_text_field($_POST[callMe][img]); else $this->_options['callMe']['img'] = '';
				if(trim($_POST[callMe][imgUrl])) $this->_options['callMe']['imgUrl'] = sanitize_text_field($_POST[callMe][imgUrl]); else $this->_options['callMe']['imgUrl'] = '';				
				if($_POST[callMe][afterProceed]){
					if($_POST[callMe][afterProceed][follow] == '1'){
						$this->_options['callMe']['afterProceed']['follow'] = 1;
						$this->_options['callMe']['afterProceed']['thank'] = 0;
					} elseif($_POST[callMe][afterProceed][thank] == '1'){
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
			
			//printr($_POST);
			//die();
			if($_POST[proOptions]){
				//if($_POST[proOptions][all] == 'on') $this->_options['callMe']['disabled'] = 0; else $this->_options['callMe']['disabled'] = 1;
				//pro sharingSideBar
				if($_POST[proOptions][sharingSideBar]){
					if((int)$_POST[proOptions][sharingSideBar][disableMainPage] == 1) $this->_options['proOptions']['sharingSideBar']['disableMainPage'] = 1; else $this->_options['proOptions']['sharingSideBar']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][sharingSideBar][whitelabel] == 1) $this->_options['proOptions']['sharingSideBar']['whitelabel'] = 1; else $this->_options['proOptions']['sharingSideBar']['whitelabel'] = 0;
					if(trim($_POST[proOptions][sharingSideBar][disableExeptPageMask][0])) $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][sharingSideBar][disableExeptPageMask][0]); else $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][sharingSideBar][disableExeptPageMask][1])) $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][sharingSideBar][disableExeptPageMask][1]); else $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][sharingSideBar][disableExeptPageMask][2])) $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][sharingSideBar][disableExeptPageMask][2]); else $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][sharingSideBar][disableExeptPageMask][3])) $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][sharingSideBar][disableExeptPageMask][3]); else $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][sharingSideBar][disableExeptPageMask][4])) $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][sharingSideBar][disableExeptPageMask][4]); else $this->_options['proOptions']['sharingSideBar']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][sharingSideBar][animation])) $this->_options['proOptions']['sharingSideBar']['animation'] = sanitize_text_field($_POST[proOptions][sharingSideBar][animation]); else $this->_options['proOptions']['sharingSideBar']['animation'] = '';
					if(trim($_POST[proOptions][sharingSideBar][hover_animation])) $this->_options['proOptions']['sharingSideBar']['hover_animation'] = sanitize_text_field($_POST[proOptions][sharingSideBar][hover_animation]); else $this->_options['proOptions']['sharingSideBar']['hover_animation'] = '';					
				}
				
				//imageSharer
				if($_POST[proOptions][imageSharer]){
					if((int)$_POST[proOptions][imageSharer][disableMainPage] == 1) $this->_options['proOptions']['imageSharer']['disableMainPage'] = 1; else $this->_options['proOptions']['imageSharer']['disableMainPage'] = 0;					
					if((int)$_POST[proOptions][imageSharer][minHeight]) $this->_options['proOptions']['imageSharer']['minHeight'] = (int)$_POST[proOptions][imageSharer][minHeight]; else $this->_options['proOptions']['imageSharer']['minHeight'] = 0;
					
					if(trim($_POST[proOptions][imageSharer][disableExeptPageMask][0])) $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptPageMask][0]); else $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptPageMask][1])) $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptPageMask][1]); else $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptPageMask][2])) $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptPageMask][2]); else $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptPageMask][3])) $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptPageMask][3]); else $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptPageMask][4])) $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptPageMask][4]); else $this->_options['proOptions']['imageSharer']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][imageSharer][disableExeptExtensions][0])) $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][0] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptExtensions][0]); else $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][0] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptExtensions][1])) $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][1] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptExtensions][1]); else $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][1] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptExtensions][2])) $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][2] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptExtensions][2]); else $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][2] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptExtensions][3])) $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][3] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptExtensions][3]); else $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][3] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptExtensions][4])) $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][4] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptExtensions][4]); else $this->_options['proOptions']['imageSharer']['disableExeptExtensions'][4] = '';
					
					
					if(trim($_POST[proOptions][imageSharer][disableExeptImageUrlMask][0])) $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][0] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptImageUrlMask][0]); else $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][0] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptImageUrlMask][1])) $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][1] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptImageUrlMask][1]); else $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][1] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptImageUrlMask][2])) $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][2] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptImageUrlMask][2]); else $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][2] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptImageUrlMask][3])) $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][3] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptImageUrlMask][3]); else $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][3] = '';
					if(trim($_POST[proOptions][imageSharer][disableExeptImageUrlMask][4])) $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][4] = sanitize_text_field($_POST[proOptions][imageSharer][disableExeptImageUrlMask][4]); else $this->_options['proOptions']['imageSharer']['disableExeptImageUrlMask'][4] = '';
					
					if(trim($_POST[proOptions][imageSharer][animation])) $this->_options['proOptions']['imageSharer']['animation'] = sanitize_text_field($_POST[proOptions][imageSharer][animation]); else $this->_options['proOptions']['imageSharer']['animation'] = '';
					if(trim($_POST[proOptions][imageSharer][hover_animation])) $this->_options['proOptions']['imageSharer']['hover_animation'] = sanitize_text_field($_POST[proOptions][imageSharer][hover_animation]); else $this->_options['proOptions']['imageSharer']['hover_animation'] = '';					
				}
				
				//subscribeBar
				if($_POST[proOptions][subscribeBar]){
					if((int)$_POST[proOptions][subscribeBar][disableMainPage] == 1) $this->_options['proOptions']['subscribeBar']['disableMainPage'] = 1; else $this->_options['proOptions']['subscribeBar']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][subscribeBar][whitelabel] == 1) $this->_options['proOptions']['subscribeBar']['whitelabel'] = 1; else $this->_options['proOptions']['subscribeBar']['whitelabel'] = 0;
					if(trim($_POST[proOptions][subscribeBar][disableExeptPageMask][0])) $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][subscribeBar][disableExeptPageMask][0]); else $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][subscribeBar][disableExeptPageMask][1])) $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][subscribeBar][disableExeptPageMask][1]); else $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][subscribeBar][disableExeptPageMask][2])) $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][subscribeBar][disableExeptPageMask][2]); else $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][subscribeBar][disableExeptPageMask][3])) $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][subscribeBar][disableExeptPageMask][3]); else $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][subscribeBar][disableExeptPageMask][4])) $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][subscribeBar][disableExeptPageMask][4]); else $this->_options['proOptions']['subscribeBar']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][subscribeBar][animation])) $this->_options['proOptions']['subscribeBar']['animation'] = sanitize_text_field($_POST[proOptions][subscribeBar][animation]); else $this->_options['proOptions']['subscribeBar']['animation'] = '';
					if(trim($_POST[proOptions][subscribeBar][hover_animation])) $this->_options['proOptions']['subscribeBar']['hover_animation'] = sanitize_text_field($_POST[proOptions][subscribeBar][hover_animation]); else $this->_options['proOptions']['subscribeBar']['hover_animation'] = '';					
					
					if(trim($_POST[proOptions][subscribeBar][font])) $this->_options['proOptions']['subscribeBar']['font'] = sanitize_text_field($_POST[proOptions][subscribeBar][font]); else $this->_options['proOptions']['subscribeBar']['font'] = '';
					if(trim($_POST[proOptions][subscribeBar][shape])) $this->_options['proOptions']['subscribeBar']['shape'] = sanitize_text_field($_POST[proOptions][subscribeBar][shape]); else $this->_options['proOptions']['subscribeBar']['shape'] = '';
					if(trim($_POST[proOptions][subscribeBar][b_c_color])) $this->_options['proOptions']['subscribeBar']['b_c_color'] = sanitize_text_field($_POST[proOptions][subscribeBar][b_c_color]); else $this->_options['proOptions']['subscribeBar']['b_c_color'] = '';
				}
				
				//subscribeExit
				if($_POST[proOptions][subscribeExit]){
					if((int)$_POST[proOptions][subscribeExit][disableMainPage] == 1) $this->_options['proOptions']['subscribeExit']['disableMainPage'] = 1; else $this->_options['proOptions']['subscribeExit']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][subscribeExit][whitelabel] == 1) $this->_options['proOptions']['subscribeExit']['whitelabel'] = 1; else $this->_options['proOptions']['subscribeExit']['whitelabel'] = 0;
					if(trim($_POST[proOptions][subscribeExit][disableExeptPageMask][0])) $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][subscribeExit][disableExeptPageMask][0]); else $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][subscribeExit][disableExeptPageMask][1])) $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][subscribeExit][disableExeptPageMask][1]); else $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][subscribeExit][disableExeptPageMask][2])) $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][subscribeExit][disableExeptPageMask][2]); else $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][subscribeExit][disableExeptPageMask][3])) $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][subscribeExit][disableExeptPageMask][3]); else $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][subscribeExit][disableExeptPageMask][4])) $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][subscribeExit][disableExeptPageMask][4]); else $this->_options['proOptions']['subscribeExit']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][subscribeExit][animation])) $this->_options['proOptions']['subscribeExit']['animation'] = sanitize_text_field($_POST[proOptions][subscribeExit][animation]); else $this->_options['proOptions']['subscribeExit']['animation'] = '';
					if(trim($_POST[proOptions][subscribeExit][hover_animation])) $this->_options['proOptions']['subscribeExit']['hover_animation'] = sanitize_text_field($_POST[proOptions][subscribeExit][hover_animation]); else $this->_options['proOptions']['subscribeExit']['hover_animation'] = '';					
					
					if(trim($_POST[proOptions][subscribeExit][font])) $this->_options['proOptions']['subscribeExit']['font'] = sanitize_text_field($_POST[proOptions][subscribeExit][font]); else $this->_options['proOptions']['subscribeExit']['font'] = '';
					if(trim($_POST[proOptions][subscribeExit][head_font])) $this->_options['proOptions']['subscribeExit']['head_font'] = sanitize_text_field($_POST[proOptions][subscribeExit][head_font]); else $this->_options['proOptions']['subscribeExit']['head_font'] = '';
					if(trim($_POST[proOptions][subscribeExit][head_color])) $this->_options['proOptions']['subscribeExit']['head_color'] = sanitize_text_field($_POST[proOptions][subscribeExit][head_color]); else $this->_options['proOptions']['subscribeExit']['head_color'] = '';
					if(trim($_POST[proOptions][subscribeExit][head_size])) $this->_options['proOptions']['subscribeExit']['head_size'] = sanitize_text_field($_POST[proOptions][subscribeExit][head_size]); else $this->_options['proOptions']['subscribeExit']['head_size'] = '';
					if(trim($_POST[proOptions][subscribeExit][text_size])) $this->_options['proOptions']['subscribeExit']['text_size'] = sanitize_text_field($_POST[proOptions][subscribeExit][text_size]); else $this->_options['proOptions']['subscribeExit']['text_size'] = '';
					if(trim($_POST[proOptions][subscribeExit][text_color])) $this->_options['proOptions']['subscribeExit']['text_color'] = sanitize_text_field($_POST[proOptions][subscribeExit][text_color]); else $this->_options['proOptions']['subscribeExit']['text_color'] = '';
					if(trim($_POST[proOptions][subscribeExit][b_radius])) $this->_options['proOptions']['subscribeExit']['b_radius'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_radius]); else $this->_options['proOptions']['subscribeExit']['b_radius'] = '';
					if(trim($_POST[proOptions][subscribeExit][b_color])) $this->_options['proOptions']['subscribeExit']['b_color'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_color]); else $this->_options['proOptions']['subscribeExit']['b_color'] = '';
					if(trim($_POST[proOptions][subscribeExit][b_opacity])) $this->_options['proOptions']['subscribeExit']['b_opacity'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_opacity]); else $this->_options['proOptions']['subscribeExit']['b_opacity'] = '';
					if(trim($_POST[proOptions][subscribeExit][b_style])) $this->_options['proOptions']['subscribeExit']['b_style'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_style]); else $this->_options['proOptions']['subscribeExit']['b_style'] = '';
					if(trim($_POST[proOptions][subscribeExit][b_shadow])) $this->_options['proOptions']['subscribeExit']['b_shadow'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_shadow]); else $this->_options['proOptions']['subscribeExit']['b_shadow'] = '';
					if(trim($_POST[proOptions][subscribeExit][background_image])) $this->_options['proOptions']['subscribeExit']['background_image'] = sanitize_text_field($_POST[proOptions][subscribeExit][background_image]); else $this->_options['proOptions']['subscribeExit']['background_image'] = '';										
					if(trim($_POST[proOptions][subscribeExit][b_width])) $this->_options['proOptions']['subscribeExit']['b_width'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_width]); else $this->_options['proOptions']['subscribeExit']['b_width'] = '';
					if(trim($_POST[proOptions][subscribeExit][b_c_color])) $this->_options['proOptions']['subscribeExit']['b_c_color'] = sanitize_text_field($_POST[proOptions][subscribeExit][b_c_color]); else $this->_options['proOptions']['subscribeExit']['b_c_color'] = '';
				}
				
				//contactUs
				if($_POST[proOptions][contactUs]){
					if((int)$_POST[proOptions][contactUs][disableMainPage] == 1) $this->_options['proOptions']['contactUs']['disableMainPage'] = 1; else $this->_options['proOptions']['contactUs']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][contactUs][whitelabel] == 1) $this->_options['proOptions']['contactUs']['whitelabel'] = 1; else $this->_options['proOptions']['contactUs']['whitelabel'] = 0;
					if(trim($_POST[proOptions][contactUs][disableExeptPageMask][0])) $this->_options['proOptions']['contactUs']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][contactUs][disableExeptPageMask][0]); else $this->_options['proOptions']['contactUs']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][contactUs][disableExeptPageMask][1])) $this->_options['proOptions']['contactUs']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][contactUs][disableExeptPageMask][1]); else $this->_options['proOptions']['contactUs']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][contactUs][disableExeptPageMask][2])) $this->_options['proOptions']['contactUs']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][contactUs][disableExeptPageMask][2]); else $this->_options['proOptions']['contactUs']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][contactUs][disableExeptPageMask][3])) $this->_options['proOptions']['contactUs']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][contactUs][disableExeptPageMask][3]); else $this->_options['proOptions']['contactUs']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][contactUs][disableExeptPageMask][4])) $this->_options['proOptions']['contactUs']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][contactUs][disableExeptPageMask][4]); else $this->_options['proOptions']['contactUs']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][contactUs][animation])) $this->_options['proOptions']['contactUs']['animation'] = sanitize_text_field($_POST[proOptions][contactUs][animation]); else $this->_options['proOptions']['contactUs']['animation'] = '';
					if(trim($_POST[proOptions][contactUs][hover_animation])) $this->_options['proOptions']['contactUs']['hover_animation'] = sanitize_text_field($_POST[proOptions][contactUs][hover_animation]); else $this->_options['proOptions']['contactUs']['hover_animation'] = '';					
					
					if(trim($_POST[proOptions][contactUs][font])) $this->_options['proOptions']['contactUs']['font'] = sanitize_text_field($_POST[proOptions][contactUs][font]); else $this->_options['proOptions']['contactUs']['font'] = '';
					if(trim($_POST[proOptions][contactUs][head_font])) $this->_options['proOptions']['contactUs']['head_font'] = sanitize_text_field($_POST[proOptions][contactUs][head_font]); else $this->_options['proOptions']['contactUs']['head_font'] = '';
					if(trim($_POST[proOptions][contactUs][head_color])) $this->_options['proOptions']['contactUs']['head_color'] = sanitize_text_field($_POST[proOptions][contactUs][head_color]); else $this->_options['proOptions']['contactUs']['head_color'] = '';
					if(trim($_POST[proOptions][contactUs][head_size])) $this->_options['proOptions']['contactUs']['head_size'] = sanitize_text_field($_POST[proOptions][contactUs][head_size]); else $this->_options['proOptions']['contactUs']['head_size'] = '';
					if(trim($_POST[proOptions][contactUs][text_size])) $this->_options['proOptions']['contactUs']['text_size'] = sanitize_text_field($_POST[proOptions][contactUs][text_size]); else $this->_options['proOptions']['contactUs']['text_size'] = '';					
					if(trim($_POST[proOptions][contactUs][text_color])) $this->_options['proOptions']['contactUs']['text_color'] = sanitize_text_field($_POST[proOptions][contactUs][text_color]); else $this->_options['proOptions']['contactUs']['text_color'] = '';
					if(trim($_POST[proOptions][contactUs][b_radius])) $this->_options['proOptions']['contactUs']['b_radius'] = sanitize_text_field($_POST[proOptions][contactUs][b_radius]); else $this->_options['proOptions']['contactUs']['b_radius'] = '';
					if(trim($_POST[proOptions][contactUs][b_color])) $this->_options['proOptions']['contactUs']['b_color'] = sanitize_text_field($_POST[proOptions][contactUs][b_color]); else $this->_options['proOptions']['contactUs']['b_color'] = '';
					if(trim($_POST[proOptions][contactUs][b_opacity])) $this->_options['proOptions']['contactUs']['b_opacity'] = sanitize_text_field($_POST[proOptions][contactUs][b_opacity]); else $this->_options['proOptions']['contactUs']['b_opacity'] = '';
					if(trim($_POST[proOptions][contactUs][b_style])) $this->_options['proOptions']['contactUs']['b_style'] = sanitize_text_field($_POST[proOptions][contactUs][b_style]); else $this->_options['proOptions']['contactUs']['b_style'] = '';
					if(trim($_POST[proOptions][contactUs][b_shadow])) $this->_options['proOptions']['contactUs']['b_shadow'] = sanitize_text_field($_POST[proOptions][contactUs][b_shadow]); else $this->_options['proOptions']['contactUs']['b_shadow'] = '';
					if(trim($_POST[proOptions][contactUs][background_image])) $this->_options['proOptions']['contactUs']['background_image'] = sanitize_text_field($_POST[proOptions][contactUs][background_image]); else $this->_options['proOptions']['contactUs']['background_image'] = '';					
					if(trim($_POST[proOptions][contactUs][b_width])) $this->_options['proOptions']['contactUs']['b_width'] = sanitize_text_field($_POST[proOptions][contactUs][b_width]); else $this->_options['proOptions']['contactUs']['b_width'] = '';
					if(trim($_POST[proOptions][contactUs][b_c_color])) $this->_options['proOptions']['contactUs']['b_c_color'] = sanitize_text_field($_POST[proOptions][contactUs][b_c_color]); else $this->_options['proOptions']['contactUs']['b_c_color'] = '';
				}
				
				//callMe
				if($_POST[proOptions][callMe]){
					if((int)$_POST[proOptions][callMe][disableMainPage] == 1) $this->_options['proOptions']['callMe']['disableMainPage'] = 1; else $this->_options['proOptions']['callMe']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][callMe][whitelabel] == 1) $this->_options['proOptions']['callMe']['whitelabel'] = 1; else $this->_options['proOptions']['callMe']['whitelabel'] = 0;
					if(trim($_POST[proOptions][callMe][disableExeptPageMask][0])) $this->_options['proOptions']['callMe']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][callMe][disableExeptPageMask][0]); else $this->_options['proOptions']['callMe']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][callMe][disableExeptPageMask][1])) $this->_options['proOptions']['callMe']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][callMe][disableExeptPageMask][1]); else $this->_options['proOptions']['callMe']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][callMe][disableExeptPageMask][2])) $this->_options['proOptions']['callMe']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][callMe][disableExeptPageMask][2]); else $this->_options['proOptions']['callMe']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][callMe][disableExeptPageMask][3])) $this->_options['proOptions']['callMe']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][callMe][disableExeptPageMask][3]); else $this->_options['proOptions']['callMe']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][callMe][disableExeptPageMask][4])) $this->_options['proOptions']['callMe']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][callMe][disableExeptPageMask][4]); else $this->_options['proOptions']['callMe']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][callMe][animation])) $this->_options['proOptions']['callMe']['animation'] = sanitize_text_field($_POST[proOptions][callMe][animation]); else $this->_options['proOptions']['callMe']['animation'] = '';
					if(trim($_POST[proOptions][callMe][hover_animation])) $this->_options['proOptions']['callMe']['hover_animation'] = sanitize_text_field($_POST[proOptions][callMe][hover_animation]); else $this->_options['proOptions']['callMe']['hover_animation'] = '';					
					
					if(trim($_POST[proOptions][callMe][font])) $this->_options['proOptions']['callMe']['font'] = sanitize_text_field($_POST[proOptions][callMe][font]); else $this->_options['proOptions']['callMe']['font'] = '';
					if(trim($_POST[proOptions][callMe][head_font])) $this->_options['proOptions']['callMe']['head_font'] = sanitize_text_field($_POST[proOptions][callMe][head_font]); else $this->_options['proOptions']['callMe']['head_font'] = '';
					if(trim($_POST[proOptions][callMe][head_color])) $this->_options['proOptions']['callMe']['head_color'] = sanitize_text_field($_POST[proOptions][callMe][head_color]); else $this->_options['proOptions']['callMe']['head_color'] = '';
					if(trim($_POST[proOptions][callMe][head_size])) $this->_options['proOptions']['callMe']['head_size'] = sanitize_text_field($_POST[proOptions][callMe][head_size]); else $this->_options['proOptions']['callMe']['head_size'] = '';
					if(trim($_POST[proOptions][callMe][text_size])) $this->_options['proOptions']['callMe']['text_size'] = sanitize_text_field($_POST[proOptions][callMe][text_size]); else $this->_options['proOptions']['callMe']['text_size'] = '';					
					if(trim($_POST[proOptions][callMe][text_color])) $this->_options['proOptions']['callMe']['text_color'] = sanitize_text_field($_POST[proOptions][callMe][text_color]); else $this->_options['proOptions']['callMe']['text_color'] = '';
					if(trim($_POST[proOptions][callMe][b_radius])) $this->_options['proOptions']['callMe']['b_radius'] = sanitize_text_field($_POST[proOptions][callMe][b_radius]); else $this->_options['proOptions']['callMe']['b_radius'] = '';
					if(trim($_POST[proOptions][callMe][b_color])) $this->_options['proOptions']['callMe']['b_color'] = sanitize_text_field($_POST[proOptions][callMe][b_color]); else $this->_options['proOptions']['callMe']['b_color'] = '';
					if(trim($_POST[proOptions][callMe][b_opacity])) $this->_options['proOptions']['callMe']['b_opacity'] = sanitize_text_field($_POST[proOptions][callMe][b_opacity]); else $this->_options['proOptions']['callMe']['b_opacity'] = '';
					if(trim($_POST[proOptions][callMe][b_style])) $this->_options['proOptions']['callMe']['b_style'] = sanitize_text_field($_POST[proOptions][callMe][b_style]); else $this->_options['proOptions']['callMe']['b_style'] = '';
					if(trim($_POST[proOptions][callMe][b_shadow])) $this->_options['proOptions']['callMe']['b_shadow'] = sanitize_text_field($_POST[proOptions][callMe][b_shadow]); else $this->_options['proOptions']['callMe']['b_shadow'] = '';
					if(trim($_POST[proOptions][callMe][background_image])) $this->_options['proOptions']['callMe']['background_image'] = sanitize_text_field($_POST[proOptions][callMe][background_image]); else $this->_options['proOptions']['callMe']['background_image'] = '';										
					if(trim($_POST[proOptions][callMe][b_width])) $this->_options['proOptions']['callMe']['b_width'] = sanitize_text_field($_POST[proOptions][callMe][b_width]); else $this->_options['proOptions']['callMe']['b_width'] = '';
					if(trim($_POST[proOptions][callMe][b_c_color])) $this->_options['proOptions']['callMe']['b_c_color'] = sanitize_text_field($_POST[proOptions][callMe][b_c_color]); else $this->_options['proOptions']['callMe']['b_c_color'] = '';
				}
				
				//thankPopup
				if($_POST[proOptions][thankPopup]){
					if((int)$_POST[proOptions][thankPopup][disableMainPage] == 1) $this->_options['proOptions']['thankPopup']['disableMainPage'] = 1; else $this->_options['proOptions']['thankPopup']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][thankPopup][whitelabel] == 1) $this->_options['proOptions']['thankPopup']['whitelabel'] = 1; else $this->_options['proOptions']['thankPopup']['whitelabel'] = 0;					
					
					if(trim($_POST[proOptions][thankPopup][animation])) $this->_options['proOptions']['thankPopup']['animation'] = sanitize_text_field($_POST[proOptions][thankPopup][animation]); else $this->_options['proOptions']['thankPopup']['animation'] = '';
					if(trim($_POST[proOptions][thankPopup][hover_animation])) $this->_options['proOptions']['thankPopup']['hover_animation'] = sanitize_text_field($_POST[proOptions][thankPopup][hover_animation]); else $this->_options['proOptions']['thankPopup']['hover_animation'] = '';					
					
					if(trim($_POST[proOptions][thankPopup][font])) $this->_options['proOptions']['thankPopup']['font'] = sanitize_text_field($_POST[proOptions][thankPopup][font]); else $this->_options['proOptions']['thankPopup']['font'] = '';
					if(trim($_POST[proOptions][thankPopup][head_font])) $this->_options['proOptions']['thankPopup']['head_font'] = sanitize_text_field($_POST[proOptions][thankPopup][head_font]); else $this->_options['proOptions']['thankPopup']['head_font'] = '';
					if(trim($_POST[proOptions][thankPopup][head_color])) $this->_options['proOptions']['thankPopup']['head_color'] = sanitize_text_field($_POST[proOptions][thankPopup][head_color]); else $this->_options['proOptions']['thankPopup']['head_color'] = '';
					if(trim($_POST[proOptions][thankPopup][head_size])) $this->_options['proOptions']['thankPopup']['head_size'] = sanitize_text_field($_POST[proOptions][thankPopup][head_size]); else $this->_options['proOptions']['thankPopup']['head_size'] = '';
					if(trim($_POST[proOptions][thankPopup][text_size])) $this->_options['proOptions']['thankPopup']['text_size'] = sanitize_text_field($_POST[proOptions][thankPopup][text_size]); else $this->_options['proOptions']['thankPopup']['text_size'] = '';					
					if(trim($_POST[proOptions][thankPopup][text_color])) $this->_options['proOptions']['thankPopup']['text_color'] = sanitize_text_field($_POST[proOptions][thankPopup][text_color]); else $this->_options['proOptions']['thankPopup']['text_color'] = '';
					if(trim($_POST[proOptions][thankPopup][b_radius])) $this->_options['proOptions']['thankPopup']['b_radius'] = sanitize_text_field($_POST[proOptions][thankPopup][b_radius]); else $this->_options['proOptions']['thankPopup']['b_radius'] = '';
					if(trim($_POST[proOptions][thankPopup][b_color])) $this->_options['proOptions']['thankPopup']['b_color'] = sanitize_text_field($_POST[proOptions][thankPopup][b_color]); else $this->_options['proOptions']['thankPopup']['b_color'] = '';
					if(trim($_POST[proOptions][thankPopup][b_opacity])) $this->_options['proOptions']['thankPopup']['b_opacity'] = sanitize_text_field($_POST[proOptions][thankPopup][b_opacity]); else $this->_options['proOptions']['thankPopup']['b_opacity'] = '';
					if(trim($_POST[proOptions][thankPopup][b_style])) $this->_options['proOptions']['thankPopup']['b_style'] = sanitize_text_field($_POST[proOptions][thankPopup][b_style]); else $this->_options['proOptions']['thankPopup']['b_style'] = '';
					if(trim($_POST[proOptions][thankPopup][b_shadow])) $this->_options['proOptions']['thankPopup']['b_shadow'] = sanitize_text_field($_POST[proOptions][thankPopup][b_shadow]); else $this->_options['proOptions']['thankPopup']['b_shadow'] = '';
					if(trim($_POST[proOptions][thankPopup][background_image])) $this->_options['proOptions']['thankPopup']['background_image'] = sanitize_text_field($_POST[proOptions][thankPopup][background_image]); else $this->_options['proOptions']['thankPopup']['background_image'] = '';										
					if(trim($_POST[proOptions][thankPopup][b_width])) $this->_options['proOptions']['thankPopup']['b_width'] = sanitize_text_field($_POST[proOptions][thankPopup][b_width]); else $this->_options['proOptions']['thankPopup']['b_width'] = '';
					if(trim($_POST[proOptions][thankPopup][b_c_color])) $this->_options['proOptions']['thankPopup']['b_c_color'] = sanitize_text_field($_POST[proOptions][thankPopup][b_c_color]); else $this->_options['proOptions']['thankPopup']['b_c_color'] = '';
				}
				
				//follow
				if($_POST[proOptions][follow]){
					if((int)$_POST[proOptions][follow][disableMainPage] == 1) $this->_options['proOptions']['follow']['disableMainPage'] = 1; else $this->_options['proOptions']['follow']['disableMainPage'] = 0;
					if((int)$_POST[proOptions][follow][whitelabel] == 1) $this->_options['proOptions']['follow']['whitelabel'] = 1; else $this->_options['proOptions']['follow']['whitelabel'] = 0;
					if(trim($_POST[proOptions][follow][disableExeptPageMask][0])) $this->_options['proOptions']['follow']['disableExeptPageMask'][0] = sanitize_text_field($_POST[proOptions][follow][disableExeptPageMask][0]); else $this->_options['proOptions']['follow']['disableExeptPageMask'][0] = '';
					if(trim($_POST[proOptions][follow][disableExeptPageMask][1])) $this->_options['proOptions']['follow']['disableExeptPageMask'][1] = sanitize_text_field($_POST[proOptions][follow][disableExeptPageMask][1]); else $this->_options['proOptions']['follow']['disableExeptPageMask'][1] = '';
					if(trim($_POST[proOptions][follow][disableExeptPageMask][2])) $this->_options['proOptions']['follow']['disableExeptPageMask'][2] = sanitize_text_field($_POST[proOptions][follow][disableExeptPageMask][2]); else $this->_options['proOptions']['follow']['disableExeptPageMask'][2] = '';
					if(trim($_POST[proOptions][follow][disableExeptPageMask][3])) $this->_options['proOptions']['follow']['disableExeptPageMask'][3] = sanitize_text_field($_POST[proOptions][follow][disableExeptPageMask][3]); else $this->_options['proOptions']['follow']['disableExeptPageMask'][3] = '';
					if(trim($_POST[proOptions][follow][disableExeptPageMask][4])) $this->_options['proOptions']['follow']['disableExeptPageMask'][4] = sanitize_text_field($_POST[proOptions][follow][disableExeptPageMask][4]); else $this->_options['proOptions']['follow']['disableExeptPageMask'][4] = '';
					
					if(trim($_POST[proOptions][follow][animation])) $this->_options['proOptions']['follow']['animation'] = sanitize_text_field($_POST[proOptions][follow][animation]); else $this->_options['proOptions']['follow']['animation'] = '';
					if(trim($_POST[proOptions][follow][hover_animation])) $this->_options['proOptions']['follow']['hover_animation'] = sanitize_text_field($_POST[proOptions][follow][hover_animation]); else $this->_options['proOptions']['follow']['hover_animation'] = '';					
					
					if(trim($_POST[proOptions][follow][font])) $this->_options['proOptions']['follow']['font'] = sanitize_text_field($_POST[proOptions][follow][font]); else $this->_options['proOptions']['follow']['font'] = '';
					if(trim($_POST[proOptions][follow][head_font])) $this->_options['proOptions']['follow']['head_font'] = sanitize_text_field($_POST[proOptions][follow][head_font]); else $this->_options['proOptions']['follow']['head_font'] = '';
					if(trim($_POST[proOptions][follow][head_color])) $this->_options['proOptions']['follow']['head_color'] = sanitize_text_field($_POST[proOptions][follow][head_color]); else $this->_options['proOptions']['follow']['head_color'] = '';
					if(trim($_POST[proOptions][follow][head_size])) $this->_options['proOptions']['follow']['head_size'] = sanitize_text_field($_POST[proOptions][follow][head_size]); else $this->_options['proOptions']['follow']['head_size'] = '';
					if(trim($_POST[proOptions][follow][text_size])) $this->_options['proOptions']['follow']['text_size'] = sanitize_text_field($_POST[proOptions][follow][text_size]); else $this->_options['proOptions']['follow']['text_size'] = '';					
					if(trim($_POST[proOptions][follow][text_color])) $this->_options['proOptions']['follow']['text_color'] = sanitize_text_field($_POST[proOptions][follow][text_color]); else $this->_options['proOptions']['follow']['text_color'] = '';
					if(trim($_POST[proOptions][follow][b_radius])) $this->_options['proOptions']['follow']['b_radius'] = sanitize_text_field($_POST[proOptions][follow][b_radius]); else $this->_options['proOptions']['follow']['b_radius'] = '';
					if(trim($_POST[proOptions][follow][b_color])) $this->_options['proOptions']['follow']['b_color'] = sanitize_text_field($_POST[proOptions][follow][b_color]); else $this->_options['proOptions']['follow']['b_color'] = '';
					if(trim($_POST[proOptions][follow][b_opacity])) $this->_options['proOptions']['follow']['b_opacity'] = sanitize_text_field($_POST[proOptions][follow][b_opacity]); else $this->_options['proOptions']['follow']['b_opacity'] = '';
					if(trim($_POST[proOptions][follow][b_style])) $this->_options['proOptions']['follow']['b_style'] = sanitize_text_field($_POST[proOptions][follow][b_style]); else $this->_options['proOptions']['follow']['b_style'] = '';
					if(trim($_POST[proOptions][follow][b_shadow])) $this->_options['proOptions']['follow']['b_shadow'] = sanitize_text_field($_POST[proOptions][follow][b_shadow]); else $this->_options['proOptions']['follow']['b_shadow'] = '';
					if(trim($_POST[proOptions][follow][background_image])) $this->_options['proOptions']['follow']['background_image'] = sanitize_text_field($_POST[proOptions][follow][background_image]); else $this->_options['proOptions']['follow']['background_image'] = '';					
					if(trim($_POST[proOptions][follow][b_width])) $this->_options['proOptions']['follow']['b_width'] = sanitize_text_field($_POST[proOptions][follow][b_width]); else $this->_options['proOptions']['follow']['b_width'] = '';
					if(trim($_POST[proOptions][follow][b_c_color])) $this->_options['proOptions']['follow']['b_c_color'] = sanitize_text_field($_POST[proOptions][follow][b_c_color]); else $this->_options['proOptions']['follow']['b_c_color'] = '';
				}
			}
			
			update_option('profitquery', $this->_options);
			
			echo '
			<div id="successPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; text-align: center; z-index: 500000;  position: fixed;  background-color: rgb(72, 232, 143);  right: 0;  width: 20%;  bottom: 12px;">
					<p style="color: white; font-size: 16px; font-family: arial; padding: 0px; margin: 0px;">Data successfully changed</p>
			</div>
			<script>
				setTimeout(function(){document.getElementById("successPQBlock").style.display="none";}, 5000);
				</script>
			';
		}
		
		
		
		/*Check Pro Version Options*/
		$getProCategoryArray = $this->checkProOptions();
		
		/*Check for dublicate sharingSideBar socnet_with_pos*/
		if(isset($this->_options['sharingSideBar']['socnet_with_pos'])){
			$tempArray = $socnet_with_pos_error = array();
			$flagAllEmpty = true;
			foreach((array)$this->_options['sharingSideBar']['socnet_with_pos'] as $k => $v){
				if(trim($v)) {
					$flagAllEmpty = false;
				}
				if(!$tempArray[$v]){
					$tempArray[$v] = 1;
				} else {					
					if(trim($v)){
						$socnet_with_pos_error[$v] = 1;
					}
				}
			}
			if($socnet_with_pos_error){
				echo '
					<div id="dublicateErrorPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; text-align: center; z-index: 500000;  position: fixed;  background-color: rgb(255, 107, 107);  right: 0;  width: 20%;  bottom: 12px;">
						<p style="color: white; font-size: 15px; font-family: arial; padding: 5px 25px;margin: 0px;">Sharing Sidebar. Provider dublicate detected<a href="javascript:void(0)" onclick="document.getElementById(\'dublicateErrorPQBlock\').style.display=\'none\';">[X]</a></p>
					</div>
					';
			}			
			if($flagAllEmpty && (int)$this->_options['sharingSideBar'][disabled] == 0){
				echo '
					<div id="SharingSidebarNoProviderPQBlock" style="display: block;width: auto; margin: 0 15px 0 5px; text-align: center; z-index: 500000;  position: fixed;  background-color: rgb(255, 107, 107);  right: 0;  width: 20%;  bottom: 12px;">
						<p style="color: white; font-size: 15px; font-family: arial; padding: 5px 25px; margin: 0px;">Sharing Sidebar enable, but no one social network selected.<a href="javascript:void(0)" onclick="document.getElementById(\'SharingSidebarNoProviderPQBlock\').style.display=\'none\';">[X]</a></p>
					</div>
					';					
			}			
		}
		
		if(profitquery_is_subscribe_enabled($this->_options)){			
			if(trim($this->_options[subscribeProvider]) == '' || (int)$this->_options['subscribeProviderOption'][$this->_options['subscribeProvider']][is_error] == 1){
				echo '
					<div id="subscribeProviderError" style="display: block;width: auto; margin: 0 15px 0 5px; text-align: center; z-index: 500000;  position: fixed;  background-color: rgb(255, 107, 107);  right: 0;  width: 20%;  bottom: 180px;">
						<p style="color: white; font-size: 15px; font-family: arial; padding: 5px 25px; margin: 0px;">For complete install Subscribe tools please copy/paste correct sign up form from selected provider <a href="javascript:void(0)" onclick="document.getElementById(\'subscribeProviderError\').style.display=\'none\';">[X]</a></p>
					</div>
				';
			}
		}
			
		if(profitquery_is_follow_enabled_and_not_setup($this->_options)){
			echo '
					<div id="FollowProviderError" style="display: block;width: auto; margin: 0 15px 0 5px; text-align: center; z-index: 500000;  position: fixed;  background-color: rgb(255, 107, 107);  right: 0;  width: 20%;  bottom: 102px;">
						<p style="color: white; ffont-size: 15px; font-family: arial;  padding: 5px 25px; margin: 0px;">For complete install follow popup after proceed, please set up any follow address <a href="javascript:void(0)" onclick="document.getElementById(\'FollowProviderError\').style.display=\'none\';">[X]</a></p>
					</div>
				';
		}
		
		?>
<script>
function changePopupImg(img, custom_photo_block_id){
	try{							
		if(img == 'custom'){												
			document.getElementById(custom_photo_block_id).style.display = 'block';
		} else {				
			document.getElementById(custom_photo_block_id).style.display = 'none';
		}
	}catch(err){};		
}
</script>

<div id="profitquery">
	<div class="pq1">
		<a href="http://profitquery.com/" target="_blank"> <img src="<?php echo plugins_url('i/profitquery.png', __FILE__);?>" /></a>				
		<p class="pq_default selected">Select tools</p>
		<p class="share">Sharing Sidebar</p>
		<p class="imagesharer">Image Sharer</p>
		<p class="marketing">Subscribe Bar</p>
		<p class="exit">Exit Intent Popup</p>
		<p class="collect">Floating Popup</p>
		<p class="contact">Contact Form</p>
		<p class="callme">Call Me Popup</p>
		<p class="follow">Follow Popup</p>
		<p class="thankyou">Thankyou Popup</p>
		<a href="javascript:void(0)" onclick="document.getElementById('SProviderSettings').style.display='block';">Email settings</a>
		<p class="pq_tooltip1">&larr; Subscribe Provider Settings Here</p>
		<p class="pq_tooltip2">&larr; Mail Settings Here</p>
		<!--span style="position: absolute;top: 6px;right: 300px;" onclick="document.getElementById('SProviderSettings').style.display='block';">Subscribe Provider Stngs</span>
		<span class="free">FREE</span-->
		<a class="pq_adds" href="javascript:void(0)" onclick="document.getElementById('PQ_lang').style.display='block';">Languages</a>
		<input class="pro" type="button" value="Try Pro options for free" onclick="document.getElementById('Get_Pro').style.display='block';">
		<!--span class="pro">PRO 23$</span-->
	</div>

	<div class="pq2">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post" id="pq">
		<input type="hidden" name="action" value="edit">
						<label class="choise home">
							<input type="radio" name="choise" class="choise1" checked="checked" value="1">
							<div><img src="<?php echo plugins_url('i/32.png', __FILE__);?>" /></div>
						</label><label class="choise set2">
							<input type="radio" name="choise" class="choise2" value="2">
							<div><img src="<?php echo plugins_url('i/33.png', __FILE__);?>" /></div>
						</label><label class="choise set3">
							<input type="radio" name="choise" class="choise3" value="3">
							<div><img src="<?php echo plugins_url('i/34.png', __FILE__);?>" /></div>
						</label><label class="choise set4">
							<input type="radio" name="choise" class="choise4" value="4">
							<div><img src="<?php echo plugins_url('i/35.png', __FILE__);?>" /></div>							
						</label>
						<div class="pq_clear"></div>
					<div class="f_wrapper pq_li1 selected">
					<div><h2>Main Page</h2></div>
						<label>
							<input type="radio" name="task" value="1" class="share_tools">
							<input type="checkbox" name="sharingSideBar[enabled]" <?php if((int)$this->_options[sharingSideBar][disabled] == 0) echo 'checked';?> class="share_checked">
							<div><img src="<?php echo plugins_url('i/2.png', __FILE__);?>" class="pq_card" /><p>Sharing Sidebar</p><input type="button" class="task activate share_tools_activate" value="Activate"><input type="button" class="task settings share_tools_activate" value="settings"><input type="button" class="task desabled share_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						<label>
							<input type="radio" name="task" value="6" class="imagesharer_tools">
							<input type="checkbox" name="imageSharer[enabled]" <?php if((int)$this->_options[imageSharer][disabled] == 0) echo 'checked';?> class="imagesharer_checked">
							<div><img src="<?php echo plugins_url('i/15.png', __FILE__);?>" class="pq_card" /><p>Image Sharer</p><input type="button" class="task activate imagesharer_tools_activate" value="Activate"><input type="button" class="task settings imagesharer_tools_activate" value="settings"><input type="button" class="task desabled imagesharer_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						<label>
							<input type="radio" name="task" value="12" class="marketing_tools">
							<input type="checkbox" name="subscribeBar[enabled]" <?php if((int)$this->_options[subscribeBar][disabled] == 0) echo 'checked';?> class="marketing_checked">
							<div><img src="<?php echo plugins_url('i/1.png', __FILE__);?>" class="pq_card" /><p>Subscribe Bar</p><input type="button" class="task activate marketing_tools_activate" value="Activate"><input type="button" class="task settings marketing_tools_activate" value="settings"><input type="button" class="task desabled marketing_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						<label>
							<input type="radio" name="task" value="6" class="exit_tools">
							<input type="checkbox" name="subscribeExit[enabled]" <?php if((int)$this->_options[subscribeExit][disabled] == 0) echo 'checked';?> class="exit_checked">
							<div><img src="<?php echo plugins_url('i/9.png', __FILE__);?>" class="pq_card" /><p>Exit Intent Popup</p><input type="button" class="task activate exit_tools_activate" value="Activate"><input type="button" class="task settings exit_tools_activate" value="settings"><input type="button" class="task desabled exit_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						<!--label>
							<input type="radio" name="task" value="1" class="collect_tools">
							<input type="checkbox" name="imageSharer[enabled]" <?php if((int)$this->_options[imageSharer][disabled] == 0) echo 'checked';?> class="collect_checked">
							<div><img src="<?php echo plugins_url('i/4.png', __FILE__);?>" class="pq_card" /><p>Floating Popup</p><input type="button" class="task activate collect_tools_activate" value="Activate"><input type="button" class="task settings collect_tools_activate" value="settings"><input type="button" class="task desabled collect_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label-->
						<label>
							<input type="radio" name="task" value="7" class="contact_tools">
							<input type="checkbox" name="contactUs[enabled]" <?php if((int)$this->_options[contactUs][disabled] == 0) echo 'checked';?> class="contact_checked">
							<div><img src="<?php echo plugins_url('i/7.png', __FILE__);?>" class="pq_card" /><p>Contact Form</p><input type="button" class="task activate contact_tools_activate" value="Activate"><input type="button" class="task settings contact_tools_activate" value="settings"><input type="button" class="task desabled contact_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>							
						</label>
						<label>
							<input type="radio" name="task" value="5" class="callme_tools">
							<input type="checkbox" name="callMe[enabled]" <?php if((int)$this->_options[callMe][disabled] == 0) echo 'checked';?> class="callme_checked">
							<div><img src="<?php echo plugins_url('i/8.png', __FILE__);?>" class="pq_card" /><p>Call Me Now</p><input type="button" class="task activate callme_tools_activate" value="Activate"><input type="button" class="task settings callme_tools_activate" value="settings"><input type="button" class="task desabled callme_tools_desabled" value="Disable"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						<label>
							<input type="radio" name="task" value="2" class="follow_tools">
							<input type="checkbox" name="follow[enabled]" checked class="follow_checked">
							<div><img src="<?php echo plugins_url('i/3.png', __FILE__);?>" class="pq_card" /><p>Follow Popup</p><input type="button" class="task activate follow_tools_activate" value="Activate"><input type="button" class="task settings follow_tools_activate" value="settings"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						
						<label>
							<input type="radio" name="task" value="13" class="thankyou_tools">
							<input type="checkbox" name="thankPopup[enabled]" checked class="thankyou_checked">
							<div><img src="<?php echo plugins_url('i/10.png', __FILE__);?>" class="pq_card" /><p>Thankyou Popup</p><input type="button" class="task activate thankyou_tools_activate" value="Activate"><input type="button" class="task settings thankyou_tools_activate" value="settings"><img src="<?php echo plugins_url('i/36.png', __FILE__);?>" class="active" /><img src="<?php echo plugins_url('i/37.png', __FILE__);?>" class="pro" /></div>
						</label>
						
						<label>
							<input type="radio" name="task" value="13" class="">
							<input type="checkbox" name="" class="">
							<div><img src="<?php echo plugins_url('i/57.png', __FILE__);?>" class="pq_card" /><p>Custom Tool</p><input type="button" class="task activate any_tools_activate" value="More info"></div>
						</label>
						
					</div>
						
					<a class="ganalytics" href="javascript:void(0)" onclick="document.getElementById('GA').style.display='block';">
						<img src="<?php echo plugins_url('i/ga.png', __FILE__);?>" class="pq_card" />	
					Google Analytics - <?php if((int)$this->_options[additionalOptions][enableGA] == 1) echo 'on'; else echo 'off';?></a>
						
					<div class="f_wrapper pq_li3">
					<h2>Settings</h2>
					
						<div class="settings collect">
							<div class="floating selected">
								<h3>Text</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="" value="" placeholder="">
									
								</label>
								<label>
								<p>Text</p>
									<input class="text" type="text" name="" value="" placeholder="">
									
								</label>
								<label>
								<p>Input Placeholder</p>
									<input class="text" type="text" name="" value="" placeholder="">
									
								</label>
								<label>
								<p>Input Placeholder</p>
									<input class="text" type="text" name="" value="" placeholder="">
									
								</label>
								<label>
								<p>Textarea Placeholder</p>
									<input class="text" type="text" name="" value="" placeholder="">
									
								</label>
								<h3>Button</h3>
								<label>
								<p>Button Title</p>
									<input class="text" type="text" name="" value="" placeholder="">
									
								</label>
								<label>
									<select>
										<option name="">Close</option>
										<option name="">Send Email</option>
										<option name="">Mailchimp</option>
										<option name="1">Aweber</option>
									</select>
								</label>
								<label><p>Paste code</p><a href="http://profitquery.com/mailchimp.html" target="_blank">How to get code</a>
									<textarea rows="2"></textarea>
								</label>
								<label>
								<p>Send Email to</p>
									<input class="text" type="text" name="" value="" placeholder="">
								</label>
							</div>
							
						</div>
						
						<div class="settings share selected">
							<div class="sidebar selected">
							<h3>SHOW SERVICES</h3>
								<label class="sm nn n01">							
									<select name="sharingSideBar[socnet_with_pos][0]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][0]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][0] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n02">							
									<select name="sharingSideBar[socnet_with_pos][1]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][1]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][1] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n03">							
									<select name="sharingSideBar[socnet_with_pos][2]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][2]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][2] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n04">							
									<select name="sharingSideBar[socnet_with_pos][3]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][3]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][3] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n05">							
									<select name="sharingSideBar[socnet_with_pos][4]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][4]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][4] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n06">							
									<select name="sharingSideBar[socnet_with_pos][5]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][5]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][5] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<input type="button" class="pq_li_button addservices7-12" value="more services">
								<label class="sm nn n07">							
									<select name="sharingSideBar[socnet_with_pos][6]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][6]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][6] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n08">							
									<select name="sharingSideBar[socnet_with_pos][7]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][7]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][7] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n09">							
									<select name="sharingSideBar[socnet_with_pos][8]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][8]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][8] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n10">							
									<select name="sharingSideBar[socnet_with_pos][9]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][9]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][9] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n11">							
									<select name="sharingSideBar[socnet_with_pos][10]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][10]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][10] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n12">							
									<select name="sharingSideBar[socnet_with_pos][11]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][11]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][11] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<input type="button" class="pq_li_button addservices13-18" value="more services">
								<label class="sm nn n13">							
									<select name="sharingSideBar[socnet_with_pos][12]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][12]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][12] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n14">							
									<select name="sharingSideBar[socnet_with_pos][13]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][13]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][13] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n15">							
									<select name="sharingSideBar[socnet_with_pos][14]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][14]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][14] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n16">							
									<select name="sharingSideBar[socnet_with_pos][15]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][15]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][15] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n17">							
									<select name="sharingSideBar[socnet_with_pos][16]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][16]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][16] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n18">							
									<select name="sharingSideBar[socnet_with_pos][17]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][17]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][17] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<input type="button" class="pq_li_button addservices19-20" value="more services">
								<label class="sm nn n19">							
									<select name="sharingSideBar[socnet_with_pos][18]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][18]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][18] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<label class="sm nn n20">							
									<select name="sharingSideBar[socnet_with_pos][19]" <?php if($socnet_with_pos_error[$this->_options[sharingSideBar][socnet_with_pos][19]]) echo 'class="pq_error"';?>>
										<option value="" selected>None</option>
										<option value="FB" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'FB') echo 'selected';?> >Facebook</option>
										<option value="TW" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'TW') echo 'selected';?>>Twitter</option>
										<option value="GP" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'GP') echo 'selected';?>>Google plus</option>
										<option value="RD" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'RD') echo 'selected';?>>Reddit</option>
										<option value="PI" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'PI') echo 'selected';?>>Pinterest</option>
										<option value="VK" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'VK') echo 'selected';?>>Vkontakte</option>
										<option value="OD" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'OD') echo 'selected';?>>Odnoklassniki</option>
										<option value="LJ" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'LJ') echo 'selected';?>>Live Journal</option>
										<option value="TR" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'TR') echo 'selected';?>>Tumblr</option>
										<option value="LI" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'LI') echo 'selected';?>>LinkedIn</option>
										<option value="SU" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'SU') echo 'selected';?>>StumbleUpon</option>
										<option value="DG" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'DG') echo 'selected';?>>Digg</option>
										<option value="DL" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'DL') echo 'selected';?>>Delicious</option>
										<option value="WU" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'WU') echo 'selected';?>>WhatsApp</option>
										<option value="BR" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'BR') echo 'selected';?>>Blogger</option>
										<option value="RR" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'RR') echo 'selected';?>>Renren</option>
										<option value="WB" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'WB') echo 'selected';?>>Weibo</option>
										<option value="MW" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'MW') echo 'selected';?>>My World</option>
										<option value="EN" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'EN') echo 'selected';?>>Evernote</option>
										<option value="PO" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'PO') echo 'selected';?>>Pocket</option>
										<option value="AK" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'AK') echo 'selected';?>>Kindle</option>
										<option value="FL" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'FL') echo 'selected';?>>Flipboard</option>
										<option value="Print" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'Print') echo 'selected';?>>Print</option>
										<option value="Mail" <?php if($this->_options[sharingSideBar][socnet_with_pos][19] == 'Mail') echo 'selected';?>>Email</option>
									</select>
								</label>
								<h3>Sharing Image Window</h3>
								<label>							
									<select name="sharingSideBar[galleryOption][disable]" >
										<option <?php if((int)$this->_options[sharingSideBar][galleryOption][disable] == 1) echo 'selected';?> value="1">Disabled</option>
										<option <?php if((int)$this->_options[sharingSideBar][galleryOption][disable] == 0) echo 'selected';?> value="0">Enabled</option>
									</select>
								</label>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="sharingSideBar[galleryOption][title]" value="<?php echo stripslashes($this->_options[sharingSideBar][galleryOption][title]);?>">
									
								</label>
								<label>
								<p>Button</p>
									<input class="text" type="text" name="sharingSideBar[galleryOption][buttonTitle]" value="<?php echo stripslashes($this->_options[sharingSideBar][galleryOption][buttonTitle]);?>">
									
								</label>
								<label>
								<label><p>Share Image</p>						
									<select name="sharingSideBar[galleryOption][minWidth]">
										<option value="100" <?php if((int)$this->_options[sharingSideBar][galleryOption][minWidth] == '100' || $this->_options[sharingSideBar][galleryOption][minWidth] == '') echo 'selected';?>>100 px and more</option>
										<option value="200" <?php if((int)$this->_options[sharingSideBar][galleryOption][minWidth] == '200') echo 'selected';?>>200 px and more</option>
										<option value="300" <?php if((int)$this->_options[sharingSideBar][galleryOption][minWidth] == '300') echo 'selected';?>>300 px and more</option>
										<option value="400" <?php if((int)$this->_options[sharingSideBar][galleryOption][minWidth] == '400') echo 'selected';?>>400 px and more</option>
										<option value="500" <?php if((int)$this->_options[sharingSideBar][galleryOption][minWidth] == '500') echo 'selected';?>>500 px and more</option>
										<option value="600" <?php if((int)$this->_options[sharingSideBar][galleryOption][minWidth] == '600') echo 'selected';?>>600 px and more</option>
									</select>
								</label>
								<h3>FOR MOBILE</h3>
								<p>Heading</p>
									<input class="text" type="text" name="sharingSideBar[mobile_title]" value="<?php echo stripslashes($this->_options[sharingSideBar][mobile_title]);?>">
									
								</label>
							</div>
						</div>
						<div class="settings follow">
							<div class="popup selected">
								<h3>Text</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="follow[title]" value="<?php echo stripslashes($this->_options[follow][title])?>">
									
								</label>
								<label>
								<p>Text</p>
									<textarea rows="2" type="text" name="follow[sub_title]" ><?php echo stripslashes($this->_options[follow][sub_title])?></textarea>
									
								</label>
								<label class="sm fb">
									<p>facebook.com/</p>
									<input type="text" name="follow[follow_socnet][FB]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][FB]);?>">
								</label>
								<label class="sm tw">							
									<p>twitter.com/</p>
									<input type="text" name="follow[follow_socnet][TW]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][TW]);?>">
								</label>
								<label class="sm gp">
									<p>plus.google.com/</p>								
									<input type="text" name="follow[follow_socnet][GP]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][GP]);?>">
								</label>
								<input type="button" class="pq_li_button addservices4-6" value="more services">
								<label class="sm pi f04">							
									<p>pinterest.com/</p>
									<input type="text" name="follow[follow_socnet][PI]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][PI]);?>">
								</label>
								<label class="sm vk f05">							
									<p>vk.com/</p>
									<input type="text" name="follow[follow_socnet][VK]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][VK]);?>">
								</label>
								<label class="sm od f06">
									<p>odnoklassniki.ru/</p>
									<input type="text" name="follow[follow_socnet][OD]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][OD]);?>">
								</label>
								<input type="button" class="pq_li_button addservices7-8" value="more services">
								<label class="sm ig f07">
									<p>instagram.com/</p>
									<input type="text" name="follow[follow_socnet][IG]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][IG]);?>">
								</label>
								<label class="sm rs f08">
									<p>RSS feed</p>
									<input type="text" name="follow[follow_socnet][RSS]" value="<?php echo stripslashes($this->_options[follow][follow_socnet][RSS]);?>">
								</label>																																
								
								<h3>DISPLAY AFTER</h3>
								<label><p>Sharing Sidebar</p>
								<select name="sharingSideBar[afterProceed][follow]">
									<option value="1" <?php if((int)$this->_options[sharingSideBar][afterProceed][follow] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[sharingSideBar][afterProceed][follow] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Image Sharer</p>
								<select name="imageSharer[afterProceed][follow]">
									<option value="1" <?php if((int)$this->_options[imageSharer][afterProceed][follow] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[imageSharer][afterProceed][follow] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Subscribe Bar </p>
								<select name="subscribeBar[afterProceed][follow]">
									<option value="1" <?php if((int)$this->_options[subscribeBar][afterProceed][follow] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[subscribeBar][afterProceed][follow] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Exit Intent Popup</p>
								<select name="subscribeExit[afterProceed][follow]">
									<option value="1" <?php if((int)$this->_options[subscribeExit][afterProceed][follow] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[subscribeExit][afterProceed][follow] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>								
								<label><p>Contact Form</p>
								<select name="contactUs[afterProceed][follow]">
									<option value="1" <?php if((int)$this->_options[contactUs][afterProceed][follow] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[contactUs][afterProceed][follow] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Call Me Now</p>
								<select name="callMe[afterProceed][follow]">
									<option value="1" <?php if((int)$this->_options[callMe][afterProceed][follow] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[callMe][afterProceed][follow] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<br>
							</div>
						</div>
						
						<div class="settings marketing">
							<div class="pq_bar selected">
								<h3>Text</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="subscribeBar[title]" value="<?php echo stripslashes($this->_options[subscribeBar][title]);?>">
									
								</label>
								
								<label>
								<p>Input email text</p>
									<input class="text" type="text" name="subscribeBar[inputEmailTitle]" value="<?php echo stripslashes($this->_options[subscribeBar][inputEmailTitle]);?>">
									
								</label>
								<label>
								<p>Input name text (Aweber)</p>
									<input class="text" type="text" name="subscribeBar[inputNameTitle]" value="<?php echo stripslashes($this->_options[subscribeBar][inputNameTitle]);?>">
									
								</label>
									
								
								
								<h3>For mobile</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="subscribeBar[mobile_title]" value="<?php echo stripslashes($this->_options[subscribeBar][mobile_title]);?>">
									
								</label>
								<h3>Button</h3>
								<label>
								<p>Title</p>
									<input class="text" type="text" name="subscribeBar[buttonTitle]" value="<?php echo stripslashes($this->_options[subscribeBar][buttonTitle]);?>">
									
								</label>								
							</div>
						</div>
						<div class="settings contact">
							<div class="popup selected">
								<h3>Text</h3>
								<label>
								<p>Heading</p>
									<input type="text" name="contactUs[title]" value="<?php echo stripslashes($this->_options[contactUs][title]);?>">
									
								</label>
								<label>
								<p>Text</p>
									<input type="text" name="contactUs[sub_title]" value="<?php echo stripslashes($this->_options[contactUs][sub_title]);?>">
									
								</label>
								<label>
								<p>Enter Name Text</p>
									<input class="text" type="text" name="contactUs[enter_name_text]" value="<?php echo stripslashes($this->_options[contactUs][enter_name_text]);?>">
									
								</label>
								<label>
								<p>Enter Email Text</p>
									<input class="text" type="text" name="contactUs[enter_email_text]" value="<?php echo stripslashes($this->_options[contactUs][enter_email_text]);?>">
									
								</label>
								<label>
									<p>Enter Message Text</p>
										<input class="text" type="text" name="contactUs[enter_message_text]" value="<?php echo stripslashes($this->_options[contactUs][enter_message_text]);?>">
								</label>
								
								<h3>Loader</h3>	
								<!--label>
									<select>
										<option name="4">Descktop and Mobile</option>
										<option name="4">Descktop</option>
										<option name="4">Mobile</option>
										<option name="4">Disabled</option>
									</select>
								</label-->
								<label>
									<p>Heading</p>
									<input class="text" type="text" name="contactUs[loaderText]" value="<?php echo stripslashes($this->_options[contactUs][loaderText]);?>">
								</label>
								<h3>Button</h3>
								<label>
								<p>Button Title</p>
									<input class="text" type="text" name="contactUs[buttonTitle]" value="<?php echo stripslashes($this->_options[contactUs][buttonTitle]);?>">
								</label>
							</div>
						</div>
						<div class="settings callme">
							<div class="popup selected">
								<h3>Text</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="callMe[title]" value="<?php echo stripslashes($this->_options[callMe][title])?>">
									
								</label>
								<label>
								<p>Text</p>
									<textarea rows="2" type="text" name="callMe[sub_title]"><?php echo stripslashes($this->_options[callMe][sub_title])?></textarea>
									
								</label>
								<label class="">
								<p>Enter Name Text</p>
									<input class="text" type="text" name="callMe[enter_name_text]" value="<?php echo stripslashes($this->_options[callMe][enter_name_text]);?>">
									
								</label>
								<label class="">
								<p>Enter Phone Text</p>
									<input class="text" type="text" name="callMe[enter_phone_text]" value="<?php echo stripslashes($this->_options[callMe][enter_phone_text]);?>">									
								</label>								
								
								<h3>Loader</h3>	
								<!--label>
									<select>
										<option name="4">Descktop and Mobile</option>
										<option name="4">Descktop</option>
										<option name="4">Mobile</option>
										<option name="4">Disabled</option>
									</select>
								</label-->
								<label>
									<p>Heading</p>
									<input class="text" type="text" name="callMe[loaderText]" value="<?php echo stripslashes($this->_options[callMe][loaderText]);?>">
								</label>
								<h3>Button</h3>
								<label>
								<p>Button Title</p>
									<input class="text" type="text" name="callMe[buttonTitle]" value="<?php echo stripslashes($this->_options[callMe][buttonTitle])?>">
								</label>
							</div>
						</div>
						<div class="settings exit">
							<div class="popup selected">
								
								<h3>TEXT</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="subscribeExit[title]" value="<?php echo stripslashes($this->_options[subscribeExit][title]);?>">
									
								</label>
								<label>
								<p>Text</p>
									<textarea rows="2" type="text" name="subscribeExit[sub_title]" ><?php echo stripslashes($this->_options[subscribeExit][sub_title]);?></textarea>
								
								</label>
								<label>
								<p>Input email text</p>
									<input class="text" type="text" name="subscribeExit[inputEmailTitle]" value="<?php echo stripslashes($this->_options[subscribeExit][inputEmailTitle]);?>">
								</label>
								<label>
								<p>Input name text (Aweber)</p>
									<input class="text" type="text" name="subscribeExit[inputNameTitle]" value="<?php echo stripslashes($this->_options[subscribeExit][inputNameTitle]);?>">
								</label>
							
								<label>
								<p>Button Title</p>
									<input class="text" type="text" name="subscribeExit[buttonTitle]" value="<?php echo stripslashes($this->_options[subscribeExit][buttonTitle]);?>">									
								</label>															
							</div>
							
						</div>
						<div class="settings imagesharer">
							<div class="onmedia selected">
								<h3>SHOW SERVICES </h3>
								<label class="sm">							
									<input type="checkbox" name="imageSharer[socnet][FB]" <?php if((int)$this->_options[imageSharer][socnet][FB] == 1) echo 'checked';?> />
									<div class="fb pq_checkbox"></div>
									<select name="imageSharer[socnetOption][FB][type]" class="sm">
										<option value="app" <?php if($this->_options[imageSharer][socnetOption][FB][type] == 'app') echo 'selected';?>>Use Facebook App</option>
										<option value="" <?php if($this->_options[imageSharer][socnetOption][FB][type] == '') echo 'selected';?>>Default Facebook Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][FB][type] == 'pq') echo 'selected';?>>Without Apps & OG Tags</option>
										
									</select>
								</label>
								
								<label class="sm">
									<a class="pq_question" href="http://profitquery.com/fb_app.html" target="_blank">?</a>								
									<input type="text" name="imageSharer[socnetOption][FB][app_id]" value="<?php if(stripslashes($this->_options[imageSharer][socnetOption][FB][app_id]) != '') echo stripslashes($this->_options[imageSharer][socnetOption][FB][app_id]);?>" placeholder="FaceBook APP ID"/>
								</label>
								<label class="sm">
									<input type="checkbox" name="imageSharer[socnet][TW]" <?php if((int)$this->_options[imageSharer][socnet][TW] == 1) echo 'checked';?> />
									<div class="tw pq_checkbox"></div>									
									<select name="imageSharer[socnetOption][TW][type]" class="sm">
										<option value="" <?php if($this->_options[imageSharer][socnetOption][TW][type] == '') echo 'selected';?>>Default Twitter Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][TW][type] == 'pq') echo 'selected';?>>Without Apps & OG Tags</option>
										
									</select>
								</label>
								<label class="sm">
									<input type="checkbox" />
									<div class="gp pq_checkbox"></div>
									<select class="sm" name="imageSharer[socnetOption][GP][type]" >
										<option value="" <?php if($this->_options[imageSharer][socnetOption][GP][type] == '') echo 'selected';?>>Default Google+ Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][GP][type] == 'pq') echo 'selected';?>>Without Apps & OG Tags</option>
										
									</select>
								</label>
								<label class="sm">	
									<input type="checkbox" name="imageSharer[socnet][PI]" <?php if((int)$this->_options[imageSharer][socnet][PI] == 1) echo 'checked';?> />
									<div class="pi pq_checkbox"></div>
									<select class="sm" name="imageSharer[socnetOption][PI][type]">
										<option value="" <?php if($this->_options[imageSharer][socnetOption][PI][type] == '') echo 'selected';?> >Default Pinterest Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][PI][type] == 'pq') echo 'selected';?> >Without Apps & OG Tags</option>
										
									</select>
								</label>
								<label class="sm">	
									<input type="checkbox" name="imageSharer[socnet][TR]" <?php if((int)$this->_options[imageSharer][socnet][TR] == 1) echo 'checked';?> />
									<div class="tr pq_checkbox"></div>
									<select class="sm" name="imageSharer[socnetOption][TR][type]" >
										<option value="" <?php if($this->_options[imageSharer][socnetOption][TR][type] == '') echo 'selected';?>>Default Tumbrl Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][TR][type] == 'pq') echo 'selected';?> >Without Apps & OG Tags</option>
										
									</select>
								</label>
								<label class="sm">
									<input type="checkbox" name="imageSharer[socnet][VK]" <?php if((int)$this->_options[imageSharer][socnet][VK] == 1) echo 'checked';?> />
									<div class="vk pq_checkbox"></div>
									<select class="sm" name="imageSharer[socnetOption][VK][type]" >
										<option value="" <?php if($this->_options[imageSharer][socnetOption][VK][type] == '') echo 'selected';?> >Default VKontakte Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][VK][type] == 'pq') echo 'selected';?> >Without Apps & OG Tags</option>
										
									</select>
								</label>
								<label class="sm">	
									<input type="checkbox" name="imageSharer[socnet][OD]" <?php if((int)$this->_options[imageSharer][socnet][OD] == 1) echo 'checked';?> />
									<div class="od pq_checkbox"></div>									
									<select class="sm" name="imageSharer[socnetOption][OD][type]" >
										<option value="" <?php if($this->_options[imageSharer][socnetOption][OD][type] == '') echo 'selected';?> >Default Odnoklassniki Share</option>
										<option value="pq" <?php if($this->_options[imageSharer][socnetOption][OD][type] == 'pq') echo 'selected';?> >Without Apps & OG Tags</option>
										
									</select>
								</label>
								<label class="sm">
									<input type="checkbox" name="imageSharer[socnet][WU]" <?php if((int)$this->_options[imageSharer][socnet][WU] == 1) echo 'checked';?> />
									<div class="wu pq_checkbox"></div>
									<select class="sm" disabled="disabled"></select>									
								</label>
								<label class="sm">
									<input type="checkbox" name="imageSharer[socnet][Mail]" <?php if((int)$this->_options[imageSharer][socnet][Mail] == 1) echo 'checked';?> />
									<div class="em pq_checkbox"></div>
									<select class="sm" disabled="disabled"></select>										
								</label>									
								<h3>SHARE IMAGE</h3>
								<label>
									<img class="picture" src="<?php echo plugins_url('i/capture.png', __FILE__);?>" />
									<div><p>&larr;</p> 
										<label>
											<select name="imageSharer[minWidth]">
												<option value="100" <?php if((int)$this->_options[imageSharer][minWidth] == 100 || (int)$this->_options[imageSharer][minWidth] == 0) echo 'selected';?>>100 px and more</option>
												<option value="200" <?php if((int)$this->_options[imageSharer][minWidth] == 200) echo 'selected';?>>200 px and more</option>
												<option value="300" <?php if((int)$this->_options[imageSharer][minWidth] == 300) echo 'selected';?>>300 px and more</option>
												<option value="400" <?php if((int)$this->_options[imageSharer][minWidth] == 400) echo 'selected';?>>400 px and more</option>
												<option value="500" <?php if((int)$this->_options[imageSharer][minWidth] == 500) echo 'selected';?>>500 px and more</option>
												<option value="600" <?php if((int)$this->_options[imageSharer][minWidth] == 600) echo 'selected';?>>600 px and more</option>
											</select>
										</label>
										<p>&rarr;</p></div>
								</label>
								<h3>DISABLE AFTER IMAGE CLICK</h3>
								<label>							
									<select name="imageSharer[disableAfterClick]">
										<option value="0" <?php if((int)$this->_options[imageSharer][disableAfterClick] == 0) echo 'selected';?>>Enable</option>
										<option value="1" <?php if((int)$this->_options[imageSharer][disableAfterClick] == 1) echo 'selected';?>>Disabled</option>
									</select>
								</label>
							</div>
						</div>
						<div class="settings thankyou">
							<div class="popup selected">
								<h3>Text</h3>
								<label>
								<p>Heading</p>
									<input class="text" type="text" name="thankPopup[title]" value="<?php echo stripslashes($this->_options[thankPopup][title])?>">
									
								</label>
								<label>
								<p>Text</p>
									<textarea rows="2" type="text" name="thankPopup[sub_title]" ><?php echo stripslashes($this->_options[thankPopup][sub_title])?></textarea>
									
								</label>
											
								<h3>Button</h3>
								<label>
								<p>Button Title</p>
									<input class="text" type="text" name="thankPopup[buttonTitle]" value="<?php echo stripslashes($this->_options[thankPopup][buttonTitle])?>">
									
								</label>
								
								<h3>DISPLAY AFTER</h3>
								<label><p>Sharing Sidebar</p>
								<select  name="sharingSideBar[afterProceed][thank]">
									<option value="1" <?php if((int)$this->_options[sharingSideBar][afterProceed][thank] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[sharingSideBar][afterProceed][thank] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Image Sharer</p>
								<select name="imageSharer[afterProceed][thank]">
									<option value="1" <?php if((int)$this->_options[imageSharer][afterProceed][thank] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[imageSharer][afterProceed][thank] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Subscribe Bar </p>
								<select name="subscribeBar[afterProceed][thank]">
									<option value="1" <?php if((int)$this->_options[subscribeBar][afterProceed][thank] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[subscribeBar][afterProceed][thank] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Exit Intent Popup</p>
								<select name="subscribeExit[afterProceed][thank]">
									<option value="1" <?php if((int)$this->_options[subscribeExit][afterProceed][thank] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[subscribeExit][afterProceed][thank] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>								
								<label><p>Contact Form</p>
								<select name="contactUs[afterProceed][thank]">
									<option value="1" <?php if((int)$this->_options[contactUs][afterProceed][thank] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[contactUs][afterProceed][thank] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<label><p>Call Me Now</p>
								<select name="callMe[afterProceed][thank]">
									<option value="1" <?php if((int)$this->_options[callMe][afterProceed][thank] == 1) echo 'selected';?>>On</option>
									<option value="0" <?php if((int)$this->_options[callMe][afterProceed][thank] == 0) echo 'selected';?>>Off</option>
								</select>
								</label>
								<br>
																								
								
							</div>
						</div>
						
					</div>
						<div class="pq_button_group button_group_li3">
							<input type="button" value="prev" class="ToLi1">
							<input type="button" value="Next" class="ToLi4">
							<br><input type="submit" value="Save and Activate" class="share_submit">
							<input type="submit" value="Save and Activate" class="follow_submit">
							<input type="submit" value="Save and Activate" class="contact_submit">
							<input type="submit" value="Save and Activate" class="callme_submit">
							<input type="submit" value="Save and Activate" class="exit_submit">
							<input type="submit" value="Save and Activate" class="imagesharer_submit">
							<input type="submit" value="Save and Activate" class="thankyou_submit">
							<input type="submit" value="Save and Activate" class="marketing_submit">
							<input type="submit" value="Save and Activate" class="collect_submit">
						</div>
					<div class="f_wrapper pq_li4">
					<h2>Design</h2>
						<div class="settings collect">
							
							<div class="floating selected">
							<h3>popup</h3>
								<label>
								<select>
									<option value="bg_white">Background - White</option>
									<option value="bg_iceblue">Background - Iceblue</option>
									<option value="bg_beige">Background - Beige</option>
									<option value="bg_lilac">Background - Lilac</option>
									<option value="bg_wormwood">Background - Wormwood</option>
									<option value="bg_yellow">Background - Yellow</option>
									<option value="bg_grey">Background - Grey</option>
									<option value="bg_red">Background - Red</option>
									<option value="bg_skyblue">Background - Skyblue</option>
									<option value="bg_blue">Background - Blue</option>
									<option value="bg_green">Background - Green</option>
									<option value="bg_black">Background - Black</option>
								</select>
								</label>
								
								<label>
								<select>
									<option value="btn_black">Button - Black</option>
									<option value="btn_black invert">Button - Black Invert</option>
									<option value="btn_orange">Button - Orange</option>
									<option value="btn_orange invert">Button - Orange Invert</option>
									<option value="btn_green">Button - Green</option>
									<option value="btn_green invert">Button - Green Invert</option>
									<option value="btn_lightblue">Button - Lightblue</option>
									<option value="btn_lightblue invert">Button - Lightblue Invert</option>
									<option value="btn_violet">Button - Violet</option>
									<option value="btn_violet invert">Button - Violet Invert</option>
									<option value="btn_blue">Button - Blue</option>
									<option value="btn_blue invert">Button - Blue Invert</option>
									<option value="btn_red">Button - Red</option>
									<option value="btn_red invert">Button - Red Invert</option>
									<option value="btn_lilac">Button - Lilac</option>
									<option value="btn_lilac invert">Button - Lilac Invert</option>
									<option value="btn_white">Button - White </option>
									<option value="btn_white invert">Button - White Invert</option>
								</select>
								</label>
								
								<label>
									<select name="vibor" class="vibor">										
										<option value="" selected >No picture</option>
										<option value="img_01.png">Question</option>
										<option value="img_02.png">Attention</option>
										<option value="img_03.png">Info</option>
										<option value="img_04.png">Knowledge</option>
										<option value="img_05.png">Idea</option>
										<option value="img_06.png">Talk</option>
										<option value="img_07.png">News</option>
										<option value="img_08.png">Megaphone</option>
										<option value="img_09.png">Gift</option>
										<option value="img_10.png">Success</option>
										<option name="7" class="custom_image">Custom image</option>
									</select>
								</label>
								<label class="custom_i"><p>Link</p>
									<input class="text" type="text" name="" value="" placeholder="">
								</label>
								<label>
								<select>
									<option value="pq_medium">Floating Size M</option>
									<option value="pq_mini">Floating Size S</option>
								</select>
								</label>
								<h3>Animation</h3>
								<label>
								<select>
									<option name="1">None</option>
									<option name="2">Bounce</option>
									<option name="3">Fading</option>
								</select>
								</label>
								<h3>Position</h3>
								<label>
								<select>
									<option value="pq_top">Top</option>
									<option value="pq_bottom">Bottom</option>
								</select>
								</label>
								<label>
									<select>
									<option value="pq_left">Left</option>
									<option value="pq_right">Right</option>
									</select>
								</label>
							</div>
							
						</div>
						<div class="settings share selected">
							<div class="sidebar selected">
							<h3>ICONS SERVICES</h3>
								<label>
								<select id="sharingSideBar_side" onchange="sharingSideBarPreview();" name="sharingSideBar[side]">
									<option value="pq_left" <?php if($this->_options[sharingSideBar][side] == 'pq_left') echo 'selected';?>>Left</option>
									<option value="pq_right" <?php if($this->_options[sharingSideBar][side] == 'pq_right') echo 'selected';?>>Right</option>
								</select>
								</label>
								<label>
								<select id="sharingSideBar_top" onchange="sharingSideBarPreview();" name="sharingSideBar[top]">
									<option value="pq_top" <?php if($this->_options[sharingSideBar][top] == 'pq_top') echo 'selected';?>>Top</option>
									<option value="pq_middle" <?php if($this->_options[sharingSideBar][top] == 'pq_middle') echo 'selected';?>>Middle</option>
									<option value="pq_bottom" <?php if($this->_options[sharingSideBar][top] == 'pq_bottom') echo 'selected';?>>Bottom</option>
								</select>
								</label>
								<label>
								<select id="sharingSideBar_design_form" onchange="sharingSideBarPreview();" name="sharingSideBar[design][form]">
									<option value="" <?php if($this->_options[sharingSideBar][design][form] == '') echo 'selected';?>>Shape - Square</option>
									<option value="rounded" <?php if($this->_options[sharingSideBar][design][form] == 'rounded') echo 'selected';?>>Shape - Rounded</option>
									<option value="circle" <?php if($this->_options[sharingSideBar][design][form] == 'circle') echo 'selected';?>>Shape - Circle</option>
								</select>
								</label>
								<label>
								<select id="sharingSideBar_design_size" onchange="sharingSideBarPreview();" name="sharingSideBar[design][size]">
									<option value="x20" <?php if($this->_options[sharingSideBar][design][size] == 'x20') echo 'selected';?>>Size S</option>
									<option value="x30" <?php if($this->_options[sharingSideBar][design][size] == 'x30') echo 'selected';?>>Size M</option>
									<option value="x40" <?php if($this->_options[sharingSideBar][design][size] == 'x40') echo 'selected';?>>Size M+</option>
									<option value="x50" <?php if($this->_options[sharingSideBar][design][size] == 'x50') echo 'selected';?>>Size L</option>
									<option value="x70" <?php if($this->_options[sharingSideBar][design][size] == 'x70') echo 'selected';?>>Size XL</option>
								</select>
								</label>
								<label>
								<select id="sharingSideBar_design_color" onchange="sharingSideBarPreview();" name="sharingSideBar[design][color]">
									<option value="c4" <?php if($this->_options[sharingSideBar][design][color] == 'c4') echo 'selected';?>>Color</option>
									<option value="c1" <?php if($this->_options[sharingSideBar][design][color] == 'c1') echo 'selected';?>>Color light</option>
									<option value="c2" <?php if($this->_options[sharingSideBar][design][color] == 'c2') echo 'selected';?>>Color volume</option>
									<option value="c3" <?php if($this->_options[sharingSideBar][design][color] == 'c3') echo 'selected';?>>Color dark</option>
									<option value="c5" <?php if($this->_options[sharingSideBar][design][color] == 'c5') echo 'selected';?>>Black</option>
									<option value="c6" <?php if($this->_options[sharingSideBar][design][color] == 'c6') echo 'selected';?>>Black volume</option>
									<option value="c7" <?php if($this->_options[sharingSideBar][design][color] == 'c7') echo 'selected';?>>White volume</option>
									<option value="c8" <?php if($this->_options[sharingSideBar][design][color] == 'c8') echo 'selected';?>>White</option>
									<option value="c9" <?php if($this->_options[sharingSideBar][design][color] == 'c9') echo 'selected';?>>Bordered - color</option>
									<option value="c10" <?php if($this->_options[sharingSideBar][design][color] == 'c10') echo 'selected';?>>Bordered - black</option>
									<option value="c11" <?php if($this->_options[sharingSideBar][design][color] == 'c11') echo 'selected';?>>Lightest</option>
								</select>
								</label>
								<h3>IMAGE POPUP BACKGROUND</h3>
								<label>
								<select id="sharingSideBar_popup_bg_color" onchange="sharingSideBarPreview();" name="sharingSideBar[galleryOption][background_color]">
									<option value="bg_white" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_white') echo 'selected';?>>Background - White</option>
									<option value="bg_white_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_white_lt') echo 'selected';?>>Background - White Lite</option>
									<option value="bg_iceblue" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_iceblue_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_iceblue_lt') echo 'selected';?>>Background - Iceblue Lite</option>
									<option value="bg_beige" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_beige_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_beige_lt') echo 'selected';?>>Background - Beige Lite</option>
									<option value="bg_lilac" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
									<option value="bg_lilac_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_lilac_lt') echo 'selected';?>>Background - Lilac Lite</option>
									<option value="bg_wormwood" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_wormwood_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_wormwood_lt') echo 'selected';?>>Background - Wormwood Lite</option>
									<option value="bg_yellow" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_yellow_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_yellow_lt') echo 'selected';?>>Background - Yellow Lite</option>
									<option value="bg_grey" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="bg_grey_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_grey_lt') echo 'selected';?>>Background - Grey Lite</option>
									<option value="bg_red" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_red_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_red_lt') echo 'selected';?>>Background - Red Lite</option>
									<option value="bg_skyblue" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_skyblue_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_skyblue_lt') echo 'selected';?>>Background - Skyblue Lite</option>
									<option value="bg_blue" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_blue_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_blue_lt') echo 'selected';?>>Background - Blue Lite</option>
									<option value="bg_green" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_green_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_green_lt') echo 'selected';?>>Background - Green Lite</option>
									<option value="bg_black" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_black_lt" <?php if($this->_options[sharingSideBar][galleryOption][background_color] == 'bg_black_lt') echo 'selected';?>>Background - Black Lite</option>
								</select>
								</label>
								<h3>IMAGE POPUP BUTTON COLOR</h3>
								<label>
									<select id="sharingSideBar_popup_button_color" onchange="sharingSideBarPreview();" name="sharingSideBar[galleryOption][button_color]">
									<option value="btn_lightblue" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_lightblue') echo 'selected';?>>Button - Lightblue</option>
									<option value="btn_lightblue invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_lightblue invert' || $this->_options[sharingSideBar][galleryOption][button_color] == '') echo 'selected';?>>Button - Lightblue Transparent</option>
									<option value="btn_blue" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_blue') echo 'selected';?>>Button - Blue</option>
									<option value="btn_blue invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_blue invert') echo 'selected';?>>Button - Blue Transparent</option>
									<option value="btn_black" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_black') echo 'selected';?>>Button - Black</option>
									<option value="btn_black invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_black invert') echo 'selected';?>>Button - Black Transparent</option>
									<option value="btn_green" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_green') echo 'selected';?>>Button - Green</option>
									<option value="btn_green invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_green invert') echo 'selected';?>>Button - Green Transparent</option>
									<option value="btn_violet" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_violet') echo 'selected';?>>Button - Violet</option>
									<option value="btn_violet invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_violet invert') echo 'selected';?>>Button - Violet Transparent</option>
									<option value="btn_orange" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_orange') echo 'selected';?>>Button - Orange</option>
									<option value="btn_orange invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_orange invert') echo 'selected';?>>Button - Orange Transparent</option>
									<option value="btn_red" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_red') echo 'selected';?>>Button - Red</option>
									<option value="btn_red invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_red invert') echo 'selected';?>>Button - Red Transparent</option>
									<option value="btn_lilac" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_lilac') echo 'selected';?>>Button - Lilac</option>
									<option value="btn_lilac invert" <?php if($this->_options[sharingSideBar][galleryOption][button_color] == 'btn_lilac invert') echo 'selected';?>>Button - Lilac Transparent</option>
									</select>
								</label>
								
																
								<!--h3>Animation</h3>
								<label>
								<select>
									<option name="1">None</option>
									<option name="2">1</option>
									<option name="3">2</option>
								</select>
								</label-->
							</div>
						</div>
						<div class="settings follow">
							<div class="popup selected">
							<h3>POPUP</h3>
								<label>
								<select id="follow_typeWindow" onchange="followPreview();"  name="follow[typeWindow]">
									<option value="pq_large" <?php if($this->_options[follow][typeWindow] == 'pq_large' || $this->_options[follow][typeWindow] == '') echo 'selected';?>>Size L</option>
									<option value="pq_medium" <?php if($this->_options[follow][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
									<option value="pq_mini" <?php if($this->_options[follow][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
								</select>
								</label>
								<label>
								<select id="follow_background" onchange="followPreview();"  name="follow[background]">
									<option value="" <?php if($this->_options[follow][background] == '') echo 'selected';?>>Background - White</option>
								    <option value="bg_grey" <?php if($this->_options[follow][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>									
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
								</select>
								</label>																
																
								<h3>Animation</h3>
								<label>
								<select name="follow[animation]" id="follow_animation" onchange="followPreview();"  >									
									<option value="bounceInDown" <?php if($this->_options[follow][animation] == 'bounceInDown') echo 'selected';?>>Bounce</option>
									<option value="fade" <?php if($this->_options[follow][animation] == 'fade' || $this->_options[follow][animation] == '') echo 'selected';?>>Fade In</option>
								</select>
								</label>
								
								<h3>Overlay</h3>
								<label>
								<select id="follow_overlay" onchange="followPreview();" name="follow[overlay]">
								    <option value="over_grey" <?php if($this->_options[follow][overlay] == 'over_grey') echo 'selected';?>>Color overlay - Grey</option>
									<option value="over_white" <?php if($this->_options[follow][overlay] == 'over_white' || $this->_options[follow][overlay] == '') echo 'selected';?>>Color overlay - White</option>
									<option value="over_yellow" <?php if($this->_options[follow][overlay] == 'over_yellow') echo 'selected';?>>Color overlay - Yellow</option>
									<option value="over_wormwood" <?php if($this->_options[follow][overlay] == 'over_wormwood') echo 'selected';?>>Color overlay - Wormwood</option>
									<option value="over_blue" <?php if($this->_options[follow][overlay] == 'over_blue') echo 'selected';?>>Color overlay - Blue</option>
									<option value="over_green" <?php if($this->_options[follow][overlay] == 'over_green') echo 'selected';?>>Color overlay - Green</option>
									<option value="over_beige" <?php if($this->_options[follow][overlay] == 'over_beige') echo 'selected';?>>Color overlay - Beige</option>
									<option value="over_red" <?php if($this->_options[follow][overlay] == 'over_red') echo 'selected';?>>Color overlay - Red</option>
									<option value="over_iceblue" <?php if($this->_options[follow][overlay] == 'over_iceblue') echo 'selected';?>>Color overlay - Iceblue</option>
									<option value="over_black" <?php if($this->_options[follow][overlay] == 'over_black') echo 'selected';?>>Color overlay - Black</option>
									<option value="over_skyblue" <?php if($this->_options[follow][overlay] == 'over_skyblue') echo 'selected';?>>Color overlay - Skyblue</option>
									<option value="over_lilac" <?php if($this->_options[follow][overlay] == 'over_lilac') echo 'selected';?>>Color overlay - Lilac</option>
									<option value="over_grey_lt" <?php if($this->_options[follow][overlay] == 'over_grey_lt') echo 'selected';?>>Color overlay - Grey - Light</option>
									<option value="over_white_lt" <?php if($this->_options[follow][overlay] == 'over_white_lt') echo 'selected';?>>Color overlay - White - Light</option>
									<option value="over_yellow_lt" <?php if($this->_options[follow][overlay] == 'over_yellow_lt') echo 'selected';?>>Color overlay - Yellow - Light</option>
									<option value="over_wormwood_lt" <?php if($this->_options[follow][overlay] == 'over_wormwood_lt') echo 'selected';?>>Color overlay - Wormwood - Light</option>
									<option value="over_blue_lt" <?php if($this->_options[follow][overlay] == 'over_blue_lt') echo 'selected';?>>Color overlay - Blue - Light</option>
									<option value="over_green_lt" <?php if($this->_options[follow][overlay] == 'over_green_lt') echo 'selected';?>>Color overlay - Green - Light</option>
									<option value="over_beige_lt" <?php if($this->_options[follow][overlay] == 'over_beige_lt') echo 'selected';?>>Color overlay - Beige - Light</option>
									<option value="over_red_lt" <?php if($this->_options[follow][overlay] == 'over_red_lt') echo 'selected';?>>Color overlay - Red - Light</option>
									<option value="over_iceblue_lt" <?php if($this->_options[follow][overlay] == 'over_iceblue_lt') echo 'selected';?>>Color overlay - Iceblue - Light</option>
									<option value="over_black_lt" <?php if($this->_options[follow][overlay] == 'over_black_lt') echo 'selected';?>>Color overlay - Black - Light</option>
									<option value="over_skyblue_lt" <?php if($this->_options[follow][overlay] == 'over_skyblue_lt') echo 'selected';?>>Color overlay - Skyblue - Light</option>
									<option value="over_lilac_lt" <?php if($this->_options[follow][overlay] == 'over_lilac_lt') echo 'selected';?>>Color overlay - Lilac - Light</option>
									<option value="over_grey_solid" <?php if($this->_options[follow][overlay] == 'over_grey_solid') echo 'selected';?>>Color overlay - Grey - Solid</option>
									<option value="over_white_solid" <?php if($this->_options[follow][overlay] == 'over_white_solid') echo 'selected';?>>Color overlay - White - Solid</option>
									<option value="over_yellow_solid" <?php if($this->_options[follow][overlay] == 'over_yellow_solid') echo 'selected';?>>Color overlay - Yellow - Solid</option>
									<option value="over_wormwood_solid" <?php if($this->_options[follow][overlay] == 'over_wormwood_solid') echo 'selected';?>>Color overlay - Wormwood - Solid</option>
									<option value="over_blue_solid" <?php if($this->_options[follow][overlay] == 'over_blue_solid') echo 'selected';?>>Color overlay - Blue - Solid</option>
									<option value="over_green_solid" <?php if($this->_options[follow][overlay] == 'over_green_solid') echo 'selected';?>>Color overlay - Green - Solid</option>
									<option value="over_beige_solid" <?php if($this->_options[follow][overlay] == 'over_beige_solid') echo 'selected';?>>Color overlay - Beige - Solid</option>
									<option value="over_red_solid" <?php if($this->_options[follow][overlay] == 'over_red_solid') echo 'selected';?>>Color overlay - Red - Solid</option>
									<option value="over_iceblue_solid" <?php if($this->_options[follow][overlay] == 'over_iceblue_solid') echo 'selected';?>>Color overlay - Iceblue - Solid</option>
									<option value="over_black_solid" <?php if($this->_options[follow][overlay] == 'over_black_solid') echo 'selected';?>>Color overlay - Black - Solid</option>
									<option value="over_skyblue_solid" <?php if($this->_options[follow][overlay] == 'over_skyblue_solid') echo 'selected';?>>Color overlay - Skyblue - Solid</option>
									<option value="over_lilac_solid" <?php if($this->_options[follow][overlay] == 'over_lilac_solid') echo 'selected';?>>Color overlay - Lilac - Solid</option>
								</select>
								</label>
								<h3>Icons SERVICES</h3>
								<label>
								<select name="follow[design][form]" id="follow_design_form" onchange="followPreview();">
									<option value="" <?php if($this->_options[follow][design][form] == '') echo 'selected';?>>Shape - Square</option>
									<option value="rounded" <?php if($this->_options[follow][design][form] == 'rounded') echo 'selected';?>>Shape - Rounded</option>
									<option value="circle" <?php if($this->_options[follow][design][form] == 'circle') echo 'selected';?>>Shape - Circle</option>
								</select>
								</label>
								<label>
								<select name="follow[design][size]" id="follow_design_size" onchange="followPreview();">
									<option value="x20" <?php if($this->_options[follow][design][size] == 'x20') echo 'selected';?>>Size S</option>
									<option value="x30" <?php if($this->_options[follow][design][size] == 'x30') echo 'selected';?>>Size M</option>
									<option value="x40" <?php if($this->_options[follow][design][size] == 'x40') echo 'selected';?>>Size M+</option>
									<option value="x50" <?php if($this->_options[follow][design][size] == 'x50') echo 'selected';?>>Size L</option>
									<option value="x70" <?php if($this->_options[follow][design][size] == 'x70') echo 'selected';?>>Size XL</option>
								</select>
								</label>
								<label>
								<select name="follow[design][color]" id="follow_design_color" onchange="followPreview();">
									<option value="c4" <?php if($this->_options[follow][design][color] == 'c4') echo 'selected';?>>Color</option>
									<option value="c1" <?php if($this->_options[follow][design][color] == 'c1') echo 'selected';?>>Color light</option>
									<option value="c2" <?php if($this->_options[follow][design][color] == 'c2') echo 'selected';?>>Color volume</option>
									<option value="c3" <?php if($this->_options[follow][design][color] == 'c3') echo 'selected';?>>Color dark</option>
									<option value="c5" <?php if($this->_options[follow][design][color] == 'c5') echo 'selected';?>>Black</option>
									<option value="c6" <?php if($this->_options[follow][design][color] == 'c6') echo 'selected';?>>Black volume</option>
									<option value="c7" <?php if($this->_options[follow][design][color] == 'c7') echo 'selected';?>>White volume</option>
									<option value="c8" <?php if($this->_options[follow][design][color] == 'c8') echo 'selected';?>>White</option>
									<option value="c9" <?php if($this->_options[follow][design][color] == 'c9') echo 'selected';?>>Bordered - color</option>
									<option value="c10" <?php if($this->_options[follow][design][color] == 'c10') echo 'selected';?>>Bordered - black</option>
									<option value="c11" <?php if($this->_options[follow][design][color] == 'c11') echo 'selected';?>>Lightest</option>
								</select>
								</label>
								<h3>Shadow</h3>
								<label>
									<select id="follow_design_shadow" onchange="followPreview();" name="follow[design][shadow]">
										<option value="sh1" <?php if($this->_options[follow][design][shadow] == 'sh1') echo 'selected';?>>Shadow1</option>
										<option value="sh2" <?php if($this->_options[follow][design][shadow] == 'sh2') echo 'selected';?>>Shadow2</option>
										<option value="sh3" <?php if($this->_options[follow][design][shadow] == 'sh3') echo 'selected';?>>Shadow3</option>
										<option value="sh4" <?php if($this->_options[follow][design][shadow] == 'sh4') echo 'selected';?>>Shadow4</option>
										<option value="sh5" <?php if($this->_options[follow][design][shadow] == 'sh5') echo 'selected';?>>Shadow5</option>
										<option value="sh6" <?php if($this->_options[follow][design][shadow] == 'sh6') echo 'selected';?>>Shadow6</option>
									</select>
								</label>
							</div>
								
						</div>
						<div class="settings marketing">
							<div class="pq_bar selected">
							<h3>Colors</h3>
								<label>
								<select id="subscribeBar_background" onchange="subscribeBarPreview();" name="subscribeBar[background]">
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
								</select>
								</label>
								<label>
								<select id="subscribeBar_button_color" onchange="subscribeBarPreview();" name="subscribeBar[button_color]">
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
								</select>
								</label>
								<label>
								<select id="subscribeBar_size" onchange="subscribeBarPreview();" name="subscribeBar[typeWindow]">
									<option value="" <?php if($this->_options[subscribeBar][typeWindow] == '') echo 'selected';?>>Size L</option>
									<option value="pq_small" <?php if($this->_options[subscribeBar][typeWindow] == 'pq_medium') echo 'selected';?>>Size S</o4ption>																	
								</select>
								</label>
								
								<h3>Position</h3>
								<label>
								<select id="subscribeBar_position" onchange="subscribeBarPreview();" name="subscribeBar[position]">
									<option value="pq_top"  <?php if($this->_options[subscribeBar][position] == 'pq_top' || $this->_options[subscribeBar][position] == '') echo 'selected';?>>Top</option>
									<option value="pq_bottom" <?php if($this->_options[subscribeBar][position] == 'pq_bottom') echo 'selected';?>>Bottom</option>
								</select>
								</label>
								<h3>Animation</h3>
								<label>
								<select name="subscribeBar[animation]" id="subscribeBar_animation" onchange="subscribeBarPreview();">									
									<option value="bounceInDown" <?php if($this->_options[subscribeBar][animation] == 'bounceInDown') echo 'selected';?>>Bounce</option>
									<option value="fade" <?php if($this->_options[subscribeBar][animation] == 'fade' || $this->_options[subscribeBar][animation] == '') echo 'selected';?>>Fade In</option>
								</select>
								</label>
								
							</div>
						</div>
						<div class="settings contact">
							<div class="popup selected">
							<h3>POPUP</h3>
								<label>
								<select id="contactUs_background" onchange="contactUsPreview();" name="contactUs[background]">
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
								</select>
								</label>
								<label>
								<select id="contactUs_button_color" onchange="contactUsPreview();" name="contactUs[button_color]">
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
								</select>
								</label>
								
								<label>
									<select id="contactUs_img" name="contactUs[img]" onchange="contactUsPreview();changePopupImg(this.value, 'contactUsCustomFotoBlock');">
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
									</select>
								</label>								
								<label class="custom_i" id="contactUsCustomFotoBlock" style="display:none;"><p>Link</p>									
									<input type="text" name="contactUs[imgUrl]" id="contactUsCustomFotoSrc" onkeyup="contactUsPreview();" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[contactUs][imgUrl]);?>">
								</label>
								<script>
									changePopupImg('<?php echo $this->_options[contactUs][img];?>', 'contactUsCustomFotoBlock');
								</script>
								<label>
								<select id="contactUs_typeWindow" onchange="contactUsPreview();"  name="contactUs[typeWindow]">
									<option value="pq_large" <?php if($this->_options[contactUs][typeWindow] == 'pq_large' || $this->_options[contactUs][typeWindow] == '') echo 'selected';?>>Size L</option>
									<option value="pq_medium" <?php if($this->_options[contactUs][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
									<option value="pq_mini" <?php if($this->_options[contactUs][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
								</select>
								</label>
								<h3>Animation</h3>
								<label>
								<select name="contactUs[animation]" id="contactUs_animation" onchange="contactUsPreview();">									
									<option value="bounceInDown" <?php if($this->_options[contactUs][animation] == 'bounceInDown') echo 'selected';?>>Bounce</option>
									<option value="fade" <?php if($this->_options[contactUs][animation] == 'fade' || $this->_options[contactUs][animation] == '') echo 'selected';?>>Fade In</option>
								</select>
								</label>
								
								<h3>Overlay</h3>
								<label>
								<select id="contactUs_overlay" name="contactUs[overlay]" onchange="contactUsPreview();">
								    <option value="over_grey" <?php if($this->_options[contactUs][overlay] == 'over_grey') echo 'selected';?>>Color overlay - Grey</option>
									<option value="over_white" <?php if($this->_options[contactUs][overlay] == 'over_white' || $this->_options[contactUs][overlay] == '') echo 'selected';?>>Color overlay - White</option>
									<option value="over_yellow" <?php if($this->_options[contactUs][overlay] == 'over_yellow') echo 'selected';?>>Color overlay - Yellow</option>
									<option value="over_wormwood" <?php if($this->_options[contactUs][overlay] == 'over_wormwood') echo 'selected';?>>Color overlay - Wormwood</option>
									<option value="over_blue" <?php if($this->_options[contactUs][overlay] == 'over_blue') echo 'selected';?>>Color overlay - Blue</option>
									<option value="over_green" <?php if($this->_options[contactUs][overlay] == 'over_green') echo 'selected';?>>Color overlay - Green</option>
									<option value="over_beige" <?php if($this->_options[contactUs][overlay] == 'over_beige') echo 'selected';?>>Color overlay - Beige</option>
									<option value="over_red" <?php if($this->_options[contactUs][overlay] == 'over_red') echo 'selected';?>>Color overlay - Red</option>
									<option value="over_iceblue" <?php if($this->_options[contactUs][overlay] == 'over_iceblue') echo 'selected';?>>Color overlay - Iceblue</option>
									<option value="over_black" <?php if($this->_options[contactUs][overlay] == 'over_black') echo 'selected';?>>Color overlay - Black</option>
									<option value="over_skyblue" <?php if($this->_options[contactUs][overlay] == 'over_skyblue') echo 'selected';?>>Color overlay - Skyblue</option>
									<option value="over_lilac" <?php if($this->_options[contactUs][overlay] == 'over_lilac') echo 'selected';?>>Color overlay - Lilac</option>
									<option value="over_grey_lt" <?php if($this->_options[contactUs][overlay] == 'over_grey_lt') echo 'selected';?>>Color overlay - Grey - Light</option>
									<option value="over_white_lt" <?php if($this->_options[contactUs][overlay] == 'over_white_lt') echo 'selected';?>>Color overlay - White - Light</option>
									<option value="over_yellow_lt" <?php if($this->_options[contactUs][overlay] == 'over_yellow_lt') echo 'selected';?>>Color overlay - Yellow - Light</option>
									<option value="over_wormwood_lt" <?php if($this->_options[contactUs][overlay] == 'over_wormwood_lt') echo 'selected';?>>Color overlay - Wormwood - Light</option>
									<option value="over_blue_lt" <?php if($this->_options[contactUs][overlay] == 'over_blue_lt') echo 'selected';?>>Color overlay - Blue - Light</option>
									<option value="over_green_lt" <?php if($this->_options[contactUs][overlay] == 'over_green_lt') echo 'selected';?>>Color overlay - Green - Light</option>
									<option value="over_beige_lt" <?php if($this->_options[contactUs][overlay] == 'over_beige_lt') echo 'selected';?>>Color overlay - Beige - Light</option>
									<option value="over_red_lt" <?php if($this->_options[contactUs][overlay] == 'over_red_lt') echo 'selected';?>>Color overlay - Red - Light</option>
									<option value="over_iceblue_lt" <?php if($this->_options[contactUs][overlay] == 'over_iceblue_lt') echo 'selected';?>>Color overlay - Iceblue - Light</option>
									<option value="over_black_lt" <?php if($this->_options[contactUs][overlay] == 'over_black_lt') echo 'selected';?>>Color overlay - Black - Light</option>
									<option value="over_skyblue_lt" <?php if($this->_options[contactUs][overlay] == 'over_skyblue_lt') echo 'selected';?>>Color overlay - Skyblue - Light</option>
									<option value="over_lilac_lt" <?php if($this->_options[contactUs][overlay] == 'over_lilac_lt') echo 'selected';?>>Color overlay - Lilac - Light</option>
									<option value="over_grey_solid" <?php if($this->_options[contactUs][overlay] == 'over_grey_solid') echo 'selected';?>>Color overlay - Grey - Solid</option>
									<option value="over_white_solid" <?php if($this->_options[contactUs][overlay] == 'over_white_solid') echo 'selected';?>>Color overlay - White - Solid</option>
									<option value="over_yellow_solid" <?php if($this->_options[contactUs][overlay] == 'over_yellow_solid') echo 'selected';?>>Color overlay - Yellow - Solid</option>
									<option value="over_wormwood_solid" <?php if($this->_options[contactUs][overlay] == 'over_wormwood_solid') echo 'selected';?>>Color overlay - Wormwood - Solid</option>
									<option value="over_blue_solid" <?php if($this->_options[contactUs][overlay] == 'over_blue_solid') echo 'selected';?>>Color overlay - Blue - Solid</option>
									<option value="over_green_solid" <?php if($this->_options[contactUs][overlay] == 'over_green_solid') echo 'selected';?>>Color overlay - Green - Solid</option>
									<option value="over_beige_solid" <?php if($this->_options[contactUs][overlay] == 'over_beige_solid') echo 'selected';?>>Color overlay - Beige - Solid</option>
									<option value="over_red_solid" <?php if($this->_options[contactUs][overlay] == 'over_red_solid') echo 'selected';?>>Color overlay - Red - Solid</option>
									<option value="over_iceblue_solid" <?php if($this->_options[contactUs][overlay] == 'over_iceblue_solid') echo 'selected';?>>Color overlay - Iceblue - Solid</option>
									<option value="over_black_solid" <?php if($this->_options[contactUs][overlay] == 'over_black_solid') echo 'selected';?>>Color overlay - Black - Solid</option>
									<option value="over_skyblue_solid" <?php if($this->_options[contactUs][overlay] == 'over_skyblue_solid') echo 'selected';?>>Color overlay - Skyblue - Solid</option>
									<option value="over_lilac_solid" <?php if($this->_options[contactUs][overlay] == 'over_lilac_solid') echo 'selected';?>>Color overlay - Lilac - Solid</option>
								</select>
								</label>
								<h3>Loader</h3>
								<label>
								<select id="contactUs_loader_background" onchange="contactUsPreview();" name="contactUs[loader_background]">
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
								</select>
								</label>
								<label>
								<select id="contactUs_top" onchange="contactUsPreview();" name="contactUs[top]">
									<option value="pq_top" <?php if($this->_options[contactUs][top] == 'pq_top') echo 'selected';?>>Top</option>
									<option value="pq_middle" <?php if($this->_options[contactUs][top] == 'pq_middle') echo 'selected';?>>Middle</option>
									<option value="pq_bottom" <?php if($this->_options[contactUs][top] == 'pq_bottom') echo 'selected';?>>Bottom</option>
								</select>
								</label>
								<label>
									<select id="contactUs_side" onchange="contactUsPreview();" name="contactUs[side]">
									<option value="pq_left" <?php if($this->_options[contactUs][side] == 'pq_left') echo 'selected';?>>Left</option>
									<option value="pq_right" <?php if($this->_options[contactUs][side] == 'pq_right') echo 'selected';?>>Right</option>
									</select>
								</label>
							</div>	
							
						</div>
						<div class="settings callme">
							<div class="popup selected">
							<h3>POPUP</h3>
								<label>
								<select id="callMe_background" onchange="callMePreview();" name="callMe[background]">
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
								</select>
								</label>
								
								<label>
								<select id="callMe_button_color" onchange="callMePreview();" name="callMe[button_color]">
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
								</select>
								</label>
								
								<label>
									<select id="callMe_img" class="vibor" name="callMe[img]" onchange="callMePreview();changePopupImg(this.value, 'callMeCustomFotoBlock');">
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
									</select>
								</label>
								<label class="custom_i" id="callMeCustomFotoBlock"><p>Link</p>
									<input type="text" name="callMe[imgUrl]" onkeyup="callMePreview();" id="callMeCustomFotoSrc" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[callMe][imgUrl])?>">
								</label>
								<label>
								<script>
									changePopupImg('<?php echo $this->_options[callMe][img];?>', 'callMeCustomFotoBlock');
								</script>
								<select id="callMe_typeWindow" onchange="callMePreview();" name="callMe[typeWindow]">
									<option value="pq_large" <?php if($this->_options[callMe][typeWindow] == 'pq_large' || $this->_options[callMe][typeWindow] == '') echo 'selected';?>>Size L</option>
									<option value="pq_medium" <?php if($this->_options[callMe][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
									<option value="pq_mini" <?php if($this->_options[callMe][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
								</select>
								</label>
								<h3>Animation</h3>
								<label>
								<select name="callMe[animation]" id="callMe_animation" onchange="callMePreview();">									
									<option value="bounceInDown" <?php if($this->_options[callMe][animation] == 'bounceInDown') echo 'selected';?>>Bounce</option>
									<option value="fade" <?php if($this->_options[callMe][animation] == 'fade' || $this->_options[callMe][animation] == '') echo 'selected';?>>Fade In</option>
								</select>
								</label>
								
								<h3>Overlay</h3>
								<label>
								<select id="callMe_overlay" name="callMe[overlay]" onchange="callMePreview();">
								    <option value="over_grey" <?php if($this->_options[callMe][overlay] == 'over_grey') echo 'selected';?>>Color overlay - Grey</option>
									<option value="over_white" <?php if($this->_options[callMe][overlay] == 'over_white' || $this->_options[callMe][overlay] == '') echo 'selected';?>>Color overlay - White</option>
									<option value="over_yellow" <?php if($this->_options[callMe][overlay] == 'over_yellow') echo 'selected';?>>Color overlay - Yellow</option>
									<option value="over_wormwood" <?php if($this->_options[callMe][overlay] == 'over_wormwood') echo 'selected';?>>Color overlay - Wormwood</option>
									<option value="over_blue" <?php if($this->_options[callMe][overlay] == 'over_blue') echo 'selected';?>>Color overlay - Blue</option>
									<option value="over_green" <?php if($this->_options[callMe][overlay] == 'over_green') echo 'selected';?>>Color overlay - Green</option>
									<option value="over_beige" <?php if($this->_options[callMe][overlay] == 'over_beige') echo 'selected';?>>Color overlay - Beige</option>
									<option value="over_red" <?php if($this->_options[callMe][overlay] == 'over_red') echo 'selected';?>>Color overlay - Red</option>
									<option value="over_iceblue" <?php if($this->_options[callMe][overlay] == 'over_iceblue') echo 'selected';?>>Color overlay - Iceblue</option>
									<option value="over_black" <?php if($this->_options[callMe][overlay] == 'over_black') echo 'selected';?>>Color overlay - Black</option>
									<option value="over_skyblue" <?php if($this->_options[callMe][overlay] == 'over_skyblue') echo 'selected';?>>Color overlay - Skyblue</option>
									<option value="over_lilac" <?php if($this->_options[callMe][overlay] == 'over_lilac') echo 'selected';?>>Color overlay - Lilac</option>
									<option value="over_grey_lt" <?php if($this->_options[callMe][overlay] == 'over_grey_lt') echo 'selected';?>>Color overlay - Grey - Light</option>
									<option value="over_white_lt" <?php if($this->_options[callMe][overlay] == 'over_white_lt') echo 'selected';?>>Color overlay - White - Light</option>
									<option value="over_yellow_lt" <?php if($this->_options[callMe][overlay] == 'over_yellow_lt') echo 'selected';?>>Color overlay - Yellow - Light</option>
									<option value="over_wormwood_lt" <?php if($this->_options[callMe][overlay] == 'over_wormwood_lt') echo 'selected';?>>Color overlay - Wormwood - Light</option>
									<option value="over_blue_lt" <?php if($this->_options[callMe][overlay] == 'over_blue_lt') echo 'selected';?>>Color overlay - Blue - Light</option>
									<option value="over_green_lt" <?php if($this->_options[callMe][overlay] == 'over_green_lt') echo 'selected';?>>Color overlay - Green - Light</option>
									<option value="over_beige_lt" <?php if($this->_options[callMe][overlay] == 'over_beige_lt') echo 'selected';?>>Color overlay - Beige - Light</option>
									<option value="over_red_lt" <?php if($this->_options[callMe][overlay] == 'over_red_lt') echo 'selected';?>>Color overlay - Red - Light</option>
									<option value="over_iceblue_lt" <?php if($this->_options[callMe][overlay] == 'over_iceblue_lt') echo 'selected';?>>Color overlay - Iceblue - Light</option>
									<option value="over_black_lt" <?php if($this->_options[callMe][overlay] == 'over_black_lt') echo 'selected';?>>Color overlay - Black - Light</option>
									<option value="over_skyblue_lt" <?php if($this->_options[callMe][overlay] == 'over_skyblue_lt') echo 'selected';?>>Color overlay - Skyblue - Light</option>
									<option value="over_lilac_lt" <?php if($this->_options[callMe][overlay] == 'over_lilac_lt') echo 'selected';?>>Color overlay - Lilac - Light</option>
									<option value="over_grey_solid" <?php if($this->_options[callMe][overlay] == 'over_grey_solid') echo 'selected';?>>Color overlay - Grey - Solid</option>
									<option value="over_white_solid" <?php if($this->_options[callMe][overlay] == 'over_white_solid') echo 'selected';?>>Color overlay - White - Solid</option>
									<option value="over_yellow_solid" <?php if($this->_options[callMe][overlay] == 'over_yellow_solid') echo 'selected';?>>Color overlay - Yellow - Solid</option>
									<option value="over_wormwood_solid" <?php if($this->_options[callMe][overlay] == 'over_wormwood_solid') echo 'selected';?>>Color overlay - Wormwood - Solid</option>
									<option value="over_blue_solid" <?php if($this->_options[callMe][overlay] == 'over_blue_solid') echo 'selected';?>>Color overlay - Blue - Solid</option>
									<option value="over_green_solid" <?php if($this->_options[callMe][overlay] == 'over_green_solid') echo 'selected';?>>Color overlay - Green - Solid</option>
									<option value="over_beige_solid" <?php if($this->_options[callMe][overlay] == 'over_beige_solid') echo 'selected';?>>Color overlay - Beige - Solid</option>
									<option value="over_red_solid" <?php if($this->_options[callMe][overlay] == 'over_red_solid') echo 'selected';?>>Color overlay - Red - Solid</option>
									<option value="over_iceblue_solid" <?php if($this->_options[callMe][overlay] == 'over_iceblue_solid') echo 'selected';?>>Color overlay - Iceblue - Solid</option>
									<option value="over_black_solid" <?php if($this->_options[callMe][overlay] == 'over_black_solid') echo 'selected';?>>Color overlay - Black - Solid</option>
									<option value="over_skyblue_solid" <?php if($this->_options[callMe][overlay] == 'over_skyblue_solid') echo 'selected';?>>Color overlay - Skyblue - Solid</option>
									<option value="over_lilac_solid" <?php if($this->_options[callMe][overlay] == 'over_lilac_solid') echo 'selected';?>>Color overlay - Lilac - Solid</option>
								</select>
								</label>
								<h3>Loader</h3>
								<label>
								<select id="callMe_loader_background" onchange="callMePreview();" name="callMe[loader_background]">
								    <option value="bg_white" <?php if($this->_options[callMe][loader_background] == 'bg_white') echo 'selected';?>>Loader - Background - White</option>
									<option value="bg_white_lt" <?php if($this->_options[callMe][loader_background] == 'bg_white_lt') echo 'selected';?>>Loader - Background - White Lite</option>
									<option value="bg_iceblue" <?php if($this->_options[callMe][loader_background] == 'bg_iceblue') echo 'selected';?>>Loader - Background - Iceblue</option>
									<option value="bg_iceblue_lt" <?php if($this->_options[callMe][loader_background] == 'bg_iceblue_lt') echo 'selected';?>>Loader - Background - Iceblue Lite</option>
									<option value="bg_beige" <?php if($this->_options[callMe][loader_background] == 'bg_beige') echo 'selected';?>>Loader - Background - Beige</option>
									<option value="bg_beige_lt" <?php if($this->_options[callMe][loader_background] == 'bg_beige_lt') echo 'selected';?>>Loader - Background - Beige Lite</option>
									<option value="bg_lilac" <?php if($this->_options[callMe][loader_background] == 'bg_lilac') echo 'selected';?>>Loader - Background - Lilac</option>
									<option value="bg_lilac_lt" <?php if($this->_options[callMe][loader_background] == 'bg_lilac_lt') echo 'selected';?>>Loader - Background - Lilac Lite</option>
									<option value="bg_wormwood" <?php if($this->_options[callMe][loader_background] == 'bg_wormwood') echo 'selected';?>>Loader - Background - Wormwood</option>
									<option value="bg_wormwood_lt" <?php if($this->_options[callMe][loader_background] == 'bg_wormwood_lt') echo 'selected';?>>Loader - Background - Wormwood Lite</option>
									<option value="bg_yellow" <?php if($this->_options[callMe][loader_background] == 'bg_yellow') echo 'selected';?>>Loader - Background - Yellow</option>
									<option value="bg_yellow_lt" <?php if($this->_options[callMe][loader_background] == 'bg_yellow_lt') echo 'selected';?>>Loader - Background - Yellow Lite</option>
									<option value="bg_grey" <?php if($this->_options[callMe][loader_background] == 'bg_grey') echo 'selected';?>>Loader - Background - Grey</option>
									<option value="bg_grey_lt" <?php if($this->_options[callMe][loader_background] == 'bg_grey_lt') echo 'selected';?>>Loader - Background - Grey Lite</option>
									<option value="bg_red" <?php if($this->_options[callMe][loader_background] == 'bg_red') echo 'selected';?>>Loader - Background - Red</option>
									<option value="bg_red_lt" <?php if($this->_options[callMe][loader_background] == 'bg_red_lt') echo 'selected';?>>Loader - Background - Red Lite</option>
									<option value="bg_skyblue" <?php if($this->_options[callMe][loader_background] == 'bg_skyblue') echo 'selected';?>>Loader - Background - Skyblue</option>
									<option value="bg_skyblue_lt" <?php if($this->_options[callMe][loader_background] == 'bg_skyblue_lt') echo 'selected';?>>Loader - Background - Skyblue Lite</option>
									<option value="bg_blue" <?php if($this->_options[callMe][loader_background] == 'bg_blue') echo 'selected';?>>Loader - Background - Blue</option>
									<option value="bg_blue_lt" <?php if($this->_options[callMe][loader_background] == 'bg_blue_lt') echo 'selected';?>>Loader - Background - Blue Lite</option>
									<option value="bg_green" <?php if($this->_options[callMe][loader_background] == 'bg_green') echo 'selected';?>>Loader - Background - Green</option>
									<option value="bg_green_lt" <?php if($this->_options[callMe][loader_background] == 'bg_green_lt') echo 'selected';?>>Loader - Background - Green Lite</option>
									<option value="bg_black" <?php if($this->_options[callMe][loader_background] == 'bg_black') echo 'selected';?>>Loader - Background - Black</option>
									<option value="bg_black_lt" <?php if($this->_options[callMe][loader_background] == 'bg_black_lt') echo 'selected';?>>Loader - Background - Black Lite</option>
								</select>
								</label>
								<label>
								<select id="callMe_top" onchange="callMePreview();" name="callMe[top]">
									<option value="pq_top" <?php if($this->_options[callMe][top] == 'pq_top') echo 'selected';?>>Top</option>
									<option value="pq_middle" <?php if($this->_options[callMe][top] == 'pq_middle') echo 'selected';?>>Middle</option>
									<option value="pq_bottom" <?php if($this->_options[callMe][top] == 'pq_bottom') echo 'selected';?>>Bottom</option>
								</select>
								</label>
								<label>
									<select id="callMe_side" onchange="callMePreview();" name="callMe[side]">
									<option value="pq_left" <?php if($this->_options[callMe][side] == 'pq_left') echo 'selected';?>>Left</option>
									<option value="pq_right" <?php if($this->_options[callMe][side] == 'pq_right') echo 'selected';?>>Right</option>
									</select>
								</label>
							</div>	
							
						</div>
						<div class="settings exit">
							<div class="popup selected">
							<h3>POPUP</h3>
								<label>
								<select id="subscribeExit_background" onchange="subscribeExitPreview();" name="subscribeExit[background]">
								    <option value="bg_white" <?php if($this->_options[subscribeExit][background] == 'bg_white') echo 'selected';?>>Background - White</option>
									<option value="bg_white_lt" <?php if($this->_options[subscribeExit][background] == 'bg_white_lt') echo 'selected';?>>Background - White Lite</option>
									<option value="bg_iceblue" <?php if($this->_options[subscribeExit][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_iceblue_lt" <?php if($this->_options[subscribeExit][background] == 'bg_iceblue_lt') echo 'selected';?>>Background - Iceblue Lite</option>
									<option value="bg_beige" <?php if($this->_options[subscribeExit][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_beige_lt" <?php if($this->_options[subscribeExit][background] == 'bg_beige_lt') echo 'selected';?>>Background - Beige Lite</option>
									<option value="bg_lilac" <?php if($this->_options[subscribeExit][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
									<option value="bg_lilac_lt" <?php if($this->_options[subscribeExit][background] == 'bg_lilac_lt') echo 'selected';?>>Background - Lilac Lite</option>
									<option value="bg_wormwood" <?php if($this->_options[subscribeExit][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_wormwood_lt" <?php if($this->_options[subscribeExit][background] == 'bg_wormwood_lt') echo 'selected';?>>Background - Wormwood Lite</option>
									<option value="bg_yellow" <?php if($this->_options[subscribeExit][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_yellow_lt" <?php if($this->_options[subscribeExit][background] == 'bg_yellow_lt') echo 'selected';?>>Background - Yellow Lite</option>
									<option value="bg_grey" <?php if($this->_options[subscribeExit][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="bg_grey_lt" <?php if($this->_options[subscribeExit][background] == 'bg_grey_lt') echo 'selected';?>>Background - Grey Lite</option>
									<option value="bg_red" <?php if($this->_options[subscribeExit][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_red_lt" <?php if($this->_options[subscribeExit][background] == 'bg_red_lt') echo 'selected';?>>Background - Red Lite</option>
									<option value="bg_skyblue" <?php if($this->_options[subscribeExit][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_skyblue_lt" <?php if($this->_options[subscribeExit][background] == 'bg_skyblue_lt') echo 'selected';?>>Background - Skyblue Lite</option>
									<option value="bg_blue" <?php if($this->_options[subscribeExit][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_blue_lt" <?php if($this->_options[subscribeExit][background] == 'bg_blue_lt') echo 'selected';?>>Background - Blue Lite</option>
									<option value="bg_green" <?php if($this->_options[subscribeExit][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_green_lt" <?php if($this->_options[subscribeExit][background] == 'bg_green_lt') echo 'selected';?>>Background - Green Lite</option>
									<option value="bg_black" <?php if($this->_options[subscribeExit][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_black_lt" <?php if($this->_options[subscribeExit][background] == 'bg_black_lt') echo 'selected';?>>Background - Black Lite</option>
								</select>
								</label>
								
								<label>
								<select id="subscribeExit_button_color" onchange="subscribeExitPreview();" name="subscribeExit[button_color]">
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
								</select>
								</label>
								
								<label>
									<select id="subscribeExit_img" class="vibor"  name="subscribeExit[img]" onchange="subscribeExitPreview();changePopupImg(this.value, 'subscribeExitCustomFotoBlock');">
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
									</select>
								</label>
								<label class="custom_i" id="subscribeExitCustomFotoBlock"><p>Link</p>
									<input type="text" name="subscribeExit[imgUrl]" onkeyup="subscribeExitPreview();"  id="subscribeExitCustomFotoSrc" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[subscribeExit][imgUrl]);?>">
								</label>
								<script>
									changePopupImg('<?php echo $this->_options[subscribeExit][img];?>', 'subscribeExitCustomFotoBlock');
								</script>
								<label>
								<select id="subscribeExit_typeWindow" onchange="subscribeExitPreview();" name="subscribeExit[typeWindow]">
									<option value="pq_large" <?php if($this->_options[subscribeExit][typeWindow] == 'pq_large' || $this->_options[subscribeExit][typeWindow] == '') echo 'selected';?>>Size L</option>
									<option value="pq_medium" <?php if($this->_options[subscribeExit][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
									<option value="pq_mini" <?php if($this->_options[subscribeExit][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
								</select>
								</label>
								<h3>Animation</h3>
								<label>
								<select name="subscribeExit[animation]" id="subscribeExit_animation" onchange="subscribeExitPreview();">									
									<option value="bounceInDown" <?php if($this->_options[subscribeExit][animation] == 'bounceInDown') echo 'selected';?>>Bounce</option>
									<option value="fade" <?php if($this->_options[subscribeExit][animation] == 'fade' || $this->_options[subscribeExit][animation] == '') echo 'selected';?>>Fade In</option>
								</select>
								</label>
								
								<h3>Overlay</h3>
								<label>
								<select id="subscribeExit_overlay" onchange="subscribeExitPreview();" name="subscribeExit[overlay]">
								    <option value="over_grey" <?php if($this->_options[subscribeExit][overlay] == 'over_grey') echo 'selected';?>>Color overlay - Grey</option>
									<option value="over_white" <?php if($this->_options[subscribeExit][overlay] == 'over_white' || $this->_options[subscribeExit][overlay] == '') echo 'selected';?>>Color overlay - White</option>
									<option value="over_yellow" <?php if($this->_options[subscribeExit][overlay] == 'over_yellow') echo 'selected';?>>Color overlay - Yellow</option>
									<option value="over_wormwood" <?php if($this->_options[subscribeExit][overlay] == 'over_wormwood') echo 'selected';?>>Color overlay - Wormwood</option>
									<option value="over_blue" <?php if($this->_options[subscribeExit][overlay] == 'over_blue') echo 'selected';?>>Color overlay - Blue</option>
									<option value="over_green" <?php if($this->_options[subscribeExit][overlay] == 'over_green') echo 'selected';?>>Color overlay - Green</option>
									<option value="over_beige" <?php if($this->_options[subscribeExit][overlay] == 'over_beige') echo 'selected';?>>Color overlay - Beige</option>
									<option value="over_red" <?php if($this->_options[subscribeExit][overlay] == 'over_red') echo 'selected';?>>Color overlay - Red</option>
									<option value="over_iceblue" <?php if($this->_options[subscribeExit][overlay] == 'over_iceblue') echo 'selected';?>>Color overlay - Iceblue</option>
									<option value="over_black" <?php if($this->_options[subscribeExit][overlay] == 'over_black') echo 'selected';?>>Color overlay - Black</option>
									<option value="over_skyblue" <?php if($this->_options[subscribeExit][overlay] == 'over_skyblue') echo 'selected';?>>Color overlay - Skyblue</option>
									<option value="over_lilac" <?php if($this->_options[subscribeExit][overlay] == 'over_lilac') echo 'selected';?>>Color overlay - Lilac</option>
									<option value="over_grey_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_grey_lt') echo 'selected';?>>Color overlay - Grey - Light</option>
									<option value="over_white_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_white_lt') echo 'selected';?>>Color overlay - White - Light</option>
									<option value="over_yellow_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_yellow_lt') echo 'selected';?>>Color overlay - Yellow - Light</option>
									<option value="over_wormwood_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_wormwood_lt') echo 'selected';?>>Color overlay - Wormwood - Light</option>
									<option value="over_blue_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_blue_lt') echo 'selected';?>>Color overlay - Blue - Light</option>
									<option value="over_green_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_green_lt') echo 'selected';?>>Color overlay - Green - Light</option>
									<option value="over_beige_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_beige_lt') echo 'selected';?>>Color overlay - Beige - Light</option>
									<option value="over_red_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_red_lt') echo 'selected';?>>Color overlay - Red - Light</option>
									<option value="over_iceblue_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_iceblue_lt') echo 'selected';?>>Color overlay - Iceblue - Light</option>
									<option value="over_black_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_black_lt') echo 'selected';?>>Color overlay - Black - Light</option>
									<option value="over_skyblue_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_skyblue_lt') echo 'selected';?>>Color overlay - Skyblue - Light</option>
									<option value="over_lilac_lt" <?php if($this->_options[subscribeExit][overlay] == 'over_lilac_lt') echo 'selected';?>>Color overlay - Lilac - Light</option>
									<option value="over_grey_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_grey_solid') echo 'selected';?>>Color overlay - Grey - Solid</option>
									<option value="over_white_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_white_solid') echo 'selected';?>>Color overlay - White - Solid</option>
									<option value="over_yellow_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_yellow_solid') echo 'selected';?>>Color overlay - Yellow - Solid</option>
									<option value="over_wormwood_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_wormwood_solid') echo 'selected';?>>Color overlay - Wormwood - Solid</option>
									<option value="over_blue_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_blue_solid') echo 'selected';?>>Color overlay - Blue - Solid</option>
									<option value="over_green_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_green_solid') echo 'selected';?>>Color overlay - Green - Solid</option>
									<option value="over_beige_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_beige_solid') echo 'selected';?>>Color overlay - Beige - Solid</option>
									<option value="over_red_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_red_solid') echo 'selected';?>>Color overlay - Red - Solid</option>
									<option value="over_iceblue_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_iceblue_solid') echo 'selected';?>>Color overlay - Iceblue - Solid</option>
									<option value="over_black_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_black_solid') echo 'selected';?>>Color overlay - Black - Solid</option>
									<option value="over_skyblue_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_skyblue_solid') echo 'selected';?>>Color overlay - Skyblue - Solid</option>
									<option value="over_lilac_solid" <?php if($this->_options[subscribeExit][overlay] == 'over_lilac_solid') echo 'selected';?>>Color overlay - Lilac - Solid</option>
								</select>
								</label>		
							</div>
						</div>
						<div class="settings imagesharer">
							<div class="onmedia selected">
							<h3>ICONS SERVICES</h3>
								<label>
								<select id="imageSharer_position" onchange="imageSharerPreview();" name="imageSharer[position]">
									<option value="inline" <?php if($this->_options[imageSharer][position] == 'inline') echo 'selected';?>>Horizontally</option>
									<option name="" <?php if($this->_options[imageSharer][position] == '') echo 'selected';?>>Vertically</option>
								</select>
								</label>
								<label>
								<select id="imageSharer_design_form" onchange="imageSharerPreview();" name="imageSharer[design][form]">
									<option value="" <?php if($this->_options[imageSharer][design][form] == '') echo 'selected';?>>Square</option>
									<option value="circle" <?php if($this->_options[imageSharer][design][form] == 'circle') echo 'selected';?>>Circle</option>
									<option value="rounded" <?php if($this->_options[imageSharer][design][form] == 'rounded') echo 'selected';?>>Rounded</option>
								</select>
								</label>
								<label>
								<select id="imageSharer_design_size" onchange="imageSharerPreview();" name="imageSharer[design][size]">
									<option value="x20" <?php if($this->_options[imageSharer][design][size] == 'x20') echo 'selected';?>>Size S</option>
									<option value="x30" <?php if($this->_options[imageSharer][design][size] == 'x30') echo 'selected';?>>Size M</option>
									<option value="x40" <?php if($this->_options[imageSharer][design][size] == 'x40') echo 'selected';?>>Size M+</option>
									<option value="x50" <?php if($this->_options[imageSharer][design][size] == 'x50') echo 'selected';?>>Size L</option>
									<option value="x70" <?php if($this->_options[imageSharer][design][size] == 'x70') echo 'selected';?>>Size XL</option>
								</select>
								</label>
								<label>
								<select id="imageSharer_design_color" onchange="imageSharerPreview();" name="imageSharer[design][color]">
									<option value="c4" <?php if($this->_options[imageSharer][design][color] == 'c4') echo 'selected';?>>Color</option>
									<option value="c1" <?php if($this->_options[imageSharer][design][color] == 'c1') echo 'selected';?>>Color light</option>
									<option value="c2" <?php if($this->_options[imageSharer][design][color] == 'c2') echo 'selected';?>>Color volume</option>
									<option value="c3" <?php if($this->_options[imageSharer][design][color] == 'c3') echo 'selected';?>>Color dark</option>
									<option value="c5" <?php if($this->_options[imageSharer][design][color] == 'c5') echo 'selected';?>>Black</option>
									<option value="c6" <?php if($this->_options[imageSharer][design][color] == 'c6') echo 'selected';?>>Black volume</option>
									<option value="c7" <?php if($this->_options[imageSharer][design][color] == 'c7') echo 'selected';?>>White volume</option>
									<option value="c8" <?php if($this->_options[imageSharer][design][color] == 'c8') echo 'selected';?>>White</option>
									<option value="c9" <?php if($this->_options[sharingSideBar][design][color] == 'c9') echo 'selected';?>>Bordered - color</option>
									<option value="c10" <?php if($this->_options[sharingSideBar][design][color] == 'c10') echo 'selected';?>>Bordered - black</option>
									<option value="c11" <?php if($this->_options[sharingSideBar][design][color] == 'c11') echo 'selected';?>>Lightest</option>
								</select>
								</label>
								<h3>Shadow</h3>
								<label>
								<select id="imageSharer_design_shadow" onchange="imageSharerPreview();" name="imageSharer[design][shadow]">
								<option value="sh1" <?php if($this->_options[imageSharer][design][shadow] == 'sh1') echo 'selected';?>>Shadow1</option>
								<option value="sh2" <?php if($this->_options[imageSharer][design][shadow] == 'sh2') echo 'selected';?>>Shadow2</option>
								<option value="sh3" <?php if($this->_options[imageSharer][design][shadow] == 'sh3') echo 'selected';?>>Shadow3</option>
								<option value="sh4" <?php if($this->_options[imageSharer][design][shadow] == 'sh4') echo 'selected';?>>Shadow4</option>
								<option value="sh5" <?php if($this->_options[imageSharer][design][shadow] == 'sh5') echo 'selected';?>>Shadow5</option>
								<option value="sh6" <?php if($this->_options[imageSharer][design][shadow] == 'sh6') echo 'selected';?>>Shadow6</option>
							</select>
								</label>
								
							</div>
						</div>
						<div class="settings thankyou">
							<div class="popup selected">
								<h3>POPUP</h3>
								<label>
								<select id="thankPopup_background" onchange="thankPopupPreview();" name="thankPopup[background]">
									<option value="bg_white" <?php if($this->_options[thankPopup][background] == 'bg_white') echo 'selected';?>>Background - White</option>
									<option value="bg_white_lt" <?php if($this->_options[thankPopup][background] == 'bg_white_lt') echo 'selected';?>>Background - White Lite</option>
									<option value="bg_iceblue" <?php if($this->_options[thankPopup][background] == 'bg_iceblue') echo 'selected';?>>Background - Iceblue</option>
									<option value="bg_iceblue_lt" <?php if($this->_options[thankPopup][background] == 'bg_iceblue_lt') echo 'selected';?>>Background - Iceblue Lite</option>
									<option value="bg_beige" <?php if($this->_options[thankPopup][background] == 'bg_beige') echo 'selected';?>>Background - Beige</option>
									<option value="bg_beige_lt" <?php if($this->_options[thankPopup][background] == 'bg_beige_lt') echo 'selected';?>>Background - Beige Lite</option>
									<option value="bg_lilac" <?php if($this->_options[thankPopup][background] == 'bg_lilac') echo 'selected';?>>Background - Lilac</option>
									<option value="bg_lilac_lt" <?php if($this->_options[thankPopup][background] == 'bg_lilac_lt') echo 'selected';?>>Background - Lilac Lite</option>
									<option value="bg_wormwood" <?php if($this->_options[thankPopup][background] == 'bg_wormwood') echo 'selected';?>>Background - Wormwood</option>
									<option value="bg_wormwood_lt" <?php if($this->_options[thankPopup][background] == 'bg_wormwood_lt') echo 'selected';?>>Background - Wormwood Lite</option>
									<option value="bg_yellow" <?php if($this->_options[thankPopup][background] == 'bg_yellow') echo 'selected';?>>Background - Yellow</option>
									<option value="bg_yellow_lt" <?php if($this->_options[thankPopup][background] == 'bg_yellow_lt') echo 'selected';?>>Background - Yellow Lite</option>
									<option value="bg_grey" <?php if($this->_options[thankPopup][background] == 'bg_grey') echo 'selected';?>>Background - Grey</option>
									<option value="bg_grey_lt" <?php if($this->_options[thankPopup][background] == 'bg_grey_lt') echo 'selected';?>>Background - Grey Lite</option>
									<option value="bg_red" <?php if($this->_options[thankPopup][background] == 'bg_red') echo 'selected';?>>Background - Red</option>
									<option value="bg_red_lt" <?php if($this->_options[thankPopup][background] == 'bg_red_lt') echo 'selected';?>>Background - Red Lite</option>
									<option value="bg_skyblue" <?php if($this->_options[thankPopup][background] == 'bg_skyblue') echo 'selected';?>>Background - Skyblue</option>
									<option value="bg_skyblue_lt" <?php if($this->_options[thankPopup][background] == 'bg_skyblue_lt') echo 'selected';?>>Background - Skyblue Lite</option>
									<option value="bg_blue" <?php if($this->_options[thankPopup][background] == 'bg_blue') echo 'selected';?>>Background - Blue</option>
									<option value="bg_blue_lt" <?php if($this->_options[thankPopup][background] == 'bg_blue_lt') echo 'selected';?>>Background - Blue Lite</option>
									<option value="bg_green" <?php if($this->_options[thankPopup][background] == 'bg_green') echo 'selected';?>>Background - Green</option>
									<option value="bg_green_lt" <?php if($this->_options[thankPopup][background] == 'bg_green_lt') echo 'selected';?>>Background - Green Lite</option>
									<option value="bg_black" <?php if($this->_options[thankPopup][background] == 'bg_black') echo 'selected';?>>Background - Black</option>
									<option value="bg_black_lt" <?php if($this->_options[thankPopup][background] == 'bg_black_lt') echo 'selected';?>>Background - Black Lite</option>
								</select>
								</label>
								
								<label>
								<select id="thankPopup_button_color" onchange="thankPopupPreview();" name="thankPopup[button_color]">
								    <option value="btn_lightblue" <?php if($this->_options[thankPopup][button_color] == 'btn_lightblue') echo 'selected';?>>Button - Lightblue</option>
									<option value="btn_lightblue invert" <?php if($this->_options[thankPopup][button_color] == 'btn_lightblue invert' || $this->_options[thankPopup][button_color] == '') echo 'selected';?>>Button - Lightblue Transparent</option>
									<option value="btn_blue" <?php if($this->_options[thankPopup][button_color] == 'btn_blue') echo 'selected';?>>Button - Blue</option>
									<option value="btn_blue invert" <?php if($this->_options[thankPopup][button_color] == 'btn_blue invert') echo 'selected';?>>Button - Blue Transparent</option>
									<option value="btn_black" <?php if($this->_options[thankPopup][button_color] == 'btn_black') echo 'selected';?>>Button - Black</option>
									<option value="btn_black invert" <?php if($this->_options[thankPopup][button_color] == 'btn_black invert') echo 'selected';?>>Button - Black Transparent</option>
									<option value="btn_green" <?php if($this->_options[thankPopup][button_color] == 'btn_green') echo 'selected';?>>Button - Green</option>
									<option value="btn_green invert" <?php if($this->_options[thankPopup][button_color] == 'btn_green invert') echo 'selected';?>>Button - Green Transparent</option>
									<option value="btn_violet" <?php if($this->_options[thankPopup][button_color] == 'btn_violet') echo 'selected';?>>Button - Violet</option>
									<option value="btn_violet invert" <?php if($this->_options[thankPopup][button_color] == 'btn_violet invert') echo 'selected';?>>Button - Violet Transparent</option>
									<option value="btn_orange" <?php if($this->_options[thankPopup][button_color] == 'btn_orange') echo 'selected';?>>Button - Orange</option>
									<option value="btn_orange invert" <?php if($this->_options[thankPopup][button_color] == 'btn_orange invert') echo 'selected';?>>Button - Orange Transparent</option>
									<option value="btn_red" <?php if($this->_options[thankPopup][button_color] == 'btn_red') echo 'selected';?>>Button - Red</option>
									<option value="btn_red invert" <?php if($this->_options[thankPopup][button_color] == 'btn_red invert') echo 'selected';?>>Button - Red Transparent</option>
									<option value="btn_lilac" <?php if($this->_options[thankPopup][button_color] == 'btn_lilac') echo 'selected';?>>Button - Lilac</option>
									<option value="btn_lilac invert" <?php if($this->_options[thankPopup][button_color] == 'btn_lilac invert') echo 'selected';?>>Button - Lilac Transparent</option>
								</select>
								</label>
								
								<label>
									<select id="thankPopup_img" class="vibor" name="thankPopup[img]" onchange="thankPopupPreview();changePopupImg(this.value, 'thankPopupCustomFotoBlock');">
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
								<label class="custom_i" id="thankPopupCustomFotoBlock"><p>Link</p>
									<input type="text" name="thankPopup[imgUrl]" onchange="thankPopupPreview();" style="margin-top: 10px;" id="thankPopupCustomFotoSrc" placeholder="Enter your image URL" value="<?php echo stripslashes($this->_options[thankPopup][imgUrl])?>">
								</label>
								<script>
									changePopupImg('<?php echo $this->_options[thankPopup][img];?>', 'thankPopupCustomFotoBlock');
								</script>
								<label>
								<select name="thankPopup[typeWindow]" id="thankPopup_typeWindow" onchange="thankPopupPreview();">
									<option value="pq_large" <?php if($this->_options[thankPopup][typeWindow] == 'pq_large' || $this->_options[thankPopup][typeWindow] == '') echo 'selected';?>>Size L</option>
									<option value="pq_medium" <?php if($this->_options[thankPopup][typeWindow] == 'pq_medium') echo 'selected';?>>Size M</option>								
									<option value="pq_mini" <?php if($this->_options[thankPopup][typeWindow] == 'pq_mini') echo 'selected';?>>Size S</option>
								</select>
								</label>
								<h3>Animation</h3>
								<label>
								<select name="thankPopup[animation]" id="thankPopup_animation" onchange="thankPopupPreview();">
									<option value="bounceInDown" <?php if($this->_options[thankPopup][animation] == 'bounceInDown') echo 'selected';?>>Bounce</option>
									<option value="fade" <?php if($this->_options[thankPopup][animation] == 'fade' || $this->_options[thankPopup][animation] == '') echo 'selected';?>>Fade In</option>
								</select>
								</label>
								
								<h3>Overlay</h3>
								<label>
								<select name="thankPopup[overlay]" id="thankPopup_overlay" onchange="thankPopupPreview();">
								    <option value="over_grey" <?php if($this->_options[thankPopup][overlay] == 'over_grey') echo 'selected';?>>Color overlay - Grey</option>
									<option value="over_white" <?php if($this->_options[thankPopup][overlay] == 'over_white' || $this->_options[thankPopup][overlay] == '') echo 'selected';?>>Color overlay - White</option>
									<option value="over_yellow" <?php if($this->_options[thankPopup][overlay] == 'over_yellow') echo 'selected';?>>Color overlay - Yellow</option>
									<option value="over_wormwood" <?php if($this->_options[thankPopup][overlay] == 'over_wormwood') echo 'selected';?>>Color overlay - Wormwood</option>
									<option value="over_blue" <?php if($this->_options[thankPopup][overlay] == 'over_blue') echo 'selected';?>>Color overlay - Blue</option>
									<option value="over_green" <?php if($this->_options[thankPopup][overlay] == 'over_green') echo 'selected';?>>Color overlay - Green</option>
									<option value="over_beige" <?php if($this->_options[thankPopup][overlay] == 'over_beige') echo 'selected';?>>Color overlay - Beige</option>
									<option value="over_red" <?php if($this->_options[thankPopup][overlay] == 'over_red') echo 'selected';?>>Color overlay - Red</option>
									<option value="over_iceblue" <?php if($this->_options[thankPopup][overlay] == 'over_iceblue') echo 'selected';?>>Color overlay - Iceblue</option>
									<option value="over_black" <?php if($this->_options[thankPopup][overlay] == 'over_black') echo 'selected';?>>Color overlay - Black</option>
									<option value="over_skyblue" <?php if($this->_options[thankPopup][overlay] == 'over_skyblue') echo 'selected';?>>Color overlay - Skyblue</option>
									<option value="over_lilac" <?php if($this->_options[thankPopup][overlay] == 'over_lilac') echo 'selected';?>>Color overlay - Lilac</option>
									<option value="over_grey_lt" <?php if($this->_options[thankPopup][overlay] == 'over_grey_lt') echo 'selected';?>>Color overlay - Grey - Light</option>
									<option value="over_white_lt" <?php if($this->_options[thankPopup][overlay] == 'over_white_lt') echo 'selected';?>>Color overlay - White - Light</option>
									<option value="over_yellow_lt" <?php if($this->_options[thankPopup][overlay] == 'over_yellow_lt') echo 'selected';?>>Color overlay - Yellow - Light</option>
									<option value="over_wormwood_lt" <?php if($this->_options[thankPopup][overlay] == 'over_wormwood_lt') echo 'selected';?>>Color overlay - Wormwood - Light</option>
									<option value="over_blue_lt" <?php if($this->_options[thankPopup][overlay] == 'over_blue_lt') echo 'selected';?>>Color overlay - Blue - Light</option>
									<option value="over_green_lt" <?php if($this->_options[thankPopup][overlay] == 'over_green_lt') echo 'selected';?>>Color overlay - Green - Light</option>
									<option value="over_beige_lt" <?php if($this->_options[thankPopup][overlay] == 'over_beige_lt') echo 'selected';?>>Color overlay - Beige - Light</option>
									<option value="over_red_lt" <?php if($this->_options[thankPopup][overlay] == 'over_red_lt') echo 'selected';?>>Color overlay - Red - Light</option>
									<option value="over_iceblue_lt" <?php if($this->_options[thankPopup][overlay] == 'over_iceblue_lt') echo 'selected';?>>Color overlay - Iceblue - Light</option>
									<option value="over_black_lt" <?php if($this->_options[thankPopup][overlay] == 'over_black_lt') echo 'selected';?>>Color overlay - Black - Light</option>
									<option value="over_skyblue_lt" <?php if($this->_options[thankPopup][overlay] == 'over_skyblue_lt') echo 'selected';?>>Color overlay - Skyblue - Light</option>
									<option value="over_lilac_lt" <?php if($this->_options[thankPopup][overlay] == 'over_lilac_lt') echo 'selected';?>>Color overlay - Lilac - Light</option>
									<option value="over_grey_solid" <?php if($this->_options[thankPopup][overlay] == 'over_grey_solid') echo 'selected';?>>Color overlay - Grey - Solid</option>
									<option value="over_white_solid" <?php if($this->_options[thankPopup][overlay] == 'over_white_solid') echo 'selected';?>>Color overlay - White - Solid</option>
									<option value="over_yellow_solid" <?php if($this->_options[thankPopup][overlay] == 'over_yellow_solid') echo 'selected';?>>Color overlay - Yellow - Solid</option>
									<option value="over_wormwood_solid" <?php if($this->_options[thankPopup][overlay] == 'over_wormwood_solid') echo 'selected';?>>Color overlay - Wormwood - Solid</option>
									<option value="over_blue_solid" <?php if($this->_options[thankPopup][overlay] == 'over_blue_solid') echo 'selected';?>>Color overlay - Blue - Solid</option>
									<option value="over_green_solid" <?php if($this->_options[thankPopup][overlay] == 'over_green_solid') echo 'selected';?>>Color overlay - Green - Solid</option>
									<option value="over_beige_solid" <?php if($this->_options[thankPopup][overlay] == 'over_beige_solid') echo 'selected';?>>Color overlay - Beige - Solid</option>
									<option value="over_red_solid" <?php if($this->_options[thankPopup][overlay] == 'over_red_solid') echo 'selected';?>>Color overlay - Red - Solid</option>
									<option value="over_iceblue_solid" <?php if($this->_options[thankPopup][overlay] == 'over_iceblue_solid') echo 'selected';?>>Color overlay - Iceblue - Solid</option>
									<option value="over_black_solid" <?php if($this->_options[thankPopup][overlay] == 'over_black_solid') echo 'selected';?>>Color overlay - Black - Solid</option>
									<option value="over_skyblue_solid" <?php if($this->_options[thankPopup][overlay] == 'over_skyblue_solid') echo 'selected';?>>Color overlay - Skyblue - Solid</option>
									<option value="over_lilac_solid" <?php if($this->_options[thankPopup][overlay] == 'over_lilac_solid') echo 'selected';?>>Color overlay - Lilac - Solid</option>
							</select>
								</label>
								</div>
						</div>
						
						
					</div>
						<div class="pq_button_group button_group_li4">
							<input type="button" value="prev" class="ToLi3">
							<input type="button" value="Next" class="ToLi5">
							<br><input type="submit" value="Save and Activate" class="share_submit">
							<input type="submit" value="Save and Activate" class="follow_submit">
							<input type="submit" value="Save and Activate" class="contact_submit">
							<input type="submit" value="Save and Activate" class="callme_submit">
							<input type="submit" value="Save and Activate" class="exit_submit">
							<input type="submit" value="Save and Activate" class="imagesharer_submit">
							<input type="submit" value="Save and Activate" class="thankyou_submit">
							<input type="submit" value="Save and Activate" class="marketing_submit">
							<input type="submit" value="Save and Activate" class="collect_submit">
						</div>
					<div class="f_wrapper pq_li5">
					
						<div class="settings share selected">
							<div class="sidebar selected">
								
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>DISPLAY RULES</h3><a href="#top" class="tooltip_pro">?</a>
								<label><p>Disable Main Page</p>
									<select name="proOptions[sharingSideBar][disableMainPage]">
										<option value="1" <?php if((int)$this->_options[proOptions][sharingSideBar][disableMainPage] == 1) echo 'selected';?>>Yes</option>
										<option value="0" <?php if((int)$this->_options[proOptions][sharingSideBar][disableMainPage] == 0) echo 'selected';?>>No</option>
									</select>
									
								</label>								
								<label><p>Disable Exept Url Mask</p>
								<input type="text" name="proOptions[sharingSideBar][disableExeptPageMask][0]" value="<?php echo stripslashes($this->_options[proOptions][sharingSideBar][disableExeptPageMask][0]);?>">
								
								</label>
								<label>
								<input type="text" name="proOptions[sharingSideBar][disableExeptPageMask][1]" value="<?php echo stripslashes($this->_options[proOptions][sharingSideBar][disableExeptPageMask][1]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[sharingSideBar][disableExeptPageMask][2]" value="<?php echo stripslashes($this->_options[proOptions][sharingSideBar][disableExeptPageMask][2]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[sharingSideBar][disableExeptPageMask][3]" value="<?php echo stripslashes($this->_options[proOptions][sharingSideBar][disableExeptPageMask][3]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[sharingSideBar][disableExeptPageMask][4]" value="<?php echo stripslashes($this->_options[proOptions][sharingSideBar][disableExeptPageMask][4]);?>">
								</label>								
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[sharingSideBar][animation]" id="sharingSideBar_animation" onchange="sharingSideBarPreview();">
									<option value="" <?php if($this->_options[proOptions][sharingSideBar][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][sharingSideBar][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								
								</label>
								<label><p>Hover</p>
								<select name="proOptions[sharingSideBar][hover_animation]" id="sharingSideBar_hover_animation" onchange="sharingSideBarPreview();">
									<option value="" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_ha_hvr_grow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_grow') echo 'selected';?>>Grow</option>
									<option value="pq_pro_ha_hvr_shrink" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_shrink') echo 'selected';?>>Shrink</option>
									<option value="pq_pro_ha_hvr_pulse" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_pulse') echo 'selected';?>>Pulse</option>
									<option value="pq_pro_ha_hvr_pulse_grow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_pulse_grow') echo 'selected';?>>Pulse Grow</option>
									<option value="pq_pro_ha_hvr_push" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_push') echo 'selected';?>>Push</option>
									<option value="pq_pro_ha_hvr_pop" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_pop') echo 'selected';?>>Pop</option>
									<option value="pq_pro_ha_hvr_bounce_in" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_bounce_in') echo 'selected';?>>Bounce In</option>
									<option value="pq_pro_ha_hvr_bounce_out" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_bounce_out') echo 'selected';?>>Bounce Out</option>
									<option value="pq_pro_ha_hvr_rotate" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_rotate') echo 'selected';?>>Rotate</option>
									<option value="pq_pro_ha_hvr_grow_rotate" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_grow_rotate') echo 'selected';?>>Grow Rotate</option>
									<option value="pq_pro_ha_hvr_sink" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_sink') echo 'selected';?>>Sink</option>
									<option value="pq_pro_ha_hvr_bob" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_bob') echo 'selected';?>>Bob</option>
									<option value="pq_pro_ha_hvr_hang" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_hang') echo 'selected';?>>Hang</option>
									<option value="pq_pro_ha_hvr_skew" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_skew') echo 'selected';?>>Skew</option>
									<option value="pq_pro_ha_hvr_skew_forward" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_skew_forward') echo 'selected';?>>Skew Forward</option>
									<option value="pq_pro_ha_hvr_skew_backward" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_skew_backward') echo 'selected';?>>Skew Backward</option>
									<option value="pq_pro_ha_hvr_wobble_vertical" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_wobble_vertical') echo 'selected';?>>Wobble Vertical</option>
									<option value="pq_pro_ha_hvr_wobble_horizontal" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_wobble_horizontal') echo 'selected';?>>Wobble Horizontal</option>
									<option value="pq_pro_ha_hvr_wobble_to_bottom_right" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_wobble_to_bottom_right') echo 'selected';?>>Wobble B&amp;R</option>
									<option value="pq_pro_ha_hvr_wobble_top" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_wobble_top') echo 'selected';?>>Wobble Top</option>
									<option value="pq_pro_ha_hvr_wobble_bottom" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_wobble_bottom') echo 'selected';?>>Wobble Bottom</option>
									<option value="pq_pro_ha_hvr_wobble_skew" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_wobble_skew') echo 'selected';?>>Wobble Skew</option>
									<option value="pq_pro_ha_hvr_buzz" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_buzz') echo 'selected';?>>Buzz</option>
									<option value="pq_pro_ha_hvr_buzz_out" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_buzz_out') echo 'selected';?>>Buzz Out</option>
									<option value="pq_pro_ha_hvr_border_fade" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_border_fade') echo 'selected';?>>Bdr Fade</option>
									<option value="pq_pro_ha_hvr_hollow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_hollow') echo 'selected';?>>Hollow</option>
									<option value="pq_pro_ha_hvr_trim" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_trim') echo 'selected';?>>Trim</option>
									<option value="pq_pro_ha_hvr_ripple_out" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_ripple_out') echo 'selected';?>>Ripple Out</option>
									<option value="pq_pro_ha_hvr_ripple_in" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_ripple_in') echo 'selected';?>>Ripple In</option>
									<option value="pq_pro_ha_hvr_glow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_glow') echo 'selected';?>>Glow</option>
									<option value="pq_pro_ha_hvr_shadow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_shadow') echo 'selected';?>>Shadow</option>
									<option value="pq_pro_ha_hvr_grow_shadow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_grow_shadow') echo 'selected';?>>Grow Shadow</option>
									<option value="pq_pro_ha_hvr_float_shadow" <?php if($this->_options[proOptions][sharingSideBar][hover_animation] == 'pq_pro_ha_hvr_float_shadow') echo 'selected';?>>Float Shadow</option>
								</select>
								
								</label>
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[sharingSideBar][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][sharingSideBar][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][sharingSideBar][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>
						<div class="settings imagesharer">
							<div class="onmedia selected">
								
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>DISPLAY RULES</h3><a class="tooltip_pro">?</a>
								<label><p>Disable Main Page</p>
									<select name="proOptions[imageSharer][disableMainPage]">
										<option value="1" <?php if((int)$this->_options[proOptions][imageSharer][disableMainPage] == 1) echo 'selected';?>>Yes</option>
										<option value="0" <?php if((int)$this->_options[proOptions][imageSharer][disableMainPage] == 0) echo 'selected';?>>No</option>
									</select>
								</label>								
								<label><p>Disable Exept Url Mask</p>
								<input type="text" name="proOptions[imageSharer][disableExeptPageMask][0]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptPageMask][0]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[imageSharer][disableExeptPageMask][1]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptPageMask][1]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[imageSharer][disableExeptPageMask][2]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptPageMask][2]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[imageSharer][disableExeptPageMask][3]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptPageMask][3]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[imageSharer][disableExeptPageMask][4]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptPageMask][4]);?>">
								</label>								
								<h3>SHARE IMAGE</h3><a class="tooltip_pro">?</a>
								<label>
									<img class="picture" src="<?php echo plugins_url('i/capture.png', __FILE__);?>" />
									<div><p>&#8597;</p> 
										<label>
											<select name="proOptions[imageSharer][minHeight]" >
												<option value="0" selected>0 px and more</option>
												<option value="100" <?php if((int)$this->_options[proOptions][imageSharer][minHeight] == 100) echo 'selected';?>>100 px and more</option>
												<option value="200" <?php if((int)$this->_options[proOptions][imageSharer][minHeight] == 200) echo 'selected';?>>200 px and more</option>
												<option value="300" <?php if((int)$this->_options[proOptions][imageSharer][minHeight] == 300) echo 'selected';?>>300 px and more</option>
												<option value="400" <?php if((int)$this->_options[proOptions][imageSharer][minHeight] == 400) echo 'selected';?>>400 px and more</option>
												<option value="500" <?php if((int)$this->_options[proOptions][imageSharer][minHeight] == 500) echo 'selected';?>>500 px and more</option>
												<option value="600" <?php if((int)$this->_options[proOptions][imageSharer][minHeight] == 600) echo 'selected';?>>600 px and more</option>
											</select>
										</label>
										<p>&#8597;</p></div>
								</label>
								<label><p>Disable All Exept Image Extensions</p>
									<input type="text" name="proOptions[imageSharer][disableExeptExtensions][0]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptExtensions][0]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptExtensions][1]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptExtensions][1]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptExtensions][2]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptExtensions][2]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptExtensions][3]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptExtensions][3]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptExtensions][4]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptExtensions][4]);?>">
								</label>
								<label><p>Disable All Exept Image Url Mask</p>
									<input type="text" name="proOptions[imageSharer][disableExeptImageUrlMask][0]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptImageUrlMask][0]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptImageUrlMask][1]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptImageUrlMask][1]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptImageUrlMask][2]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptImageUrlMask][2]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptImageUrlMask][3]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptImageUrlMask][3]);?>">
								</label>
								<label>
									<input type="text" name="proOptions[imageSharer][disableExeptImageUrlMask][4]" value="<?php echo stripslashes($this->_options[proOptions][imageSharer][disableExeptImageUrlMask][4]);?>">
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>								
								<label><p>Hover</p>
								<select name="proOptions[imageSharer][hover_animation]" id="imageSharer_hover_animation" onchange="imageSharerPreview()">
									<option value="" <?php if($this->_options[proOptions][imageSharer][hover_animation] == '') echo 'selected';?>>Default</option-->
									<option value="pq_pro_ha_hvr_grow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_grow') echo 'selected';?>>Grow</option>
									<option value="pq_pro_ha_hvr_shrink" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_shrink') echo 'selected';?>>Shrink</option>
									<option value="pq_pro_ha_hvr_pulse" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_pulse') echo 'selected';?>>Pulse</option>
									<option value="pq_pro_ha_hvr_pulse_grow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_pulse_grow') echo 'selected';?>>Pulse Grow</option>
									<option value="pq_pro_ha_hvr_push" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_push') echo 'selected';?>>Push</option>
									<option value="pq_pro_ha_hvr_pop" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_pop') echo 'selected';?>>Pop</option>
									<option value="pq_pro_ha_hvr_bounce_in" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_bounce_in') echo 'selected';?>>Bounce In</option>
									<option value="pq_pro_ha_hvr_bounce_out" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_bounce_out') echo 'selected';?>>Bounce Out</option>
									<option value="pq_pro_ha_hvr_rotate" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_rotate') echo 'selected';?>>Rotate</option>
									<option value="pq_pro_ha_hvr_grow_rotate" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_grow_rotate') echo 'selected';?>>Grow Rotate</option>
									<option value="pq_pro_ha_hvr_sink" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_sink') echo 'selected';?>>Sink</option>
									<option value="pq_pro_ha_hvr_bob" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_bob') echo 'selected';?>>Bob</option>
									<option value="pq_pro_ha_hvr_hang" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_hang') echo 'selected';?>>Hang</option>
									<option value="pq_pro_ha_hvr_skew" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_skew') echo 'selected';?>>Skew</option>
									<option value="pq_pro_ha_hvr_skew_forward" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_skew_forward') echo 'selected';?>>Skew Forward</option>
									<option value="pq_pro_ha_hvr_skew_backward" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_skew_backward') echo 'selected';?>>Skew Backward</option>
									<option value="pq_pro_ha_hvr_wobble_vertical" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_wobble_vertical') echo 'selected';?>>Wobble Vertical</option>
									<option value="pq_pro_ha_hvr_wobble_horizontal" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_wobble_horizontal') echo 'selected';?>>Wobble Horizontal</option>
									<option value="pq_pro_ha_hvr_wobble_to_bottom_right" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_wobble_to_bottom_right') echo 'selected';?>>Wobble B&amp;R</option>
									<option value="pq_pro_ha_hvr_wobble_top" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_wobble_top') echo 'selected';?>>Wobble Top</option>
									<option value="pq_pro_ha_hvr_wobble_bottom" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_wobble_bottom') echo 'selected';?>>Wobble Bottom</option>
									<option value="pq_pro_ha_hvr_wobble_skew" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_wobble_skew') echo 'selected';?>>Wobble Skew</option>
									<option value="pq_pro_ha_hvr_buzz" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_buzz') echo 'selected';?>>Buzz</option>
									<option value="pq_pro_ha_hvr_buzz_out" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_buzz_out') echo 'selected';?>>Buzz Out</option>
									<option value="pq_pro_ha_hvr_border_fade" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_border_fade') echo 'selected';?>>Bdr Fade</option>
									<option value="pq_pro_ha_hvr_hollow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_hollow') echo 'selected';?>>Hollow</option>
									<option value="pq_pro_ha_hvr_trim" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_trim') echo 'selected';?>>Trim</option>
									<option value="pq_pro_ha_hvr_ripple_out" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_ripple_out') echo 'selected';?>>Ripple Out</option>
									<option value="pq_pro_ha_hvr_ripple_in" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_ripple_in') echo 'selected';?>>Ripple In</option>
									<option value="pq_pro_ha_hvr_glow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_glow') echo 'selected';?>>Glow</option>
									<option value="pq_pro_ha_hvr_shadow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_shadow') echo 'selected';?>>Shadow</option>
									<option value="pq_pro_ha_hvr_grow_shadow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_grow_shadow') echo 'selected';?>>Grow Shadow</option>
									<option value="pq_pro_ha_hvr_float_shadow" <?php if($this->_options[proOptions][imageSharer][hover_animation] == 'pq_pro_ha_hvr_float_shadow') echo 'selected';?>>Float Shadow</option-->
								</select>
								</label>
								
							</div>
						</div>
						<div class="settings marketing">
							<div class="pq_bar selected">
								
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>DISPLAY RULES</h3><a class="tooltip_pro">?</a>
								<label><p>Disable Main Page</p>
									<select name="proOptions[subscribeBar][disableMainPage]">
										<option value="1" <?php if((int)$this->_options[proOptions][subscribeBar][disableMainPage] == 1) echo 'selected';?>>Yes</option>
										<option value="0" <?php if((int)$this->_options[proOptions][subscribeBar][disableMainPage] == 0) echo 'selected';?>>No</option>
									</select>
								</label>
								<label><p>Disable Exept Url Mask</p>
								<input type="text" name="proOptions[subscribeBar][disableExeptPageMask][0]" value="<?php echo stripslashes($this->_options[proOptions][subscribeBar][disableExeptPageMask][0]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeBar][disableExeptPageMask][1]" value="<?php echo stripslashes($this->_options[proOptions][subscribeBar][disableExeptPageMask][1]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeBar][disableExeptPageMask][2]" value="<?php echo stripslashes($this->_options[proOptions][subscribeBar][disableExeptPageMask][2]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeBar][disableExeptPageMask][3]" value="<?php echo stripslashes($this->_options[proOptions][subscribeBar][disableExeptPageMask][3]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeBar][disableExeptPageMask][4]" value="<?php echo stripslashes($this->_options[proOptions][subscribeBar][disableExeptPageMask][4]);?>">
								</label>
								<h3>Design</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="subscribeBar_pro_1" onchange="subscribeBarPreview();" name="proOptions[subscribeBar][font]">
									<option value="pq_pro_arial" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_arial') echo 'selected';?>>Text - Arial</option>
									<option value="pq_pro_georgia" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_georgia') echo 'selected';?>>Text - Georgia</option>
									<option value="pq_pro_helvetica" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_helvetica') echo 'selected';?>>Text - Helvetica</option>
									<option value="pq_pro_courier" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_courier') echo 'selected';?>>Text - Courier New</option>
									<option value="pq_pro_times" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_times') echo 'selected';?>>Text - Times New Roman</option>
									<option value="pq_pro_verdana" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_verdana') echo 'selected';?>>Text - Verdana</option>
									<option value="pq_pro_arial_black" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_arial_black') echo 'selected';?>>Text - Arial Black</option>
									<option value="pq_pro_comic" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_comic') echo 'selected';?>>Text - Comic Sans MS</option>
									<option value="pq_pro_impact" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_impact') echo 'selected';?>>Text - Impact</option>
									<option value="pq_pro_trebuchet" <?php if($this->_options[proOptions][subscribeBar][font] == 'pq_pro_trebuchet') echo 'selected';?>>Text - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="subscribeBar_pro_2" onchange="subscribeBarPreview();" name="proOptions[subscribeBar][shape]">
									<option value="" <?php if($this->_options[proOptions][subscribeBar][shape] == '') echo 'selected';?>>Button Shape - Default</option>
									<option value="pq_pro_br_sq" <?php if($this->_options[proOptions][subscribeBar][shape] == 'pq_pro_br_sq') echo 'selected';?>>Button Shape - Square</option>
									<option value="pq_pro_br_cr" <?php if($this->_options[proOptions][subscribeBar][shape] == 'pq_pro_br_cr') echo 'selected';?>>Button Shape - Rounded</option>
								</select>
								</label>
								
								<label>
									<select id="subscribeBar_pro_3" onchange="subscribeBarPreview();" name="proOptions[subscribeBar][b_c_color]">
										<option value="pq_pro_x_color_white" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_white') echo 'selected';?>>Cross color - White</option>
										<option value="pq_pro_x_color_iceblue" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_iceblue') echo 'selected';?>>Cross color - Iceblue</option>
										<option value="pq_pro_x_color_beige" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_beige') echo 'selected';?>>Cross color - Beige</option>
										<option value="pq_pro_x_color_lilac" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_lilac') echo 'selected';?>>Cross color - Lilac</option>
										<option name="pq_pro_x_color_wormwood" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_wormwood') echo 'selected';?>>Cross color - Wormwood</option>
										<option name="pq_pro_x_color_yellow" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_yellow') echo 'selected';?>>Cross color - Yellow</option>
										<option name="pq_pro_x_color_grey" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_grey') echo 'selected';?>>Cross color - Grey</option>
										<option value="pq_pro_x_color_red" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_red') echo 'selected';?>>Cross color - Red</option>
										<option value="pq_pro_x_color_skyblue" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_skyblue') echo 'selected';?>>Cross color - Skyblue</option>
										<option value="pq_pro_x_color_blue" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_blue') echo 'selected';?>>Cross color - Blue</option>
										<option value="pq_pro_x_color_green" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_green') echo 'selected';?>>Cross color - Green</option>
										<option name="pq_pro_x_color_black" <?php if($this->_options[proOptions][subscribeBar][border_color] == 'pq_pro_x_color_black') echo 'selected';?>>Cross color - Black</option>
									</select>
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="subscribeBar_pro_animation" onchange="subscribeBarPreview();" name="proOptions[subscribeBar][animation]">
									<option value="" <?php if($this->_options[proOptions][subscribeBar][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][subscribeBar][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								</label>
								
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[subscribeBar][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][subscribeBar][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][subscribeBar][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>
						
						<div class="settings exit">
							<div class="popup selected">
								
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>DISPLAY RULES</h3><a class="tooltip_pro">?</a>
								<label><p>Disable Main Page</p>
									<select name="proOptions[subscribeExit][disableMainPage]">
										<option value="1" <?php if((int)$this->_options[proOptions][subscribeExit][disableMainPage] == 1) echo 'selected';?>>Yes</option>
										<option value="0" <?php if((int)$this->_options[proOptions][subscribeExit][disableMainPage] == 0) echo 'selected';?>>No</option>
									</select>
								</label>
								<label><p>Disable Exept Url Mask</p>
								<input type="text" name="proOptions[subscribeExit][disableExeptPageMask][0]" value="<?php echo stripslashes($this->_options[proOptions][subscribeExit][disableExeptPageMask][0]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeExit][disableExeptPageMask][1]" value="<?php echo stripslashes($this->_options[proOptions][subscribeExit][disableExeptPageMask][1]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeExit][disableExeptPageMask][2]" value="<?php echo stripslashes($this->_options[proOptions][subscribeExit][disableExeptPageMask][2]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeExit][disableExeptPageMask][3]" value="<?php echo stripslashes($this->_options[proOptions][subscribeExit][disableExeptPageMask][3]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[subscribeExit][disableExeptPageMask][4]" value="<?php echo stripslashes($this->_options[proOptions][subscribeExit][disableExeptPageMask][4]);?>">
								</label>
								<h3>Design</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="subscribeExit_pro_1" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][head_font]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][head_font] == '') echo 'selected';?>>Heading and Button - PT Sans Narrow</option>
									<option value="pq_pro_h_georgia" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_georgia') echo 'selected';?>>Heading and Button - Georgia</option>
									<option value="pq_pro_h_helvetica" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_helvetica') echo 'selected';?>>Heading and Button - Helvetica</option>
									<option value="pq_pro_h_courier" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_courier') echo 'selected';?>>Heading and Button - Courier New</option>
									<option value="pq_pro_h_times" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_times') echo 'selected';?>>Heading and Button - Times New Roman</option>
									<option value="pq_pro_h_verdana" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_verdana') echo 'selected';?>>Heading and Button - Verdana</option>
									<option value="pq_pro_h_arial_black" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_arial_black') echo 'selected';?>>Heading and Button - Arial Black</option>
									<option value="pq_pro_h_comic" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_comic') echo 'selected';?>>Heading and Button - Comic Sans MS</option>
									<option value="pq_pro_h_impact" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_impact') echo 'selected';?>>Heading and Button - Impact</option>
									<option value="pq_pro_h_trebuchet" <?php if($this->_options[proOptions][subscribeExit][head_font] == 'pq_pro_h_trebuchet') echo 'selected';?>>Heading and Button - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="subscribeExit_pro_2" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][head_size]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][head_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_head_size16" <?php if($this->_options[proOptions][subscribeExit][head_size] == 'pq_pro_head_size16') echo 'selected';?>>Heading Size - 16</option>
									<option value="pq_pro_head_size20" <?php if($this->_options[proOptions][subscribeExit][head_size] == 'pq_pro_head_size20') echo 'selected';?>>Heading Size - 20</option>
									<option value="pq_pro_head_size24" <?php if($this->_options[proOptions][subscribeExit][head_size] == 'pq_pro_head_size24') echo 'selected';?>>Heading Size - 24</option>
									<option value="pq_pro_head_size28" <?php if($this->_options[proOptions][subscribeExit][head_size] == 'pq_pro_head_size28') echo 'selected';?>>Heading Size - 28</option>
									<option value="pq_pro_head_size36" <?php if($this->_options[proOptions][subscribeExit][head_size] == 'pq_pro_head_size36') echo 'selected';?>>Heading Size - 36</option>
									<option value="pq_pro_head_size48" <?php if($this->_options[proOptions][subscribeExit][head_size] == 'pq_pro_head_size48') echo 'selected';?>>Heading Size - 48</option>
								</select>
								</label>
								<label>
									<select id="subscribeExit_pro_3" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][head_color]">
										<option value="pq_pro_head_color_white" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_white') echo 'selected';?>>Heading color - White</option>
										<option value="pq_pro_head_color_iceblue" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_iceblue') echo 'selected';?>>Heading color - Iceblue</option>
										<option value="pq_pro_head_color_beige" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_beige') echo 'selected';?>>Heading color - Beige</option>
										<option value="pq_pro_head_color_lilac" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_lilac') echo 'selected';?>>Heading color - Lilac</option>
										<option value="pq_pro_head_color_wormwood" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_wormwood') echo 'selected';?>>Heading color - Wormwood</option>
										<option value="pq_pro_head_color_yellow" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_yellow') echo 'selected';?>>Heading color - Yellow</option>
										<option value="pq_pro_head_color_grey" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_grey') echo 'selected';?>>Heading color - Grey</option>
										<option value="pq_pro_head_color_red" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_red') echo 'selected';?>>Heading color - Red</option>
										<option value="pq_pro_head_color_skyblue" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_skyblue') echo 'selected';?>>Heading color - Skyblue</option>
										<option value="pq_pro_head_color_blue" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_blue') echo 'selected';?>>Heading color - Blue</option>
										<option value="pq_pro_head_color_green" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_green') echo 'selected';?>>Heading color - Green</option>
										<option value="pq_pro_head_color_black" <?php if($this->_options[proOptions][subscribeExit][head_color] == 'pq_pro_head_color_black') echo 'selected';?>>Heading color - Black</option>
									</select>
								</label>
								<label>
								<select id="subscribeExit_pro_4" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][font]">
									<option value="pq_pro_arial" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_arial') echo 'selected';?>>Text - Arial</option>
									<option value="pq_pro_georgia" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_georgia') echo 'selected';?>>Text - Georgia</option>
									<option value="pq_pro_helvetica" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_helvetica') echo 'selected';?>>Text - Helvetica</option>
									<option value="pq_pro_courier" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_courier') echo 'selected';?>>Text - Courier New</option>
									<option value="pq_pro_times" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_times') echo 'selected';?>>Text - Times New Roman</option>
									<option value="pq_pro_verdana" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_verdana') echo 'selected';?>>Text - Verdana</option>
									<option value="pq_pro_arial_black" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_arial_black') echo 'selected';?>>Text - Arial Black</option>
									<option value="pq_pro_comic" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_comic') echo 'selected';?>>Text - Comic Sans MS</option>
									<option value="pq_pro_impact" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_impact') echo 'selected';?>>Text - Impact</option>
									<option value="pq_pro_trebuchet" <?php if($this->_options[proOptions][subscribeExit][font] == 'pq_pro_trebuchet') echo 'selected';?>>Text - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="subscribeExit_pro_5" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][text_size]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][text_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_text_size12" <?php if($this->_options[proOptions][subscribeExit][text_size] == 'pq_pro_text_size12') echo 'selected';?>>Text Size - 12</option>
									<option value="pq_pro_text_size16" <?php if($this->_options[proOptions][subscribeExit][text_size] == 'pq_pro_text_size16') echo 'selected';?>>Text Size - 16</option>
									<option value="pq_pro_text_size20" <?php if($this->_options[proOptions][subscribeExit][text_size] == 'pq_pro_text_size20') echo 'selected';?>>Text Size - 20</option>
									<option value="pq_pro_text_size24" <?php if($this->_options[proOptions][subscribeExit][text_size] == 'pq_pro_text_size24') echo 'selected';?>>Text Size - 24</option>
									<option value="pq_pro_text_size28" <?php if($this->_options[proOptions][subscribeExit][text_size] == 'pq_pro_text_size28') echo 'selected';?>>Text Size - 28</option>
									<option value="pq_pro_text_size36" <?php if($this->_options[proOptions][subscribeExit][text_size] == 'pq_pro_text_size36') echo 'selected';?>>Text Size - 36</option>	
								</select>
								</label>
								<label>
									<select id="subscribeExit_pro_6" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][text_color]">
										<option value="pq_pro_text_color_white" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_white') echo 'selected';?>>Text color - White</option>
										<option value="pq_pro_text_color_iceblue" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_iceblue') echo 'selected';?>>Text color - Iceblue</option>
										<option value="pq_pro_text_color_beige" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_beige') echo 'selected';?>>Text color - Beige</option>
										<option value="pq_pro_text_color_lilac" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_lilac') echo 'selected';?>>Text color - Lilac</option>
										<option value="pq_pro_text_color_wormwood" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_wormwood') echo 'selected';?>>Text color - Wormwood</option>
										<option value="pq_pro_text_color_yellow" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_yellow') echo 'selected';?>>Text color - Yellow</option>
										<option value="pq_pro_text_color_grey" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_grey') echo 'selected';?>>Text color - Grey</option>
										<option value="pq_pro_text_color_red" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_red') echo 'selected';?>>Text color - Red</option>
										<option value="pq_pro_text_color_skyblue" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_skyblue') echo 'selected';?>>Text color - Skyblue</option>
										<option value="pq_pro_text_color_blue" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_blue') echo 'selected';?>>Text color - Blue</option>
										<option value="pq_pro_text_color_green" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_green') echo 'selected';?>>Text color - Green</option>
										<option value="pq_pro_text_color_black" <?php if($this->_options[proOptions][subscribeExit][text_color] == 'pq_pro_text_color_black') echo 'selected';?>>Text color - Black</option>
									</select>
								</label>
								<label>
								<select id="subscribeExit_pro_7" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][b_radius]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][b_radius] == '') echo 'selected';?>>Border and Button Radius - Default</option>
									<option value="pq_pro_br_sq" <?php if($this->_options[proOptions][subscribeExit][b_radius] == 'pq_pro_br_sq') echo 'selected';?>>Border and Button Radius - Square</option>
									<option value="pq_pro_br_cr" <?php if($this->_options[proOptions][subscribeExit][b_radius] == 'pq_pro_br_cr') echo 'selected';?>>Border and Button Radius - Rounded</option>
								</select>
								</label>
								<label>
								<select id="subscribeExit_pro_9" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][b_width]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][b_width] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_bd1" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd1') echo 'selected';?>>Border Width - 1px</option>
									<option value="pq_pro_bd2" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd2') echo 'selected';?>>Border Width - 2px</option>
									<option value="pq_pro_bd3" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd3') echo 'selected';?>>Border Width - 3px</option>
									<option value="pq_pro_bd4" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd4') echo 'selected';?>>Border Width - 4px</option>
									<option value="pq_pro_bd5" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd5') echo 'selected';?>>Border Width - 5px</option>
									<option value="pq_pro_bd6" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd6') echo 'selected';?>>Border Width - 6px</option>
									<option value="pq_pro_bd7" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd7') echo 'selected';?>>Border Width - 7px</option>
									<option value="pq_pro_bd8" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd8') echo 'selected';?>>Border Width - 8px</option>
									<option value="pq_pro_bd9" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd9') echo 'selected';?>>Border Width - 9px</option>
									<option value="pq_pro_bd10" <?php if($this->_options[proOptions][subscribeExit][b_width] == 'pq_pro_bd10') echo 'selected';?>>Border Width - 10px</option>
								</select>
								</label>
								<label>
								<select id="subscribeExit_pro_8" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][b_color]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][b_color] == '') echo 'selected';?>>Border Color - White</option>
									<option value="pq_pro_bd_iceblue" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_iceblue') echo 'selected';?>>Border Color - Iceblue</option>
									<option value="pq_pro_bd_iceblue_lt " <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_iceblue_lt ') echo 'selected';?>>Border Color - Iceblue LT</option>
									<option value="pq_pro_bd_beige" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_beige') echo 'selected';?>>Border Color - Beige</option>
									<option value="pq_pro_bd_beige_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_beige_lt') echo 'selected';?>>Border Color - Beige  LT</option>
									<option value="pq_pro_bd_lilac" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_lilac') echo 'selected';?>>Border Color - Lilac</option>
									<option value="pq_pro_bd_lilac_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_lilac_lt') echo 'selected';?>>Border Color - Lilac LT</option>
									<option value="pq_pro_bd_wormwood" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_wormwood') echo 'selected';?>>Border Color - Wormwood</option>
									<option value="pq_pro_bd_wormwood_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_wormwood_lt') echo 'selected';?>>Border Color - Wormwood LT</option>
									<option value="pq_pro_bd_yellow" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_yellow') echo 'selected';?>>Border Color - Yellow</option>
									<option value="pq_pro_bd_yellow_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_yellow_lt') echo 'selected';?>>Border Color - Yellow LT</option>
									<option value="pq_pro_bd_grey" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_grey') echo 'selected';?>>Border Color - Grey</option>
									<option value="pq_pro_bd_grey_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_grey_lt') echo 'selected';?>>Border Color - Grey LT</option>
									<option value="pq_pro_bd_red" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_red') echo 'selected';?>>Border Color - Red</option>
									<option value="pq_pro_bd_red_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_red_lt') echo 'selected';?>>Border Color - Red LT</option>
									<option value="pq_pro_bd_skyblue" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_skyblue') echo 'selected';?>>Border Color - Skyblue</option>
									<option value="pq_pro_bd_skyblue_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_skyblue_lt') echo 'selected';?>>Border Color - Skyblue LT</option>
									<option value="pq_pro_bd_blue" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_blue') echo 'selected';?>>Border Color - Blue</option>
									<option value="pq_pro_bd_blue_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_blue_lt') echo 'selected';?>>Border Color - Blue LT</option>
									<option value="pq_pro_bd_green" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_green') echo 'selected';?>>Border Color - Green</option>
									<option value="pq_pro_bd_green_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_green_lt') echo 'selected';?>>Border Color - Green LT</option>
									<option value="pq_pro_bd_black" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_black') echo 'selected';?>>Border Color - Black</option>
									<option value="pq_pro_bd_black_lt" <?php if($this->_options[proOptions][subscribeExit][b_color] == 'pq_pro_bd_black_lt') echo 'selected';?>>Border Color - Black LT</option>
								</select>
								</label>
																
								<label>
								<select id="subscribeExit_pro_11" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][b_style]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][b_style] == '') echo 'selected';?>>Border Style - Solid</option>
									<option value="pq_pro_bs_dotted" <?php if($this->_options[proOptions][subscribeExit][b_style] == 'pq_pro_bs_dotted') echo 'selected';?>>Border Style - Dotted</option>
									<option value="pq_pro_bs_dashed" <?php if($this->_options[proOptions][subscribeExit][b_style] == 'pq_pro_bs_dashed') echo 'selected';?>>Border Style - Dashed</option>
									<option value="pq_pro_bs_double" <?php if($this->_options[proOptions][subscribeExit][b_style] == 'pq_pro_bs_double') echo 'selected';?>>Border Style - Double</option>
									<option value="pq_pro_bs_post" <?php if($this->_options[proOptions][subscribeExit][b_style] == 'pq_pro_bs_post') echo 'selected';?>>Border Style - Mail Post</option>
								</select>
								</label>
								<label>
								<select id="subscribeExit_pro_12" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][b_shadow]">
								    <option value="" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == '') echo 'selected';?>>No</option>
								    <option value="pq_pro_sh0" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == 'pq_pro_sh0') echo 'selected';?>>Shadow 0</option>
									<option value="pq_pro_sh1" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == 'pq_pro_sh1') echo 'selected';?>>Shadow 1</option>
									<option value="pq_pro_sh2" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == 'pq_pro_sh2') echo 'selected';?>>Shadow 2</option>
									<option value="pq_pro_sh3" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == 'pq_pro_sh3') echo 'selected';?>>Shadow 3</option>
									<option value="pq_pro_sh4" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == 'pq_pro_sh4') echo 'selected';?>>Shadow 4</option>
									<option value="pq_pro_sh5" <?php if($this->_options[proOptions][subscribeExit][b_shadow] == 'pq_pro_sh5') echo 'selected';?>>Shadow 5</option>
								</select>
								</label>
								<label>
									<select id="subscribeExit_pro_13" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][b_c_color]">
										<option value="pq_pro_x_color_white" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_white') echo 'selected';?>>Cross color - White</option>
										<option value="pq_pro_x_color_iceblue" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_iceblue') echo 'selected';?>>Cross color - Iceblue</option>
										<option value="pq_pro_x_color_beige" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_beige') echo 'selected';?>>Cross color - Beige</option>
										<option value="pq_pro_x_color_lilac" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_lilac') echo 'selected';?>>Cross color - Lilac</option>
										<option name="pq_pro_x_color_wormwood" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_wormwood') echo 'selected';?>>Cross color - Wormwood</option>
										<option name="pq_pro_x_color_yellow" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_yellow') echo 'selected';?>>Cross color - Yellow</option>
										<option name="pq_pro_x_color_grey" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_grey') echo 'selected';?>>Cross color - Grey</option>
										<option value="pq_pro_x_color_red" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_red') echo 'selected';?>>Cross color - Red</option>
										<option value="pq_pro_x_color_skyblue" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_skyblue') echo 'selected';?>>Cross color - Skyblue</option>
										<option value="pq_pro_x_color_blue" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_blue') echo 'selected';?>>Cross color - Blue</option>
										<option value="pq_pro_x_color_green" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_green') echo 'selected';?>>Cross color - Green</option>
										<option name="pq_pro_x_color_black" <?php if($this->_options[proOptions][subscribeExit][border_color] == 'pq_pro_x_color_black') echo 'selected';?>>Cross color - Black</option>
									</select>
								</label>
								<label><p>Background-image URL</p>
								<input type="text" id="subscribeExit_pro_background_image" onKeyUp="subscribeExitPreview();" name="proOptions[subscribeExit][background_image]" value="<?php echo stripslashes($this->_options[proOptions][subscribeExit][background_image]);?>">
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="subscribeExit_pro_animation" onchange="subscribeExitPreview();" name="proOptions[subscribeExit][animation]">
									<option value="" <?php if($this->_options[proOptions][subscribeExit][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][subscribeExit][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								</label>																
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[subscribeExit][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][subscribeExit][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][subscribeExit][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>						
						<div class="settings contact">
							<div class="popup selected">
								
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>DISPLAY RULES</h3><a class="tooltip_pro">?</a>
								<label><p>Disable Main Page</p>
									<select name="proOptions[contactUs][disableMainPage]">
										<option value="1" <?php if((int)$this->_options[proOptions][contactUs][disableMainPage] == 1) echo 'selected';?>>Yes</option>
										<option value="0" <?php if((int)$this->_options[proOptions][contactUs][disableMainPage] == 0) echo 'selected';?>>No</option>
									</select>
								</label>
								<label><p>Disable Exept Url Mask</p>
								<input type="text" name="proOptions[contactUs][disableExeptPageMask][0]" value="<?php echo stripslashes($this->_options[proOptions][contactUs][disableExeptPageMask][0]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[contactUs][disableExeptPageMask][1]" value="<?php echo stripslashes($this->_options[proOptions][contactUs][disableExeptPageMask][1]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[contactUs][disableExeptPageMask][2]" value="<?php echo stripslashes($this->_options[proOptions][contactUs][disableExeptPageMask][2]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[contactUs][disableExeptPageMask][3]" value="<?php echo stripslashes($this->_options[proOptions][contactUs][disableExeptPageMask][3]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[contactUs][disableExeptPageMask][4]" value="<?php echo stripslashes($this->_options[proOptions][contactUs][disableExeptPageMask][4]);?>">
								</label>								
								<h3>Design</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="contactUs_pro_1" onchange="contactUsPreview();" name="proOptions[contactUs][head_font]">
									<option value="" <?php if($this->_options[proOptions][contactUs][head_font] == '') echo 'selected';?>>Heading and Button - PT Sans Narrow</option>
									<option value="pq_pro_h_georgia" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_georgia') echo 'selected';?>>Heading and Button - Georgia</option>
									<option value="pq_pro_h_helvetica" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_helvetica') echo 'selected';?>>Heading and Button - Helvetica</option>
									<option value="pq_pro_h_courier" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_courier') echo 'selected';?>>Heading and Button - Courier New</option>
									<option value="pq_pro_h_times" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_times') echo 'selected';?>>Heading and Button - Times New Roman</option>
									<option value="pq_pro_h_verdana" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_verdana') echo 'selected';?>>Heading and Button - Verdana</option>
									<option value="pq_pro_h_arial_black" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_arial_black') echo 'selected';?>>Heading and Button - Arial Black</option>
									<option value="pq_pro_h_comic" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_comic') echo 'selected';?>>Heading and Button - Comic Sans MS</option>
									<option value="pq_pro_h_impact" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_impact') echo 'selected';?>>Heading and Button - Impact</option>
									<option value="pq_pro_h_trebuchet" <?php if($this->_options[proOptions][contactUs][head_font] == 'pq_pro_h_trebuchet') echo 'selected';?>>Heading and Button - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="contactUs_pro_2" onchange="contactUsPreview();" name="proOptions[contactUs][head_size]">
									<option value="" <?php if($this->_options[proOptions][contactUs][head_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_head_size16" <?php if($this->_options[proOptions][contactUs][head_size] == 'pq_pro_head_size16') echo 'selected';?>>Heading Size - 16</option>
									<option value="pq_pro_head_size20" <?php if($this->_options[proOptions][contactUs][head_size] == 'pq_pro_head_size20') echo 'selected';?>>Heading Size - 20</option>
									<option value="pq_pro_head_size24" <?php if($this->_options[proOptions][contactUs][head_size] == 'pq_pro_head_size24') echo 'selected';?>>Heading Size - 24</option>
									<option value="pq_pro_head_size28" <?php if($this->_options[proOptions][contactUs][head_size] == 'pq_pro_head_size28') echo 'selected';?>>Heading Size - 28</option>
									<option value="pq_pro_head_size36" <?php if($this->_options[proOptions][contactUs][head_size] == 'pq_pro_head_size36') echo 'selected';?>>Heading Size - 36</option>
									<option value="pq_pro_head_size48" <?php if($this->_options[proOptions][contactUs][head_size] == 'pq_pro_head_size48') echo 'selected';?>>Heading Size - 48</option>
								</select>
								</label>
								<label>
									<select id="contactUs_pro_3" onchange="contactUsPreview();" name="proOptions[contactUs][head_color]">
										<option value="pq_pro_head_color_white" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_white') echo 'selected';?>>Heading color - White</option>
										<option value="pq_pro_head_color_iceblue" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_iceblue') echo 'selected';?>>Heading color - Iceblue</option>
										<option value="pq_pro_head_color_beige" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_beige') echo 'selected';?>>Heading color - Beige</option>
										<option value="pq_pro_head_color_lilac" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_lilac') echo 'selected';?>>Heading color - Lilac</option>
										<option value="pq_pro_head_color_wormwood" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_wormwood') echo 'selected';?>>Heading color - Wormwood</option>
										<option value="pq_pro_head_color_yellow" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_yellow') echo 'selected';?>>Heading color - Yellow</option>
										<option value="pq_pro_head_color_grey" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_grey') echo 'selected';?>>Heading color - Grey</option>
										<option value="pq_pro_head_color_red" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_red') echo 'selected';?>>Heading color - Red</option>
										<option value="pq_pro_head_color_skyblue" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_skyblue') echo 'selected';?>>Heading color - Skyblue</option>
										<option value="pq_pro_head_color_blue" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_blue') echo 'selected';?>>Heading color - Blue</option>
										<option value="pq_pro_head_color_green" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_green') echo 'selected';?>>Heading color - Green</option>
										<option value="pq_pro_head_color_black" <?php if($this->_options[proOptions][contactUs][head_color] == 'pq_pro_head_color_black') echo 'selected';?>>Heading color - Black</option>
									</select>
								</label>
								<label>
								<select id="contactUs_pro_4" onchange="contactUsPreview();" name="proOptions[contactUs][font]">
									<option value="pq_pro_arial" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_arial') echo 'selected';?>>Text - Arial</option>
									<option value="pq_pro_georgia" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_georgia') echo 'selected';?>>Text - Georgia</option>
									<option value="pq_pro_helvetica" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_helvetica') echo 'selected';?>>Text - Helvetica</option>
									<option value="pq_pro_courier" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_courier') echo 'selected';?>>Text - Courier New</option>
									<option value="pq_pro_times" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_times') echo 'selected';?>>Text - Times New Roman</option>
									<option value="pq_pro_verdana" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_verdana') echo 'selected';?>>Text - Verdana</option>
									<option value="pq_pro_arial_black" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_arial_black') echo 'selected';?>>Text - Arial Black</option>
									<option value="pq_pro_comic" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_comic') echo 'selected';?>>Text - Comic Sans MS</option>
									<option value="pq_pro_impact" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_impact') echo 'selected';?>>Text - Impact</option>
									<option value="pq_pro_trebuchet" <?php if($this->_options[proOptions][contactUs][font] == 'pq_pro_trebuchet') echo 'selected';?>>Text - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="contactUs_pro_5" onchange="contactUsPreview();" name="proOptions[contactUs][text_size]">
									<option value="" <?php if($this->_options[proOptions][contactUs][text_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_text_size12" <?php if($this->_options[proOptions][contactUs][text_size] == 'pq_pro_text_size12') echo 'selected';?>>Text Size - 12</option>
									<option value="pq_pro_text_size16" <?php if($this->_options[proOptions][contactUs][text_size] == 'pq_pro_text_size16') echo 'selected';?>>Text Size - 16</option>
									<option value="pq_pro_text_size20" <?php if($this->_options[proOptions][contactUs][text_size] == 'pq_pro_text_size20') echo 'selected';?>>Text Size - 20</option>
									<option value="pq_pro_text_size24" <?php if($this->_options[proOptions][contactUs][text_size] == 'pq_pro_text_size24') echo 'selected';?>>Text Size - 24</option>
									<option value="pq_pro_text_size28" <?php if($this->_options[proOptions][contactUs][text_size] == 'pq_pro_text_size28') echo 'selected';?>>Text Size - 28</option>
									<option value="pq_pro_text_size36" <?php if($this->_options[proOptions][contactUs][text_size] == 'pq_pro_text_size36') echo 'selected';?>>Text Size - 36</option>	
								</select>
								</label>
								<label>
									<select id="contactUs_pro_6" onchange="contactUsPreview();" name="proOptions[contactUs][text_color]">
										<option value="pq_pro_text_color_white" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_white') echo 'selected';?>>Text color - White</option>
										<option value="pq_pro_text_color_iceblue" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_iceblue') echo 'selected';?>>Text color - Iceblue</option>
										<option value="pq_pro_text_color_beige" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_beige') echo 'selected';?>>Text color - Beige</option>
										<option value="pq_pro_text_color_lilac" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_lilac') echo 'selected';?>>Text color - Lilac</option>
										<option value="pq_pro_text_color_wormwood" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_wormwood') echo 'selected';?>>Text color - Wormwood</option>
										<option value="pq_pro_text_color_yellow" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_yellow') echo 'selected';?>>Text color - Yellow</option>
										<option value="pq_pro_text_color_grey" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_grey') echo 'selected';?>>Text color - Grey</option>
										<option value="pq_pro_text_color_red" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_red') echo 'selected';?>>Text color - Red</option>
										<option value="pq_pro_text_color_skyblue" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_skyblue') echo 'selected';?>>Text color - Skyblue</option>
										<option value="pq_pro_text_color_blue" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_blue') echo 'selected';?>>Text color - Blue</option>
										<option value="pq_pro_text_color_green" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_green') echo 'selected';?>>Text color - Green</option>
										<option value="pq_pro_text_color_black" <?php if($this->_options[proOptions][contactUs][text_color] == 'pq_pro_text_color_black') echo 'selected';?>>Text color - Black</option>
									</select>
								</label>
								<label>
								<select id="contactUs_pro_7" onchange="contactUsPreview();" name="proOptions[contactUs][b_radius]">
									<option value="" <?php if($this->_options[proOptions][contactUs][b_radius] == '') echo 'selected';?>>Border and Button Radius - Default</option>
									<option value="pq_pro_br_sq" <?php if($this->_options[proOptions][contactUs][b_radius] == 'pq_pro_br_sq') echo 'selected';?>>Border and Button Radius - Square</option>
									<option value="pq_pro_br_cr" <?php if($this->_options[proOptions][contactUs][b_radius] == 'pq_pro_br_cr') echo 'selected';?>>Border and Button Radius - Rounded</option>
								</select>
								</label>
								<label>
								<select id="contactUs_pro_9" onchange="contactUsPreview();" name="proOptions[contactUs][b_width]">
									<option value="" <?php if($this->_options[proOptions][contactUs][b_width] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_bd1" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd1') echo 'selected';?>>Border Width - 1px</option>
									<option value="pq_pro_bd2" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd2') echo 'selected';?>>Border Width - 2px</option>
									<option value="pq_pro_bd3" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd3') echo 'selected';?>>Border Width - 3px</option>
									<option value="pq_pro_bd4" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd4') echo 'selected';?>>Border Width - 4px</option>
									<option value="pq_pro_bd5" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd5') echo 'selected';?>>Border Width - 5px</option>
									<option value="pq_pro_bd6" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd6') echo 'selected';?>>Border Width - 6px</option>
									<option value="pq_pro_bd7" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd7') echo 'selected';?>>Border Width - 7px</option>
									<option value="pq_pro_bd8" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd8') echo 'selected';?>>Border Width - 8px</option>
									<option value="pq_pro_bd9" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd9') echo 'selected';?>>Border Width - 9px</option>
									<option value="pq_pro_bd10" <?php if($this->_options[proOptions][contactUs][b_width] == 'pq_pro_bd10') echo 'selected';?>>Border Width - 10px</option>
								</select>
								</label>
								<label>
								<select id="contactUs_pro_8" onchange="contactUsPreview();" name="proOptions[contactUs][b_color]">
									<option value="" <?php if($this->_options[proOptions][contactUs][b_color] == '') echo 'selected';?>>Border Color - White</option>
									<option value="pq_pro_bd_iceblue" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_iceblue') echo 'selected';?>>Border Color - Iceblue</option>
									<option value="pq_pro_bd_iceblue_lt " <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_iceblue_lt ') echo 'selected';?>>Border Color - Iceblue LT</option>
									<option value="pq_pro_bd_beige" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_beige') echo 'selected';?>>Border Color - Beige</option>
									<option value="pq_pro_bd_beige_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_beige_lt') echo 'selected';?>>Border Color - Beige  LT</option>
									<option value="pq_pro_bd_lilac" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_lilac') echo 'selected';?>>Border Color - Lilac</option>
									<option value="pq_pro_bd_lilac_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_lilac_lt') echo 'selected';?>>Border Color - Lilac LT</option>
									<option value="pq_pro_bd_wormwood" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_wormwood') echo 'selected';?>>Border Color - Wormwood</option>
									<option value="pq_pro_bd_wormwood_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_wormwood_lt') echo 'selected';?>>Border Color - Wormwood LT</option>
									<option value="pq_pro_bd_yellow" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_yellow') echo 'selected';?>>Border Color - Yellow</option>
									<option value="pq_pro_bd_yellow_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_yellow_lt') echo 'selected';?>>Border Color - Yellow LT</option>
									<option value="pq_pro_bd_grey" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_grey') echo 'selected';?>>Border Color - Grey</option>
									<option value="pq_pro_bd_grey_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_grey_lt') echo 'selected';?>>Border Color - Grey LT</option>
									<option value="pq_pro_bd_red" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_red') echo 'selected';?>>Border Color - Red</option>
									<option value="pq_pro_bd_red_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_red_lt') echo 'selected';?>>Border Color - Red LT</option>
									<option value="pq_pro_bd_skyblue" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_skyblue') echo 'selected';?>>Border Color - Skyblue</option>
									<option value="pq_pro_bd_skyblue_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_skyblue_lt') echo 'selected';?>>Border Color - Skyblue LT</option>
									<option value="pq_pro_bd_blue" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_blue') echo 'selected';?>>Border Color - Blue</option>
									<option value="pq_pro_bd_blue_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_blue_lt') echo 'selected';?>>Border Color - Blue LT</option>
									<option value="pq_pro_bd_green" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_green') echo 'selected';?>>Border Color - Green</option>
									<option value="pq_pro_bd_green_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_green_lt') echo 'selected';?>>Border Color - Green LT</option>
									<option value="pq_pro_bd_black" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_black') echo 'selected';?>>Border Color - Black</option>
									<option value="pq_pro_bd_black_lt" <?php if($this->_options[proOptions][contactUs][b_color] == 'pq_pro_bd_black_lt') echo 'selected';?>>Border Color - Black LT</option>
								</select>
								</label>
																
								<label>
								<select id="contactUs_pro_11" onchange="contactUsPreview();" name="proOptions[contactUs][b_style]">
									<option value="" <?php if($this->_options[proOptions][contactUs][b_style] == '') echo 'selected';?>>Border Style - Solid</option>
									<option value="pq_pro_bs_dotted" <?php if($this->_options[proOptions][contactUs][b_style] == 'pq_pro_bs_dotted') echo 'selected';?>>Border Style - Dotted</option>
									<option value="pq_pro_bs_dashed" <?php if($this->_options[proOptions][contactUs][b_style] == 'pq_pro_bs_dashed') echo 'selected';?>>Border Style - Dashed</option>
									<option value="pq_pro_bs_double" <?php if($this->_options[proOptions][contactUs][b_style] == 'pq_pro_bs_double') echo 'selected';?>>Border Style - Double</option>
									<option value="pq_pro_bs_post" <?php if($this->_options[proOptions][contactUs][b_style] == 'pq_pro_bs_post') echo 'selected';?>>Border Style - Mail Post</option>
								</select>
								</label>
								<label>
								<select id="contactUs_pro_12" onchange="contactUsPreview();" name="proOptions[contactUs][b_shadow]">
								    <option value="" <?php if($this->_options[proOptions][contactUs][b_shadow] == '') echo 'selected';?>>No</option>
								    <option value="pq_pro_sh0" <?php if($this->_options[proOptions][contactUs][b_shadow] == 'pq_pro_sh0') echo 'selected';?>>Shadow 0</option>
									<option value="pq_pro_sh1" <?php if($this->_options[proOptions][contactUs][b_shadow] == 'pq_pro_sh1') echo 'selected';?>>Shadow 1</option>
									<option value="pq_pro_sh2" <?php if($this->_options[proOptions][contactUs][b_shadow] == 'pq_pro_sh2') echo 'selected';?>>Shadow 2</option>
									<option value="pq_pro_sh3" <?php if($this->_options[proOptions][contactUs][b_shadow] == 'pq_pro_sh3') echo 'selected';?>>Shadow 3</option>
									<option value="pq_pro_sh4" <?php if($this->_options[proOptions][contactUs][b_shadow] == 'pq_pro_sh4') echo 'selected';?>>Shadow 4</option>
									<option value="pq_pro_sh5" <?php if($this->_options[proOptions][contactUs][b_shadow] == 'pq_pro_sh5') echo 'selected';?>>Shadow 5</option>
								</select>
								</label>
								<label>
									<select id="contactUs_pro_13" onchange="contactUsPreview();" name="proOptions[contactUs][b_c_color]">
										<option value="pq_pro_x_color_white" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_white') echo 'selected';?>>Cross color - White</option>
										<option value="pq_pro_x_color_iceblue" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_iceblue') echo 'selected';?>>Cross color - Iceblue</option>
										<option value="pq_pro_x_color_beige" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_beige') echo 'selected';?>>Cross color - Beige</option>
										<option value="pq_pro_x_color_lilac" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_lilac') echo 'selected';?>>Cross color - Lilac</option>
										<option name="pq_pro_x_color_wormwood" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_wormwood') echo 'selected';?>>Cross color - Wormwood</option>
										<option name="pq_pro_x_color_yellow" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_yellow') echo 'selected';?>>Cross color - Yellow</option>
										<option name="pq_pro_x_color_grey" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_grey') echo 'selected';?>>Cross color - Grey</option>
										<option value="pq_pro_x_color_red" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_red') echo 'selected';?>>Cross color - Red</option>
										<option value="pq_pro_x_color_skyblue" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_skyblue') echo 'selected';?>>Cross color - Skyblue</option>
										<option value="pq_pro_x_color_blue" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_blue') echo 'selected';?>>Cross color - Blue</option>
										<option value="pq_pro_x_color_green" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_green') echo 'selected';?>>Cross color - Green</option>
										<option name="pq_pro_x_color_black" <?php if($this->_options[proOptions][contactUs][border_color] == 'pq_pro_x_color_black') echo 'selected';?>>Cross color - Black</option>
									</select>								
								</label>
								<label><p>Background-image URL</p>
								<input type="text" id="contactUs_pro_background_image" onkeyup="contactUsPreview();" name="proOptions[contactUs][background_image]" value="<?php echo stripslashes($this->_options[proOptions][contactUs][background_image]);?>">
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="contactUs_pro_animation" onchange="contactUsPreview();" name="proOptions[contactUs][animation]">
									<option value="" <?php if($this->_options[proOptions][contactUs][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][contactUs][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								</label>																
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[contactUs][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][contactUs][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][contactUs][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>
						<div class="settings callme">
							<div class="popup selected">
								
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>DISPLAY RULES</h3><a class="tooltip_pro">?</a>
								<label><p>Disable Main Page</p>
									<select name="proOptions[callMe][disableMainPage]">
										<option value="1" <?php if((int)$this->_options[proOptions][callMe][disableMainPage] == 1) echo 'selected';?>>Yes</option>
										<option value="0" <?php if((int)$this->_options[proOptions][callMe][disableMainPage] == 0) echo 'selected';?>>No</option>
									</select>
								</label>
								<label><p>Disable Exept Url Mask</p>
								<input type="text" name="proOptions[callMe][disableExeptPageMask][0]" value="<?php echo stripslashes($this->_options[proOptions][callMe][disableExeptPageMask][0]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[callMe][disableExeptPageMask][1]" value="<?php echo stripslashes($this->_options[proOptions][callMe][disableExeptPageMask][1]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[callMe][disableExeptPageMask][2]" value="<?php echo stripslashes($this->_options[proOptions][callMe][disableExeptPageMask][2]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[callMe][disableExeptPageMask][3]" value="<?php echo stripslashes($this->_options[proOptions][callMe][disableExeptPageMask][3]);?>">
								</label>
								<label>
								<input type="text" name="proOptions[callMe][disableExeptPageMask][4]" value="<?php echo stripslashes($this->_options[proOptions][callMe][disableExeptPageMask][4]);?>">
								</label>
								
								<h3>Design</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="callMe_pro_1" onchange="callMePreview();" name="proOptions[callMe][head_font]">
									<option value="" <?php if($this->_options[proOptions][callMe][head_font] == '') echo 'selected';?>>Heading and Button - PT Sans Narrow</option>
									<option value="pq_pro_h_georgia" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_georgia') echo 'selected';?>>Heading and Button - Georgia</option>
									<option value="pq_pro_h_helvetica" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_helvetica') echo 'selected';?>>Heading and Button - Helvetica</option>
									<option value="pq_pro_h_courier" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_courier') echo 'selected';?>>Heading and Button - Courier New</option>
									<option value="pq_pro_h_times" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_times') echo 'selected';?>>Heading and Button - Times New Roman</option>
									<option value="pq_pro_h_verdana" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_verdana') echo 'selected';?>>Heading and Button - Verdana</option>
									<option value="pq_pro_h_arial_black" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_arial_black') echo 'selected';?>>Heading and Button - Arial Black</option>
									<option value="pq_pro_h_comic" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_comic') echo 'selected';?>>Heading and Button - Comic Sans MS</option>
									<option value="pq_pro_h_impact" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_impact') echo 'selected';?>>Heading and Button - Impact</option>
									<option value="pq_pro_h_trebuchet" <?php if($this->_options[proOptions][callMe][head_font] == 'pq_pro_h_trebuchet') echo 'selected';?>>Heading and Button - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="callMe_pro_2" onchange="callMePreview();" name="proOptions[callMe][head_size]">
									<option value="" <?php if($this->_options[proOptions][callMe][head_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_head_size16" <?php if($this->_options[proOptions][callMe][head_size] == 'pq_pro_head_size16') echo 'selected';?>>Heading Size - 16</option>
									<option value="pq_pro_head_size20" <?php if($this->_options[proOptions][callMe][head_size] == 'pq_pro_head_size20') echo 'selected';?>>Heading Size - 20</option>
									<option value="pq_pro_head_size24" <?php if($this->_options[proOptions][callMe][head_size] == 'pq_pro_head_size24') echo 'selected';?>>Heading Size - 24</option>
									<option value="pq_pro_head_size28" <?php if($this->_options[proOptions][callMe][head_size] == 'pq_pro_head_size28') echo 'selected';?>>Heading Size - 28</option>
									<option value="pq_pro_head_size36" <?php if($this->_options[proOptions][callMe][head_size] == 'pq_pro_head_size36') echo 'selected';?>>Heading Size - 36</option>
									<option value="pq_pro_head_size48" <?php if($this->_options[proOptions][callMe][head_size] == 'pq_pro_head_size48') echo 'selected';?>>Heading Size - 48</option>
								</select>
								</label>
								<label>
									<select id="callMe_pro_3" onchange="callMePreview();" name="proOptions[callMe][head_color]">
										<option value="pq_pro_head_color_white" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_white') echo 'selected';?>>Heading color - White</option>
										<option value="pq_pro_head_color_iceblue" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_iceblue') echo 'selected';?>>Heading color - Iceblue</option>
										<option value="pq_pro_head_color_beige" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_beige') echo 'selected';?>>Heading color - Beige</option>
										<option value="pq_pro_head_color_lilac" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_lilac') echo 'selected';?>>Heading color - Lilac</option>
										<option value="pq_pro_head_color_wormwood" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_wormwood') echo 'selected';?>>Heading color - Wormwood</option>
										<option value="pq_pro_head_color_yellow" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_yellow') echo 'selected';?>>Heading color - Yellow</option>
										<option value="pq_pro_head_color_grey" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_grey') echo 'selected';?>>Heading color - Grey</option>
										<option value="pq_pro_head_color_red" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_red') echo 'selected';?>>Heading color - Red</option>
										<option value="pq_pro_head_color_skyblue" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_skyblue') echo 'selected';?>>Heading color - Skyblue</option>
										<option value="pq_pro_head_color_blue" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_blue') echo 'selected';?>>Heading color - Blue</option>
										<option value="pq_pro_head_color_green" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_green') echo 'selected';?>>Heading color - Green</option>
										<option value="pq_pro_head_color_black" <?php if($this->_options[proOptions][callMe][head_color] == 'pq_pro_head_color_black') echo 'selected';?>>Heading color - Black</option>
									</select>
								</label>
								<label>
								<select id="callMe_pro_4" onchange="callMePreview();" name="proOptions[callMe][font]">
									<option value="pq_pro_arial" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_arial') echo 'selected';?>>Text - Arial</option>
									<option value="pq_pro_georgia" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_georgia') echo 'selected';?>>Text - Georgia</option>
									<option value="pq_pro_helvetica" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_helvetica') echo 'selected';?>>Text - Helvetica</option>
									<option value="pq_pro_courier" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_courier') echo 'selected';?>>Text - Courier New</option>
									<option value="pq_pro_times" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_times') echo 'selected';?>>Text - Times New Roman</option>
									<option value="pq_pro_verdana" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_verdana') echo 'selected';?>>Text - Verdana</option>
									<option value="pq_pro_arial_black" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_arial_black') echo 'selected';?>>Text - Arial Black</option>
									<option value="pq_pro_comic" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_comic') echo 'selected';?>>Text - Comic Sans MS</option>
									<option value="pq_pro_impact" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_impact') echo 'selected';?>>Text - Impact</option>
									<option value="pq_pro_trebuchet" <?php if($this->_options[proOptions][callMe][font] == 'pq_pro_trebuchet') echo 'selected';?>>Text - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="callMe_pro_5" onchange="callMePreview();" name="proOptions[callMe][text_size]">
									<option value="" <?php if($this->_options[proOptions][callMe][text_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_text_size12" <?php if($this->_options[proOptions][callMe][text_size] == 'pq_pro_text_size12') echo 'selected';?>>Text Size - 12</option>
									<option value="pq_pro_text_size16" <?php if($this->_options[proOptions][callMe][text_size] == 'pq_pro_text_size16') echo 'selected';?>>Text Size - 16</option>
									<option value="pq_pro_text_size20" <?php if($this->_options[proOptions][callMe][text_size] == 'pq_pro_text_size20') echo 'selected';?>>Text Size - 20</option>
									<option value="pq_pro_text_size24" <?php if($this->_options[proOptions][callMe][text_size] == 'pq_pro_text_size24') echo 'selected';?>>Text Size - 24</option>
									<option value="pq_pro_text_size28" <?php if($this->_options[proOptions][callMe][text_size] == 'pq_pro_text_size28') echo 'selected';?>>Text Size - 28</option>
									<option value="pq_pro_text_size36" <?php if($this->_options[proOptions][callMe][text_size] == 'pq_pro_text_size36') echo 'selected';?>>Text Size - 36</option>	
								</select>
								</label>
								<label>
									<select id="callMe_pro_6" onchange="callMePreview();" name="proOptions[callMe][text_color]">
										<option value="pq_pro_text_color_white" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_white') echo 'selected';?>>Text color - White</option>
										<option value="pq_pro_text_color_iceblue" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_iceblue') echo 'selected';?>>Text color - Iceblue</option>
										<option value="pq_pro_text_color_beige" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_beige') echo 'selected';?>>Text color - Beige</option>
										<option value="pq_pro_text_color_lilac" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_lilac') echo 'selected';?>>Text color - Lilac</option>
										<option value="pq_pro_text_color_wormwood" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_wormwood') echo 'selected';?>>Text color - Wormwood</option>
										<option value="pq_pro_text_color_yellow" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_yellow') echo 'selected';?>>Text color - Yellow</option>
										<option value="pq_pro_text_color_grey" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_grey') echo 'selected';?>>Text color - Grey</option>
										<option value="pq_pro_text_color_red" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_red') echo 'selected';?>>Text color - Red</option>
										<option value="pq_pro_text_color_skyblue" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_skyblue') echo 'selected';?>>Text color - Skyblue</option>
										<option value="pq_pro_text_color_blue" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_blue') echo 'selected';?>>Text color - Blue</option>
										<option value="pq_pro_text_color_green" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_green') echo 'selected';?>>Text color - Green</option>
										<option value="pq_pro_text_color_black" <?php if($this->_options[proOptions][callMe][text_color] == 'pq_pro_text_color_black') echo 'selected';?>>Text color - Black</option>
									</select>
								</label>
								<label>
								<select id="callMe_pro_7" onchange="callMePreview();" name="proOptions[callMe][b_radius]">
									<option value="" <?php if($this->_options[proOptions][callMe][b_radius] == '') echo 'selected';?>>Border and Button Radius - Default</option>
									<option value="pq_pro_br_sq" <?php if($this->_options[proOptions][callMe][b_radius] == 'pq_pro_br_sq') echo 'selected';?>>Border and Button Radius - Square</option>
									<option value="pq_pro_br_cr" <?php if($this->_options[proOptions][callMe][b_radius] == 'pq_pro_br_cr') echo 'selected';?>>Border and Button Radius - Rounded</option>
								</select>
								</label>
								<label>
								<select id="callMe_pro_9" onchange="callMePreview();" name="proOptions[callMe][b_width]">
									<option value="" <?php if($this->_options[proOptions][callMe][b_width] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_bd1" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd1') echo 'selected';?>>Border Width - 1px</option>
									<option value="pq_pro_bd2" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd2') echo 'selected';?>>Border Width - 2px</option>
									<option value="pq_pro_bd3" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd3') echo 'selected';?>>Border Width - 3px</option>
									<option value="pq_pro_bd4" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd4') echo 'selected';?>>Border Width - 4px</option>
									<option value="pq_pro_bd5" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd5') echo 'selected';?>>Border Width - 5px</option>
									<option value="pq_pro_bd6" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd6') echo 'selected';?>>Border Width - 6px</option>
									<option value="pq_pro_bd7" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd7') echo 'selected';?>>Border Width - 7px</option>
									<option value="pq_pro_bd8" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd8') echo 'selected';?>>Border Width - 8px</option>
									<option value="pq_pro_bd9" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd9') echo 'selected';?>>Border Width - 9px</option>
									<option value="pq_pro_bd10" <?php if($this->_options[proOptions][callMe][b_width] == 'pq_pro_bd10') echo 'selected';?>>Border Width - 10px</option>
								</select>
								</label>
								<label>
								<select id="callMe_pro_8" onchange="callMePreview();" name="proOptions[callMe][b_color]">
									<option value="" <?php if($this->_options[proOptions][callMe][b_color] == '') echo 'selected';?>>Border Color - White</option>
									<option value="pq_pro_bd_iceblue" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_iceblue') echo 'selected';?>>Border Color - Iceblue</option>
									<option value="pq_pro_bd_iceblue_lt " <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_iceblue_lt ') echo 'selected';?>>Border Color - Iceblue LT</option>
									<option value="pq_pro_bd_beige" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_beige') echo 'selected';?>>Border Color - Beige</option>
									<option value="pq_pro_bd_beige_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_beige_lt') echo 'selected';?>>Border Color - Beige  LT</option>
									<option value="pq_pro_bd_lilac" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_lilac') echo 'selected';?>>Border Color - Lilac</option>
									<option value="pq_pro_bd_lilac_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_lilac_lt') echo 'selected';?>>Border Color - Lilac LT</option>
									<option value="pq_pro_bd_wormwood" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_wormwood') echo 'selected';?>>Border Color - Wormwood</option>
									<option value="pq_pro_bd_wormwood_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_wormwood_lt') echo 'selected';?>>Border Color - Wormwood LT</option>
									<option value="pq_pro_bd_yellow" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_yellow') echo 'selected';?>>Border Color - Yellow</option>
									<option value="pq_pro_bd_yellow_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_yellow_lt') echo 'selected';?>>Border Color - Yellow LT</option>
									<option value="pq_pro_bd_grey" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_grey') echo 'selected';?>>Border Color - Grey</option>
									<option value="pq_pro_bd_grey_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_grey_lt') echo 'selected';?>>Border Color - Grey LT</option>
									<option value="pq_pro_bd_red" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_red') echo 'selected';?>>Border Color - Red</option>
									<option value="pq_pro_bd_red_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_red_lt') echo 'selected';?>>Border Color - Red LT</option>
									<option value="pq_pro_bd_skyblue" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_skyblue') echo 'selected';?>>Border Color - Skyblue</option>
									<option value="pq_pro_bd_skyblue_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_skyblue_lt') echo 'selected';?>>Border Color - Skyblue LT</option>
									<option value="pq_pro_bd_blue" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_blue') echo 'selected';?>>Border Color - Blue</option>
									<option value="pq_pro_bd_blue_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_blue_lt') echo 'selected';?>>Border Color - Blue LT</option>
									<option value="pq_pro_bd_green" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_green') echo 'selected';?>>Border Color - Green</option>
									<option value="pq_pro_bd_green_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_green_lt') echo 'selected';?>>Border Color - Green LT</option>
									<option value="pq_pro_bd_black" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_black') echo 'selected';?>>Border Color - Black</option>
									<option value="pq_pro_bd_black_lt" <?php if($this->_options[proOptions][callMe][b_color] == 'pq_pro_bd_black_lt') echo 'selected';?>>Border Color - Black LT</option>
								</select>
								</label>
																
								<label>
								<select id="callMe_pro_11" onchange="callMePreview();" name="proOptions[callMe][b_style]">
									<option value="" <?php if($this->_options[proOptions][callMe][b_style] == '') echo 'selected';?>>Border Style - Solid</option>
									<option value="pq_pro_bs_dotted" <?php if($this->_options[proOptions][callMe][b_style] == 'pq_pro_bs_dotted') echo 'selected';?>>Border Style - Dotted</option>
									<option value="pq_pro_bs_dashed" <?php if($this->_options[proOptions][callMe][b_style] == 'pq_pro_bs_dashed') echo 'selected';?>>Border Style - Dashed</option>
									<option value="pq_pro_bs_double" <?php if($this->_options[proOptions][callMe][b_style] == 'pq_pro_bs_double') echo 'selected';?>>Border Style - Double</option>
									<option value="pq_pro_bs_post" <?php if($this->_options[proOptions][callMe][b_style] == 'pq_pro_bs_post') echo 'selected';?>>Border Style - Mail Post</option>
								</select>
								</label>
								<label>
								<select id="callMe_pro_12" onchange="callMePreview();" name="proOptions[callMe][b_shadow]">
								    <option value="" <?php if($this->_options[proOptions][callMe][b_shadow] == '') echo 'selected';?>>No</option>
								    <option value="pq_pro_sh0" <?php if($this->_options[proOptions][callMe][b_shadow] == 'pq_pro_sh0') echo 'selected';?>>Shadow 0</option>
									<option value="pq_pro_sh1" <?php if($this->_options[proOptions][callMe][b_shadow] == 'pq_pro_sh1') echo 'selected';?>>Shadow 1</option>
									<option value="pq_pro_sh2" <?php if($this->_options[proOptions][callMe][b_shadow] == 'pq_pro_sh2') echo 'selected';?>>Shadow 2</option>
									<option value="pq_pro_sh3" <?php if($this->_options[proOptions][callMe][b_shadow] == 'pq_pro_sh3') echo 'selected';?>>Shadow 3</option>
									<option value="pq_pro_sh4" <?php if($this->_options[proOptions][callMe][b_shadow] == 'pq_pro_sh4') echo 'selected';?>>Shadow 4</option>
									<option value="pq_pro_sh5" <?php if($this->_options[proOptions][callMe][b_shadow] == 'pq_pro_sh5') echo 'selected';?>>Shadow 5</option>
								</select>
								</label>
								<label>
									<select id="callMe_pro_13" onchange="callMePreview();" name="proOptions[callMe][b_c_color]">
										<option value="pq_pro_x_color_white" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_white') echo 'selected';?>>Cross color - White</option>
										<option value="pq_pro_x_color_iceblue" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_iceblue') echo 'selected';?>>Cross color - Iceblue</option>
										<option value="pq_pro_x_color_beige" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_beige') echo 'selected';?>>Cross color - Beige</option>
										<option value="pq_pro_x_color_lilac" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_lilac') echo 'selected';?>>Cross color - Lilac</option>
										<option name="pq_pro_x_color_wormwood" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_wormwood') echo 'selected';?>>Cross color - Wormwood</option>
										<option name="pq_pro_x_color_yellow" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_yellow') echo 'selected';?>>Cross color - Yellow</option>
										<option name="pq_pro_x_color_grey" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_grey') echo 'selected';?>>Cross color - Grey</option>
										<option value="pq_pro_x_color_red" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_red') echo 'selected';?>>Cross color - Red</option>
										<option value="pq_pro_x_color_skyblue" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_skyblue') echo 'selected';?>>Cross color - Skyblue</option>
										<option value="pq_pro_x_color_blue" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_blue') echo 'selected';?>>Cross color - Blue</option>
										<option value="pq_pro_x_color_green" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_green') echo 'selected';?>>Cross color - Green</option>
										<option name="pq_pro_x_color_black" <?php if($this->_options[proOptions][callMe][border_color] == 'pq_pro_x_color_black') echo 'selected';?>>Cross color - Black</option>
									</select>
								</label>
								<label><p>Background-image URL</p>
								<input type="text" id="callMe_pro_background_image" onkeyUp="callMePreview();" name="proOptions[callMe][background_image]" value="<?php echo stripslashes($this->_options[proOptions][callMe][background_image]);?>">
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="callMe_pro_animation" onchange="callMePreview();" name="proOptions[callMe][animation]">
									<option value="" <?php if($this->_options[proOptions][callMe][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][callMe][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								</label>																
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[callMe][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][callMe][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][callMe][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>
						<div class="settings thankyou">							
							<div class="popup selected">
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>Design</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="thankPopup_pro_1" onchange="thankPopupPreview();" name="proOptions[thankPopup][head_font]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][head_font] == '') echo 'selected';?>>Heading and Button - PT Sans Narrow</option>
									<option value="pq_pro_h_georgia" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_georgia') echo 'selected';?>>Heading and Button - Georgia</option>
									<option value="pq_pro_h_helvetica" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_helvetica') echo 'selected';?>>Heading and Button - Helvetica</option>
									<option value="pq_pro_h_courier" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_courier') echo 'selected';?>>Heading and Button - Courier New</option>
									<option value="pq_pro_h_times" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_times') echo 'selected';?>>Heading and Button - Times New Roman</option>
									<option value="pq_pro_h_verdana" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_verdana') echo 'selected';?>>Heading and Button - Verdana</option>
									<option value="pq_pro_h_arial_black" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_arial_black') echo 'selected';?>>Heading and Button - Arial Black</option>
									<option value="pq_pro_h_comic" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_comic') echo 'selected';?>>Heading and Button - Comic Sans MS</option>
									<option value="pq_pro_h_impact" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_impact') echo 'selected';?>>Heading and Button - Impact</option>
									<option value="pq_pro_h_trebuchet" <?php if($this->_options[proOptions][thankPopup][head_font] == 'pq_pro_h_trebuchet') echo 'selected';?>>Heading and Button - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="thankPopup_pro_2" onchange="thankPopupPreview();" name="proOptions[thankPopup][head_size]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][head_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_head_size16" <?php if($this->_options[proOptions][thankPopup][head_size] == 'pq_pro_head_size16') echo 'selected';?>>Heading Size - 16</option>
									<option value="pq_pro_head_size20" <?php if($this->_options[proOptions][thankPopup][head_size] == 'pq_pro_head_size20') echo 'selected';?>>Heading Size - 20</option>
									<option value="pq_pro_head_size24" <?php if($this->_options[proOptions][thankPopup][head_size] == 'pq_pro_head_size24') echo 'selected';?>>Heading Size - 24</option>
									<option value="pq_pro_head_size28" <?php if($this->_options[proOptions][thankPopup][head_size] == 'pq_pro_head_size28') echo 'selected';?>>Heading Size - 28</option>
									<option value="pq_pro_head_size36" <?php if($this->_options[proOptions][thankPopup][head_size] == 'pq_pro_head_size36') echo 'selected';?>>Heading Size - 36</option>
									<option value="pq_pro_head_size48" <?php if($this->_options[proOptions][thankPopup][head_size] == 'pq_pro_head_size48') echo 'selected';?>>Heading Size - 48</option>
								</select>
								</label>
								<label>
									<select id="thankPopup_pro_3" onchange="thankPopupPreview();" name="proOptions[thankPopup][head_color]">
										<option value="pq_pro_head_color_white" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_white') echo 'selected';?>>Heading color - White</option>
										<option value="pq_pro_head_color_iceblue" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_iceblue') echo 'selected';?>>Heading color - Iceblue</option>
										<option value="pq_pro_head_color_beige" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_beige') echo 'selected';?>>Heading color - Beige</option>
										<option value="pq_pro_head_color_lilac" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_lilac') echo 'selected';?>>Heading color - Lilac</option>
										<option value="pq_pro_head_color_wormwood" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_wormwood') echo 'selected';?>>Heading color - Wormwood</option>
										<option value="pq_pro_head_color_yellow" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_yellow') echo 'selected';?>>Heading color - Yellow</option>
										<option value="pq_pro_head_color_grey" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_grey') echo 'selected';?>>Heading color - Grey</option>
										<option value="pq_pro_head_color_red" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_red') echo 'selected';?>>Heading color - Red</option>
										<option value="pq_pro_head_color_skyblue" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_skyblue') echo 'selected';?>>Heading color - Skyblue</option>
										<option value="pq_pro_head_color_blue" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_blue') echo 'selected';?>>Heading color - Blue</option>
										<option value="pq_pro_head_color_green" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_green') echo 'selected';?>>Heading color - Green</option>
										<option value="pq_pro_head_color_black" <?php if($this->_options[proOptions][thankPopup][head_color] == 'pq_pro_head_color_black') echo 'selected';?>>Heading color - Black</option>
									</select>
								</label>
								<label>
								<select id="thankPopup_pro_4" onchange="thankPopupPreview();" name="proOptions[thankPopup][font]">
									<option value="pq_pro_arial" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_arial') echo 'selected';?>>Text - Arial</option>
									<option value="pq_pro_georgia" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_georgia') echo 'selected';?>>Text - Georgia</option>
									<option value="pq_pro_helvetica" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_helvetica') echo 'selected';?>>Text - Helvetica</option>
									<option value="pq_pro_courier" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_courier') echo 'selected';?>>Text - Courier New</option>
									<option value="pq_pro_times" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_times') echo 'selected';?>>Text - Times New Roman</option>
									<option value="pq_pro_verdana" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_verdana') echo 'selected';?>>Text - Verdana</option>
									<option value="pq_pro_arial_black" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_arial_black') echo 'selected';?>>Text - Arial Black</option>
									<option value="pq_pro_comic" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_comic') echo 'selected';?>>Text - Comic Sans MS</option>
									<option value="pq_pro_impact" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_impact') echo 'selected';?>>Text - Impact</option>
									<option value="pq_pro_trebuchet" <?php if($this->_options[proOptions][thankPopup][font] == 'pq_pro_trebuchet') echo 'selected';?>>Text - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="thankPopup_pro_5" onchange="thankPopupPreview();" name="proOptions[thankPopup][text_size]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][text_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_text_size12" <?php if($this->_options[proOptions][thankPopup][text_size] == 'pq_pro_text_size12') echo 'selected';?>>Text Size - 12</option>
									<option value="pq_pro_text_size16" <?php if($this->_options[proOptions][thankPopup][text_size] == 'pq_pro_text_size16') echo 'selected';?>>Text Size - 16</option>
									<option value="pq_pro_text_size20" <?php if($this->_options[proOptions][thankPopup][text_size] == 'pq_pro_text_size20') echo 'selected';?>>Text Size - 20</option>
									<option value="pq_pro_text_size24" <?php if($this->_options[proOptions][thankPopup][text_size] == 'pq_pro_text_size24') echo 'selected';?>>Text Size - 24</option>
									<option value="pq_pro_text_size28" <?php if($this->_options[proOptions][thankPopup][text_size] == 'pq_pro_text_size28') echo 'selected';?>>Text Size - 28</option>
									<option value="pq_pro_text_size36" <?php if($this->_options[proOptions][thankPopup][text_size] == 'pq_pro_text_size36') echo 'selected';?>>Text Size - 36</option>	
								</select>
								</label>
								<label>
									<select id="thankPopup_pro_6" onchange="thankPopupPreview();" name="proOptions[thankPopup][text_color]">
										<option value="pq_pro_text_color_white" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_white') echo 'selected';?>>Text color - White</option>
										<option value="pq_pro_text_color_iceblue" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_iceblue') echo 'selected';?>>Text color - Iceblue</option>
										<option value="pq_pro_text_color_beige" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_beige') echo 'selected';?>>Text color - Beige</option>
										<option value="pq_pro_text_color_lilac" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_lilac') echo 'selected';?>>Text color - Lilac</option>
										<option value="pq_pro_text_color_wormwood" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_wormwood') echo 'selected';?>>Text color - Wormwood</option>
										<option value="pq_pro_text_color_yellow" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_yellow') echo 'selected';?>>Text color - Yellow</option>
										<option value="pq_pro_text_color_grey" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_grey') echo 'selected';?>>Text color - Grey</option>
										<option value="pq_pro_text_color_red" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_red') echo 'selected';?>>Text color - Red</option>
										<option value="pq_pro_text_color_skyblue" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_skyblue') echo 'selected';?>>Text color - Skyblue</option>
										<option value="pq_pro_text_color_blue" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_blue') echo 'selected';?>>Text color - Blue</option>
										<option value="pq_pro_text_color_green" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_green') echo 'selected';?>>Text color - Green</option>
										<option value="pq_pro_text_color_black" <?php if($this->_options[proOptions][thankPopup][text_color] == 'pq_pro_text_color_black') echo 'selected';?>>Text color - Black</option>
									</select>
								</label>
								<label>
								<select id="thankPopup_pro_7" onchange="thankPopupPreview();" name="proOptions[thankPopup][b_radius]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][b_radius] == '') echo 'selected';?>>Border and Button Radius - Default</option>
									<option value="pq_pro_br_sq" <?php if($this->_options[proOptions][thankPopup][b_radius] == 'pq_pro_br_sq') echo 'selected';?>>Border and Button Radius - Square</option>
									<option value="pq_pro_br_cr" <?php if($this->_options[proOptions][thankPopup][b_radius] == 'pq_pro_br_cr') echo 'selected';?>>Border and Button Radius - Rounded</option>
								</select>
								</label>
								<label>
								<select id="thankPopup_pro_9" onchange="thankPopupPreview();" name="proOptions[thankPopup][b_width]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][b_width] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_bd1" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd1') echo 'selected';?>>Border Width - 1px</option>
									<option value="pq_pro_bd2" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd2') echo 'selected';?>>Border Width - 2px</option>
									<option value="pq_pro_bd3" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd3') echo 'selected';?>>Border Width - 3px</option>
									<option value="pq_pro_bd4" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd4') echo 'selected';?>>Border Width - 4px</option>
									<option value="pq_pro_bd5" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd5') echo 'selected';?>>Border Width - 5px</option>
									<option value="pq_pro_bd6" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd6') echo 'selected';?>>Border Width - 6px</option>
									<option value="pq_pro_bd7" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd7') echo 'selected';?>>Border Width - 7px</option>
									<option value="pq_pro_bd8" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd8') echo 'selected';?>>Border Width - 8px</option>
									<option value="pq_pro_bd9" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd9') echo 'selected';?>>Border Width - 9px</option>
									<option value="pq_pro_bd10" <?php if($this->_options[proOptions][thankPopup][b_width] == 'pq_pro_bd10') echo 'selected';?>>Border Width - 10px</option>
								</select>
								</label>
								<label>
								<select id="thankPopup_pro_8" onchange="thankPopupPreview();" name="proOptions[thankPopup][b_color]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][b_color] == '') echo 'selected';?>>Border Color - White</option>
									<option value="pq_pro_bd_iceblue" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_iceblue') echo 'selected';?>>Border Color - Iceblue</option>
									<option value="pq_pro_bd_iceblue_lt " <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_iceblue_lt ') echo 'selected';?>>Border Color - Iceblue LT</option>
									<option value="pq_pro_bd_beige" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_beige') echo 'selected';?>>Border Color - Beige</option>
									<option value="pq_pro_bd_beige_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_beige_lt') echo 'selected';?>>Border Color - Beige  LT</option>
									<option value="pq_pro_bd_lilac" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_lilac') echo 'selected';?>>Border Color - Lilac</option>
									<option value="pq_pro_bd_lilac_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_lilac_lt') echo 'selected';?>>Border Color - Lilac LT</option>
									<option value="pq_pro_bd_wormwood" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_wormwood') echo 'selected';?>>Border Color - Wormwood</option>
									<option value="pq_pro_bd_wormwood_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_wormwood_lt') echo 'selected';?>>Border Color - Wormwood LT</option>
									<option value="pq_pro_bd_yellow" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_yellow') echo 'selected';?>>Border Color - Yellow</option>
									<option value="pq_pro_bd_yellow_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_yellow_lt') echo 'selected';?>>Border Color - Yellow LT</option>
									<option value="pq_pro_bd_grey" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_grey') echo 'selected';?>>Border Color - Grey</option>
									<option value="pq_pro_bd_grey_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_grey_lt') echo 'selected';?>>Border Color - Grey LT</option>
									<option value="pq_pro_bd_red" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_red') echo 'selected';?>>Border Color - Red</option>
									<option value="pq_pro_bd_red_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_red_lt') echo 'selected';?>>Border Color - Red LT</option>
									<option value="pq_pro_bd_skyblue" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_skyblue') echo 'selected';?>>Border Color - Skyblue</option>
									<option value="pq_pro_bd_skyblue_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_skyblue_lt') echo 'selected';?>>Border Color - Skyblue LT</option>
									<option value="pq_pro_bd_blue" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_blue') echo 'selected';?>>Border Color - Blue</option>
									<option value="pq_pro_bd_blue_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_blue_lt') echo 'selected';?>>Border Color - Blue LT</option>
									<option value="pq_pro_bd_green" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_green') echo 'selected';?>>Border Color - Green</option>
									<option value="pq_pro_bd_green_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_green_lt') echo 'selected';?>>Border Color - Green LT</option>
									<option value="pq_pro_bd_black" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_black') echo 'selected';?>>Border Color - Black</option>
									<option value="pq_pro_bd_black_lt" <?php if($this->_options[proOptions][thankPopup][b_color] == 'pq_pro_bd_black_lt') echo 'selected';?>>Border Color - Black LT</option>
								</select>
								</label>
																
								<label>
								<select id="thankPopup_pro_11" onchange="thankPopupPreview();" name="proOptions[thankPopup][b_style]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][b_style] == '') echo 'selected';?>>Border Style - Solid</option>
									<option value="pq_pro_bs_dotted" <?php if($this->_options[proOptions][thankPopup][b_style] == 'pq_pro_bs_dotted') echo 'selected';?>>Border Style - Dotted</option>
									<option value="pq_pro_bs_dashed" <?php if($this->_options[proOptions][thankPopup][b_style] == 'pq_pro_bs_dashed') echo 'selected';?>>Border Style - Dashed</option>
									<option value="pq_pro_bs_double" <?php if($this->_options[proOptions][thankPopup][b_style] == 'pq_pro_bs_double') echo 'selected';?>>Border Style - Double</option>
									<option value="pq_pro_bs_post" <?php if($this->_options[proOptions][thankPopup][b_style] == 'pq_pro_bs_post') echo 'selected';?>>Border Style - Mail Post</option>
								</select>
								</label>
								<label>
								<select id="thankPopup_pro_12" onchange="thankPopupPreview();" name="proOptions[thankPopup][b_shadow]">
								    <option value="" <?php if($this->_options[proOptions][thankPopup][b_shadow] == '') echo 'selected';?>>No</option>
								    <option value="pq_pro_sh0" <?php if($this->_options[proOptions][thankPopup][b_shadow] == 'pq_pro_sh0') echo 'selected';?>>Shadow 0</option>
									<option value="pq_pro_sh1" <?php if($this->_options[proOptions][thankPopup][b_shadow] == 'pq_pro_sh1') echo 'selected';?>>Shadow 1</option>
									<option value="pq_pro_sh2" <?php if($this->_options[proOptions][thankPopup][b_shadow] == 'pq_pro_sh2') echo 'selected';?>>Shadow 2</option>
									<option value="pq_pro_sh3" <?php if($this->_options[proOptions][thankPopup][b_shadow] == 'pq_pro_sh3') echo 'selected';?>>Shadow 3</option>
									<option value="pq_pro_sh4" <?php if($this->_options[proOptions][thankPopup][b_shadow] == 'pq_pro_sh4') echo 'selected';?>>Shadow 4</option>
									<option value="pq_pro_sh5" <?php if($this->_options[proOptions][thankPopup][b_shadow] == 'pq_pro_sh5') echo 'selected';?>>Shadow 5</option>
								</select>
								</label>
								<label>
									<select id="thankPopup_pro_13" onchange="thankPopupPreview();" name="proOptions[thankPopup][b_c_color]">
										<option value="pq_pro_x_color_white" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_white') echo 'selected';?>>Cross color - White</option>
										<option value="pq_pro_x_color_iceblue" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_iceblue') echo 'selected';?>>Cross color - Iceblue</option>
										<option value="pq_pro_x_color_beige" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_beige') echo 'selected';?>>Cross color - Beige</option>
										<option value="pq_pro_x_color_lilac" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_lilac') echo 'selected';?>>Cross color - Lilac</option>
										<option name="pq_pro_x_color_wormwood" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_wormwood') echo 'selected';?>>Cross color - Wormwood</option>
										<option name="pq_pro_x_color_yellow" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_yellow') echo 'selected';?>>Cross color - Yellow</option>
										<option name="pq_pro_x_color_grey" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_grey') echo 'selected';?>>Cross color - Grey</option>
										<option value="pq_pro_x_color_red" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_red') echo 'selected';?>>Cross color - Red</option>
										<option value="pq_pro_x_color_skyblue" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_skyblue') echo 'selected';?>>Cross color - Skyblue</option>
										<option value="pq_pro_x_color_blue" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_blue') echo 'selected';?>>Cross color - Blue</option>
										<option value="pq_pro_x_color_green" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_green') echo 'selected';?>>Cross color - Green</option>
										<option name="pq_pro_x_color_black" <?php if($this->_options[proOptions][thankPopup][border_color] == 'pq_pro_x_color_black') echo 'selected';?>>Cross color - Black</option>
									</select>
								</label>
								<label><p>Background-image URL</p>
								<input type="text" id="thankPopup_pro_background_image" onchange="thankPopupPreview();" name="proOptions[thankPopup][background_image]" value="<?php echo stripslashes($this->_options[proOptions][thankPopup][background_image]);?>">
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="thankPopup_pro_animation" onchange="thankPopupPreview();" name="proOptions[thankPopup][animation]">
									<option value="" <?php if($this->_options[proOptions][thankPopup][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][thankPopup][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								</label>																
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[thankPopup][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][thankPopup][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][thankPopup][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>
						<div class="settings follow">						
							<div class="popup selected">
								
								<h2 class="pro_h1">PRO options</h2>
								<h3>Design</h3><a class="tooltip_pro">?</a>
								<label>
								<select id="follow_pro_1" onchange="followPreview();" name="proOptions[follow][head_font]">
									<option value="" <?php if($this->_options[proOptions][follow][head_font] == '') echo 'selected';?>>Heading and Button - PT Sans Narrow</option>
									<option value="pq_pro_h_georgia" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_georgia') echo 'selected';?>>Heading and Button - Georgia</option>
									<option value="pq_pro_h_helvetica" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_helvetica') echo 'selected';?>>Heading and Button - Helvetica</option>
									<option value="pq_pro_h_courier" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_courier') echo 'selected';?>>Heading and Button - Courier New</option>
									<option value="pq_pro_h_times" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_times') echo 'selected';?>>Heading and Button - Times New Roman</option>
									<option value="pq_pro_h_verdana" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_verdana') echo 'selected';?>>Heading and Button - Verdana</option>
									<option value="pq_pro_h_arial_black" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_arial_black') echo 'selected';?>>Heading and Button - Arial Black</option>
									<option value="pq_pro_h_comic" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_comic') echo 'selected';?>>Heading and Button - Comic Sans MS</option>
									<option value="pq_pro_h_impact" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_impact') echo 'selected';?>>Heading and Button - Impact</option>
									<option value="pq_pro_h_trebuchet" <?php if($this->_options[proOptions][follow][head_font] == 'pq_pro_h_trebuchet') echo 'selected';?>>Heading and Button - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="follow_pro_2" onchange="followPreview();" name="proOptions[follow][head_size]">
									<option value="" <?php if($this->_options[proOptions][follow][head_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_head_size16" <?php if($this->_options[proOptions][follow][head_size] == 'pq_pro_head_size16') echo 'selected';?>>Heading Size - 16</option>
									<option value="pq_pro_head_size20" <?php if($this->_options[proOptions][follow][head_size] == 'pq_pro_head_size20') echo 'selected';?>>Heading Size - 20</option>
									<option value="pq_pro_head_size24" <?php if($this->_options[proOptions][follow][head_size] == 'pq_pro_head_size24') echo 'selected';?>>Heading Size - 24</option>
									<option value="pq_pro_head_size28" <?php if($this->_options[proOptions][follow][head_size] == 'pq_pro_head_size28') echo 'selected';?>>Heading Size - 28</option>
									<option value="pq_pro_head_size36" <?php if($this->_options[proOptions][follow][head_size] == 'pq_pro_head_size36') echo 'selected';?>>Heading Size - 36</option>
									<option value="pq_pro_head_size48" <?php if($this->_options[proOptions][follow][head_size] == 'pq_pro_head_size48') echo 'selected';?>>Heading Size - 48</option>
								</select>
								</label>
								<label>
									<select id="follow_pro_3" onchange="followPreview();" name="proOptions[follow][head_color]">
										<option value="pq_pro_head_color_white" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_white') echo 'selected';?>>Heading color - White</option>
										<option value="pq_pro_head_color_iceblue" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_iceblue') echo 'selected';?>>Heading color - Iceblue</option>
										<option value="pq_pro_head_color_beige" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_beige') echo 'selected';?>>Heading color - Beige</option>
										<option value="pq_pro_head_color_lilac" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_lilac') echo 'selected';?>>Heading color - Lilac</option>
										<option value="pq_pro_head_color_wormwood" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_wormwood') echo 'selected';?>>Heading color - Wormwood</option>
										<option value="pq_pro_head_color_yellow" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_yellow') echo 'selected';?>>Heading color - Yellow</option>
										<option value="pq_pro_head_color_grey" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_grey') echo 'selected';?>>Heading color - Grey</option>
										<option value="pq_pro_head_color_red" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_red') echo 'selected';?>>Heading color - Red</option>
										<option value="pq_pro_head_color_skyblue" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_skyblue') echo 'selected';?>>Heading color - Skyblue</option>
										<option value="pq_pro_head_color_blue" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_blue') echo 'selected';?>>Heading color - Blue</option>
										<option value="pq_pro_head_color_green" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_green') echo 'selected';?>>Heading color - Green</option>
										<option value="pq_pro_head_color_black" <?php if($this->_options[proOptions][follow][head_color] == 'pq_pro_head_color_black') echo 'selected';?>>Heading color - Black</option>
									</select>
								</label>
								<label>
								<select id="follow_pro_4" onchange="followPreview();" name="proOptions[follow][font]">
									<option value="pq_pro_arial" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_arial') echo 'selected';?>>Text - Arial</option>
									<option value="pq_pro_georgia" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_georgia') echo 'selected';?>>Text - Georgia</option>
									<option value="pq_pro_helvetica" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_helvetica') echo 'selected';?>>Text - Helvetica</option>
									<option value="pq_pro_courier" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_courier') echo 'selected';?>>Text - Courier New</option>
									<option value="pq_pro_times" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_times') echo 'selected';?>>Text - Times New Roman</option>
									<option value="pq_pro_verdana" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_verdana') echo 'selected';?>>Text - Verdana</option>
									<option value="pq_pro_arial_black" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_arial_black') echo 'selected';?>>Text - Arial Black</option>
									<option value="pq_pro_comic" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_comic') echo 'selected';?>>Text - Comic Sans MS</option>
									<option value="pq_pro_impact" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_impact') echo 'selected';?>>Text - Impact</option>
									<option value="pq_pro_trebuchet" <?php if($this->_options[proOptions][follow][font] == 'pq_pro_trebuchet') echo 'selected';?>>Text - Trebuchet MS</option>
								</select>
								</label>
								<label>
								<select id="follow_pro_5" onchange="followPreview();" name="proOptions[follow][text_size]">
									<option value="" <?php if($this->_options[proOptions][follow][text_size] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_text_size12" <?php if($this->_options[proOptions][follow][text_size] == 'pq_pro_text_size12') echo 'selected';?>>Text Size - 12</option>
									<option value="pq_pro_text_size16" <?php if($this->_options[proOptions][follow][text_size] == 'pq_pro_text_size16') echo 'selected';?>>Text Size - 16</option>
									<option value="pq_pro_text_size20" <?php if($this->_options[proOptions][follow][text_size] == 'pq_pro_text_size20') echo 'selected';?>>Text Size - 20</option>
									<option value="pq_pro_text_size24" <?php if($this->_options[proOptions][follow][text_size] == 'pq_pro_text_size24') echo 'selected';?>>Text Size - 24</option>
									<option value="pq_pro_text_size28" <?php if($this->_options[proOptions][follow][text_size] == 'pq_pro_text_size28') echo 'selected';?>>Text Size - 28</option>
									<option value="pq_pro_text_size36" <?php if($this->_options[proOptions][follow][text_size] == 'pq_pro_text_size36') echo 'selected';?>>Text Size - 36</option>	
								</select>
								</label>
								<label>
									<select id="follow_pro_6" onchange="followPreview();" name="proOptions[follow][text_color]">
										<option value="pq_pro_text_color_white" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_white') echo 'selected';?>>Text color - White</option>
										<option value="pq_pro_text_color_iceblue" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_iceblue') echo 'selected';?>>Text color - Iceblue</option>
										<option value="pq_pro_text_color_beige" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_beige') echo 'selected';?>>Text color - Beige</option>
										<option value="pq_pro_text_color_lilac" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_lilac') echo 'selected';?>>Text color - Lilac</option>
										<option value="pq_pro_text_color_wormwood" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_wormwood') echo 'selected';?>>Text color - Wormwood</option>
										<option value="pq_pro_text_color_yellow" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_yellow') echo 'selected';?>>Text color - Yellow</option>
										<option value="pq_pro_text_color_grey" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_grey') echo 'selected';?>>Text color - Grey</option>
										<option value="pq_pro_text_color_red" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_red') echo 'selected';?>>Text color - Red</option>
										<option value="pq_pro_text_color_skyblue" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_skyblue') echo 'selected';?>>Text color - Skyblue</option>
										<option value="pq_pro_text_color_blue" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_blue') echo 'selected';?>>Text color - Blue</option>
										<option value="pq_pro_text_color_green" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_green') echo 'selected';?>>Text color - Green</option>
										<option value="pq_pro_text_color_black" <?php if($this->_options[proOptions][follow][text_color] == 'pq_pro_text_color_black') echo 'selected';?>>Text color - Black</option>
									</select>
								</label>
								<label>
								<select id="follow_pro_7" onchange="followPreview();" name="proOptions[follow][b_radius]">
									<option value="" <?php if($this->_options[proOptions][follow][b_radius] == '') echo 'selected';?>>Border and Button Radius - Default</option>
									<option value="pq_pro_br_sq" <?php if($this->_options[proOptions][follow][b_radius] == 'pq_pro_br_sq') echo 'selected';?>>Border and Button Radius - Square</option>
									<option value="pq_pro_br_cr" <?php if($this->_options[proOptions][follow][b_radius] == 'pq_pro_br_cr') echo 'selected';?>>Border and Button Radius - Rounded</option>
								</select>
								</label>
								<label>
								<select id="follow_pro_9" onchange="followPreview();" name="proOptions[follow][b_width]">
									<option value="" <?php if($this->_options[proOptions][follow][b_width] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_bd1" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd1') echo 'selected';?>>Border Width - 1px</option>
									<option value="pq_pro_bd2" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd2') echo 'selected';?>>Border Width - 2px</option>
									<option value="pq_pro_bd3" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd3') echo 'selected';?>>Border Width - 3px</option>
									<option value="pq_pro_bd4" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd4') echo 'selected';?>>Border Width - 4px</option>
									<option value="pq_pro_bd5" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd5') echo 'selected';?>>Border Width - 5px</option>
									<option value="pq_pro_bd6" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd6') echo 'selected';?>>Border Width - 6px</option>
									<option value="pq_pro_bd7" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd7') echo 'selected';?>>Border Width - 7px</option>
									<option value="pq_pro_bd8" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd8') echo 'selected';?>>Border Width - 8px</option>
									<option value="pq_pro_bd9" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd9') echo 'selected';?>>Border Width - 9px</option>
									<option value="pq_pro_bd10" <?php if($this->_options[proOptions][follow][b_width] == 'pq_pro_bd10') echo 'selected';?>>Border Width - 10px</option>
								</select>
								</label>
								<label>
								<select id="follow_pro_8" onchange="followPreview();" name="proOptions[follow][b_color]">
									<option value="" <?php if($this->_options[proOptions][follow][b_color] == '') echo 'selected';?>>Border Color - White</option>
									<option value="pq_pro_bd_iceblue" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_iceblue') echo 'selected';?>>Border Color - Iceblue</option>
									<option value="pq_pro_bd_iceblue_lt " <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_iceblue_lt ') echo 'selected';?>>Border Color - Iceblue LT</option>
									<option value="pq_pro_bd_beige" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_beige') echo 'selected';?>>Border Color - Beige</option>
									<option value="pq_pro_bd_beige_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_beige_lt') echo 'selected';?>>Border Color - Beige  LT</option>
									<option value="pq_pro_bd_lilac" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_lilac') echo 'selected';?>>Border Color - Lilac</option>
									<option value="pq_pro_bd_lilac_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_lilac_lt') echo 'selected';?>>Border Color - Lilac LT</option>
									<option value="pq_pro_bd_wormwood" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_wormwood') echo 'selected';?>>Border Color - Wormwood</option>
									<option value="pq_pro_bd_wormwood_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_wormwood_lt') echo 'selected';?>>Border Color - Wormwood LT</option>
									<option value="pq_pro_bd_yellow" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_yellow') echo 'selected';?>>Border Color - Yellow</option>
									<option value="pq_pro_bd_yellow_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_yellow_lt') echo 'selected';?>>Border Color - Yellow LT</option>
									<option value="pq_pro_bd_grey" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_grey') echo 'selected';?>>Border Color - Grey</option>
									<option value="pq_pro_bd_grey_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_grey_lt') echo 'selected';?>>Border Color - Grey LT</option>
									<option value="pq_pro_bd_red" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_red') echo 'selected';?>>Border Color - Red</option>
									<option value="pq_pro_bd_red_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_red_lt') echo 'selected';?>>Border Color - Red LT</option>
									<option value="pq_pro_bd_skyblue" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_skyblue') echo 'selected';?>>Border Color - Skyblue</option>
									<option value="pq_pro_bd_skyblue_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_skyblue_lt') echo 'selected';?>>Border Color - Skyblue LT</option>
									<option value="pq_pro_bd_blue" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_blue') echo 'selected';?>>Border Color - Blue</option>
									<option value="pq_pro_bd_blue_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_blue_lt') echo 'selected';?>>Border Color - Blue LT</option>
									<option value="pq_pro_bd_green" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_green') echo 'selected';?>>Border Color - Green</option>
									<option value="pq_pro_bd_green_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_green_lt') echo 'selected';?>>Border Color - Green LT</option>
									<option value="pq_pro_bd_black" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_black') echo 'selected';?>>Border Color - Black</option>
									<option value="pq_pro_bd_black_lt" <?php if($this->_options[proOptions][follow][b_color] == 'pq_pro_bd_black_lt') echo 'selected';?>>Border Color - Black LT</option-->
								</select>
								</label>
																
								<label>
								<select id="follow_pro_11" onchange="followPreview();" name="proOptions[follow][b_style]">
									<option value="" <?php if($this->_options[proOptions][follow][b_style] == '') echo 'selected';?>>Border Style - Solid</option>
									<option value="pq_pro_bs_dotted" <?php if($this->_options[proOptions][follow][b_style] == 'pq_pro_bs_dotted') echo 'selected';?>>Border Style - Dotted</option>
									<option value="pq_pro_bs_dashed" <?php if($this->_options[proOptions][follow][b_style] == 'pq_pro_bs_dashed') echo 'selected';?>>Border Style - Dashed</option>
									<option value="pq_pro_bs_double" <?php if($this->_options[proOptions][follow][b_style] == 'pq_pro_bs_double') echo 'selected';?>>Border Style - Double</option>
									<option value="pq_pro_bs_post" <?php if($this->_options[proOptions][follow][b_style] == 'pq_pro_bs_post') echo 'selected';?>>Border Style - Mail Post</option>
								</select>
								</label>
								<label>
								<select id="follow_pro_12" onchange="followPreview();" name="proOptions[follow][b_shadow]">
								    <option value="" <?php if($this->_options[proOptions][follow][b_shadow] == '') echo 'selected';?>>No</option>
								    <option value="pq_pro_sh0" <?php if($this->_options[proOptions][follow][b_shadow] == 'pq_pro_sh0') echo 'selected';?>>Shadow 0</option>
									<option value="pq_pro_sh1" <?php if($this->_options[proOptions][follow][b_shadow] == 'pq_pro_sh1') echo 'selected';?>>Shadow 1</option>
									<option value="pq_pro_sh2" <?php if($this->_options[proOptions][follow][b_shadow] == 'pq_pro_sh2') echo 'selected';?>>Shadow 2</option>
									<option value="pq_pro_sh3" <?php if($this->_options[proOptions][follow][b_shadow] == 'pq_pro_sh3') echo 'selected';?>>Shadow 3</option>
									<option value="pq_pro_sh4" <?php if($this->_options[proOptions][follow][b_shadow] == 'pq_pro_sh4') echo 'selected';?>>Shadow 4</option>
									<option value="pq_pro_sh5" <?php if($this->_options[proOptions][follow][b_shadow] == 'pq_pro_sh5') echo 'selected';?>>Shadow 5</option>
								</select>
								</label>
								<label>
									<select id="follow_pro_13" onchange="followPreview();" name="proOptions[follow][b_c_color]">
										<option value="pq_pro_x_color_white" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_white') echo 'selected';?>>Cross color - White</option>
										<option value="pq_pro_x_color_iceblue" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_iceblue') echo 'selected';?>>Cross color - Iceblue</option>
										<option value="pq_pro_x_color_beige" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_beige') echo 'selected';?>>Cross color - Beige</option>
										<option value="pq_pro_x_color_lilac" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_lilac') echo 'selected';?>>Cross color - Lilac</option>
										<option name="pq_pro_x_color_wormwood" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_wormwood') echo 'selected';?>>Cross color - Wormwood</option>
										<option name="pq_pro_x_color_yellow" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_yellow') echo 'selected';?>>Cross color - Yellow</option>
										<option name="pq_pro_x_color_grey" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_grey') echo 'selected';?>>Cross color - Grey</option>
										<option value="pq_pro_x_color_red" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_red') echo 'selected';?>>Cross color - Red</option>
										<option value="pq_pro_x_color_skyblue" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_skyblue') echo 'selected';?>>Cross color - Skyblue</option>
										<option value="pq_pro_x_color_blue" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_blue') echo 'selected';?>>Cross color - Blue</option>
										<option value="pq_pro_x_color_green" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_green') echo 'selected';?>>Cross color - Green</option>
										<option name="pq_pro_x_color_black" <?php if($this->_options[proOptions][follow][border_color] == 'pq_pro_x_color_black') echo 'selected';?>>Cross color - Black</option>
									</select>
								</label>
								<label><p>Background-image URL</p>
								<input type="text" id="follow_pro_background_image" onkeyup="followPreview();" name="proOptions[follow][background_image]" value="<?php echo stripslashes($this->_options[proOptions][follow][background_image]);?>">
								</label>
								<h3>ANIMATION</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[follow][animation]" id="follow_pro_animation" onchange="followPreview();">
									<option value="" <?php if($this->_options[proOptions][follow][animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_a_bounceInLeft" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_bounceInLeft') echo 'selected';?>>Bounceinleft</option>
									<option value="pq_pro_a_bounceInRight" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_bounceInRight') echo 'selected';?>>Bounceinright</option>
									<option value="pq_pro_a_bounceInUp" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_bounceInUp') echo 'selected';?>>Bounceinup</option>									
									<option value="pq_pro_a_fadeIn" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeIn') echo 'selected';?>>Fadein</option>
									<option value="pq_pro_a_fadeInDown" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInDown') echo 'selected';?>>Fadeindown</option>
									<option value="pq_pro_a_fadeInDownBig" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInDownBig') echo 'selected';?>>Fadeindownbig</option>
									<option value="pq_pro_a_fadeInLeft" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInLeft') echo 'selected';?>>Fadeinleft</option>
									<option value="pq_pro_a_fadeInLeftBig" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInLeftBig') echo 'selected';?>>Fadeinleftbig</option>
									<option value="pq_pro_a_fadeInRight" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInRight') echo 'selected';?>>Fadeinright</option>
									<option value="pq_pro_a_fadeInRightBig" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInRightBig') echo 'selected';?>>Fadeinrightbig</option>
									<option value="pq_pro_a_fadeInUp" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInUp') echo 'selected';?>>Fadeinup</option>
									<option value="pq_pro_a_fadeInUpBig" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_fadeInUpBig') echo 'selected';?>>Fadeinupbig</option>
									<option value="pq_pro_a_flip" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_flip') echo 'selected';?>>Flip</option>
									<option value="pq_pro_a_flipInX" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_flipInX') echo 'selected';?>>Flipinx</option>
									<option value="pq_pro_a_flipInY" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_flipInY') echo 'selected';?>>Flipiny</option>									
									<option value="pq_pro_a_lightSpeedIn" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_lightSpeedIn') echo 'selected';?>>Lightspeedin</option>									
									<option value="pq_pro_a_rotateIn" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_rotateIn') echo 'selected';?>>Rotatein</option>
									<option value="pq_pro_a_rotateInDownLeft" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_rotateInDownLeft') echo 'selected';?>>Rotateindownleft</option>
									<option value="pq_pro_a_rotateInDownRight" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_rotateInDownRight') echo 'selected';?>>Rotateindownright</option>
									<option value="pq_pro_a_rotateInUpLeft" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_rotateInUpLeft') echo 'selected';?>>Rotateinupleft</option>
									<option value="pq_pro_a_rotateInUpRight" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_rotateInUpRight') echo 'selected';?>>Rotateinupright</option>
									<option value="pq_pro_a_rollIn" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_rollIn') echo 'selected';?>>Rollin</option>									
									<option value="pq_pro_a_zoomIn" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_zoomIn') echo 'selected';?>>Zoomin</option>
									<option value="pq_pro_a_zoomInDown" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_zoomInDown') echo 'selected';?>>Zoomindown</option>
									<option value="pq_pro_a_zoomInLeft" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_zoomInLeft') echo 'selected';?>>Zoominleft</option>
									<option value="pq_pro_a_zoomInRight" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_zoomInRight') echo 'selected';?>>Zoominright</option>
									<option value="pq_pro_a_zoomInUp" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_zoomInUp') echo 'selected';?>>Zoominup</option>
									<option value="pq_pro_a_slideInDown" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_slideInDown') echo 'selected';?>>Slideindown</option>
									<option value="pq_pro_a_slideInLeft" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_slideInLeft') echo 'selected';?>>Slideinleft</option>
									<option value="pq_pro_a_slideInRight" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_slideInRight') echo 'selected';?>>Slideinright</option>
									<option value="pq_pro_a_slideInUp" <?php if($this->_options[proOptions][follow][animation] == 'pq_pro_a_slideInUp') echo 'selected';?>>Slideinup</option>
								</select>
								</label>
								<label><p>Hover</p>
								<select name="proOptions[follow][hover_animation]" id="follow_pro_hover_animation" onchange="followPreview();">
									<option value="" <?php if($this->_options[proOptions][follow][hover_animation] == '') echo 'selected';?>>Default</option>
									<option value="pq_pro_ha_hvr_grow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_grow') echo 'selected';?>>Grow</option>
									<option value="pq_pro_ha_hvr_shrink" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_shrink') echo 'selected';?>>Shrink</option>
									<option value="pq_pro_ha_hvr_pulse" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_pulse') echo 'selected';?>>Pulse</option>
									<option value="pq_pro_ha_hvr_pulse_grow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_pulse_grow') echo 'selected';?>>Pulse Grow</option>
									<option value="pq_pro_ha_hvr_push" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_push') echo 'selected';?>>Push</option>
									<option value="pq_pro_ha_hvr_pop" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_pop') echo 'selected';?>>Pop</option>
									<option value="pq_pro_ha_hvr_bounce_in" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_bounce_in') echo 'selected';?>>Bounce In</option>
									<option value="pq_pro_ha_hvr_bounce_out" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_bounce_out') echo 'selected';?>>Bounce Out</option>
									<option value="pq_pro_ha_hvr_rotate" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_rotate') echo 'selected';?>>Rotate</option>
									<option value="pq_pro_ha_hvr_grow_rotate" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_grow_rotate') echo 'selected';?>>Grow Rotate</option>
									<option value="pq_pro_ha_hvr_sink" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_sink') echo 'selected';?>>Sink</option>
									<option value="pq_pro_ha_hvr_bob" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_bob') echo 'selected';?>>Bob</option>
									<option value="pq_pro_ha_hvr_hang" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_hang') echo 'selected';?>>Hang</option>
									<option value="pq_pro_ha_hvr_skew" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_skew') echo 'selected';?>>Skew</option>
									<option value="pq_pro_ha_hvr_skew_forward" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_skew_forward') echo 'selected';?>>Skew Forward</option>
									<option value="pq_pro_ha_hvr_skew_backward" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_skew_backward') echo 'selected';?>>Skew Backward</option>
									<option value="pq_pro_ha_hvr_wobble_vertical" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_wobble_vertical') echo 'selected';?>>Wobble Vertical</option>
									<option value="pq_pro_ha_hvr_wobble_horizontal" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_wobble_horizontal') echo 'selected';?>>Wobble Horizontal</option>
									<option value="pq_pro_ha_hvr_wobble_to_bottom_right" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_wobble_to_bottom_right') echo 'selected';?>>Wobble B&amp;R</option>
									<option value="pq_pro_ha_hvr_wobble_top" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_wobble_top') echo 'selected';?>>Wobble Top</option>
									<option value="pq_pro_ha_hvr_wobble_bottom" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_wobble_bottom') echo 'selected';?>>Wobble Bottom</option>
									<option value="pq_pro_ha_hvr_wobble_skew" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_wobble_skew') echo 'selected';?>>Wobble Skew</option>
									<option value="pq_pro_ha_hvr_buzz" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_buzz') echo 'selected';?>>Buzz</option>
									<option value="pq_pro_ha_hvr_buzz_out" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_buzz_out') echo 'selected';?>>Buzz Out</option>
									<option value="pq_pro_ha_hvr_border_fade" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_border_fade') echo 'selected';?>>Bdr Fade</option>
									<option value="pq_pro_ha_hvr_hollow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_hollow') echo 'selected';?>>Hollow</option>
									<option value="pq_pro_ha_hvr_trim" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_trim') echo 'selected';?>>Trim</option>
									<option value="pq_pro_ha_hvr_ripple_out" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_ripple_out') echo 'selected';?>>Ripple Out</option>
									<option value="pq_pro_ha_hvr_ripple_in" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_ripple_in') echo 'selected';?>>Ripple In</option>
									<option value="pq_pro_ha_hvr_glow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_glow') echo 'selected';?>>Glow</option>
									<option value="pq_pro_ha_hvr_shadow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_shadow') echo 'selected';?>>Shadow</option>
									<option value="pq_pro_ha_hvr_grow_shadow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_grow_shadow') echo 'selected';?>>Grow Shadow</option>
									<option value="pq_pro_ha_hvr_float_shadow" <?php if($this->_options[proOptions][follow][hover_animation] == 'pq_pro_ha_hvr_float_shadow') echo 'selected';?>>Float Shadow</option>
								</select>
								</label>
								<!--h3>WHITELABEL</h3><a class="tooltip_pro">?</a>
								<label>
								<select name="proOptions[follow][whitelabel]">
									<option value="1" <?php if((int)$this->_options[proOptions][follow][whitelabel] == 1) echo 'selected';?>>Enable</option>
									<option value="0" <?php if((int)$this->_options[proOptions][follow][whitelabel] == 0) echo 'selected';?>>Disabled</option>
								</select>
								</label-->
							</div>
						</div>
						
					</div>
						<div class="pq_button_group button_group_li5">
							<input type="button" value="prev" class="ToLi4">
							<br><input type="submit" value="Save and Activate" class="share_submit">
							<input type="submit" value="Save and Activate" class="follow_submit">
							<input type="submit" value="Save and Activate" class="contact_submit">
							<input type="submit" value="Save and Activate" class="callme_submit">
							<input type="submit" value="Save and Activate" class="exit_submit">
							<input type="submit" value="Save and Activate" class="imagesharer_submit">
							<input type="submit" value="Save and Activate" class="thankyou_submit">
							<input type="submit" value="Save and Activate" class="marketing_submit">
							<input type="submit" value="Save and Activate" class="collect_submit">
						</div>
		</form>
	</div>
	<div class="pq3">
		<div style="text-align: center;">
			<!--label class="choise">
							<input type="radio" name="choise" value="3" checked="checked">
							<div><img src="<?php echo plugins_url('i/30.png', __FILE__);?>" /></div>
						</label><label class="choise">
							<input type="radio" name="choise" value="4">
							<div><img src="<?php echo plugins_url('i/31.png', __FILE__);?>" /></div>							
						</label-->
			<div class="pq_like_dash">
				<p>Is that board comfortable for you?</p>
				<a href="http://profitquery.com/dashboard_yes.html" target="blank"><img src="<?php echo plugins_url('i/like.png', __FILE__);?>" /></a>
				<a href="http://profitquery.com/dashboard_no.html" target="blank"><img src="<?php echo plugins_url('i/dislike.png', __FILE__);?>" /></a>
			</div>
		</div>
		<div class="frame">
			<iframe src="about:blank" id="PQPreviewID" width="100%" height="100%" class="pq_if"></iframe>
			<h2>Only Design & position demo</h2>
			<h2 class="pro">PRO Design Option Preview</h2>
			<p style="text-align: center; padding-top: 50px;">Your screen is too small to use. Min width is 850px.</p>
		</div>
		
		<div id="Hello" class="pq_popup hello selected">
			<h1>What you can setup here?</h1>			
			
			<!--p>Latest news and plans of our team.</p>
			<p><strong><a href="javascrip:void(0);" onclick="document.getElementById('Get_Pro').style.display='block';">Try Pro for free right now. You can enable free trial with all pro features for 3 Day</a></strong></p-->
			<p>You can setup sharing sidebar, image sharer hover icons block (if you want more providers, email us <a href="mailto:support@profitquery.com">support@profitquery.com</a>), subscribe bar and subscribe exit intent which you can integrate with Aweber, Mailchimp and Active Campaign (if you need more mail providers just email us <a href="mailto:support@profitquery.com">support@profitquery.com</a>), you can setup Contact Us and Call Me tools which help you to get feedback from customers, emails and phone numbers. You can setup Follow popup and Thank popup and bind them after any action proceed (for example, after sharing, or after subscribing action). Also, you can order our technical specialist for generate for you some new tools. For example Bar with promoting link, or floating popup with a subscribe or exit popup with follow button. <a href="javascript:void(0)" onclick="document.getElementById('Any_tools').style.display='block';">Read more about Custom Tools</a>.</p>
			<br>
			<h1>All tools for free</h1>
			<p>But you can can greatly upgrade each tool if you enable pro options. Each option increases the efficiency of the decision by hundreds of percent.
			<a class="pq_pro_options_">Read more about pro options.</a></p>
			<br>
			<img src="<?php echo plugins_url('i/lesson.png', __FILE__);?>" />
			<br>
			<a class="hello_btn">Start Dashboard Guide</a>
			<br>
			<p>If you have any question or feedback or some ideas you can email us any time you want <a href="mailto:support@profitquery.com" target="_blank">support@profitquery.com</a> or visit Profitquery <a href="http://profitquery.com/community.html" target="_blank">community page.</a></p>
			<!--input type="button" value="get started" onclick="document.getElementById('Hello').style.display='none';"-->
			<br>
			<a href="https://wordpress.org/plugins/share-subscribe-contact-aio-widget/" target="_blank"> <img src="<?php echo plugins_url('i/stars.png', __FILE__);?>" /></a>
			<p>We work hard 7 days of week for make a best ever growth tools. If you like our work, you can make our team happy, please, <a href="https://wordpress.org/plugins/share-subscribe-contact-aio-widget/" target="_blank">rate our plugin.</a></p>
			<a class="pq_close" onclick="document.getElementById('Hello').style.display='none';"></a>
		</div>
		<div id="Any_tools" class="pq_popup any_tools">
			<h1>Generate new tool</h1>
			<p>Great News! If you want some new tools, you can offer our technical specialist which can generate new tool for your website. For example subscribe bar with promo link, or exit popup with follow button or something else. Cost of service 5$ (single payment). Email us <a href="mailto:support@profitquery.com">support@profitquery.com</a> (with subject: Order custom popup)</p>
			<a class="pq_close" onclick="document.getElementById('Any_tools').style.display='none';"></a>
		</div>	
		<div id="affiliate_program" class="pq_popup" style="z-index:600;">
			<h1>Affiliate program</h1>
			<p>This is a wonderful opportunity use <strong>PRO options for free</strong>! You need to invite new customers, which order PRO options. And you can use pro options for free. For more detail email us <a href="mailto:support@profitquery.com">support@profitquery.com</a> (subject:Affiliate program) For webmaster/web studios we have a <strong>special offer</strong>(subject:Affiliate program for webmaster). <strong>Do not miss, this offer is limited.</strong></p>
			<a class="pq_close" onclick="document.getElementById('affiliate_program').style.display='none';"></a>
		</div>	
		<div class="pq_popup protection share_desabled">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
		<input type="hidden" name="action" value="disableSharingSidebar">
			<h1>Disable Sharing Sidebar?</h1>
			<input type="submit" value="disable" id="pq">
			<input type="button" value="no" class="share_submit">
		</form>	
		</div>
		<div class="pq_popup protection imagesharer_desabled">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
		<input type="hidden" name="action" value="disableImageSharer">
			<h1>Disable Image Sharer?</h1>
			<input type="submit" value="disable">
			<input type="button" value="no" class="imagesharer_submit">
		</form>
		</div>
		<div class="pq_popup" id="PQActivatePlugin" style="display:none">		
			<h1>Thanks for activate Profitquery AIO Plugin.</h1>
			<input type="button" value="Close" class="marketing_submit" onclick="document.getElementById('PQActivatePlugin').style.display='none';">
		</div>		
		<div class="pq_popup protection marketing_desabled">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
		<input type="hidden" name="action" value="disableSubscribeBar">
			<h1>Disable Subscribe Bar?</h1>
			<input type="submit" value="disable">
			<input type="button" value="no" class="marketing_submit">
		</form>
		</div>
		<div class="pq_popup protection exit_desabled">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
		<input type="hidden" name="action" value="disableSubscribeExit">
			<h1>Disable Exit Intent Popup?</h1>
			<input type="submit" value="disable">
			<input type="button" value="no" class="exit_submit">
		</form>
		</div>		
		<div class="pq_popup protection contact_desabled">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
		<input type="hidden" name="action" value="disableContactUs">
			<h1>Disable Contact Form?</h1>
			<input type="submit" value="disable">
			<input type="button" value="no" class="contact_submit">
		</form>
		</div>
		<div class="pq_popup protection callme_desabled">
		<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
		<input type="hidden" name="action" value="disableCallMe">
			<h1>Disable Call Me Popup</h1>
			<input type="submit" value="disable">
			<input type="button" value="no" class="callme_submit">
		</form>
		</div>		
	</div>
	<div class="pq_clear"></div>
	<a class="question" href="javascript:void(0)" onclick="document.getElementById('Question').style.display='block';">
		<img src="<?php echo plugins_url('i/29.png', __FILE__);?>" />
	</a>
	<div id="Question" class="pq_popup question">
						<label class="how_it_works" style="width:34%;">
							<input type="radio" name="about" checked="checked" value="1" class="pq_pro_1">
							<div><p>How it works</p></div>
						</label><label class="pro_optoins">
							<input type="radio" name="about" value="2" class="pq_pro_2">
							<div><p>Pro options</p></div>
						</label><label class="contact_us">
							<input type="radio" name="about" value="3">
							<div><p>Contact us</p></div>
						</label>
			<div class="question1 selected">
				<img src="<?php echo plugins_url('i/37.png', __FILE__);?>"/>
				<h1>Choose Tool for configure</h1>
				<p>In this step you can start to configure any tools you want. Green border means that tool activated, without border - deactivated.</p>
				<img src="<?php echo plugins_url('i/38.png', __FILE__);?>"/>
				<h1>Activate/Deactivate Tools</h1>
				<p>When you hover to the tool image on the first step you can Active or Deactivate tools.</p>
				<img src="<?php echo plugins_url('i/39.png', __FILE__);?>"/>
				<h1>Second Step Settings</h1>
				<p>On the second step for each tool you can setup general settings for current tool</p>
				<img src="<?php echo plugins_url('i/40.png', __FILE__);?>"/>
				<h1>Next Step</h1>
				<p>For navigate between steps you can click Prev or Next Button. </p>
				<img src="<?php echo plugins_url('i/41.png', __FILE__);?>"/>
				<h1>Third Step Design options</h1>
				<p>On this section you can set-up free design option.</p>
				<img src="<?php echo plugins_url('i/42.png', __FILE__);?>"/>
				<h1>PRO Options</h1>
				<p>Each tool has pro options step. All design pro options you can see in the preview window (for demonstration only). If you want to enable it for your website you need try free trial or order pro tools, also we have an affiliate program. <a href="javascript:void(0)" onclick="document.getElementById('affiliate_program').style.display='block';">Read more</a>. </p>
				<img src="<?php echo plugins_url('i/43.png', __FILE__);?>"/>
				<h1>Save changes</h1>
				<p>For save changes you need click Save and activate button. </p>
				<img src="<?php echo plugins_url('i/44.png', __FILE__);?>"/>
				<h1>Deactivate tool</h1>
				<p>For disable already active tools, you need to go to the first step (Choose Tools), hover to the tool you need to disable and click disable. Then you need confirm disable. </p>
				<img src="<?php echo plugins_url('i/45.png', __FILE__);?>"/>
				<h1>Email settings</h1>
				<p>This section for setup your email address. Some profitquery tools send email to website admin.</p>
				<img src="<?php echo plugins_url('i/46.png', __FILE__);?>"/>
				<h1>Subscribe Provider Setup</h1>
				<p>This section for setup, subscribe sign in form. You can choose one of the provider (<a href="http://profitquery.com/mailchimp.html" target="_blank">Mailchimp</a>, <a href="http://profitquery.com/aweber.html" target="_blank">Aweber</a>, <a href="http://profitquery.com/acampaign.html" target="_blank">ActiveCampaign</a>) and paste sign-in form which you need to create on the chosen provider website. If you paste the wrong sign-in code, we will write Error Message. For use Bar and Exit popup you need a setup provider. If you want another mail provider which can generate sign-in form (not another plugin, only external mail service like mailchimp) you can email us <a href="mailto:support@profitquery.com">support@profitquery.com</a></p>
				<img src="<?php echo plugins_url('i/47.png', __FILE__);?>"/>
				<h1>Provider Setup Status</h1>
				<p>If you right set-up your subscribe provider by the click to the Email Settings you will see that window. Also, you can change provider sign in form by the click Settings. </p>
				<img src="<?php echo plugins_url('i/48.png', __FILE__);?>"/>
				<h1>Choose Language</h1>
				<p>This option only for front-end side default tools text. If you want to use default tools text, you can setup empty value for all text input fields. If you select Persian language, all tools would be from right to left on the front-end side of your website. If you want to translate Profitquery default dictionary to your native language, just email us <a href="mailto:support@profitquery.com">support@profitquery.com</a> and we add information about you to our Translators block in the plugin description which will see million people. </p>
				<img src="<?php echo plugins_url('i/49.png', __FILE__);?>"/>
				<h1>Google Analytics</h1>
				<p>Profitquery bind for current google analytics (if you use it on your website) Profitquery tool's action (generate tools, share action, etc.) If you want to disable track Profitquery tools, set its option to Off. </p>
			</div>
			<div class="question2">
				<img src="<?php echo plugins_url('i/1.gif', __FILE__);?>"/>
				<h1>Pro Design Option</h1>
				<p>Create with Profitquery, you could create amazing tools, for all themes and all tasks. Your customers will appreciate your unique style. One pays for all Profitquery tools (popup, bar, thank pop up, follow, subscribe exit intent, custom tools from Profitquery etc.). This option can increase all action to 200%. </p>
				<img src="<?php echo plugins_url('i/2.gif', __FILE__);?>"/>
				<h1>Pro Animation</h1>
				<p>All visitors love beautiful visual effects, this option allows you to animate any popup or object on your website. Many animation options can satisfy even the most refined taste. You can increase conversion, loyalty to 230% right now. </p>
				<img src="<?php echo plugins_url('i/3.gif', __FILE__);?>"/>
				<h1>Pro Hover Animation</h1>
				<p>Hover animation can increase share action, follow action on your website. You can use all hover animation for all objects on your website. </p>
				<img src="<?php echo plugins_url('i/50.png', __FILE__);?>"/>
				<h1>Send Mail</h1>
				<p>When you enable sending letters through Profiquery mail service from the first minute you start to collect customer contacts, feedbacks. From that moment your customers can send images, link from your website to friends without any setup. You can increase to 200% from customers' inbox, shares and loyalty. </p><a name="top"></a>
				<img src="<?php echo plugins_url('i/51.png', __FILE__);?>"/>
				<h1>Whitelabel</h1>
				<p>You can use all amazing Profitquery tools without a copyright link, these features need to be enable for branded web sites. </p>
				<img src="<?php echo plugins_url('i/52.png', __FILE__);?>"/>
				<h1>Disable Exept Page (Mask opt.)</h1>
				<p>There is special option for disable any Profitquery tools for all pages except you set-up. You can set-up a part of the url, for example, disable for all pages except page url address contained /product/ text. </p>
				<img src="<?php echo plugins_url('i/53.png', __FILE__);?>"/>
				<h1>Main Page disable</h1>
				<p>There is special option for disable any Profitquery tools for the main page, even if your main page is for example example.com/mainpage/. </p>
				<img src="<?php echo plugins_url('i/54.png', __FILE__);?>"/>
				<h1>Can set height</h1>
				<p>There is add-on for Image Sharer, which can skip image which height smaller than the option value. </p>
				<img src="<?php echo plugins_url('i/55.png', __FILE__);?>"/>
				<h1>Image Url Mask</h1>
				<p>Image Sharer unique option, which can work with image if the image url address contains any mask you want. For ex. for image which url address contains /uploads/customers-image/ text. </p>
				<img src="<?php echo plugins_url('i/56.png', __FILE__);?>"/>
				<h1>Image Expressions</h1>
				<p>There is add-on for Image Sharer which can work on with image expressions which you can set up. For ex. only with JPG image. </p>
			</div>
			<div class="question3">
				<p>You need help, or you want some special PRO option, or you want offer Custom Tool? No problem, email us <a href='mailto:support@profitquery.com'>support@profitquery.com</a> with subject(New PRO option, or Order custom popup)<br><br>
					You want use pro option <strong>for free</strong>? We have affiliate program, <a href="javascript:void(0);" onclick="document.getElementById('affiliate_program').style.display='block';">read more</a>.
				</p>
			</div>
			<!--div class="question_footer">
				<img src="<?php echo plugins_url('i/face.png', __FILE__);?>" />
				<h1>Support</h1>
				<p>If you have any question or feedback or some ideas you can email us any time you want <a href="mailto:support@profitquery.com" target="_blank">support@profitquery.com</a> or visit Profitquery <a href="http://profitquery.com/community.html" target="_blank">community page.</a></p>
				<!--input type="button" value="support"-->
			</div-->
			<a class="pq_close" onclick="document.getElementById('Question').style.display='none';"></a> 
	</div>
	
	<!--div id="Question" class="pq_popup question" style="display:none">
						<label class="about">
							<input type="radio" name="about" checked="checked" value="1">
							<div><p>About PQ</p></div>
						</label><label class="developer">
							<input type="radio" name="about" value="2">
							<div><p>Developer</p></div>
						</label><label class="community">
							<input type="radio" name="about" value="3">
							<div><p>Community</p></div>
						</label>
			<div class="question1 selected"><p>Profitquery is fast-growing intelligence system for boost any website. For now we have over 20 smart tools some of them are unique. And this is just the beginning. Our team make the complex simple. Make pro version similar tools - free. </p></div>
			<div class="question2"><p>Write your article. Promote your blog.You can write any article about Profitquery for your customers, friends and send for us your link or content. We <a href="http://profitquery.com/blog.html#send" target="_blank">paste your work</a> on our blog. Use your native language.</p></div>
			<div class="question3"><p>You can bind any enabled Profitquery popup for any event on your website.This is <a href="http://profitquery.com/developer.html" target="_blank">wonderfull opportunity</a> to make your website smarter. You can use Thank popup, Share popup, Follow us even Subscribe popup anywhere you want.</p></div>
			<div class="question_footer">
				<img src="<?php echo plugins_url('i/face.png', __FILE__);?>" />
				<h1>Support</h1>
				<p>If you have any question or feedback or some ideas you can email us any time you want <a href="mailto:support@profitquery.com" target="_blank">support@profitquery.com</a> or visit Profitquery <a href="http://profitquery.com/community.html" target="_blank">community page.</a></p>
				
			</div>
			<a class="pq_close" onclick="document.getElementById('Question').style.display='none';"></a> 
	</div-->
	<div id="GA" class="pq_popup analytics">
	<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
	<input type="hidden" name="action" value="editGA">
		<label>
			<p>Google Analytics Enable</p><input type="radio" name="additionalOptions[enableGA]" value="1" <?php if((int)$this->_options[additionalOptions][enableGA] == 1) echo 'checked';?>>
		</label>
		<label>
			<p>Google Analytics Disable</p><input type="radio" name="additionalOptions[enableGA]" value="0" <?php if((int)$this->_options[additionalOptions][enableGA] == 0) echo 'checked';?>>
		</label>
		<input type="submit" value="save" onclick="document.getElementById('GA').style.display='none';">
		<a class="pq_close" onclick="document.getElementById('GA').style.display='none';"></a>
	</form>
	</div>
	<div id="PQ_lang" class="pq_popup lang">
	<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
	<input type="hidden" name="action" value="editLang">
		
			<h1>Default language for tools frontend side</h1>
		
		<label>
			<p>English</p><input type="radio" name="additionalOptions[lang]" value="en" <?php if($this->_options[additionalOptions][lang] == 'en') echo "checked";?>>
		</label>
		<label>
			<p>Russian</p><input type="radio" name="additionalOptions[lang]" value="ru" <?php if($this->_options[additionalOptions][lang] == 'ru') echo "checked";?>>
		</label>
		<label>
			<p>Persian (Farsi; fa_IR)</p><input type="radio" name="additionalOptions[lang]" value="fa" <?php if($this->_options[additionalOptions][lang] == 'fa') echo "checked";?>>
		</label>
		<label>
			<p>Browser Lang</p><input type="radio" name="additionalOptions[lang]" value="" <?php if($this->_options[additionalOptions][lang] == '') echo "checked";?>>
		</label>
		<input type="submit" value="save" onclick="document.getElementById('PQ_lang').style.display='none';">
		<a class="pq_close" onclick="document.getElementById('PQ_lang').style.display='none';"></a>
	</form>
	</div>		
	<div id="SProviderSettings" class="pq_popup emailsettings">
	<span id="subscribeProviderFormID" <?php if((int)$this->_options['subscribeProviderOption'][$this->_options[subscribeProvider]]['is_error'] == 1 || !$this->_options['subscribeProviderOption'][$this->_options[subscribeProvider]][formAction]) echo 'style="display:block;";'; else echo 'style="display:none;";';?>>
	<form   action="<?php echo $this->getSettingsPageUrl();?>" method="post">
	<input type="hidden" name="action" value="subscribeProviderSetup">
			<h1>Email Settings</h1>
			<label>
				<select onchange="changeSubscribeProviderHelpUrl(1);" id="subscribeProvider" name="subscribeProvider">
					<option value="mailchimp" <?php if($this->_options[subscribeProvider] == '' || $this->_options[subscribeProvider] == 'mailchimp') echo "selected";?>>MailChimp</option>
					<option value="aweber" <?php if($this->_options[subscribeProvider] == 'aweber') echo "selected";?>>AWeber</option>
					<option value="acampaign" <?php if($this->_options[subscribeProvider] == 'acampaign') echo "selected";?>>Active Campaign</option>
				</select>
			</label>
			<label>
				<p>Paste code</p>
				<a id="subscribeProviderHelpUrl" href="http://profitquery.com/mailchimp.html" target="_blank">How to get code</a>
				<textarea rows="2" name="subscribeProviderFormContent" ></textarea>
			</label>	
			<input type="submit" value="save">
			
	</form>
	</span>
	<div id="subscribeProviderEditLinkID" class="pq_result" onclick="enableSubsribeForm();" <?php if((int)$this->_options['subscribeProviderOption'][$this->_options[subscribeProvider]]['is_error'] == 1 || !$this->_options['subscribeProviderOption'][$this->_options[subscribeProvider]][formAction]) echo 'style="display:none;";'; else echo 'style="display:block;";';?> />
		<img src="<?php echo plugins_url('i/ok.png', __FILE__);?>" />
		<span>MailChimp Integration</span><!-- добавить Aweber-->
		<a href="javascript:void(0)" onclick="return false;">Settings</a>
	</div>
	<script>
		function changeSubscribeProviderHelpUrl(withCheckCurrent){
			var currentSubscribeProvider = '<?php echo $this->_options[subscribeProvider];?>'
			if(document.getElementById('subscribeProvider').value == 'mailchimp'){
				document.getElementById('subscribeProviderHelpUrl').href = 'http://profitquery.com/mailchimp.html';
			}
			if(document.getElementById('subscribeProvider').value == 'aweber'){
				document.getElementById('subscribeProviderHelpUrl').href = 'http://profitquery.com/aweber.html';
			}
			if(document.getElementById('subscribeProvider').value == 'acampaign'){
				document.getElementById('subscribeProviderHelpUrl').href = 'http://profitquery.com/acampaign.html';
			}
			if(withCheckCurrent == '1'){
				if(currentSubscribeProvider){
					if(currentSubscribeProvider == document.getElementById('subscribeProvider').value){
						document.getElementById('subscribeProviderFormID').style.display = 'none';												
						document.getElementById('subscribeProviderEditLinkID').style.display = 'block';
					} else {
						document.getElementById('subscribeProviderFormID').style.display = 'block';												
						document.getElementById('subscribeProviderEditLinkID').style.display = 'none';
					}
				}
			}
		}											
		function enableSubsribeForm(){												
			document.getElementById('subscribeProviderFormID').style.display = 'block';												
			document.getElementById('subscribeProviderEditLinkID').style.display = 'none';												
		}
		
		changeSubscribeProviderHelpUrl();
	</script>
	<br>
	<form  action="<?php echo $this->getSettingsPageUrl();?>" method="post">
	<input type="hidden" name="action" value="setAdminEmail">
			<h1>Send Mail To</h1>			
			<label>
				<input type="text" name="adminEmail" value="<?php echo stripslashes($this->_options[adminEmail])?>">
			</label>
			<input type="submit" value="save">
			
	</form>
	<a class="pq_close" onclick="document.getElementById('SProviderSettings').style.display='none';"></a>
	</div>
	
	<div id="Get_Pro" class="pq_popup hello getpro">
	<div style="overflow: hidden; background: rgb(246, 246, 223); margin: 0 0 20px;">
		<h1>Pro options</h1>
		<p>Pro options the most powerful add-on for greatly increase the efficiency of the each tool by hundreds of percent. Create your unique design, using whitelabel for all tools, 2D 3D animation, visual effects, url settings, Profitquery mail service and more for making your website smarter and grow faster. <a class="pq_more_btn">Read more</a>.</p>
		
	<label class="free_activate">
		<input type="radio" name="pro" checked="checked" value="1">
		<div><p>free trial</p></div>
	</label>
	<label class="used_pro">
		<input type="radio" id="PQRadioProActivate" name="pro" value="2">
		<div><p>Activate Pro</p></div>
	</label>
	<label class="order_options">
		<input type="radio" name="pro" value="3">
		<div><p>order Pro options</p></div>
	</label>
	</div>
	<form  action="<?php echo $this->getSettingsPageUrl();?>" method="post">
	<div id="PQActivateProStep1" class="pq_pro1 selected">
	<input type="hidden" name="action" value="savePro">
		<p>You can test All Pro options for Free whole 3 Day. Without any payments. </p><br>
		<p style="text-align:left;">1) Go to the <a href="http://api.profitquery.com/getTrial/aio/" target="_blank">trial page</a> to enable Pro Options. Set-up form fields (note:You can test all pro options on any website exept localhost and website without domain name (for ex 192.168....))<br>
		2) We send to you email with apiKey and you pro loader filename. <a href="javascript:void(0)" onclick="document.getElementById('PQActivateProStep2').className='pq_pro2 selected'; document.getElementById('PQActivateProStep1').className='pq_pro1'; document.getElementById('PQRadioProActivate').checked=true; ">Set up</a> apiKey and pro loader filename.<br>
		3) Go to the frontend side your website, clear browser cache, reload page and you can see all pro options that you set-up for each tools.<br>
		</p>
		<br>
		<a href="http://api.profitquery.com/getTrial/aio/" target="_blank"><input type="button" value="Enable Free Trial"></a>
		<p>Atfer 3 Day we disable pro options and your enabled tools start work only with free options</p><br>
		
		<!--a class="pro_loader_filename">Pro Loader Filename</a>
		<input type="text" class="filename filename_add filename_hidden" name="proOptions[proLoaderFilename]" value="<?php echo stripslashes($this->_options['proOptions']['proLoaderFilename']);?>">
		<input type="submit" value="Save" class="filename_add filename_hidden"-->
	</div>
	<div id="PQActivateProStep2" class="pq_pro2">
		<p>For Using Pro Options insert your <a target="_getApiKey" href="http://api.profitquery.com/cms-sign-in/?domain=<?php echo $this->getDomain();?>&cms=wp&ae=<?php echo get_settings('admin_email');?>&redirect=<?php echo str_replace(".", "%2E", urlencode($this->getSettingsPageUrl()));?>">Api Key</a></p>
		<input type="text" name="apiKey" value="<?php echo stripslashes($this->_options['apiKey']);?>" class="filename">
		<input type="submit" value="Save" class="filename_url">
		<br>
		<p class="pro_loader_filename">Pro Loader Filename</p>
		<input type="text" class="filename filename_add" name="proOptions[proLoaderFilename]" value="<?php echo stripslashes($this->_options['proOptions']['proLoaderFilename']);?>">
		<input type="submit" value="Save" class="filename_add">
		<p class="pro_url">Website Full Main Page URL (http://example.com)</p>
			<input type="text" class="filename filename_url" name="proOptions[mainPageUrl]" value="<?php echo stripslashes($this->_options['proOptions']['mainPageUrl']);?>"; />	
			<input type="submit" value="Save" class="filename_url">
		<br><br>	
		<div class="pq_pro">
			<h1>Pro Options Detected</h1>
			<table>
				<?php
					if(empty($getProCategoryArray)){
						?>
						<tr>
							<td>						
								<p>Only free options detected</p>
							</td>
						</tr>
						<?php
					}else{
						if((int)$getProCategoryArray[disableMainPage]){
							?>
							<tr>
								<td>						
									<p>Disable main page option</p>
								</td>
							</tr>
							<?php
						}
						if((int)$getProCategoryArray[disableExeptPageMask]){
							?>
							<tr>
								<td>						
									<p>Disable exept page mask options</p>
								</td>
							</tr>
							<?php
						}
						
						if((int)$getProCategoryArray[ISdisableExeptImageUrlMask]){
							?>
							<tr>
								<td>						
									<p>Image Sharer image url mask options</p>
								</td>
							</tr>
							<?php
						}
						if((int)$getProCategoryArray[ISdisableExeptExtensions]){
							?>
							<tr>
								<td>						
									<p>Image Sharer Extension options</p>
								</td>
							</tr>
							<?php
						}
						if((int)$getProCategoryArray[ISminHeight]){
							?>
							<tr>
								<td>						
									<p>Image Sharer min Height option</p>
								</td>
							</tr>
							<?php
						}
						if((int)$getProCategoryArray[useProAnimation]){
							?>
							<tr>
								<td>						
									<p>Pro Animation Options</p>
								</td>
							</tr>
							<?php
						}
						if((int)$getProCategoryArray[useProDesignOptions]){
							?>
							<tr>
								<td>						
									<p>Pro Design Options</p>
								</td>
							</tr>
							<?php
						}
						if((int)$getProCategoryArray[useProHover]){
							?>
							<tr>
								<td>						
									<p>Pro Hover Animation Options</p>
								</td>
							</tr>
							<?php
						}
						?>
						
						<?php
					}
				?>				
			</table>
			<a href="<?php echo $this->getSettingsPageUrl();?>&action=deleteProOptions">Disable Pro Options for All Tools</a>		
		</div>
		<a href="http://api.profitquery.com/getPro/aio/" target="_blank"><input type="button" value="Order Pro Options" class="pq_order"></a>	
	</div>
	<div class="pq_pro3">	
		<p>
			A wise decision! Order Pro Right Now!
			<br>
			<a href="http://api.profitquery.com/getPro/aio/" target="_blank"><input type="button" value="Order Pro Options" class="pq_order"></a>
			<br><br>
			You want pro tools, but can not pay for that? No problem, we have an affiliate program, you can get pro tools for free. <a href="javascript:void(0)" onclick="document.getElementById('affiliate_program').style.display='block';">Read more.</a>			
		</p>
	</div>
		
	</form>
	<!--div class="pq_support">
		<h1>support</h1>
		<p>If you have any questions or feedback or some ideas you can email us any time you want <a href="mailto:support@profitquery.com">support@profitquery.com</a> or visit Profitquery <a>community page</a></p>
		<div></div>
		
	</div-->
	<a class="pq_close" onclick="document.getElementById('Get_Pro').style.display='none';"></a>
	</div>
	
	<div id="ES" class="pq_popup emailsettings">
	<form  action="<?php echo $this->getSettingsPageUrl();?>" method="post">
	<input type="hidden" name="action" value="setAdminEmail">
			<h1>Email settings</h1>			
			<label>
				<p>Send mail to</p>
				<input type="text" name="adminEmail" value="<?php echo stripslashes($this->_options[adminEmail])?>">
			</label>
			<input type="submit" value="save">
			<a class="pq_close" onclick="document.getElementById('ES').style.display='none';"></a>
	</form>
	</div>
</div>
<script type="text/javascript">
	jQuery(".ToLi1").click(function(){
		jQuery(".pq_li3").removeClass("selected");
		jQuery(".button_group_li3").removeClass("selected");
		jQuery(".pq_li1").addClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
		jQuery(".pq_tooltip1").removeClass("selected");
		jQuery(".pq_tooltip2").removeClass("selected");
		jQuery(".frame").removeClass("pro");
	});
	jQuery(".home").click(function(){
		jQuery(".pq_li3").removeClass("selected");
		jQuery(".pq_li4").removeClass("selected");
		jQuery(".pq_li5").removeClass("selected");
		jQuery(".button_group_li3").removeClass("selected");
		jQuery(".button_group_li4").removeClass("selected");
		jQuery(".button_group_li5").removeClass("selected");
		jQuery(".pq_li1").addClass("selected");
		jQuery(".pq_tooltip1").removeClass("selected");
		jQuery(".pq_tooltip2").removeClass("selected");
		jQuery(".frame").removeClass("pro");
	});
	jQuery(".set2").click(function(){
		jQuery(".choise2").prop('checked', false);
	});
	jQuery(".set3").click(function(){
		jQuery(".choise3").prop('checked', false);
	});
	jQuery(".set4").click(function(){
		jQuery(".choise4").prop('checked', false);
	});
	jQuery(".ToLi3").click(function(){
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li4").removeClass("selected");
		jQuery(".button_group_li4").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".frame").removeClass("pro");
	});
	
	jQuery(".ToLi4").click(function(){
		jQuery(".pq_li3").removeClass("selected");
		jQuery(".button_group_li3").removeClass("selected");
		jQuery(".pq_li5").removeClass("selected");
		jQuery(".button_group_li5").removeClass("selected");
		jQuery(".pq_li4").addClass("selected");
		jQuery(".button_group_li4").addClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise3").prop('checked', true);
		jQuery(".frame").removeClass("pro");		
	});
	jQuery(".ToLi5").click(function(){
		jQuery(".pq_li4").removeClass("selected");
		jQuery(".button_group_li4").removeClass("selected");
		jQuery(".pq_li5").addClass("selected");
		jQuery(".button_group_li5").addClass("selected");
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', true);
		jQuery(".frame").addClass("pro");
	});
	jQuery(".collect_tools_activate").click(function(){		
		jQuery(".pq_default").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".collect").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".collect_tools").prop('checked', true);
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".collect_submit").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	function sharingSideBarPreview(){									
		var designIcons = 'pq-social-block '+document.getElementById('sharingSideBar_design_size').value+' pq_'+document.getElementById('sharingSideBar_design_form').value+' '+document.getElementById('sharingSideBar_design_color').value;
		var position = 'pq_icons '+document.getElementById('sharingSideBar_side').value+' '+document.getElementById('sharingSideBar_top').value;
		var hoverAnimation = document.getElementById('sharingSideBar_hover_animation').value;
		var animation = document.getElementById('sharingSideBar_animation').value;
		var galleryBTColor = document.getElementById('sharingSideBar_popup_button_color').value;
		var galleryBGColor = document.getElementById('sharingSideBar_popup_bg_color').value;
		
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=sidebarShare&position='+position+'&typeBlock='+designIcons+'&hoverAnimation='+hoverAnimation+'&galleryBTColor='+galleryBTColor+'&galleryBGColor='+galleryBGColor+'&animation='+animation;
		document.getElementById('PQPreviewID').src = previewUrl;									
		//document.getElementById('PQPreviewID').width = '100%';
		
	}
	function imageSharerPreview(){									
		var design = document.getElementById('imageSharer_design_size').value+' pq_'+document.getElementById('imageSharer_design_form').value+' '+document.getElementById('imageSharer_design_color').value+' '+document.getElementById('imageSharer_design_shadow').value+' '+document.getElementById('imageSharer_position').value;		
		var hoverAnimation = document.getElementById('imageSharer_hover_animation').value;
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=imageSharer&design='+design+'&hoverAnimation='+hoverAnimation;									
		document.getElementById('PQPreviewID').src = previewUrl;
		//document.getElementById('PQPreviewID').width = '100%';
	}
	
	function callMePreview(){
		var animation = 'pq_animated '+document.getElementById('callMe_animation').value;		
		if(document.getElementById('callMe_pro_animation').value){
			animation = 'pq_animated '+document.getElementById('callMe_pro_animation').value;
		}
		var background_image = document.getElementById('callMe_pro_background_image').value;	
		
		var design = document.getElementById('callMe_typeWindow').value+' '+document.getElementById('callMe_background').value+' '+document.getElementById('callMe_button_color').value+' '+animation;
		var img = document.getElementById('callMe_img').value;
		var imgUrl = document.getElementById('callMeCustomFotoSrc').value;
		var loaderBackground = document.getElementById('callMe_loader_background').value;
		var overlay = document.getElementById('callMe_overlay').value;
		var position = document.getElementById('callMe_top').value+' '+document.getElementById('callMe_side').value;
		
		
		if(document.getElementById('callMe_pro_1').value) design += ' '+document.getElementById('callMe_pro_1').value;		
		if(document.getElementById('callMe_pro_2').value) design += ' '+document.getElementById('callMe_pro_2').value;		
		if(document.getElementById('callMe_pro_3').value) design += ' '+document.getElementById('callMe_pro_3').value;		
		if(document.getElementById('callMe_pro_4').value) design += ' '+document.getElementById('callMe_pro_4').value;		
		if(document.getElementById('callMe_pro_5').value) design += ' '+document.getElementById('callMe_pro_5').value;		
		if(document.getElementById('callMe_pro_6').value) design += ' '+document.getElementById('callMe_pro_6').value;		
		if(document.getElementById('callMe_pro_7').value) design += ' '+document.getElementById('callMe_pro_7').value;		
		if(document.getElementById('callMe_pro_8').value) design += ' '+document.getElementById('callMe_pro_8').value;		
		if(document.getElementById('callMe_pro_9').value) design += ' '+document.getElementById('callMe_pro_9').value;		
		//if(document.getElementById('callMe_pro_10').value) design += ' '+document.getElementById('callMe_pro_10').value;		
		if(document.getElementById('callMe_pro_11').value) design += ' '+document.getElementById('callMe_pro_11').value;		
		if(document.getElementById('callMe_pro_12').value) design += ' '+document.getElementById('callMe_pro_12').value;		
		if(document.getElementById('callMe_pro_13').value) design += ' '+document.getElementById('callMe_pro_13').value;		
		
		
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=callMe&design='+design+'&overlay='+encodeURIComponent(overlay)+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl)+'&loaderDesign='+loaderBackground+'&position='+position+'&background_image='+encodeURIComponent(background_image);
											
		document.getElementById('PQPreviewID').src = previewUrl;
		//document.getElementById('PQPreviewID').width = '100%';
		
	}
	
	function contactUsPreview(){
		var animation = 'pq_animated '+document.getElementById('contactUs_animation').value;		
		if(document.getElementById('contactUs_pro_animation').value){
			animation = 'pq_animated '+document.getElementById('contactUs_pro_animation').value;
		}
		var background_image = document.getElementById('contactUs_pro_background_image').value;		
		
		var design = document.getElementById('contactUs_typeWindow').value+' '+document.getElementById('contactUs_background').value+' '+document.getElementById('contactUs_button_color').value+' '+animation;
		if(document.getElementById('contactUs_pro_1').value) design += ' '+document.getElementById('contactUs_pro_1').value;		
		if(document.getElementById('contactUs_pro_2').value) design += ' '+document.getElementById('contactUs_pro_2').value;
		if(document.getElementById('contactUs_pro_3').value) design += ' '+document.getElementById('contactUs_pro_3').value;
		if(document.getElementById('contactUs_pro_4').value) design += ' '+document.getElementById('contactUs_pro_4').value;
		if(document.getElementById('contactUs_pro_5').value) design += ' '+document.getElementById('contactUs_pro_5').value;
		if(document.getElementById('contactUs_pro_6').value) design += ' '+document.getElementById('contactUs_pro_6').value;
		if(document.getElementById('contactUs_pro_7').value) design += ' '+document.getElementById('contactUs_pro_7').value;
		if(document.getElementById('contactUs_pro_8').value) design += ' '+document.getElementById('contactUs_pro_8').value;
		if(document.getElementById('contactUs_pro_9').value) design += ' '+document.getElementById('contactUs_pro_9').value;
		//if(document.getElementById('contactUs_pro_10').value) design += ' '+document.getElementById('contactUs_pro_10').value;
		if(document.getElementById('contactUs_pro_11').value) design += ' '+document.getElementById('contactUs_pro_11').value;
		if(document.getElementById('contactUs_pro_12').value) design += ' '+document.getElementById('contactUs_pro_12').value;
		if(document.getElementById('contactUs_pro_13').value) design += ' '+document.getElementById('contactUs_pro_13').value;
		
		
		var img = document.getElementById('contactUs_img').value;
		var imgUrl = document.getElementById('contactUsCustomFotoSrc').value;
		var loaderBackground = document.getElementById('contactUs_loader_background').value;									
		var position = document.getElementById('contactUs_top').value+' '+document.getElementById('contactUs_side').value;
		var overlay = document.getElementById('contactUs_overlay').value;
		
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=contactUs&design='+design+'&overlay='+encodeURIComponent(overlay)+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl)+'&loaderDesign='+loaderBackground+'&position='+position+'&background_image='+encodeURIComponent(background_image);
		document.getElementById('PQPreviewID').src = previewUrl;
		//document.getElementById('PQPreviewID').width = '100%';		
	}
	
	function subscribeExitPreview(){									
		var img = document.getElementById('subscribeExit_img').value;
		var imgUrl = document.getElementById('subscribeExitCustomFotoSrc').value;
		
		var animation = 'pq_animated '+document.getElementById('subscribeExit_animation').value;		
		if(document.getElementById('subscribeExit_pro_animation').value){
			animation = 'pq_animated '+document.getElementById('subscribeExit_pro_animation').value;
		}
		var design = document.getElementById('subscribeExit_typeWindow').value+' '+document.getElementById('subscribeExit_background').value+' '+document.getElementById('subscribeExit_button_color').value+' '+animation;
		
		if(document.getElementById('subscribeExit_pro_1').value) design += ' '+document.getElementById('subscribeExit_pro_1').value;		
		if(document.getElementById('subscribeExit_pro_2').value) design += ' '+document.getElementById('subscribeExit_pro_2').value;
		if(document.getElementById('subscribeExit_pro_3').value) design += ' '+document.getElementById('subscribeExit_pro_3').value;
		if(document.getElementById('subscribeExit_pro_4').value) design += ' '+document.getElementById('subscribeExit_pro_4').value;
		if(document.getElementById('subscribeExit_pro_5').value) design += ' '+document.getElementById('subscribeExit_pro_5').value;
		if(document.getElementById('subscribeExit_pro_6').value) design += ' '+document.getElementById('subscribeExit_pro_6').value;
		if(document.getElementById('subscribeExit_pro_7').value) design += ' '+document.getElementById('subscribeExit_pro_7').value;
		if(document.getElementById('subscribeExit_pro_8').value) design += ' '+document.getElementById('subscribeExit_pro_8').value;
		if(document.getElementById('subscribeExit_pro_9').value) design += ' '+document.getElementById('subscribeExit_pro_9').value;
		//if(document.getElementById('subscribeExit_pro_10').value) design += ' '+document.getElementById('subscribeExit_pro_10').value;
		if(document.getElementById('subscribeExit_pro_11').value) design += ' '+document.getElementById('subscribeExit_pro_11').value;
		if(document.getElementById('subscribeExit_pro_12').value) design += ' '+document.getElementById('subscribeExit_pro_12').value;
		if(document.getElementById('subscribeExit_pro_13').value) design += ' '+document.getElementById('subscribeExit_pro_13').value;
		
		var background_image = document.getElementById('subscribeExit_pro_background_image').value;		
		var overlay = document.getElementById('subscribeExit_overlay').value;									
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=subscribeExit&design='+design+'&overlay='+encodeURIComponent(overlay)+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl)+'&background_image='+encodeURIComponent(background_image);
		document.getElementById('PQPreviewID').src = previewUrl;
		//document.getElementById('PQPreviewID').width = '100%';
		
	}
	
	function subscribeBarPreview(){	
		
		var animation = 'pq_animated '+document.getElementById('subscribeBar_animation').value;		
		if(document.getElementById('subscribeBar_pro_animation').value){
			animation = 'pq_animated '+document.getElementById('subscribeBar_pro_animation').value;
		}
		
		var design = document.getElementById('subscribeBar_size').value+' '+document.getElementById('subscribeBar_position').value+' '+document.getElementById('subscribeBar_background').value+' '+document.getElementById('subscribeBar_button_color').value+' '+animation;		
		if(document.getElementById('subscribeBar_pro_1').value) design += ' '+document.getElementById('subscribeBar_pro_1').value;		
		if(document.getElementById('subscribeBar_pro_2').value) design += ' '+document.getElementById('subscribeBar_pro_2').value;
		if(document.getElementById('subscribeBar_pro_3').value) design += ' '+document.getElementById('subscribeBar_pro_3').value;
		
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=subscribeBar&design='+design;									
		
		document.getElementById('PQPreviewID').src = previewUrl;									
		//document.getElementById('PQPreviewID').width = '100%';
		
	}
	
	function thankPopupPreview(){
		var img = document.getElementById('thankPopup_img').value;
		var imgUrl = document.getElementById('thankPopupCustomFotoSrc').value;
		
		var animation = 'pq_animated '+document.getElementById('thankPopup_animation').value;		
		if(document.getElementById('thankPopup_pro_animation').value){
			animation = 'pq_animated '+document.getElementById('thankPopup_pro_animation').value;
		}
		var design = document.getElementById('thankPopup_typeWindow').value+' '+document.getElementById('thankPopup_background').value+' '+document.getElementById('thankPopup_button_color').value+' '+animation;
		
		if(document.getElementById('thankPopup_pro_1').value) design += ' '+document.getElementById('thankPopup_pro_1').value;		
		if(document.getElementById('thankPopup_pro_2').value) design += ' '+document.getElementById('thankPopup_pro_2').value;		
		if(document.getElementById('thankPopup_pro_3').value) design += ' '+document.getElementById('thankPopup_pro_3').value;		
		if(document.getElementById('thankPopup_pro_4').value) design += ' '+document.getElementById('thankPopup_pro_4').value;		
		if(document.getElementById('thankPopup_pro_5').value) design += ' '+document.getElementById('thankPopup_pro_5').value;		
		if(document.getElementById('thankPopup_pro_6').value) design += ' '+document.getElementById('thankPopup_pro_6').value;		
		if(document.getElementById('thankPopup_pro_7').value) design += ' '+document.getElementById('thankPopup_pro_7').value;		
		if(document.getElementById('thankPopup_pro_8').value) design += ' '+document.getElementById('thankPopup_pro_8').value;		
		if(document.getElementById('thankPopup_pro_9').value) design += ' '+document.getElementById('thankPopup_pro_9').value;		
		//if(document.getElementById('thankPopup_pro_10').value) design += ' '+document.getElementById('thankPopup_pro_10').value;		
		if(document.getElementById('thankPopup_pro_11').value) design += ' '+document.getElementById('thankPopup_pro_11').value;		
		if(document.getElementById('thankPopup_pro_12').value) design += ' '+document.getElementById('thankPopup_pro_12').value;		
		if(document.getElementById('thankPopup_pro_13').value) design += ' '+document.getElementById('thankPopup_pro_13').value;		
		
		
		var background_image = document.getElementById('thankPopup_pro_background_image').value;		
		var overlay = document.getElementById('thankPopup_overlay').value;									
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=thankPopup&design='+design+'&overlay='+encodeURIComponent(overlay)+'&img='+encodeURIComponent(img)+'&imgUrl='+encodeURIComponent(imgUrl)+'&background_image='+encodeURIComponent(background_image);
		document.getElementById('PQPreviewID').src = previewUrl;
		//document.getElementById('PQPreviewID').width = '100%';
	}
	function followPreview(){
		var animation = 'pq_animated '+document.getElementById('follow_animation').value;		
		if(document.getElementById('follow_pro_animation').value){
			animation = 'pq_animated '+document.getElementById('follow_pro_animation').value;
		}
		var design = document.getElementById('follow_typeWindow').value+' '+document.getElementById('follow_background').value+' '+animation;
		
		var typeBlock = document.getElementById('follow_design_size').value+' pq_'+document.getElementById('follow_design_form').value+' '+document.getElementById('follow_design_color').value+' '+document.getElementById('follow_design_shadow').value;
		var hoverAnimation = document.getElementById('follow_pro_hover_animation').value;
		
		if(document.getElementById('follow_pro_1').value) design += ' '+document.getElementById('follow_pro_1').value;		
		if(document.getElementById('follow_pro_2').value) design += ' '+document.getElementById('follow_pro_2').value;		
		if(document.getElementById('follow_pro_3').value) design += ' '+document.getElementById('follow_pro_3').value;		
		if(document.getElementById('follow_pro_4').value) design += ' '+document.getElementById('follow_pro_4').value;		
		if(document.getElementById('follow_pro_5').value) design += ' '+document.getElementById('follow_pro_5').value;		
		if(document.getElementById('follow_pro_6').value) design += ' '+document.getElementById('follow_pro_6').value;		
		if(document.getElementById('follow_pro_7').value) design += ' '+document.getElementById('follow_pro_7').value;		
		if(document.getElementById('follow_pro_8').value) design += ' '+document.getElementById('follow_pro_8').value;		
		if(document.getElementById('follow_pro_9').value) design += ' '+document.getElementById('follow_pro_9').value;		
		//if(document.getElementById('follow_pro_10').value) design += ' '+document.getElementById('follow_pro_10').value;		
		if(document.getElementById('follow_pro_11').value) design += ' '+document.getElementById('follow_pro_11').value;		
		if(document.getElementById('follow_pro_12').value) design += ' '+document.getElementById('follow_pro_12').value;		
		if(document.getElementById('follow_pro_13').value) design += ' '+document.getElementById('follow_pro_13').value;		
			
		
		
		var background_image = document.getElementById('follow_pro_background_image').value;		
		var overlay = document.getElementById('follow_overlay').value;									
		var previewUrl = 'http://profitquery.com/aio_widgets_iframe_demo_v3.html?utm-campaign=wp_aio_widgets&p=follow&design='+design+'&overlay='+encodeURIComponent(overlay)+'&background_image='+encodeURIComponent(background_image)+'&hoverAnimation='+encodeURIComponent(hoverAnimation)+'&typeBlock='+encodeURIComponent(typeBlock);
		document.getElementById('PQPreviewID').src = previewUrl;
		//document.getElementById('PQPreviewID').width = '100%';
	}	
				  
	
	jQuery(".share_tools_activate").click(function(){
		sharingSideBarPreview();
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".share").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".share_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".share_submit").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").addClass("pq_share");
	});
	jQuery(".follow_tools_activate").click(function(){
		followPreview()
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".follow").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".follow_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".follow_submit").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	
	jQuery(".contact_tools_activate").click(function(){
		contactUsPreview();
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".contact").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".contact_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".pq_tooltip2").addClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".contact_submit").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	jQuery(".callme_tools_activate").click(function(){
		callMePreview();
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".callme").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".callme_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".callme_submit").addClass("selected");
		jQuery(".pq_tooltip2").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	jQuery(".exit_tools_activate").click(function(){
		subscribeExitPreview();
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".exit").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".exit_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".exit_submit").addClass("selected");
		jQuery(".pq_tooltip1").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	jQuery(".imagesharer_tools_activate").click(function(){
		imageSharerPreview();
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".imagesharer").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".imagesharer_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".imagesharer_submit").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").addClass("pq_share");
	});
	jQuery(".marketing_tools_activate").click(function(){
		subscribeBarPreview();
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".thankyou").removeClass("selected");
		jQuery(".marketing").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".marketing_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".thankyou_submit").removeClass("selected");
		jQuery(".marketing_submit").addClass("selected");
		jQuery(".pq_tooltip1").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").addClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	jQuery(".thankyou_tools_activate").click(function(){
		thankPopupPreview()
		jQuery(".pq_default").removeClass("selected");
		jQuery(".collect").removeClass("selected");
		jQuery(".share").removeClass("selected");
		jQuery(".follow").removeClass("selected");
		jQuery(".contact").removeClass("selected");
		jQuery(".callme").removeClass("selected");
		jQuery(".exit").removeClass("selected");
		jQuery(".imagesharer").removeClass("selected");
		jQuery(".marketing").removeClass("selected");
		jQuery(".thankyou").addClass("selected");
		jQuery(".pq_li1").removeClass("selected");
		jQuery(".pq_li3").addClass("selected");
		jQuery(".button_group_li3").addClass("selected");
		jQuery(".choise2").prop('checked', true);
		jQuery(".thankyou_tools").prop('checked', true);
		jQuery(".collect_submit").removeClass("selected");
		jQuery(".share_submit").removeClass("selected");
		jQuery(".follow_submit").removeClass("selected");
		jQuery(".contact_submit").removeClass("selected");
		jQuery(".callme_submit").removeClass("selected");
		jQuery(".exit_submit").removeClass("selected");
		jQuery(".imagesharer_submit").removeClass("selected");
		jQuery(".marketing_submit").removeClass("selected");
		jQuery(".thankyou_submit").addClass("selected");
		jQuery(".hello").removeClass("selected");
		jQuery(".pq_if").removeClass("pq_bar");
		jQuery(".frame").removeClass("pq_share");
	});
	
	jQuery('#pq').change(function(){
		var vibor = jQuery('#pq :selected').attr('class');
		if(vibor=='custom_image'){
			jQuery('.custom_i').css('display','block');
	}
	});
	
	jQuery(".addservices7-12").click(function(){
		jQuery('.n07').css('display','block');
		jQuery('.n08').css('display','block');
		jQuery('.n09').css('display','block');
		jQuery('.n10').css('display','block');
		jQuery('.n11').css('display','block');
		jQuery('.n12').css('display','block');
		jQuery('.addservices13-18').css('display','block');
		jQuery('.addservices7-12').css('display','none');
	});
	
	jQuery(".addservices13-18").click(function(){
		jQuery('.n13').css('display','block');
		jQuery('.n14').css('display','block');
		jQuery('.n15').css('display','block');
		jQuery('.n16').css('display','block');
		jQuery('.n17').css('display','block');
		jQuery('.n18').css('display','block');
		jQuery('.addservices19-20').css('display','block');
		jQuery('.addservices13-18').css('display','none');
	});
	
	jQuery(".addservices19-20").click(function(){
		jQuery('.n19').css('display','block');
		jQuery('.n20').css('display','block');
		jQuery('.addservices19-20').css('display','none');
	});
	
	jQuery(".addservices4-6").click(function(){
		jQuery('.f04').css('display','block');
		jQuery('.f05').css('display','block');
		jQuery('.f06').css('display','block');
		jQuery('.addservices7-8').css('display','block');
		jQuery('.addservices4-6').css('display','none');
	});
	
	jQuery(".addservices7-8").click(function(){
		jQuery('.f07').css('display','block');
		jQuery('.f08').css('display','block');
		jQuery('.addservices7-8').css('display','none');
	});
	jQuery(".how_it_works").click(function(){
		jQuery(".question2").removeClass("selected");
		jQuery(".question3").removeClass("selected");
		jQuery(".question1").addClass("selected");
	});
	jQuery(".pro_optoins").click(function(){
		jQuery(".question1").removeClass("selected");
		jQuery(".question3").removeClass("selected");
		jQuery(".question2").addClass("selected");
	});
	jQuery(".contact_us").click(function(){
		jQuery(".question1").removeClass("selected");
		jQuery(".question2").removeClass("selected");
		jQuery(".question3").addClass("selected");
	});
	jQuery(".pq_pro_options_").click(function(){
		jQuery(".question").css('display','block');
		jQuery(".pq_pro_2").prop('checked', true);
		jQuery(".question1").removeClass("selected");
		jQuery(".question3").removeClass("selected");
		jQuery(".question2").addClass("selected");
	});
	jQuery(".hello_btn").click(function(){
		jQuery(".question").css('display','block');
		jQuery(".pq_pro_1").prop('checked', true);
		jQuery(".question2").removeClass("selected");
		jQuery(".question3").removeClass("selected");
		jQuery(".question1").addClass("selected");
	});
	jQuery(".pq_more_btn").click(function(){
		jQuery(".getpro").css('display','none');
		jQuery(".question").css('display','block');
		jQuery(".pq_pro_2").prop('checked', true);
		jQuery(".question1").removeClass("selected");
		jQuery(".question3").removeClass("selected");
		jQuery(".question2").addClass("selected");
	});
	jQuery(".tooltip_pro").click(function(){
		jQuery(".question").css('display','block');
		jQuery(".pq_pro_2").prop('checked', true);
		jQuery(".question1").removeClass("selected");
		jQuery(".question3").removeClass("selected");
		jQuery(".question2").addClass("selected");
	});
	jQuery('a[href^="#"]').click(function(){/*!!!*/
	var target = $(this).attr('href');
	jQuery('.question').animate({scrollTop: $(target).offset().top}, 800);
	return false;
	});
	jQuery(".any_tools_activate").click(function(){
		jQuery(".any_tools").css('display','block');
	});
	jQuery(".free_activate").click(function(){
		jQuery(".pq_pro2").removeClass("selected");
		jQuery(".pq_pro3").removeClass("selected");
		jQuery(".pq_pro1").addClass("selected");
	});
	jQuery(".used_pro").click(function(){
		jQuery(".pq_pro1").removeClass("selected");
		jQuery(".pq_pro3").removeClass("selected");
		jQuery(".pq_pro2").addClass("selected");
	});
	jQuery(".order_options").click(function(){
		jQuery(".pq_pro1").removeClass("selected");
		jQuery(".pq_pro2").removeClass("selected");
		jQuery(".pq_pro3").addClass("selected");
	});
	
	jQuery(".share_submit").click(function(){
		jQuery(".share_checked").prop('checked', true);
		jQuery(".share_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".imagesharer_submit").click(function(){
		jQuery(".imagesharer_checked").prop('checked', true);
		jQuery(".imagesharer_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".marketing_submit").click(function(){
		jQuery(".marketing_checked").prop('checked', true);
		jQuery(".marketing_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".exit_submit").click(function(){
		jQuery(".exit_checked").prop('checked', true);
		jQuery(".exit_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".collect_submit").click(function(){
		jQuery(".collect_checked").prop('checked', true);
		jQuery(".collect_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".contact_submit").click(function(){
		jQuery(".contact_checked").prop('checked', true);
		jQuery(".contact_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".callme_submit").click(function(){
		jQuery(".callme_checked").prop('checked', true);
		jQuery(".callme_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".follow_submit").click(function(){
		jQuery(".follow_checked").prop('checked', true);
		jQuery(".follow_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".thankyou_submit").click(function(){
		jQuery(".thankyou_checked").prop('checked', true);
		jQuery(".thankyou_desabled").removeClass("selected");
		jQuery(".choise2").prop('checked', false);
		jQuery(".choise3").prop('checked', false);
		jQuery(".choise4").prop('checked', false);
		jQuery(".choise1").prop('checked', true);
	});
	
	jQuery(".share_tools_desabled").click(function(){
		jQuery(".share_checked").prop('checked', false);
		jQuery(".share_desabled").addClass("selected");
	});
	
	jQuery(".imagesharer_tools_desabled").click(function(){
		jQuery(".imagesharer_checked").prop('checked', false);
		jQuery(".imagesharer_desabled").addClass("selected");
	});
	
	jQuery(".marketing_tools_desabled").click(function(){
		jQuery(".marketing_checked").prop('checked', false);
		jQuery(".marketing_desabled").addClass("selected");
	});
	
	jQuery(".exit_tools_desabled").click(function(){
		jQuery(".exit_checked").prop('checked', false);
		jQuery(".exit_desabled").addClass("selected");
	});
	
	jQuery(".collect_tools_desabled").click(function(){
		jQuery(".collect_checked").prop('checked', false);
		jQuery(".collect_desabled").addClass("selected");
	});
	
	jQuery(".contact_tools_desabled").click(function(){
		jQuery(".contact_checked").prop('checked', false);
		jQuery(".contact_desabled").addClass("selected");
	});
	
	jQuery(".callme_tools_desabled").click(function(){
		jQuery(".callme_checked").prop('checked', false);
		jQuery(".callme_desabled").addClass("selected");
	});
	
	jQuery(".follow_tools_desabled").click(function(){
		jQuery(".follow_checked").prop('checked', false);
		jQuery(".follow_desabled").addClass("selected");
	});
	
	jQuery(".thankyou_tools_desabled").click(function(){
		jQuery(".thankyou_checked").prop('checked', false);
		jQuery(".thankyou_desabled").addClass("selected");
	});
	jQuery(".pro_loader_filename").click(function(){
		jQuery(".filename_add").removeClass("filename_hidden");
	});
	jQuery(".pro_url").click(function(){
		jQuery(".filename_url").removeClass("filename_hidden");
	});
</script>
<?php
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
        $domain  = str_replace('www.', '', $domain);
        return $domain;
    }
}
