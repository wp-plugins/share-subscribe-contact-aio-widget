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
* Plugin Name: Share + Subscribe + Contact | AIO Widget
* Plugin URI: http://profitquery.com/aio_widgets.html
* Description: Next level widgets for growth your customers feedback, visitors contact information, share's, social networks referral's, folllowers and all for free.
* Version: 2.1.3
*
* Author: Profitquery Team <support@profitquery.com>
* Author URI: http://profitquery.com/?utm_campaign=aio_widgets_wp
*/



$profitquery = get_option('profitquery');

/*RESAVE BLOCK. For Old Version only*/
/*Resave rateUs*/
if(!isset($profitquery[rateUs])){
	$profitquery[rateUs][timeActivation] = time();
}

/*Resave Old ShareSidebar Socnet*/
if(isset($profitquery[sharingSideBar][socnet]) && !isset($profitquery[sharingSideBar][socnet_with_pos])){
	if(isset($profitquery[sharingSideBar][socnet][FB]) && !isset($profitquery[sharingSideBar][socnet_with_pos])){
		foreach((array)$profitquery[sharingSideBar][socnet] as $k => $v){
			if((int)$v == 1){						
				$profitquery[sharingSideBar][socnet_with_pos][] = $k;
			}
		}
		update_option('profitquery', $profitquery);
	}
}
		

//Resave Old Subsribe Provider
if(isset($profitquery['subscribeProviderUrl']) && !isset($profitquery['subscribeProvider'])){
	$profitquery['subscribeProvider'] = 'mailchimp';
	$profitquery['subscribeProviderOption']['mailchimp']['formAction'] = $profitquery['subscribeProviderUrl'];			
	$profitquery['subscribeProviderOption']['mailchimp']['is_error'] = 0;			
	update_option('profitquery', $profitquery);
}
//Resave Old additionalOptions
if(!isset($profitquery['additionalOptions'])){
	$profitquery[additionalOptions][enableGA] = 1;
	update_option('profitquery', $profitquery);
}




if (!defined('PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME'))
	define('PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME', trim(dirname(plugin_basename(__FILE__)), '/'));

if (!defined('PROFITQUERY_SMART_WIDGETS_PAGE_NAME'))
	define('PROFITQUERY_SMART_WIDGETS_PAGE_NAME', 'free_profitquery_aio_widgets');

if (!defined('PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH'))
	define('PROFITQUERY_SMART_WIDGETS_ADMIN_CSS_PATH', 'css/');

if (!defined('PROFITQUERY_SMART_WIDGETS_ADMIN_JS_PATH'))
	define('PROFITQUERY_SMART_WIDGETS_ADMIN_JS_PATH', 'js/');

if (!defined('PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PATH'))
	define('PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PATH', 'images/');

if (!defined('PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PREVIEW_PATH'))
	define('PROFITQUERY_SMART_WIDGETS_ADMIN_IMG_PREVIEW_PATH', 'preview/');

$pathParts = pathinfo(__FILE__);
$path = $pathParts['dirname'];

if (!defined('PROFITQUERY_SMART_WIDGETS_FILENAME'))
	define('PROFITQUERY_SMART_WIDGETS_FILENAME', $path.'/free_profitquery_aio_widgets.php');


require_once 'free_profitquery_aio_widgets_class.php';
$ProfitQuerySmartWidgetsClass = new ProfitQuerySmartWidgetsClass();


add_action('init', 'profitquery_smart_widgets_init');


function profitquery_smart_widgets_init(){
	global $profitquery;
	global $ProfitQuerySmartWidgetsClass;	
	if ( !is_admin() && $profitquery[apiKey] && !$profitquery['errorApiKey']){
		add_action('wp_head', 'profitquery_smart_widgets_insert_cache_hack_code');		
		add_action('wp_footer', 'profitquery_smart_widgets_insert_code');		
	}
	//echo rate us	
	if(is_admin() && $ProfitQuerySmartWidgetsClass->isPluginPage()){			
		add_action('admin_head', 'profitquery_message_on_plugin_page');
	}
}

