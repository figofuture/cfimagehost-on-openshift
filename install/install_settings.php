<?php if(!defined('cfih_i') or !cfih_i) die('This file cannot be directly accessed.');
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
 *   Used For:     Script Installer - settings
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/
	// make settings
			echo '<div class="ibox"><h2>Setting</h2>';
			
			// chech for seeings file
				if(!is_file(CFSETTINGS)){
					user_note('Settings file not found, Making new settings file',3);
				}else{
					user_note('Found Settings file, Now updating...',3);
					$update = true;
					define('CFIHP', 'installer');
					include_once(CFSETTINGS);
				}
						

			if(!isset($settings) || $settings['SET_VERSION']< 1.3){
				$self = $_SERVER['PHP_SELF'];
				$script_url = 'http://'.$_SERVER['HTTP_HOST'].str_replace('/install','',dirname($self));
				$salting = md5(time().rand(0,14));
			}

			$old_version = isset($settings['SET_VERSION'])?$settings['SET_VERSION']:2000;

			$content = array(
							'SET_PASSWORD'			=>(isset($settings['SET_PASSWORD'])			?$settings['SET_PASSWORD']:md5(md5('password').$salting)),
							'SET_USERNAME'			=>(isset($settings['SET_USERNAME'])			?$settings['SET_USERNAME']:'admin'),
							'SET_CONTACT'			=>(isset($settings['SET_CONTACT'])			?$settings['SET_CONTACT']:'your@email.com'),
							'SET_SITEURL'			=>tsl(isset($settings['SET_SITEURL'])			?$settings['SET_SITEURL']:$script_url),
							'SET_TITLE'				=>(isset($settings['SET_TITLE'])			?$settings['SET_TITLE']:'CF Image Host'),
							'SET_SLOGAN'			=>(isset($settings['SET_SLOGAN'])			?$settings['SET_SLOGAN']:'Free CF Image Host'),
							'SET_MAXSIZE'			=>(isset($settings['SET_MAXSIZE'])			?$settings['SET_MAXSIZE']:'1048576'),
				//			'SET_IMG_ON_PAGE'		=>(isset($settings['SET_IMG_ON_PAGE'])		?$settings['SET_IMG_ON_PAGE']:8),
							'SET_IMG_ROW_NO'		=> 4,
							'SET_IMG_ROWS'			=> (isset($settings['SET_IMG_ON_PAGE'])		?($settings['SET_IMG_ON_PAGE']/4):2),
							'SET_COPYRIGHT'			=>(isset($settings['SET_COPYRIGHT'])		?$settings['SET_COPYRIGHT']:'Copyright &copy; - All Rights Reserved.'),
							'SET_THEME'				=>(isset($settings['SET_THEME'])?$settings['SET_THEME']:'day'),//updated 1.4
							'SET_SALTING'				=>(isset($settings['SET_SALTING'])			?$settings['SET_SALTING']:$salting),
							'SET_MOD_REWRITE'		=>(isset($settings['SET_MOD_REWRITE'])		?$settings['SET_MOD_REWRITE']:0),
							'SET_ADMIN_MENU'		=>(isset($settings['SET_ADMIN_MENU'])		?$settings['SET_ADMIN_MENU']:0),
							'SET_MAX_BANDWIDTH'		=>(isset($settings['SET_MAX_BANDWIDTH'])	?$settings['SET_MAX_BANDWIDTH']:1024),
							'SET_VERSION'			=>$SET_VERSION,
							'SET_GOOGLE_ANALYTICS'	=>(isset($settings['SET_GOOGLE_ANALYTICS'])	?$settings['SET_GOOGLE_ANALYTICS']:''),
							'SET_GOOGLE_ADS'		=>(isset($settings['SET_GOOGLE_ADS'])		?$settings['SET_GOOGLE_ADS']:''),
							'SET_GOOGLE_CHANNAL'	=>(isset($settings['SET_GOOGLE_CHANNAL'])	?$settings['SET_GOOGLE_CHANNAL']:''),
							'SET_BANDWIDTH_RESET'	=>(isset($settings['SET_BANDWIDTH_RESET'])	?$settings['SET_BANDWIDTH_RESET']:'m'),
							'SET_MAX_UPLOAD'		=>(isset($settings['SET_MAX_UPLOAD'])		?$settings['SET_MAX_UPLOAD']:5),
							'SET_AUTO_DELETED'		=>(isset($settings['SET_AUTO_DELETED'])		?$settings['SET_AUTO_DELETED']:0),
							'SET_AUTO_DELETED_TIME'	=>(isset($settings['SET_AUTO_DELETED_TIME'])?$settings['SET_AUTO_DELETED_TIME']:'60'),
							'SET_AUTO_DELETED_JUMP'	=>(isset($settings['SET_AUTO_DELETED_JUMP'])?$settings['SET_AUTO_DELETED_JUMP']:'m'),
							'SET_HIDE_CONTACT'		=>(isset($settings['SET_HIDE_CONTACT'])		?$settings['SET_HIDE_CONTACT']:1),
							'SET_HIDE_TOS'			=>(isset($settings['SET_HIDE_TOS'])			?$settings['SET_HIDE_TOS']:1),
							'SET_HIDE_GALLERY'		=>(isset($settings['SET_HIDE_GALLERY'])		?$settings['SET_HIDE_GALLERY']:1),
							'SET_HIDE_FAQ'			=>(isset($settings['SET_HIDE_FAQ'])			?$settings['SET_HIDE_FAQ']:1),
							'SET_HIDE_SEARCH'		=>(isset($settings['SET_HIDE_SEARCH'])		?$settings['SET_HIDE_SEARCH']:1),
							'SET_EMAIL_REPORT'		=>(isset($settings['SET_EMAIL_REPORT'])		?$settings['SET_EMAIL_REPORT']:0),
							'SET_ALLOW_REPORT'		=>(isset($settings['SET_ALLOW_REPORT'])		?$settings['SET_ALLOW_REPORT']:1),
							'SET_SHORT_URL_ON'		=>(isset($settings['SET_SHORT_URL_ON'])		?$settings['SET_SHORT_URL_ON']:1),
							'SET_PRIVATE_IMG_ON'	=>(isset($settings['SET_PRIVATE_IMG_ON'])	?$settings['SET_PRIVATE_IMG_ON']:1),
							'SET_DIS_UPLOAD'		=>(isset($settings['SET_DIS_UPLOAD'])		?$settings['SET_DIS_UPLOAD']:0),
							'SET_LANGUAGE'			=>(isset($settings['SET_LANGUAGE'])			?$settings['SET_LANGUAGE']:'english'),
							'SET_SHORT_URL_API'		=>(isset($settings['SET_SHORT_URL_API'])	?$settings['SET_SHORT_URL_API']:'b54'),
							'SET_SHORT_URL_API_URL'	=>(isset($settings['SET_SHORT_URL_API_URL'])?$settings['SET_SHORT_URL_API_URL']:''),
							'SET_SHORT_URL_PASS'	=>(isset($settings['SET_SHORT_URL_PASS'])	?$settings['SET_SHORT_URL_PASS']:''),
							'SET_SHORT_URL_USER'	=>(isset($settings['SET_SHORT_URL_USER'])	?$settings['SET_SHORT_URL_USER']:''),
							'SET_WATERMARK'			=>(isset($settings['SET_WATERMARK'])		?$settings['SET_WATERMARK']:0),
							'SET_WATERMARK_TEXT'	=>(isset($settings['SET_WATERMARK_TEXT'])	?$settings['SET_WATERMARK_TEXT']:0),
							'SET_WATERMARK_PLACED'	=>(!isset($settings['SET_WATERMARK_PLACED'])?0:(is_numeric($settings['SET_WATERMARK_PLACED'])?$settings['SET_WATERMARK_PLACED']:0)),
							'SET_WATERMARK_IMAGE'	=>(isset($settings['SET_WATERMARK_IMAGE'])	?$settings['SET_WATERMARK_IMAGE']:''),
							'SET_IMAGE_WIDGIT'		=>(isset($settings['SET_IMAGE_WIDGIT'])		?$settings['SET_IMAGE_WIDGIT']:1),
							'SET_NODUPLICATE'		=>(isset($settings['SET_NODUPLICATE'])		?$settings['SET_NODUPLICATE']:0),
							'SET_RESIZE_IMG_ON'		=>(isset($settings['SET_RESIZE_IMG_ON'])	?$settings['SET_RESIZE_IMG_ON']:1),
							'SET_ADDTHIS'			=>(isset($settings['SET_ADDTHIS'])			?$settings['SET_ADDTHIS']:''),
							'SET_LAST_BACKUP_BANDWIDTH'		=>(isset($settings['SET_LAST_BACKUP_BANDWIDTH'])		?$settings['SET_LAST_BACKUP_BANDWIDTH']:0),
							'SET_LAST_BACKUP_IMAGE'		=>(isset($settings['SET_LAST_BACKUP_IMAGE'])		?$settings['SET_LAST_BACKUP_IMAGE']:0),
							'SET_HIDE_FEED'		=>(isset($settings['SET_HIDE_FEED'])		?$settings['SET_HIDE_FEED']:1),
							'SET_HIDE_SITEMAP'		=>(isset($settings['SET_HIDE_SITEMAP'])		?$settings['SET_HIDE_SITEMAP']:1),
							'SET_BACKUP_AUTO_ON'		=>(isset($settings['SET_BACKUP_AUTO_ON'])		?$settings['SET_BACKUP_AUTO_ON']:1),
							'SET_BACKUP_AUTO_TIME'		=>(isset($settings['SET_BACKUP_AUTO_TIME'])		?$settings['SET_BACKUP_AUTO_TIME']:1),
							'SET_BACKUP_AUTO_USE'		=>(isset($settings['SET_BACKUP_AUTO_USE'])		?$settings['SET_BACKUP_AUTO_USE']:1),
							'SET_BACKUP_AUTO_REBUILD'		=>(isset($settings['SET_BACKUP_AUTO_REBUILD'])		?$settings['SET_BACKUP_AUTO_REBUILD']:1),
						);

			if(!saveSettings(CFSETTINGS,$content)){
				user_note('Error Making "Settings" file!',1);
				$Err_found = true;
			}else{
				if(isset($update) && $update){
					user_note('Settings file Updated.',2);
				}else{
					user_note('Made new Settings file.',2);
				}
			}
			echo '</div>';
			flushNow();

		// Remove old files
			if($old_version == '1.5'){
				$removeOldFile = array('lightbox/fancy_close.png','lightbox/fancy_loading.png','lightbox/fancy_nav_left.png','lightbox/fancy_nav_right.png','lightbox/fancy_shadow_s.png',
				'lightbox/fancy_shadow_e.png','lightbox/fancy_shadow_n.png','lightbox/fancy_shadow_ne.png','lightbox/fancy_shadow_nw.png','lightbox/fancy_shadow_se.png',
				'lightbox/fancy_shadow_sw.png','lightbox/fancy_shadow_w.png','lightbox/fancy_title_left.png','lightbox/fancy_title_main.png','lightbox/fancy_title_over.png',
				'lightbox/fancy_title_right.png','lightbox/fancybox.png','lightbox/fancybox-x.png','lightbox/fancybox-y.png','lightbox/jquery.fancybox-1.3.4.css','lightbox/jquery.fancybox-1.3.4.pack.js',
				'img/reset.png','img/textback.png','lib/dupeimage.class.php','lib/remoteImage.class.php','lib/resize.class.php');
			}
			if(isset($removeOldFile)){
				echo '<div class="ibox"><h2>Removing Old Files</h2>';
				foreach ($removeOldFile as $oldFile){
					if(@unlink(CFROOTPATH.$oldFile)) user_note('Removed:<b>'.$oldFile.'</b><br/>',2);
				}
				echo '</div>';
			}

		// install done
			if(!$Err_found){
				echo '<div class="ibox"><h2>Install/Updated done</h2>';
				user_note('Now the installer has finished you will need to delete the install folder from your server, then navigate to the <a href="'.tsl($content['SET_SITEURL']).'admin.php">admin page</a> and use the username and password below (if updating use your login info), Once Loged in you can navigate to the settings page and edit settings to your liking.<br/><br/>
							Username: admin<br/>
							Password: password',3);
				echo '</div>';
			}
                        if(!file_exists(CFINSTALLED)){
                                //if (!is_writetable(CFINSTALLED)) chmod(CFINSTALLED, 0700);
                                file_put_contents(CFINSTALLED, 'i');
                        }

