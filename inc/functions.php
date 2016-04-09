<?php if(!defined('cfih') or !cfih) die('This file cannot be directly accessed.');
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
 *   Used For:     base functions
 *   Last edited:  11/01/2013
 *
 *************************************************************************************************************/


	/**
	 * clear the cache folder
	 */
	function cl_cache_folder() {
		$files = glob( CFIMGCACHEPATH . '*', GLOB_MARK );
		foreach( $files as $file ){
			unlink( $file );
		}
	}


	/**
	 * a very simple implementation of issetor() for PHP
	 */
	function issetor(&$v, $or = NULL) {
		return $v === NULL ? $or : $v;
	}

	/**
	 *
	 */
	function check_set($name){
		global $settings;
		if(isset($settings[$name])){
			if(!empty($settings[$name])){
				if($settings[$name]){
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * cache_cheack($cachefile,$cachetime)
	 * simple cache start function, cachefile base file name, cachetime length of time to cache a file in seconds
	 */
	function cache_cheack($cachefile, $cachetime = 240,$load_cache = true){
		global $cache_file,$settings;
		
		//get lang before cache
		_T('start');
		
		//check for user lang input
		if(defined('CFUSERLANG')){
			$cachefile .='_'.CFUSERLANG;
		}
		
		$cache_file = CFIMGCACHEPATH.$cachefile.'_'.md5(json_encode($settings).json_encode($_GET).json_encode($_POST)).'.php';

		if (file_exists($cache_file)){
			$filemtime = filemtime($cache_file);
			if(time() - $cachetime < $filemtime){
				if($load_cache){
					load_cache($filemtime);
				}
				return true;
			}
		}
		ob_start();
		return false;
	}


	/**
	 * Load cache file
	 */
	function load_cache($filemtime = ''){
		global $cache_file;
		require($cache_file);
		echo "<!-- Cached copy, generated ".date('H:i', $filemtime)." -->\n";
		exit;
	}


	/**
	 * cache_end()
	 * simple cache end function
	 */
	function cache_end(){
		global $cache_file;
		if(!checklogin()){
			$cached = fopen($cache_file, 'w');
			fwrite($cached, ob_get_contents());
			fclose($cached);
		}
		ob_end_flush();
	}


	/**
	 * error_header($code)
	 * set page error header if error code is sent and returns the error text.
	 * Only used in header.php
	 */
	function error_header($code){
	// Page errors
		$errorCode = array(
					'500' => array('HTTP/1.1 500 Internal Server Error', _T("error_500")),
					'404' => array('HTTP/1.1 404 Not Found', _T("error_404")),
					'403' => array('HTTP/1.1 403 Forbidden', _T("error_403")),
					'401' => array('HTTP/1.1 401 Unauthorized', _T("error_401")),
					'400' => array('HTTP/1.1 400 Bad Request',  _T("error_400"))
					);
		if (isset($errorCode[$code])) {
			header($errorCode[$code][0]);
			return $errorCode[$code][1];
		} else {
			return '';
		}
	}


	/**
	 * _T($text[, $args])
	 * Language function returns $lang[$text] && sprintf args
	 */
	function _T($text){
		static $lang;
		if(!isset($lang) || isset($_GET['lang'])){

			// check to see if we are on a admin page
			if(defined('cfih') && cfih != 'admin'){
				// check for user pick
				if(isset($_GET['lang']) && file_exists(CFLANGPATH.$_GET['lang'].'.lang.php')){
					$getLang = cl(removeSymbols(end(explode('/',$_GET['lang']))));
					setcookie('lang', $getLang, null);
					$language = $getLang;
					unset($_GET['lang']);
					define('CFUSERLANG', $getLang);
				}

				// see if cookie has been set before
				if(!defined('CFUSERLANG') && isset($_COOKIE['lang']) && file_exists(CFLANGPATH.$_COOKIE['lang'].'.lang.php')){
					define('CFUSERLANG', $_COOKIE['lang']);
				}
			}

			// set default (admin set)
			if(!defined('CFUSERLANG')){
				define('CFUSERLANG',getSettings('SET_LANGUAGE'));
			}

			// check for lang files
			if(!require(CFLANGPATH.CFUSERLANG.'.lang.php')){
				// fallback
				if(!require(CFLANGPATH.'english.lang.php')){
					die('Can\'t find english.lang.php');
				}
			}
		}
		if(isset($lang[$text])){
			$numargs = func_num_args();
			if($numargs >1){
				$args = func_get_args();
				unset($args[0]);
				return vsprintf($lang[$text],$args);
			}
			return $lang[$text];
		}
		return $text;
	}


	/**
	 * theme_setting(setting name)
	 * get theme setting from themes/{theme name}/settings.php
	 */
	function theme_setting($key,$eles=null){
		static $themeSet = null;
		// set default (admin set)
		$themename = getSettings('SET_THEME');
			
		// check for theme settings 
		if (is_null($themeSet)){
			if( is_file(CFROOTPATH.'themes/'.$themename.'/settings.php')){
				require CFROOTPATH.'themes/'.$themename.'/settings.php';
				$return_theme = array();
				// check for theme settings
				if(isset($theme)){
					// theme settings index
					$themeOps = array(
									'designby','url','linktitle', // footer links
									'ads_header_bg','ads_header_link_color','ads_header_text_color','ads_header_url_color',
									'ads_index_bg','ads_index_link_color','ads_index_text_color','ads_index_url_color',
									'ads_thumb_bg','ads_thumb_link_color','ads_thumb_text_color','ads_thumb_url_color',
									'ads_gallery_bg','ads_gallery_link_color','ads_gallery_text_color','ads_gallery_url_color',
									'pagination_link_no','list_languages_over','captcha_light','widgit_row','gallery_row_no',
									'gallery_rows', 'thumb_option', 'thumb_max_width', 'thumb_max_height'
									);
					foreach($themeOps as $k ) {
						if(isset($theme[$k])) {
							$themeSet[$k] = $theme[$k];
						}
					}
				}
			}else{
				$themeSet = array();
			}
		}
		if(isset($themeSet[$key])){
		//	add_code_line('footer','<!-- '.$key.' = '.$themeSet[$key].'-->');
			return $themeSet[$key];
		}
		return $eles;
		
	}


	/**
	 * removeImage($imageDeleteCode)
	 * Remove image info from the database
	 */
	function removeImage($imageDeleteCode=null){

		if (is_null($imageDeleteCode) && isset($_GET['d'])){
			$imageDeleteCode = $_GET['d'];
		}elseif (is_null($imageDeleteCode)){
			return null;
		}

		if (preg_replace("/[^0-9A-Za-z]/","",$imageDeleteCode) != $imageDeleteCode || empty($imageDeleteCode)){
			user_feedback('error',_T("site_index_delete_image_err_not_found"),'delete_image1');
			return false;
		}

		if (!$image = db_get_image($imageDeleteCode,'deleteid')){
			user_feedback('error',_T("site_index_delete_image_err_not_found"),'delete_image2');
			$_GET['err'] = '404';// not found (404)page error
			return false;
		}

	// Remove Image
		if(@unlink(imageAddress(1,$image))){
			user_feedback('success',_T("site_index_delete_image_suc"),'delete_image');
		}
	// Remove small thumb
		@unlink(imageAddress(3,$image));
	// Remove thumb
		@unlink(imageAddress(2,$image));

	// remove cache files for this image
		$search = CFIMGCACHEPATH. $image['id']. "*" . '.php';
		$files = glob($search);
		if(is_array($files) && count($files) > 0){
			foreach($files as $file){
				@unlink($file);
			}
		}

	// Remove image info from the database
		if (!removeImageDb($image['id'])){
			user_feedback('error',_T("site_index_delete_image_err_db"),'delete_image3');
			return false;
		}

	// Remove bw db
		@unlink(CFBANDWIDTHPATH.$image['id'].'_imgbw.db');
		return true;
	}


	/**
	 * add_code_line($hook_name, $added_line)
	 * Used to add a code($added_line) in to the header or footer($hook_name)
	 */
	function add_code_line($hook_name, $added_line){
		global $codelines;
		$bt = debug_backtrace();
		$shift = count($bt) - 4;// plugin name should be  
		$caller = array_shift($bt);
		$realPathName = pathinfo_filename($caller['file']);
		$realLineNumber = $caller['line'];
		while ($shift > 0) {
			$caller = array_shift($bt);
			$shift--;
		}
		$pathName= pathinfo_filename($caller['file']);
		$codelines[] = array(
			'hook' => $hook_name,
			'codeline' => $added_line,
			'file' => $pathName.'.php',
			'line' => $caller['line']
		); 
	}


	/**
	 * add_action($hook_name, $added_function, $args = array())
	 * Used for plugins (not used right now)
	 */
	function add_action($hook_name, $added_function, $args = array()){
		global $plugins;
	  
		$bt = debug_backtrace();
		$shift=count($bt) - 4;	// plugin name should be  
		$caller = array_shift($bt);
		$realPathName=pathinfo_filename($caller['file']);
		$realLineNumber=$caller['line'];
		while ($shift > 0) {
			 $caller = array_shift($bt);
			 $shift--;
		}
		$pathName= pathinfo_filename($caller['file']);
		$plugins[] = array(
			'hook' => $hook_name,
			'function' => $added_function,
			'args' => (array) $args,
			'file' => $pathName.'.php',
			'line' => $caller['line']
		); 
	}


	/**
	 * exec_action($a)
	 * Execute Action, used to call add_code_line or add_action hooks
	 */
	function exec_action($a){
		global $plugins,$codelines;

		if(!empty($plugins)){
			foreach ($plugins as $hook)	{
				if ($hook['hook'] == $a) {
					call_user_func_array($hook['function'], $hook['args']);
				}
			}
		}

		if(!empty($codelines)){
			foreach ($codelines as $hook)	{
				if ($hook['hook'] == $a) {
					echo $hook['codeline']."\r\n";
				}
			}
		}
	}


	/**
	 * pathinfo_filename($file)
	 * Safe Pathinfo Filename,used in functions add_code_line or add_action
	 */
	function pathinfo_filename($file){
		if (defined('PATHINFO_FILENAME')) return pathinfo($file,PATHINFO_FILENAME);
		$path_parts = pathinfo($file);

		if(isset($path_parts['extension']) && ($file!='..')){
		  return substr($path_parts['basename'],0 ,strlen($path_parts['basename'])-strlen($path_parts['extension'])-1);
		} else{
		  return $path_parts['basename'];
		}
	}


	/**
	 * user_feedback($type,$msg,$id=null)
	 * information to show to users
	 */
	function user_feedback($type,$msg,$id=null){
		if(empty($msg))return;
		switch($type){
			case 'error':
			case '1':
				$type = 'error';
				break;
			case 'success':
			case '2':
				$type = 'success';
				break;
			default:
				$type = 'information';
		}
		if(isset($_SESSION['feedback'][$type][$id])){
			$id = $id.'_'.time();
		}
		$_SESSION['feedback'][$type][$id] = $msg;
		return true;
	}


	/**
	 * is_feedback($type)
	 * Used to check if a type of user feedback has been set
	 */
	function is_feedback($type){
		switch($type){
			case 'error':
			case '1':
				$type = 'error';
				break;
			case 'success':
			case '2':
				$type = 'success';
				break;
			default:
				$type = 'information';
		}
		if(isset($_SESSION['feedback'][$type]) && !empty($_SESSION['feedback'][$type])){
			return true;
		}
		return false;
	}


	/**
	 * format_size ()
	 * Convert size in bytes to a human readable format 
	 */
	function format_size($size=null, $file=null){
		if (is_null($size) && !is_null($file) && is_file($file))
			$size = filesize($file);

		if (strlen($size) <= 9 && strlen($size) >= 7){
			$img_size = substr(number_format($size / 1048576,2), -2) == '00' 
						? number_format($size / 1048576,0):number_format($size / 1048576,2);
			$img_size .= " MB";
		}elseif (strlen($size) >= 10){
			$img_size = substr(number_format($size / 1073741824,2), -2) == '00' 
						? number_format($size / 1073741824,0):number_format($size / 1073741824,2);
			$img_size .= " GB";
		}else $img_size = number_format($size / 1024,0)." kb";

		return $img_size;
	}


	/**
	 * ImageWidget($numImg=null, $return = true )
	 * numImg [number of image]
	 * return [ false(echo) | true(return)]
	 */
	function ImageWidget($numImg=null, $return = true ){
		global $settings;
		if(!isset($settings['SET_IMAGE_WIDGIT']) || !$settings['SET_IMAGE_WIDGIT']) return;
		if($imageList = imageList('rand',$numImg)){

			$rand_widget = '<h2 class="boxtitle">'._T("home_image_widgit").'</h2>
					<ul class="gallery">';
					
			foreach($imageList as $image){
	
			// get image address
				$thumb_url = imageAddress(3,$image,"dt");
			// get thumb page address
				$thumb_mid_link = imageAddress(2,$image,"pm");
			//see if there is a alt(title) if not use the image name
				$alt_text = ($image['alt'] !="" ? $image['alt']:$image['name']);
			//image list for page
				$rand_widget .= '
							<li><a href="'.$thumb_mid_link.'" title="'.$alt_text.'" class="thumb" >
								<img src="'.$thumb_url.'" alt="'.$alt_text.'" />
								</a><h2><a href="'.$thumb_mid_link.'" title="'.$alt_text.'">'.$alt_text.'</a></h2>
	
							</li>';

			}//	endfor
			$rand_widget .= '</ul><div class="clear"></div>';
			if($return) return $rand_widget;
			echo $rand_widget;
		}
	}


	/**
	 * removeSymbols($string)
	 * remove Symbols from a string then return it
	 */
	function removeSymbols($string){
		$symbols = array('/','\\','\'','"',',','.','<','>','?',';',':','[',']','{','}','|','=','+','-','_',')','(','*','&','^','%','$','#','@','!','~','`');
		for ($i = 0; $i < count($symbols); $i++) {
			$string = str_replace($symbols[$i],' ',$string);
		}
		return trim($string);
	}


	/**
	 * checklogin()
	 * Used to check if the admin is logged in
	 */
	function checklogin(){
		global $settings;
		static $logged_in = null;
		if($logged_in != null) {
			return $logged_in;
		}

		if(isset($_SESSION['loggedin'])){
			if ($_SESSION['set_name'] == md5($settings['SET_USERNAME'].$settings['SET_SALTING'].$settings['SET_PASSWORD'])){
				$logged_in = true;
				return true;
			}else{
				session_unset();
				session_destroy();
			}
        }
		$logged_in = false;
		return false;
	}


	/**
	 * cl($string)
	 * Clean string function
	 */
	function cl($string){
		return stripslashes(strip_tags(html_entity_decode($string, ENT_QUOTES, 'UTF-8')));
	}


	/**
	 * imageAddress($imgType,$image,$linktype=null)
	 * Get image address 
	 * imgType [1(image url)|2(thumb url)|3(small thumb url)|4(image file address)]
	 * image [image id]
	 * linktype [pt|dt|pm|dm|di|dl]
	 */
	function imageAddress($imgType, $image, $linktype=null){
		global $settings;
		$ext = 'html';
		$thumb_ext = isset($image['ext'])?strtolower($image['ext']):'';
		switch($imgType){
			case 1:
				$ext = $thumb_ext;
				if ($ext!='html')$fileaddress = CFIMAGEPATH.$image['id'].'.'.$ext;
				if (!isset($fileaddress) || !file_exists($fileaddress)) return false;
				break;
			case 2:
				$fileaddress = CFTHUMBPATH.$image['id'].'.';
				if($linktype=='dm')$ext = $thumb_ext;
				if(!file_exists($fileaddress.$thumb_ext)) $notfound =1;
				else $fileaddress .= $thumb_ext;
				break;
			case 3:
				$fileaddress = CFSMALLTHUMBPATH.$image['id'].'.';
				if($linktype=='dt')$ext = $thumb_ext;
				if(!file_exists($fileaddress.$thumb_ext)) $notfound =1;
				else $fileaddress .= $thumb_ext;
				break;
			case 4:
				$ext = $thumb_ext;
				$fileaddress = CFIMAGEPATH.$image['id'].'.'.$ext;
				if (!isset($fileaddress) || !file_exists($fileaddress)) return false;
				break;
		}

	// look for the right file ext
		if(isset($notfound)){
			foreach (array('png','jpg','jpeg','gif') as $fileExt){
				if ($thumb_ext != $fileExt && file_exists($fileaddress.$fileExt)){
					$fileaddress .= $fileExt;
					if($linktype=='dt' || $linktype=='dm') $ext = $fileExt;
					break;
				}
			}
		}

		if (isset($fileaddress)){
			if (!is_null($linktype)){
				if(check_set('SET_MOD_REWRITE')){
					$pieces = explode(".", $image['name']);
					return $settings['SET_SITEURL'].$linktype.'/'.$image['id'].'/'.str_replace(" ","_",$pieces[0]).'.'.$ext;
				}else{
					return $settings['SET_SITEURL'].($ext!='html'?'image.php':'').'?'.$linktype.'='.$image['id'];
				}
			}
			elseif (is_null($linktype)){
				return str_replace(CFROOTPATH, "", $fileaddress);
			}
		}
		return false;
	}


	/**
	 * shorturl_url($url, $api=null)
	 * used to shorten a url
	 */
	function shorturl_url($url, $api=null){
		global $settings;

		if(is_null($api)){
			if($settings['SET_SHORT_URL_API'] == 'b54'){
				$api = 'yourls';
				$settings['SET_SHORT_URL_API_URL'] = 'http://www.b54.in/api/';
			}else
				$api = $settings['SET_SHORT_URL_API'];
		}

		switch( $api ) {
			case 'yourls':
				$api_url = sprintf( $settings['SET_SHORT_URL_API_URL'] . '?username=%s&password=%s&url=%s&format=text&action=shorturl&source=plugin',$settings['SET_SHORT_URL_USER'], $settings['SET_SHORT_URL_PASS'], urlencode($url) );
				$shorturl = file_get_contents( $api_url );
				break;
			case 'bitly':
				$api_url = sprintf( 'http://api.bit.ly/v3/shorten?longUrl=%s&login=%s&apiKey=%s&format=xml', urlencode($url), $settings['SET_SHORT_URL_USER'], $settings['SET_SHORT_URL_PASS'] );
				$shorturl = shorturl_url_xml( $api_url,'!<url>[^<]+</url>' );
				break;
			case 'tinyurl':
				$api_url = sprintf( 'http://tinyurl.com/api-create.php?url=%s', urlencode($url) );
				$shorturl = shorturl_url_simple( $api_url );
				break;
			case 'isgd':
				$api_url = sprintf( 'http://is.gd/api.php?longurl=%s', urlencode($url) );
				$shorturl = shorturl_url_simple( $api_url );
				break;
			case 'googl':
				require CFLIBPATH.'goo.class.php';
				$googer = new GoogleURLAPI($settings['SET_SHORT_URL_PASS']);
				$shorturl = $googer->shorten($url);
				break;
			default:
				$shorturl='';
		}
		return $shorturl;
	}
	function shorturl_url_xml($shorter_url,$preg_match){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $shorter_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$ShortURL = curl_exec($ch);
		curl_close($ch);
		preg_match($preg_match, $ShortURL, $elements);
		print_r($elements);
		return $elements[1];
	}
	function shorturl_url_simple($shorter_url){
		return file_get_contents($shorter_url);
	}


	/**
	 * report_img($id)
	 * used to update the database about a image being reported
	 */
	function report_img($id){
		global $settings;
		$id = cl($id);
		if(db_addReport($id)){
			user_feedback('success',_T("site_gallery_report_suc"),'image_report');
			if (check_set('SET_EMAIL_REPORT') && $settings['SET_CONTACT'] !='') {
				$subject = "Image Reported on ".$settings['SET_TITLE'];
				$message  = "reported image id: ".$id." \r\n";
				$message .= "reported on : ".$settings['SET_TITLE']." \r\n";
				$message .= "Admin Panel : ".$settings['SET_SITEURL']."/admin.php \r\n";
				$headers = "From:".$settings['SET_CONTACT']." <".$settings['SET_CONTACT'].">";
				mail($settings['SET_CONTACT'],$subject,$message,$headers);
			}
			return true;
		}
		user_feedback('error',_T("site_gallery_report_err_find"),'report_img');
		return false;
	}


	/**
	 * getSettings($key)
	 * Returns the $settings value
	 */
	function getSettings($key) {
		global $settings;
		return (isset($settings[$key])?(string)$settings[$key]:null);
	}


	/**
	 * StripSlashes HTML Decode
	 */
	function strip_decode($text) {
		$text = stripslashes(htmlspecialchars_decode($text, ENT_QUOTES));
		return $text;
	}


	/**
	 * Add Trailing Slash
	 */
	function tsl($path) {
		if( substr($path, strlen($path) - 1) != '/' ) {
			$path .= '/';
		}
		return $path;
	}


	/**
	 * Strip Quotes
	 */
	function safe_slash_html($text) {
		if (get_magic_quotes_gpc()==0) {
			$text = addslashes(htmlspecialchars($text, ENT_QUOTES, 'UTF-8'));
		} else {
			$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		}
		$text = str_replace(chr(12), '', $text);
		$text = str_replace(chr(3), ' ', $text);
		return $text;
	}


	/**
	 * Safe StripSlashes HTML Decode
	 */
	function safe_strip_decode($text) {
		if (get_magic_quotes_gpc()==0) {
			$text = htmlspecialchars_decode($text, ENT_QUOTES);
		} else {
			$text = stripslashes(htmlspecialchars_decode($text, ENT_QUOTES));
		}
		return $text;
	}


	/**
	 * autoDeleted()
	 * Automatic remove images if they have not be viewed for some time
	 */
	function autoDeleted(){
		global $settings;

	// check auto remove image is on
		if(!check_set('SET_AUTO_DELETED'))return;
	
	// make file name
		$checkfor = CFDATAPATH.'ad'.date($settings['SET_AUTO_DELETED_JUMP']);
	
	// check to see if it's been run for this time period
		if(is_file($checkfor)) return;
	
	// get image index
		$db_img = imageList(0,'all');
		
	// check image index for images
		if(empty($db_img)||count($db_img)<1) return;
		
		$delete_time = (time()-($settings['SET_AUTO_DELETED_TIME']*86400));
	
	
	// check when image was last viewed
		foreach ($db_img as $k => $image){
	
		// check to see if the image has been uploaded for long then the period
			if( $delete_time > $image['added']){
	
			// image viewed db address
				$img_view_db = CFBANDWIDTHPATH.$image['id'].'_imgbw.db';
	
			// check to see if there is a images viewed db file
				if(is_file($img_view_db)){
	
				
				// read files last modified date (as this is most likely the last viewed date)
					if($last_modified = filemtime ( $img_view_db )){
					
					// check to see if it has not been view for the period
						if( $delete_time > $last_modified){
							$delete_id[$image['id']] = array('deleteid' => $image['deleteid']);// add image to remove array
						}
	
					}
				// if we can't read the date read the data from the db
					else{
						$db_count = db_imageCounterList(null,$image['id']);
						if (isset($db_count['date'])){
							if(  $delete_time > $db_count['date']){
								$delete_id[$image['id']] = array('deleteid' => $image['deleteid']);// add image to remove array
							}
						}
	
					}	
				}
			// if it's not been viewed
				else{
					$delete_id[$image['id']] = array('deleteid' => $image['deleteid']);// add image to remove array
				}
			}
		}
	
	//remove images
		if(!empty($delete_id)){
			foreach ($delete_id as $k => $image){
				removeImage($image['deleteid']);
			}
		}
	
	// remove image removed meg
		$Suc = array();
	
	// make lasted checked file
		if(savefile(array(),CFDATAPATH.'ad'.date($settings['SET_AUTO_DELETED_JUMP']))){
		// check for old file
			if(is_file(CFDATAPATH.'ad'.(date($settings['SET_AUTO_DELETED_JUMP'])-1))){
			// remove old file if found
				@unlink (CFDATAPATH.'ad'.(date($settings['SET_AUTO_DELETED_JUMP'])-1));
			}
		}
	
	
	
	}


	/**
	 * Sanitizes a text replacing whitespace with dashes
	 */
	function whitespace2Dashes( $text ) {
		$text = preg_replace('/[\s-]+/', '-', $text);
		$text = trim($text, '.-_');
		return $text;
	}
	function dashes2Whitespace( $text ) {
		$text = str_replace("-", " ", $text);
		return $text;
	}


	/**
	 * get_ad_code($name,$decode = true)
	 */
	function get_ad_code($name,$decode = true){
		global $ads_array;
		if(empty($ads_array)){
		// Adsense ads
			if($google_ads = getSettings('SET_GOOGLE_ADS')){
				require CFROOTPATH.'AdSense.php'; // AdSense
			}
		// Your ads
			elseif(is_file(CFDATAPATH.'ads.php')){
				require CFDATAPATH.'ads.php';
			}
			$ads_array = isset($ads)?$ads:array();
		}
		if(isset($ads_array[$name])){
			if($decode) return safe_strip_decode($ads_array[$name]);
			else return $ads_array[$name];
		}
		return false;
	}


	/**
	 * pagination($pageOn,$itemsOnPage,$itemCount,$pageAddress,$numberOfPageLinks = null)
	 * used in admin_imagelist.php
	 */
	function pagination($pageOn,$itemsOnPage,$itemCount,$pageAddress,$numberOfPageLinks = null){
		$pageOn++;// add 1 to fix page number

	// the number of links to show
		if(is_null($numberOfPageLinks))$numberOfPageLinks = 11;

	// work out the No. of Pages
		$noOfPages = ceil($itemCount/$itemsOnPage);

	// On page * of **
		$pagination = '<div class="pagination"><span class="pagecount">'._T("pagination_page_of", $pageOn, $noOfPages).'</span>' ;

	//first and prev buttons
		$pagination.= ($pageOn>1) ? '<a href="'.sprintf($pageAddress, 1).'" title="'._T("pagination_page_first_tip").'">'._T("pagination_page_first").'</a><a href="'.sprintf($pageAddress, ($pageOn-1)).'" title="'._T("pagination_previous_page_tip").'">-</a>':'';


		$numberToList = $noOfPages > ($numberOfPageLinks-1) ? ($numberOfPageLinks-1) :($noOfPages-1);
		$listStart = (($pageOn-(($numberOfPageLinks-1)/2)) < 1) ? 1 : (($pageOn+(($numberOfPageLinks-1)/2))>$noOfPages ? ($noOfPages-$numberToList):($pageOn-(($numberOfPageLinks-1)/2)));

		for ($i = $listStart; $i <= ($listStart+$numberToList); $i++) {
			$pagination .=($i==$pageOn ? '<span class="current">'.$i.'</span>':'<a href="'.sprintf($pageAddress, $i).'" title="'._T("pagination_page_tip",$i).'">'.$i.'</a>');
		}

	// next and last pages
		$pagination .= ($pageOn) < $noOfPages ? '<a href="'.sprintf($pageAddress, ($pageOn+1)).'" title="'._T("pagination_next_page_tip").'">+</a><a href="'.sprintf($pageAddress, $noOfPages).'" title="'._T("pagination_page_last_tip").'">'._T("pagination_page_last").'</a>':'';
		$pagination .='</div>';

		return $pagination;
	}


	/**
	 * saveSettings($address,$settings)
	 * save setings file
	 */
	function saveSettings($address,$settings){

	$setFile ='<?php

	// stop direct access
		if(!defined(\'cfih\') or !cfih) exit("Direct access not permitted.");

	// settings
	';

		foreach ($settings as $n => $v){
			$setFile .= '	$settings[\''.$n.'\'] = '.(is_numeric($v)?$v:'\''.$v.'\'').";\n";
		}

		if($fp = fopen($address, 'w+')){
			fwrite($fp, $setFile);
			fclose($fp);
			return true;
		}else
			return false;

	}


	/*
	 * preprint()
	 * debug function
	 */
	function preprint($s, $title=null, $return=false, $height='200') {
		$x = is_null($title)?'':'<h5 style="padding: 10px 1px 0px; margin: 0px;">'.$title.'</h5>';
		$x .= '<pre style="height:'.$height.'px; overflow: auto; border: 1px solid rgb(153, 153, 153); padding: 5px; width: 550px;">';
		$x .= htmlspecialchars(print_r($s, 1));
		$x .= "</pre>";
		if ($return) return $x;
		else print $x;
	}


	/**
	 * Validate Email Address
	 */
	function check_email_address($email) {
		if (function_exists('filter_var')) {
			// PHP 5.2 or higher
			return (!filter_var((string)$email,FILTER_VALIDATE_EMAIL)) ? false: true;
		} else {
			// old way
			if (!preg_match("/[^@]{1,64}@[^@]{1,255}$/", $email)) {
				return false;
			}
			$email_array = explode("@", $email);
			$local_array = explode(".", $email_array[0]);
			for ($i = 0; $i < sizeof($local_array); $i++) {
				if (!preg_match("/(([A-Za-z0-9!#$%&'*+\/\=?^_`{|}~-][A-Za-z0-9!#$%&'*+\/\=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
					return false;
				}
			}
			if (!preg_match("/\[?[0-9\.]+\]?$/", $email_array[1])) {
				$domain_array = explode(".", $email_array[1]);
				if (sizeof($domain_array) < 2) {
					return false; // Not enough parts to domain
				}
				for ($i = 0; $i < sizeof($domain_array); $i++) {
					if (!preg_match("/(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
						return false;
					}
				}
			}
			return true;
		  }
	}


	/**
	 * Encode Quotes
	 */
	function encode_quotes($text)  { 
		$text = strip_tags($text);
		if (version_compare(PHP_VERSION, "5.2.3")  >= 0) {	
			$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8', false);
		} else {	
			$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		}
		return trim($text); 
	} 


	function order_by(&$db,$field, $order = 123) {
		if ($order == 'ASC' || $order == 123)$order = '$a,$b';
		if ($order == 'DESC' || $order == 321)$order = '$b,$a';
		$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
		@usort($db, create_function($order, $code));
	}


	function savefile($menu_array=array(),$fileaddress){
		if($fp = @fopen($fileaddress, 'w+')){
			fwrite($fp, serialize($menu_array));
			fclose($fp);
			return true;
		}else
			return false;
	}


//////////////////////////////////////////////////////////////////////////////////
// template functions
//////////////////////////////////////////////////////////////////////////////////

	function get_url($page=null,$echo=true){
		$return = getSettings('SET_SITEURL');
		if(!is_null($page)){
			$return = trim(tsl(getSettings('SET_SITEURL')) . $page);
		}
		if (!$echo) return $return;
		echo $return;
	}


	/**
	 * show_feedback($echo=true)
	 * show feedback to user
	 */
	function show_feedback($echo=true){
		if(!isset($_SESSION['feedback']) || empty($_SESSION['feedback']) || !is_array($_SESSION['feedback'])) return;
		$return ='';
		foreach ($_SESSION['feedback'] as $type => $feedback){
			foreach ($feedback as $id => $msg){
				$return .= '<div id="'.$id.'_'.$type.'" class="notification '.$type.'"><a class="close" href="#" alt="close" title="Close this notification"> </a>'.$msg.'</div>';
			}
		}
		unset($_SESSION['feedback']);
		if(!$echo) return $return;
		echo $return;
	}


	/**
	 * get_ad($name,$div_class=null,$echo=true)
	 * @param string $name, The name of the ad to get
	 * @param string $list_id Optional, Css Class for diz box around the ad code,default is null and will not add a div box
	 * @param bool $echo Optional, default is true. False will 'return' value
	 * @return string Echos or returns based on param $echo
	 */
	function get_ad($name,$div_class=null,$echo=true){
		if(!$myVar = get_ad_code($name))
			return false;
		if(!is_null($div_class))
			$myVar = '<div class="'.$div_class.'">'.$myVar.'</div>';
		if (!$echo)
			return $myVar;
		echo $myVar;
	}


	/**
	 * imageLinkCode($type,$imageaddress,$linkaddress=null,$alt=null)
	 * used in thumbnail.php 
	 */
	function imageLinkCode($type,$imageaddress,$linkaddress=null,$alt=null){
		if($type == 'bbcode'){
			return (!is_null($linkaddress)?'[URL='.$linkaddress.']':'').'[IMG]'.$imageaddress.'[/IMG]'.(!is_null($linkaddress)?'[/URL]':'');
		}elseif($type == 'html'){
			return '&lt;a href=&quot;'.$linkaddress.'&quot; title=&quot;'.$alt.'&quot; &gt;&lt;img src=&quot;'.$imageaddress.'&quot; alt=&quot;'.$alt.'&quot; /&gt;&lt/a&gt;';
		}
		return;
	}


	/**
	 * bookmarking($document_url,$document_title)
	 * used in thumbnail.php 
	 */
	function bookmarking($document_url,$document_title){
		global $settings;
		$ypid = (check_set('SET_ADDTHIS')?'#pubid='.$settings['SET_ADDTHIS']:'');
		$text = '<div class="addthis">
		<!-- AddThis Button BEGIN -->
		<div class="addthis_toolbox addthis_default_style " addthis:url="'.$document_url.'" addthis:title="'.$document_title.'">
			<a class="addthis_button_preferred_1"></a>
			<a class="addthis_button_preferred_2"></a>
			<a class="addthis_button_email"></a>
			<a class="addthis_button_pinterest_share" title="Send to Pinterest"></a>
			<a class="addthis_button_preferred_4"></a>
			<a class="addthis_button_preferred_5"></a>
			<a class="addthis_button_preferred_6"></a>
			<a class="addthis_button_preferred_7"></a>
			<a class="addthis_button_preferred_8"></a>
			<a class="addthis_button_compact"></a>
			<a class="addthis_counter addthis_bubble_style"></a>
		</div>
		<script type="text/javascript" src="http://s7.addthis.com/js/300/addthis_widget.js'.$ypid.'"></script>
		<!-- AddThis Button END -->
	</div>';
		return $text;
	}


	/**
	 * listLanguages()
	 * used in header.php to check and list links for list all languages in the language folder
	 */
	function listLanguages(){
		global $settings;
		$dir_list = opendir(CFLANGPATH);
		$lang ='';
		while(false != ($file = readdir($dir_list))){
			if(($file != ".") && ($file != "..")){
				$lang_name = explode(".", $file);
				if (count($lang_name) > 2 && $lang_name[1].'.'.$lang_name[2] == 'lang.php'){
					 if(CFUSERLANG!=$lang_name[0])
						$lang .= '<a href="'.$settings['SET_SITEURL'].'index.php?lang='.$lang_name[0].'" title="'.$lang_name[0].'" rel="nofollow"><img src="'.$settings['SET_SITEURL'].'languages/'.$lang_name[0].'.png" alt="'.$lang_name[0].'" width="23" height="15" /></a> ';
				}
			}
		}
		if(empty($lang)) return false;
		return $lang;
	}



	/**
	 * Get Page menu
	 * @param string $list_id Optional, Css ID for menu list, default is 'main-nav'
	 * @param bool $echo Optional, default is true. False will 'return' value
	 * @return string Echos or returns based on param $echo
	 */
	function get_page_menu($list_id='main-nav',$echo=true){
		global $settings;
		$return = '<ul id="'.$list_id.'">';
		$return .= '	<li id="page_home"><a href="'.$settings['SET_SITEURL'].'" title="'._T("site_menu_home").'">'._T("site_menu_home").'</a></li>';
		if($settings['SET_HIDE_GALLERY'])
			$return .= '	<li id="page_gallery"><a href="'.$settings['SET_SITEURL'].'gallery.php" title="'._T("site_menu_gallery").'">'._T("site_menu_gallery").'</a></li>';
		/*if($settings['SET_HIDE_ALBUM'])
			$return .= '	<li id="page_albums"><a href="'.$settings['SET_SITEURL'].'album.php" title="'._T("site_menu_albums").'">'._T("site_menu_albums").'</a></li>';*/
		if($settings['SET_HIDE_FAQ'])
			$return .= '	<li id="page_faq_page"><a href="'.$settings['SET_SITEURL'].'faq.php" title="'._T("site_menu_faq").'">'._T("site_menu_faq").'</a></li>';
		if($settings['SET_HIDE_TOS'])
			$return .= '	<li id="page_tos_page"><a href="'.$settings['SET_SITEURL'].'tos.php" title="'._T("site_menu_tos").'">'._T("site_menu_tos").'</a></li>';
		if($settings['SET_HIDE_CONTACT'])
			$return .= '	<li id="page_contact_page"><a href="'.$settings['SET_SITEURL'].'contact.php" title="'._T("site_menu_contact").'">'._T("site_menu_contact").'</a></li>';
		$return .= '</ul>';
		if (!$echo) return $return;
		echo $return;
	}


	/**
	 * Get_Site 
	 * This will return the value set in the control panel
	 * @param string $what, Name of the site var to return [title|slogan|copyright]
	 * @param bool $echo Optional, default is true. False will 'return' value
	 * @return string Echos or returns based on param $echo
	 */
	function get_site($what,$echo=true) {
		if($what=='title') $return = trim(stripslashes(getSettings('SET_TITLE')));
		if($what=='slogan') $return = trim(stripslashes(getSettings('SET_SLOGAN')));
		if($what=='copyright') $return = trim(stripslashes(getSettings('SET_COPYRIGHT')));
		if (!$echo) return $return;
		echo $return;
	}


	/**
	 * Get Page 
	 * @param string $pageVar, Name of the page var to return
	 * @param bool $echo Optional, default is true. False will 'return' value
	 * @return string Echos or returns based on param $echo
	 */
	function get_page($pageVar,$echo=true,$clean=true) {
		global $pageSet;
		if(isset($pageSet[$pageVar]) && !empty($pageSet[$pageVar])){
			$myVar = strip_decode($pageSet[$pageVar]);
			if($clean) $myVar = strip_tags($myVar);
		}else
			$myVar = '';
		if (!$echo) return $myVar;
		echo $myVar;
	}


	/**
	 * Get Theme URL
	 * This will return the current active theme's full URL (folder)
	 * @param bool $echo Optional, default is true. False will 'return' value
	 * @return string Echos or returns based on param $echo
	 */
	function get_theme_url($for='folder',$echo=true) {
		$theme = getSettings('SET_THEME');
		$return = trim(tsl(getSettings('SET_SITEURL')) . "themes/" . $theme);
		if($for == 'css'){
			$return .= '/'.$theme.'.css?v='.getSettings('SET_VERSION');
		}
		if (!$echo) return $return;
		echo $return;
	}


	/**
	 * slugize($str)
	 * slugize a string
	 */
	function slugize($str){
		// Convert to lowercase and remove whitespace
		$str = strtolower(trim($str));  

		// Replace high ascii characters
		$chars = array("ä", "ö", "ü", "ß");
		$replacements = array("ae", "oe", "ue", "ss");
		$str = str_replace($chars, $replacements, $str);
		$pattern = array("/(é|è|ë|ê)/", "/(ó|ò|ö|ô)/", "/(ú|ù|ü|û)/");
		$replacements = array("e", "o", "u");
		$str = preg_replace($pattern, $replacements, $str);

		// Remove puncuation
		$pattern = array(":", "!", "?", ".", "/", "'");
		$str = str_replace($pattern, "", $str);

		// Hyphenate any non alphanumeric characters
		$pattern = array("/[^a-z0-9-]/", "/-+/");
		$str = preg_replace($pattern, "-", $str);

		return $str;
	}






