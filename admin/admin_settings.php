<?php if(!defined('cfih') or !cfih or cfih!='admin') die('This file cannot be directly accessed.');
/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author:    codefuture.co.uk
 *   Version:   1.6.5
 *
 *   You can download the latest version from: http://codefuture.co.uk/projects/imagehost/
 *
 *   Copyright (c) 2010-2013 CodeFuture.co.uk
 *   This file is part of the CF Image Hosting Script.
 *
 *   THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 *   EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 *   COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *   WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
 *   OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *
 *   You may not modify and/or remove any copyright notices or labels on the software on each
 *   page (unless full license is purchase) and in the header of each script source file.
 *
 *   You should have received a full copy of the LICENSE AGREEMENT along with
 *   Codefuture Image Hosting Script. If not, see http://codefuture.co.uk/projects/imagehost/license/.
 *
 *
 *   ABOUT THIS PAGE -----
 *   Used For:     Admin page header
 *   Last edited:  11/01/2013
 *
 *************************************************************************************************************/
# variable settings
	$emailPattern = '`^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$`i';

//save new settings
	if(isset($_POST['changesettings'])) {

	//Password
		if(!empty($_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['newConfirm'])){
			if (cl($_POST['newPassword']) == cl($_POST['newConfirm'])){
				if (md5(md5(cl($_POST['oldPassword'])).$settings['SET_SALTING']) == $settings['SET_PASSWORD']){
					$settings['SET_PASSWORD'] = md5(md5(cl($_POST['newPassword'])).$settings['SET_SALTING']);
				}else{
					user_feedback('error',_T("admin_set_err_password_wrong"),'admin_set_password_wrong');
				}
			}else{
				user_feedback('error',_T("admin_set_err_password_wrong"),'admin_set_password_wrong_2');
			}
		}else if(!empty($_POST['oldPassword']) || !empty($_POST['newPassword']) || !empty($_POST['newConfirm'])){
			user_feedback('error',_T("admin_set_err_password_both"),'admin_set_password_both');
		}

	//UserName
		if (!empty($_POST['setUserName'])){
			$settings['SET_USERNAME'] = cl($_POST['setUserName']);
		}else{
			user_feedback('error',_T("admin_set_err_username"),'admin_set_username');
		}

	//email
		if(preg_match($emailPattern,  cl($_POST['setEmail']))){
			$settings['SET_CONTACT'] = cl($_POST['setEmail']);
		}else{
			user_feedback('error',_T("admin_set_err_email_invalid"),'admin_set_email_invalid');
		}

	// Script Url
		$scriptUrl = cl($_POST['setScriptUrl']);
		if (!empty($scriptUrl)){
			$settings['SET_SITEURL'] = tsl($scriptUrl);
		}else{
			user_feedback('error',_T("admin_set_err_script_url"),'admin_set_script_url');
		}

		$settings['SET_WATERMARK']			= cl($_POST['setWaterMark']) == 1? 1:0;
		$settings['SET_WATERMARK_TEXT']		= cl($_POST['setWatermarkText']);
		$settings['SET_WATERMARK_PLACED']	= (int)cl($_POST['setWatermarkPlaced']);
		$settings['SET_WATERMARK_IMAGE']	= cl($_POST['setWatermarkImage']);

		$settings['SET_MOD_REWRITE']		= cl($_POST['setModRewrite']) == 1? 1:0;
		$settings['SET_AUTO_DELETED']		= cl($_POST['setAutoDeleted']) == 1? 1:0;
		$settings['SET_HIDE_SEARCH']		= cl($_POST['setHideSearch']) == 1? 1:0;
		$settings['SET_HIDE_CONTACT']		= cl($_POST['setHideContact']) == 1? 1:0;
		$settings['SET_HIDE_TOS']			= cl($_POST['setHideTos']) == 1? 1:0;
		$settings['SET_HIDE_GALLERY']		= cl($_POST['setHideGallery']) == 1? 1:0;
		$settings['SET_HIDE_FAQ']			= cl($_POST['setHideFaq']) == 1? 1:0;
		$settings['SET_HIDE_FEED']			= cl($_POST['setHideFeed']) == 1? 1:0;
		$settings['SET_HIDE_SITEMAP']		= cl($_POST['setHideSitemap']) == 1? 1:0;
		$settings['SET_EMAIL_REPORT']		= cl($_POST['setEmailReport']) == 1? 1:0;
		$settings['SET_ALLOW_REPORT']		= cl($_POST['setAllowReport']) == 1? 1:0;
		$settings['SET_MAX_BANDWIDTH']		= (int)empty($_POST['setMaxBandwidth'])?0:$_POST['setMaxBandwidth'];
		$settings['SET_TITLE']				= cl($_POST['setTitle']);
		$settings['SET_SLOGAN']				= cl($_POST['setSlogan']);
		$settings['SET_COPYRIGHT']			= cl($_POST['setCopyright']);
		$settings['SET_MAXSIZE']			= (int)cl($_POST['setMaxSize']);
		$settings['SET_IMG_ROW_NO']			= 4;//(int)cl($_POST['setImgRowNo']);
		$settings['SET_IMG_ROWS']			= (int)cl($_POST['setImgRows']);
		$settings['SET_MAX_UPLOAD']			= (int)cl($_POST['setMaxUpload']);
		$settings['SET_THEME']				= cl($_POST['setTheme']);
		$settings['SET_GOOGLE_ANALYTICS']	= cl($_POST['setAnalytics']);
                $settings['SET_BAIDU_ANALYTICS']       = cl($_POST['setBaiduAnalytics']);
		$settings['SET_GOOGLE_CHANNAL']		= cl($_POST['setGoogleCha']);
		$settings['SET_GOOGLE_ADS']			= cl($_POST['setGoogleAds']);
		$settings['SET_BANDWIDTH_RESET']	= cl($_POST['setBandwidthReset']);
		$settings['SET_AUTO_DELETED_TIME']	= (int)cl($_POST['setAutoDeletedTime']);
		$settings['SET_AUTO_DELETED_JUMP']	= cl($_POST['setAutoDeletedJump']);
		$settings['SET_SHORT_URL_ON']		= cl($_POST['setShortUrl']) == 1? 1:0;
		$settings['SET_PRIVATE_IMG_ON']		= cl($_POST['setPrivateImg']) == 1? 1:0;
		$settings['SET_DIS_UPLOAD']			= cl($_POST['setDisUpload']) == 1? 1:0;
		$settings['SET_LANGUAGE']			= cl($_POST['setLanguage']);
		$settings['SET_IMAGE_WIDGIT']		= cl($_POST['setImageWidgit']) == 1? 1:0;
		$settings['SET_NODUPLICATE']		= cl($_POST['setNoDuplicate']) == 1? 1:0;
		$settings['SET_RESIZE_IMG_ON']		= cl($_POST['setResizeImg']) == 1? 1:0;
		$settings['SET_ADDTHIS']			= cl($_POST['setAddThis']);

	//Short url settings
		$settings['SET_SHORT_URL_API']		= cl($_POST['setSUrlApi']);
		$settings['SET_SHORT_URL_API_URL']	= cl($_POST['setSUrlApiUrl']);
		$settings['SET_SHORT_URL_PASS']		= cl($_POST['setSUrlApiPass']);
		$settings['SET_SHORT_URL_USER']		= cl($_POST['setSUrlApiUesr']);

	// save settings
		if (!is_feedback('error')){
			if(saveSettings(CFSETTINGS,$settings)){
				user_feedback('success',_T("admin_set_suc_update"),'saveing_settings');
				cl_cache_folder();
			}else{
				user_feedback('error',_T("admin_set_err_saveing_settings"),'admin_set_saveing_settings');
			}
		}
	}