/*Code generator*/
function profitquery_message_on_plugin_page(){	
	global $profitquery;	
	$timeout = 60*60*24*3;	
	if((time()-(int)$profitquery[rateUs][timeActivation]) >= $timeout && (int)$profitquery[rateUs][clickByRate] == 0 && (int)$profitquery[aio_widgets_loaded] == 1){	
	?>		
	<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
	<style type="text/css">
	 
	.pq_activate{min-width:825px;padding:5px;margin:15px 0;background:lightgrey; -moz-border-radius:3px;border-radius:3px;-webkit-border-radius:3px;position:relative;overflow:hidden}
	.pq_activate .aa_a{position:absolute;top:-5px;right:10px;font-size:140px;color:#769F33;font-family:Georgia, "Times New Roman", Times, serif;z-index:1}
	.pq_activate .aa_button{font-weight:bold;border:1px solid red;font-size:15px;text-align:center;padding:9px 0 8px 0;color:#FFF;background:red;-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px;opacity:.8;}
	.pq_activate .aa_button:hover{opacity:1;}
	.pq_activate .aa_button_border{border:1px solid transparent;-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px;}
	.pq_activate .aa_button_container{cursor:pointer;display:inline-block;padding:5px;-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px;width:266px; float: right;  margin-right: 25px;}
	.pq_activate .aa_description{position:absolute;top:22px;margin-left:25px;color:rgb(63, 63, 63);font-size:15px;z-index:1000}

	</style>
				
		<form name="pq_activate" action="<?php echo admin_url("options-general.php?page=" . PROFITQUERY_SMART_WIDGETS_PAGE_NAME);?>" method="POST"> 			
			<div class="pq_activate">  
				
				<div class="aa_button_container" onclick="document.pq_activate.submit();">  
					<div class="aa_button_border">          
						<div class="aa_button">Read message</div>  
					</div>  
				</div>  
				<div class="aa_description">System detected new message from Profitquery Team</div>  
			</div>  
		</form>  
	</div>	
	<?php
	}
}

function profitquery_smart_widgets_insert_cache_hack_code(){
	global $profitquery;
	if($profitquery[apiKey]){		
		echo '<script>var profitqueryLiteAPIKey="'.$profitquery[apiKey].'";</script>';		
	}
}

function printr($array){
	echo '<pre>';
	print_r($array);
	echo '</pre>';
}

/* Adding action links on plugin list*/
function profitquery_wordpress_admin_link($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="options-general.php?page=free_profitquery_aio_widgets">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}


//check subsccribe enabled
function profitquery_is_subscribe_enabled($profitquery){	
	$return = false;
	if((int)$profitquery[subscribeBar][disabled] == 0 || (int)$profitquery[subscribeExit][disabled] == 0){
		$return = true;
	}
	return $return;
}

function profitquery_is_follow_enabled_and_not_setup($profitquery){
	$return = false;
	$ifSetFollowAfterProceed = false;
	$isFollowSocnetSetuped = false;
	if((int)$profitquery[contactUs][disabled] == 0 && (int)$profitquery[contactUs][afterProceed][follow] == 1){
		$ifSetFollowAfterProceed = true;
	}
	if((int)$profitquery[callMe][disabled] == 0 && (int)$profitquery[callMe][afterProceed][follow] == 1){
		$ifSetFollowAfterProceed = true;
	}
	if((int)$profitquery[subscribeExit][disabled] == 0 && (int)$profitquery[subscribeExit][afterProceed][follow] == 1){
		$ifSetFollowAfterProceed = true;
	}
	if((int)$profitquery[subscribeBar][disabled] == 0 && (int)$profitquery[subscribeBar][afterProceed][follow] == 1){
		$ifSetFollowAfterProceed = true;
	}
	if((int)$profitquery[sharingSideBar][disabled] == 0 && (int)$profitquery[sharingSideBar][afterProceed][follow] == 1){
		$ifSetFollowAfterProceed = true;
	}
	if((int)$profitquery[imageSharer][disabled] == 0 && (int)$profitquery[imageSharer][afterProceed][follow] == 1){
		$ifSetFollowAfterProceed = true;
	}
	
	if($ifSetFollowAfterProceed){
		foreach((array)$profitquery[follow][follow_socnet] as $soc_id => $v){
			if($v){
				$isFollowSocnetSetuped = true;
			}
		}
		if(!$isFollowSocnetSetuped){
			$return = true;
		}
	}
		
	return $return;	
}


//Prepare output sctructure
function profitquery_prepare_sctructure_product($data){	
	$return = $data;	
	//After Proceed		
	if(isset($data[afterProceed])){		
		unset($return[afterProceed]);
		if((int)$data[afterProceed][follow] == 1 || (int)$data[afterProceed][thank] == 1){
			if((int)$data[afterProceed][follow] == 1){
				$return[afterProceed] = 'follow';
			}
			if((int)$data[afterProceed][thank] == 1){
				$return[afterProceed] = 'thank';
			}
		} else {
			$return[afterProceed] = '';
		}
	}
	//socnet
	if(isset($data[socnet])){
		unset($return[socnet]);
		foreach((array)$data[socnet] as $k => $v){
			if($v){
				$return[socnet][$k] = $data[socnetOption][$k];
				if($data[socnetOption][$k][type] == 'pq'){
					$return[socnet][$k][exactPageShare] = 0;
				} else {
					$return[socnet][$k][exactPageShare] = 1;
				}
			}
		}
		
	}
	//socnet_with_pos
	if(isset($data[socnet_with_pos])){
		unset($return[socnet]);
		foreach((array)$data[socnet_with_pos] as $k => $v){
			if($v){
				$return[socnet][$v] = $data[socnetOption][$v];
				if($data[socnetOption][$v][type] == 'pq'){
					$return[socnet][$v][exactPageShare] = 0;
				} else {
					$return[socnet][$v][exactPageShare] = 1;
				}
			}
		}
		
	}
	
	//socnet
	if(isset($data[follow_socnet])){
		unset($return[follow_socnet]);
		foreach((array)$data[follow_socnet] as $k => $v){
			if($v){
				if($k == 'FB') $return[follow_socnet][$k][url] = 'https://facebook.com/'.$v;
				if($k == 'TW') $return[follow_socnet][$k][url] = 'https://twitter.com/'.$v;
				if($k == 'GP') $return[follow_socnet][$k][url] = 'https://plus.google.com/'.$v;
				if($k == 'PI') $return[follow_socnet][$k][url] = 'https://pinterest.com/'.$v;
				if($k == 'VK') $return[follow_socnet][$k][url] = 'http://vk.com/'.$v;
				if($k == 'RSS') $return[follow_socnet][$k][url] = $v;				
				if($k == 'IG') $return[follow_socnet][$k][url] = 'http://instagram.com/'.$v;				
			}
		}
		
	}
	
	//img imgUrl
	if(isset($data[img]) || isset($data[imgUrl])){
		unset($return[img]);
		unset($return[imgUrl]);
		if($data[img] == 'custom' && $data[imgUrl]){
			$return[img] = $data[imgUrl];
		}elseif($data[img] != 'custom' && $data[img] != ''){
			$return[img] = plugins_url('images/'.$data[img], __FILE__);;
		} else {
			$return[img] = '';
		}
	}
	
	
	//design
	if(isset($data[design])){
		unset($return[design]);
		if($data[design][form] == 'square' || $data[design][form] == 'pq_square') $data[design][form]='';
		if(!strstr($data[design][form], 'pq_') && trim($data[design][form])) $data[design][form] = 'pq_'.$data[design][form];
		$return[design] = $data[design][size]." ".$data[design][form]." ".$data[design][color]." ".$data[design][shadow];
	}		
	
	return $return;
}
//$preparedObject = profitquery_prepare_sctructure_product($profitquery[sharingSideBar]);
//printr($preparedObject);
//die();

function profitquery_smart_widgets_insert_code(){
	global $profitquery;	
	
	
	$profitquerySmartWidgetsStructure = array();
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[sharingSideBar]);
	if(!$preparedObject[socnet]) $preparedObject[disabled] = 1;
	$preparedObject[socnet][typeBlock] = 'pq-social-block '.$preparedObject[design];
	$profitquerySmartWidgetsStructure['sharingSideBarOptions'] = array(
		'typeWindow'=>'pq_icons '.$preparedObject[position],
		'socnetIconsBlock'=>$preparedObject[socnet],
		'disabled'=>(int)$preparedObject[disabled],
		'afterProfitLoader'=>$preparedObject[afterProceed]
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[imageSharer]);
	$profitquerySmartWidgetsStructure['imageSharer'] = array(
		'typeDesign'=>$preparedObject[design].' '.$preparedObject[position],
		'minWidth'=>(int)$preparedObject[minWidth],
		'disabled'=>(int)$preparedObject[disabled],
		'activeSocnet'=>$preparedObject[socnet],
		'afterProfitLoader'=>stripslashes($preparedObject[afterProceed])
	);	
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[subscribeBar]);	
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];	
	$profitquerySmartWidgetsStructure['subscribeBarOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),		
		'disabled'=>(int)$preparedObject[disabled],
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'typeWindow'=>'pq_bar '.stripslashes($preparedObject[position]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]),		
		'inputEmailTitle'=>stripslashes($preparedObject[inputEmailTitle]),
		'inputNameTitle'=>stripslashes($preparedObject[inputNameTitle]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'subscribeProvider'=>stripslashes($profitquery[subscribeProvider]),
		'subscribeProviderOption'=>$profitquery[subscribeProviderOption][$profitquery[subscribeProvider]]
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[subscribeExit]);	
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$profitquerySmartWidgetsStructure['subscribeExitPopupOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),		
		'img'=>stripslashes($preparedObject[img]),
		'disabled'=>(int)$preparedObject[disabled],
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'typeWindow'=>stripslashes($preparedObject[typeWindow]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'inputEmailTitle'=>stripslashes($preparedObject[inputEmailTitle]),
		'inputNameTitle'=>stripslashes($preparedObject[inputNameTitle]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'subscribeProvider'=>stripslashes($profitquery[subscribeProvider]),
		'subscribeProviderOption'=>$profitquery[subscribeProviderOption][$profitquery[subscribeProvider]]
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[thankPopup]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$profitquerySmartWidgetsStructure['thankPopupOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'typeWindow'=>stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[animation]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'img'=>stripslashes($preparedObject[img]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle])
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[follow]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$profitquerySmartWidgetsStructure['followUsOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'typeWindow'=>stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[animation]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'socnetIconsBlock'=>$preparedObject[follow_socnet]
	);	

	$profitquerySmartWidgetsStructure['followUsFloatingPopup'] = array(
		'disabled'=>1		
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[callMe]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$profitquerySmartWidgetsStructure['phoneCollectOptions'] = array(
		'disabled'=>(int)$preparedObject[disabled],
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'img'=>stripslashes($preparedObject[img]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'typeBookmark'=>stripslashes($preparedObject[position]).' '.stripslashes($preparedObject[loader_background]).' pq_call',			
		'typeWindow'=>stripslashes($preparedObject[typeWindow]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'emailOption'=>array(
			'to_email'=>stripslashes($profitquery[adminEmail])			
		)
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[contactUs]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$profitquerySmartWidgetsStructure['contactUsOptions'] = array(
		'disabled'=>(int)$preparedObject[disabled],
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'img'=>stripslashes($preparedObject[img]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'typeBookmark'=>stripslashes($preparedObject[position]).' '.stripslashes($preparedObject[loader_background]).' pq_contact',			
		'typeWindow'=>stripslashes($preparedObject[typeWindow]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'emailOption'=>array(
			'to_email'=>stripslashes($profitquery[adminEmail])			
		)
	);
	
	$additionalOptionText = '';
	if((int)$profitquery[additionalOptions][enableGA] == 0 && isset($profitquery[additionalOptions])){
		$additionalOptionText = 'profitquery.productOptions.disableGA = 1;';
	}
	print "
	<script>	
		(function () {
			var s = document.createElement('script');
			var _isPQLibraryLoaded = false;
			s.type = 'text/javascript';
			s.async = true;
			s.src = '".plugins_url()."/".PROFITQUERY_SMART_WIDGETS_PLUGIN_NAME."/js/lite.profitquery.min.js?apiKey=".$profitquery[apiKey]."';
			s.onload = function(){
				if ( !_isPQLibraryLoaded )
				{					
				  _isPQLibraryLoaded = true;				  
				  profitquery.loadFunc.callAfterPQInit(function(){
						".$additionalOptionText."
						var smartWidgetsBoxObject = ".json_encode($profitquerySmartWidgetsStructure).";	
						profitquery.widgets.smartWidgetsBox(smartWidgetsBoxObject);	
					});
				}
			}
			s.onreadystatechange = function() {								
				if ( !_isPQLibraryLoaded && (this.readyState == 'complete' || this.readyState == 'loaded') )
				{					
				  _isPQLibraryLoaded = true;
					profitquery.loadFunc.callAfterPQInit(function(){
						".$additionalOptionText."
						var smartWidgetsBoxObject = ".json_encode($profitquerySmartWidgetsStructure).";	
						profitquery.widgets.smartWidgetsBox(smartWidgetsBoxObject);	
					});
				}
			};
			var x = document.getElementsByTagName('script')[0];						
			x.parentNode.insertBefore(s, x);			
		})();				
	</script>
	";
}

add_filter('plugin_action_links', 'profitquery_wordpress_admin_link', 10, 2);