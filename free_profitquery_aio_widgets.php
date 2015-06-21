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
* Plugin Name: Share Subscribe Contact Any Popover's | AIO
* Plugin URI: http://profitquery.com/aio_widgets.html
* Description: Any popovers for solving many website tasks. Growth email, subscription, feedback, phone number, shares, referral's, followers, etc.
* Version: 3.2
*
* Author: Profitquery Team <support@profitquery.com>
* Author URI: http://profitquery.com/?utm_campaign=aio_widgets_wp
*/

//update_option('profitquery', array());
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
	if ( !is_admin()){
		add_action('wp_head', 'profitquery_smart_widgets_insert_cache_hack_code');		
		add_action('wp_footer', 'profitquery_smart_widgets_insert_code');		
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

function profitquery_is_thank_enabled($profitquery){
	$ret = false;
	if((int)$profitquery[contactUs][afterProceed][thank] == 1){
		$ret = true;
	}
	if((int)$profitquery[callMe][afterProceed][thank] == 1){
		$ret = true;
	}
	if((int)$profitquery[subscribeExit][afterProceed][thank] == 1){
		$ret = true;
	}
	if((int)$profitquery[subscribeBar][afterProceed][thank] == 1){
		$ret = true;
	}
	if((int)$profitquery[sharingSideBar][afterProceed][thank] == 1){
		$ret = true;
	}
	if((int)$profitquery[imageSharer][afterProceed][thank] == 1){
		$ret = true;
	}
	return $ret;
}


function profitquery_is_follow_enabled($profitquery){
	$ret = false;
	if((int)$profitquery[contactUs][afterProceed][follow] == 1){
		$ret = true;
	}
	if((int)$profitquery[callMe][afterProceed][follow] == 1){
		$ret = true;
	}
	if((int)$profitquery[subscribeExit][afterProceed][follow] == 1){
		$ret = true;
	}
	if((int)$profitquery[subscribeBar][afterProceed][follow] == 1){
		$ret = true;
	}
	if((int)$profitquery[sharingSideBar][afterProceed][follow] == 1){
		$ret = true;
	}
	if((int)$profitquery[imageSharer][afterProceed][follow] == 1){
		$ret = true;
	}
	return $ret;
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
				if($k == 'OD') $return[follow_socnet][$k][url] = 'http://ok.ru/'.$v;				
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
			$return[img] = plugins_url('i/'.$data[img], __FILE__);;
		} else {
			$return[img] = '';
		}
	}
	
	
	if(isset($data[galleryOption])){
		$return[title] = stripslashes($data[galleryOption][title]);
		$return[button_color] = stripslashes($data[galleryOption][button_color]);
		$return[background_color] = stripslashes($data[galleryOption][background_color]);
		$return[buttonTitle] = stripslashes($data[galleryOption][buttonTitle]);
		$return[minWidth] = stripslashes($data[galleryOption][minWidth]);
		$return[disable] = (int)$data[galleryOption][disable];
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



function profitquery_prepare_image_url_mask_options($name){
	global $profitquery;
	$ret = array();
	if($profitquery[proOptions][$name]){		
		if($profitquery[proOptions][$name][disableExeptImageUrlMask]){
			foreach((array)$profitquery[proOptions][$name][disableExeptImageUrlMask] as $k => $v){
				if($v){
					$ret[$k] = $v;
				}
			}
		}
	}
	return $ret;
}

function profitquery_prepare_exp_options($name){
	global $profitquery;
	$ret = array();
	if($profitquery[proOptions][$name]){		
		if($profitquery[proOptions][$name][disableExeptExtensions]){
			foreach((array)$profitquery[proOptions][$name][disableExeptExtensions] as $k => $v){
				if($v){
					$ret[$k] = $v;
				}
			}
		}
	}
	return $ret;
}

function profitquery_prepare_disable_options($name){
	global $profitquery;
	$ret = array();
	if($profitquery[proOptions][$name]){
		if((int)$profitquery[proOptions][$name][disableMainPage]){
			$ret[disableMainPage] = stripslashes($profitquery[proOptions][mainPageUrl]);
		}
		if($profitquery[proOptions][$name][disableExeptPageMask]){
			foreach((array)$profitquery[proOptions][$name][disableExeptPageMask] as $k => $v){
				if($v){
					$ret[disableExeptPageMask][$k] = $v;
				}
			}
		}
	}
	return $ret;
}


function profitquery_prepare_pro_options($name){
	global $profitquery;
	$ret = array();
	if($profitquery[proOptions][$name]){
		$ret = $profitquery[proOptions][$name];
	}
	return $ret;
}
//$preparedObject = profitquery_prepare_sctructure_product($profitquery[sharingSideBar]);
//printr($preparedObject);
//die();

function profitquery_smart_widgets_insert_code(){
	global $profitquery;	
	
	
	$profitquerySmartWidgetsStructure = array();
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[sharingSideBar]);
	$disableOptions = profitquery_prepare_disable_options('sharingSideBar');	
		
	if(!$preparedObject[socnet]) $preparedObject[disabled] = 1;	
	
	$proOptions = profitquery_prepare_pro_options('sharingSideBar');
	$animation = '';
	if($proOptions[animation]){
		$animation = 'pq_animated '.$proOptions[animation];
	}
	
	//from right to left
	if($profitquery[additionalOptions][lang] == 'fa'){
		$langContOption = 'pq_rtl';
	}else{
		$langContOption = '';
	}
	
	$preparedObject[socnet][typeBlock] = 'pq-social-block '.$preparedObject[design];	
	$preparedObject[socnet][hoverAnimation] = $proOptions[hover_animation];
	//hack for old version
	if($preparedObject[position]){
		$temp = explode(' ', $preparedObject[position]);
		$profitquery[sharingSideBar][side] = $preparedObject[side] = $temp[0];
		$profitquery[sharingSideBar][top] = $preparedObject[top] = $temp[1];		
		unset($profitquery[sharingSideBar][position]);
		update_option('profitquery', $profitquery);
	}
	
	$profitquerySmartWidgetsStructure['sharingSideBarOptions'] = array(
		'typeWindow'=>'pq_icons '.$preparedObject[side].' '.$preparedObject[top].' '.$animation,
		'socnetIconsBlock'=>$preparedObject[socnet],
		'mobile_title'=>stripslashes($preparedObject[mobile_title]),
		'disabled'=>(int)$preparedObject[disabled],
		'galleryOption'=>$preparedObject[galleryOption],
		'disablePageOptions'=>$disableOptions,	
		'afterProfitLoader'=>$preparedObject[afterProceed]
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[imageSharer]);
	$disableOptions = profitquery_prepare_disable_options('imageSharer');		
	$enabledExpressions = profitquery_prepare_exp_options('imageSharer');		
	$imageUrlMaskOptions = profitquery_prepare_image_url_mask_options('imageSharer');		
	$proOptions = profitquery_prepare_pro_options('imageSharer');
	//printr($proOptions);
	//die();
	$profitquerySmartWidgetsStructure['imageSharer'] = array(
		'typeDesign'=>$preparedObject[design].' '.$preparedObject[position],
		'minWidth'=>(int)$preparedObject[minWidth],
		'disabled'=>(int)$preparedObject[disabled],
		'activeSocnet'=>$preparedObject[socnet],
		'disableAfterClick'=>$preparedObject[disableAfterClick],
		'disablePageOptions'=>$disableOptions,
		'minHeight'=>$proOptions[minHeight],
		'hoverAnimation'=>$proOptions[hover_animation],
		'enabledExpressions'=>$enabledExpressions,				
		'disableImageExeptUrlMaskOptions'=>$imageUrlMaskOptions,				
		'afterProfitLoader'=>stripslashes($preparedObject[afterProceed])
	);	
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[subscribeBar]);	
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];	
	$disableOptions = profitquery_prepare_disable_options('subscribeBar');	
	$proOptions = profitquery_prepare_pro_options('subscribeBar');	
	if($proOptions[animation]){
		$preparedObject[animation] = 'pq_animated '.$proOptions[animation];
	}	
	$profitquerySmartWidgetsStructure['subscribeBarOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'mobile_title'=>stripslashes($preparedObject[mobile_title]),
		'disabled'=>(int)$preparedObject[disabled],
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'typeWindow'=>$langContOption.' pq_bar '.stripslashes($preparedObject[position]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]).' '.$proOptions[font].' '.$proOptions[head_font].' '.$proOptions[head_color].' '.$proOptions[head_size].' '.$proOptions[text_size].' '.$proOptions[text_color].' '.$proOptions[b_radius].' '.$proOptions[b_color].' '.$proOptions[b_opacity].' '.$proOptions[b_style].' '.$proOptions[b_shadow].' '.$proOptions[b_width].' '.$proOptions[b_c_color],
		'inputEmailTitle'=>stripslashes($preparedObject[inputEmailTitle]),
		'inputNameTitle'=>stripslashes($preparedObject[inputNameTitle]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'subscribeProvider'=>stripslashes($profitquery[subscribeProvider]),
		'disablePageOptions'=>$disableOptions,	
		'subscribeProviderOption'=>$profitquery[subscribeProviderOption][$profitquery[subscribeProvider]]
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[subscribeExit]);	
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$disableOptions = profitquery_prepare_disable_options('subscribeExit');	
	$proOptions = profitquery_prepare_pro_options('subscribeExit');	
	if($proOptions[animation]){
		$preparedObject[animation] = 'pq_animated '.$proOptions[animation];
	}	
	$profitquerySmartWidgetsStructure['subscribeExitPopupOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),		
		'img'=>stripslashes($preparedObject[img]),
		'disabled'=>(int)$preparedObject[disabled],
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'typeWindow'=>$langContOption.' '.stripslashes($preparedObject[typeWindow]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]).' '.$proOptions[font].' '.$proOptions[head_font].' '.$proOptions[head_color].' '.$proOptions[head_size].' '.$proOptions[text_size].' '.$proOptions[text_color].' '.$proOptions[b_radius].' '.$proOptions[b_color].' '.$proOptions[b_opacity].' '.$proOptions[b_style].' '.$proOptions[b_shadow].' '.$proOptions[b_width].' '.$proOptions[b_c_color],
		'background_image'=>stripslashes($proOptions[background_image]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'inputEmailTitle'=>stripslashes($preparedObject[inputEmailTitle]),
		'inputNameTitle'=>stripslashes($preparedObject[inputNameTitle]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'subscribeProvider'=>stripslashes($profitquery[subscribeProvider]),
		'disablePageOptions'=>$disableOptions,	
		'subscribeProviderOption'=>$profitquery[subscribeProviderOption][$profitquery[subscribeProvider]]
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[thankPopup]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$disableOptions = profitquery_prepare_disable_options('thankPopup');	
	$proOptions = profitquery_prepare_pro_options('thankPopup');	
	if($proOptions[animation]){
		$preparedObject[animation] = 'pq_animated '.$proOptions[animation];
	}	
	$profitquerySmartWidgetsStructure['thankPopupOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'typeWindow'=>$langContOption.' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[animation]).' '.$proOptions[font].' '.$proOptions[head_font].' '.$proOptions[head_color].' '.$proOptions[head_size].' '.$proOptions[text_size].' '.$proOptions[text_color].' '.$proOptions[b_radius].' '.$proOptions[b_color].' '.$proOptions[b_opacity].' '.$proOptions[b_style].' '.$proOptions[b_shadow].' '.$proOptions[b_width].' '.$proOptions[b_c_color],
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'background_image'=>stripslashes($proOptions[background_image]),
		'img'=>stripslashes($preparedObject[img]),
		'disablePageOptions'=>$disableOptions,	
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle])
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[follow]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$disableOptions = profitquery_prepare_disable_options('follow');	
	$proOptions = profitquery_prepare_pro_options('follow');	
	if($proOptions[animation]){
		$preparedObject[animation] = 'pq_animated '.$proOptions[animation];
	}
	$preparedObject[follow_socnet][hoverAnimation] = $proOptions[hover_animation];
	$preparedObject[follow_socnet][typeBlock] = 'pq-social-block '.$preparedObject[design];
	//printr($proOptions);
	//die();
	$profitquerySmartWidgetsStructure['followUsOptions'] = array(
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'typeWindow'=>$langContOption.' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[animation]).' '.$proOptions[font].' '.$proOptions[head_font].' '.$proOptions[head_color].' '.$proOptions[head_size].' '.$proOptions[text_size].' '.$proOptions[text_color].' '.$proOptions[b_radius].' '.$proOptions[b_color].' '.$proOptions[b_opacity].' '.$proOptions[b_style].' '.$proOptions[b_shadow].' '.$proOptions[b_width].' '.$proOptions[b_c_color],
		'background_image'=>stripslashes($proOptions[background_image]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'disablePageOptions'=>$disableOptions,	
		'socnetIconsBlock'=>$preparedObject[follow_socnet]
	);		
	$profitquerySmartWidgetsStructure['followUsFloatingPopup'] = array(
		'disabled'=>1		
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[callMe]);
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$disableOptions = profitquery_prepare_disable_options('phoneCollectOptions');
	//hack for old version
	if($preparedObject[position]){		
		$temp = explode(' ', $preparedObject[position]);
		$profitquery[callMe][side] = $preparedObject[side] = $temp[0];
		$profitquery[callMe][top] = $preparedObject[top] = $temp[1];		
		unset($profitquery[callMe][position]);
		update_option('profitquery', $profitquery);
	}
	$preparedObject[position] = $preparedObject[side].' '.$preparedObject[top];
	$proOptions = profitquery_prepare_pro_options('callMe');		
	if($proOptions[animation]){
		$preparedObject[animation] = 'pq_animated '.$proOptions[animation];
	}	
	$profitquerySmartWidgetsStructure['phoneCollectOptions'] = array(
		'disabled'=>(int)$preparedObject[disabled],
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'img'=>stripslashes($preparedObject[img]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'bookmarkTitle'=>stripslashes($preparedObject[loaderText]),
		'background_image'=>stripslashes($proOptions[background_image]),		
		'typeBookmark'=>stripslashes($preparedObject[position]).' '.stripslashes($preparedObject[loader_background]).' pq_call',			
		'typeWindow'=>$langContOption.' '.stripslashes($preparedObject[typeWindow]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]).' '.$proOptions[font].' '.$proOptions[head_font].' '.$proOptions[head_color].' '.$proOptions[head_size].' '.$proOptions[text_size].' '.$proOptions[text_color].' '.$proOptions[b_radius].' '.$proOptions[b_color].' '.$proOptions[b_opacity].' '.$proOptions[b_style].' '.$proOptions[b_shadow].' '.$proOptions[b_width].' '.$proOptions[b_c_color],
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'disablePageOptions'=>$disableOptions,	
		'formTextOptions'=>array('enterPhoneText'=>stripslashes($preparedObject[enter_phone_text]), 'enterNameText'=> stripslashes($preparedObject[enter_name_text])),
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'emailOption'=>array(
			'to_email'=>stripslashes($profitquery[adminEmail])			
		)
	);
	
	$preparedObject = profitquery_prepare_sctructure_product($profitquery[contactUs]);	
	if($preparedObject[animation] && $preparedObject[animation] != 'fade') $preparedObject[animation] = 'pq_animated '.$preparedObject[animation];
	$disableOptions = profitquery_prepare_disable_options('contactUs');	
	
	//hack for old version
	if($preparedObject[position]){
		$temp = explode(' ', $preparedObject[position]);
		$profitquery[contactUs][side] = $preparedObject[side] = $temp[0];
		$profitquery[contactUs][top] = $preparedObject[top] = $temp[1];		
		unset($profitquery[contactUs][position]);
		update_option('profitquery', $profitquery);
	}
	
	$preparedObject[position] = $preparedObject[side].' '.$preparedObject[top];	
	
	$proOptions = profitquery_prepare_pro_options('contactUs');		
	if($proOptions[animation]){
		$preparedObject[animation] = 'pq_animated '.$proOptions[animation];
	}
	$preparedObject[typeWindow] .= ' '.$proOptions[font].' '.$proOptions[head_font].' '.$proOptions[head_color].' '.$proOptions[head_size].' '.$proOptions[text_size].' '.$proOptions[text_color].' '.$proOptions[b_radius].' '.$proOptions[b_color].' '.$proOptions[b_opacity].' '.$proOptions[b_style].' '.$proOptions[b_shadow].' '.$proOptions[b_width].' '.$proOptions[b_c_color];
	
	$profitquerySmartWidgetsStructure['contactUsOptions'] = array(
		'disabled'=>(int)$preparedObject[disabled],
		'title'=>stripslashes($preparedObject[title]),
		'sub_title'=>stripslashes($preparedObject[sub_title]),
		'background_image'=>stripslashes($proOptions[background_image]),
		'img'=>stripslashes($preparedObject[img]),
		'buttonTitle'=>stripslashes($preparedObject[buttonTitle]),
		'bookmarkTitle'=>stripslashes($preparedObject[loaderText]),
		'typeBookmark'=>stripslashes($preparedObject[position]).' '.stripslashes($preparedObject[loader_background]).' pq_contact',			
		'typeWindow'=>$langContOption.' '.stripslashes($preparedObject[typeWindow]).' '.stripslashes($preparedObject[background]).' '.stripslashes($preparedObject[button_color]).' '.stripslashes($preparedObject[animation]),
		'blackoutOption'=>array('disable'=>0, 'style'=>stripslashes($preparedObject[overlay])),
		'afterProfitLoader'=>$preparedObject[afterProceed],
		'formTextOptions'=>array('enterEmailText'=>stripslashes($preparedObject[enter_email_text]), 'enterNameText'=> stripslashes($preparedObject[enter_name_text]), 'enterMessageText'=> stripslashes($preparedObject[enter_message_text])),
		'disablePageOptions'=>$disableOptions,	
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
			var PQInit = function(){
				profitquery.loadFunc.callAfterPQInit(function(){					
					profitquery.loadFunc.callAfterPluginsInit(						
						function(){							
							PQLoadTools();
						}
						, ['//api.profitquery.com/plugins/aio.plugin.profitquery.min.js']
					);
				});
			};
			var s = document.createElement('script');
			var _isPQLibraryLoaded = false;
			s.type = 'text/javascript';
			s.async = true;			
			s.src = '//api.profitquery.com/lib/profitquery.min.js?version=v3.0.4&lang=".stripslashes($profitquery[additionalOptions][lang])."&pro_loader_name=".stripslashes($profitquery[proOptions][proLoaderFilename])."&apiKey=".stripslashes($profitquery[apiKey])."';
			s.onload = function(){
				if ( !_isPQLibraryLoaded )
				{					
				  _isPQLibraryLoaded = true;				  
				  PQInit();
				}
			}
			s.onreadystatechange = function() {								
				if ( !_isPQLibraryLoaded && (this.readyState == 'complete' || this.readyState == 'loaded') )
				{					
				  _isPQLibraryLoaded = true;				    
					
					PQInit();					
				}
			};
			var x = document.getElementsByTagName('script')[0];						
			x.parentNode.insertBefore(s, x);			
		})();
		
		function PQLoadTools(){
			profitquery.loadFunc.callAfterPQInit(function(){
						".$additionalOptionText."
						var smartWidgetsBoxObject = ".json_encode($profitquerySmartWidgetsStructure).";	
						profitquery.widgets.smartWidgetsBox.init(smartWidgetsBoxObject);	
					});
		}
	</script>	
	";
}


add_filter('plugin_action_links', 'profitquery_wordpress_admin_link', 10, 2);