// page settings
	$page['id']					= 'set';
	$page['title']				= 'Admin Settings page';
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['fancybox']		= true;

	require CFROOTPATH.'admin/admin_page_header.php';
?>
<!-- admin settings -->
		<div id="msg"></div>
		<form method="POST" action="admin.php?act=set">
			<div class="tabs">
					<ul class="tabs_list tabNavigation">
						<li><a href="#setSite"><?php echo _T("admin_set_title_site_setting");?></a></li>
						<li><a href="#setGallery"><?php echo _T("admin_set_title_gallery_setting");?></a></li>
						<li><a href="#setPage"><?php echo _T("admin_set_title_hide_page");?></a></li>
						<li><a href="#setDeleted"><?php echo _T("admin_set_title_auto_deleted");?></a></li>
						<li><a href="#setUpload"><?php echo _T("admin_set_title_upload_setting");?></a></li>
						<li><a href="#setWatermark"><?php echo _T("admin_set_watermark_title");?></a></li>
						<li><a href="#setShoetUrl"><?php echo _T("admin_set_title_url_shortener");?></a></li>
						<li><a href="#setGoogle"><?php echo _T("admin_set_title_google_setting");?></a></li>
						<li><a href="#setAdmin"><?php echo _T("admin_set_title_admin_setting");?></a></li>
					</ul>

				<!--site_setting-->
					<div id="setSite" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_site_setting"));
						optionText(_T("admin_set_script_url"),'setScriptUrl',$settings['SET_SITEURL'],'long');
						optionText(_T("admin_set_site_title"),'setTitle',$settings['SET_TITLE'],'long');
						optionText(_T("admin_set_site_slogan"),'setSlogan',$settings['SET_SLOGAN'],'long');
						optionText(_T("admin_set_footer_copyright"),'setCopyright',$settings['SET_COPYRIGHT'],'long');
						optionList(_T("admin_set_site_theme"),'setTheme',$settings['SET_THEME'], makeThemeArray());
						optionOnOff(_T("admin_set_mod_rewrite"),'setModRewrite',$settings['SET_MOD_REWRITE']);
						optionText(_T("admin_set_addthis"),'setAddThis',$settings['SET_ADDTHIS'],'long');
						optionList(_T("admin_set_language"),'setLanguage',$settings['SET_LANGUAGE'], makeLanguageArray());
						submitButton();
					?>
					</div>

				<!--Gallery Settings -->
					<div id="setGallery" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_gallery_setting"));
					//	optionList(_T("admin_set_images_gallery_rows_no"),'setImgRowNo',$settings['SET_IMG_ROW_NO'],array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'));
						optionList(_T("admin_set_images_gallery_rows"),'setImgRows',$settings['SET_IMG_ROWS'],array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'));
						optionOnOff(_T("admin_set_report_allow"),'setAllowReport',$settings['SET_ALLOW_REPORT']);
						optionOnOff(_T("admin_set_report_Send_email"),'setEmailReport',$settings['SET_EMAIL_REPORT']);
						submitButton();
					?>
					</div>

				<!--hide page-->
					<div id="setPage" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_hide_page"));
						optionDescription('tune off items and pages you do not need to use');
						optionOnOff(_T("admin_set_hide_gallery"),'setHideGallery',$settings['SET_HIDE_GALLERY']);// yes 1/no 0
						optionOnOff(_T("admin_set_hide_contact"),'setHideContact',$settings['SET_HIDE_CONTACT']);
						optionOnOff(_T("admin_set_hide_tos"),'setHideTos',$settings['SET_HIDE_TOS']);// yes 1/no 0
						optionOnOff(_T("admin_set_hide_faq"),'setHideFaq',$settings['SET_HIDE_FAQ']);// yes 1/no 0
						optionOnOff(_T("admin_set_hide_search"),'setHideSearch',$settings['SET_HIDE_SEARCH']);// yes 1/no 0
						optionOnOff(_T("admin_set_image_widgit"),'setImageWidgit',$settings['SET_IMAGE_WIDGIT']);// yes 1/no 0
						optionOnOff(_T("admin_set_hide_feed")	,'setHideFeed',$settings['SET_HIDE_FEED']);// yes 1/no 0
						optionOnOff(_T("admin_set_hide_sitemap"),'setHideSitemap',$settings['SET_HIDE_SITEMAP']);// yes 1/no 0
						submitButton();
					?>
					</div>

				<!--auto deleted-->
					<div id="setDeleted" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_auto_deleted"));
						optionDescription(_T("admin_set_des_auto_deleted"));
						optionOnOff(_T("admin_set_auto_deleted"),'setAutoDeleted',$settings['SET_AUTO_DELETED']);// yes 1/no 0
						optionList(_T("admin_set_auto_deleted_for"),'setAutoDeletedTime',$settings['SET_AUTO_DELETED_TIME'],array('120'=>'120 '._T("admin_set_auto_deleted_days"),'90'=>'90 '._T("admin_set_auto_deleted_days"),'60'=>'60 '._T("admin_set_auto_deleted_days"),'30'=>'30 '._T("admin_set_auto_deleted_days")));
						optionList(_T("admin_set_run_auto_deleted"),'setAutoDeletedJump',$settings['SET_AUTO_DELETED_JUMP'],array('m'=>_T("admin_set_run_auto_deleted_Month"),'W'=>_T("admin_set_run_auto_deleted_week"),'z'=>_T("admin_set_run_auto_deleted_day")));
						submitButton();
					?>
					</div>

				<!--upload_setting-->
					<div id="setUpload" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_upload_setting"));
						optionOnOff(_T("admin_set_disable_upload"),'setDisUpload',$settings['SET_DIS_UPLOAD']);// yes 0/no 1
						optionList(_T("admin_set_max_upload_file_size"),'setMaxSize',$settings['SET_MAXSIZE'], array('256000'=>'250 kb','512000'=>'500 kb','1048576'=>'1 mb','2097152'=>'2 mb','5242880'=>'5 mb','10485760'=>'10 mb'));
						optionDescription(_T("admin_set_image_max_bandwidth_des"));
						optionText(_T("admin_set_image_max_bandwidth"),'setMaxBandwidth',$settings['SET_MAX_BANDWIDTH']);
						optionList(_T("admin_set_auto_reset_bandwidth"),'setBandwidthReset',$settings['SET_BANDWIDTH_RESET'], array('m'=>_T("admin_set_run_auto_deleted_Month"),'W'=>_T("admin_set_run_auto_deleted_week")));
						optionList(_T("admin_set_multiple_upload_max"),'setMaxUpload',$settings['SET_MAX_UPLOAD'], array('0'=>'none','5'=>'5','10'=>'10'));
						optionOnOff(_T("admin_set_allow_duplicate"),'setNoDuplicate',$settings['SET_NODUPLICATE']);// yes 0/no 1
						optionOnOff(_T("admin_set_allow_image_resize"),'setResizeImg',$settings['SET_RESIZE_IMG_ON']);// yes 1/no 0
						optionOnOff(_T("admin_set_private_image_upload"),'setPrivateImg',$settings['SET_PRIVATE_IMG_ON']);// yes 0/no 1
						submitButton();
					?>
					</div>

				<!--watermark-->
					<div id="setWatermark" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_watermark_title"));
						optionDescription(_T("admin_set_watermark_des"));
						optionOnOff(_T("admin_set_watermark_title"),'setWaterMark',$settings['SET_WATERMARK']);// off 1/on 0
						optionText(_T("admin_set_watermark_text"),'setWatermarkText',$settings['SET_WATERMARK_TEXT'],'long');
						optionText(_T("admin_set_watermark_image"),'setWatermarkImage',$settings['SET_WATERMARK_IMAGE'],'long');
						optionList(_T("admin_set_watermark_position"),'setWatermarkPlaced',$settings['SET_WATERMARK_PLACED'], array(1=>_T("admin_set_watermark_top").' '._T("admin_set_watermark_left"),
																																				2=>_T("admin_set_watermark_top").' '._T("admin_set_watermark_center"),
																																				3=>_T("admin_set_watermark_top").' '._T("admin_set_watermark_right"),
																																				4=>_T("admin_set_watermark_center").' '._T("admin_set_watermark_left"),
																																				5=>_T("admin_set_watermark_center").' '._T("admin_set_watermark_center"),
																																				6=>_T("admin_set_watermark_center").' '._T("admin_set_watermark_right"),
																																				7=>_T("admin_set_watermark_bottom").' '._T("admin_set_watermark_left"),
																																				8=>_T("admin_set_watermark_bottom").' '._T("admin_set_watermark_center"),
																																				9=>_T("admin_set_watermark_bottom").' '._T("admin_set_watermark_right")
																																				));
						submitButton();
					?>
					</div>

				<!--url_shortener-->
					<div id="setShoetUrl" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_url_shortener"));
						optionOnOff(_T("admin_set_url_shortener"),'setShortUrl',$settings['SET_SHORT_URL_ON']);// off 0/on 1
						optionList(_T("admin_set_url_short_service"),'setSUrlApi',$settings['SET_SHORT_URL_API'], array('b54'=>'B54.in','yourls'=>'yourls','bitly'=>'bit.ly','tinyurl'=>'tinyurl.com','isgd'=>'is.gd','googl'=>'goo.gl'));
						optionText(_T("admin_set_url_short_api_url"),'setSUrlApiUrl',$settings['SET_SHORT_URL_API_URL']);
						optionText(_T("admin_set_url_short_api_username"),'setSUrlApiUesr',$settings['SET_SHORT_URL_USER']);
						optionText(_T("admin_set_url_short_api_password"),'setSUrlApiPass',$settings['SET_SHORT_URL_PASS']);
						submitButton();
					?>
					</div>

				<!--google_setting-->
					<div id="setGoogle" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_google_setting"));
						optionDescription(_T("admin_set_google_setting_des"));
						optionText(_T("admin_set_google_analytics_code"),'setAnalytics',$settings['SET_GOOGLE_ANALYTICS'],'long');
                                                optionText(_T("admin_set_baidu_analytics_code"),'setBaiduAnalytics',$settings['SET_BAIDU_ANALYTICS'],'long');
						optionText(_T("admin_set_google_channal_code"),'setGoogleCha',$settings['SET_GOOGLE_CHANNAL'],'long');
						optionText(_T("admin_set_google_adsense_code"),'setGoogleAds',$settings['SET_GOOGLE_ADS'],'long');
						submitButton();
					?>
					</div>
					
				<!--admin setting-->
					<div id="setAdmin" class="panel ibox">
					<?php
						optionTitle(_T("admin_set_title_admin_setting"));
						optionText(_T("admin_set_old_password"),'oldPassword','',null,'password');
						optionText(_T("admin_set_new_password"),'newPassword','',null,'password');
						optionText(_T("admin_set_confirm_new_password"),'newConfirm','',null,'password');
						optionText(_T("admin_set_admin_username"),'setUserName',$settings['SET_USERNAME']);
						optionText(_T("admin_set_email_address"),'setEmail',$settings['SET_CONTACT']);
						submitButton();
					?>
					</div>

				<div class="clear"></div>
			</div>
		</form>

<?php

// SETTINGS FUNCTIONS

	// settings functions
	function optionList($label,$name,$setting,$list,$return=0){
		$html = '
		<div class="code_box"><label>'.$label.' :</label>
		<select name="'.$name.'" class="text_input">';
		foreach ($list  as $k => $v){
			$html .=  '<option value="'.$k.'" '.($setting==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		$html .=  '</select></div>';
		if($return) return $html;
		echo $html;
	}
	function optionOnOff($label,$name,$setting,$info = null,$return=0){
		$html = '
		<div class="code_box"><label>'.$label.' :</label>
		<select name="'.$name.'" class="text_input">
			<option value="0" '.(!$setting?'selected="selected"':'').'>'._T("admin_set_option_off").'</option>
			<option value="1" '.($setting?'selected="selected"':'').'>'._T("admin_set_option_on").'</option>
		</select>'.(!is_null($info)?'<span>'.$info.'</span>':'').'</div>';
		if($return) return $html;
		echo $html;
	}
	function optionText($label,$name,$setting,$size=null,$type=null,$info = null){
		$eClass = (is_null($size)?'text_input':'text_input long');
		$eType = (is_null($type)?'text':$type);
		echo '<div class="code_box"><label>'.$label.' :</label><input class="'.$eClass.'" type="'.$eType.'" name="'.$name.'" value="'.$setting.'" autocomplete="off" size="20" />'.(!is_null($info)?'<span>'.$info.'</span>':'').'</div>';
	}
	function submitButton(){
		echo '<div class="code_box"><label></label><input class="button button_cen" type="submit" value="'._T("admin_set_save_button").'" name="changesettings[]"></div>';
	}
	function optionTitle($title){
		echo '<h2>'.$title.'</h2>';
	}
	function optionDescription($des){
		echo '<p class="teaser">'.$des.'</p>';
	}
	function makeThemeArray(){
		$dirname = "themes/";// Define the path to the themes folder
		$path_len = strlen($dirname);
		foreach(glob($dirname . '*', GLOB_ONLYDIR) as $dir) {
			$dir = substr($dir, $path_len);
			if(file_exists($dirname.$dir.'/'.$dir.'.css')){
				$array[$dir] = $dir;
			}
		}
		return $array;
	}
	function makeLanguageArray(){
		$dirname = "languages/";
		$dir_list = opendir($dirname);
		while(false != ($file = readdir($dir_list))){
			if(($file != ".") && ($file != "..")){
				$lang_name = explode(".", $file);
				if (count($lang_name) > 2 && $lang_name[1].'.'.$lang_name[2] == 'lang.php'){
					$array[$lang_name[0]]=$lang_name[0];
				}
			}
		}
		return $array;
	}

// PAGE END
	require CFROOTPATH.'admin/admin_page_footer.php';
	die();
	exit;
