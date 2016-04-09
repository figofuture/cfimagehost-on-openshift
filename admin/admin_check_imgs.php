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
 *   Used For:     Check images
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

	$stopwatch = new StopWatch();
	$autouse = 0;

	if(!checklogin()){
		header('Location: ../index.php');
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Image Folder Check</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<meta name="robots" content="index,follow" />
	<style type="text/css">
		* {font-family: "Lucida Grande", "Lucida Sans", "Lucida", Arial, Verdana, Helvetica, sans-serif;font-size: 100%;margin: 0;padding: 0;}
		html { overflow-y: scroll; }
		body {background:#f9f9f9;color: #666;font-size: 75%;}
		h2{font-weight: normal;color: #BBBBBB;display: inline-block;font-size: 20px;margin: 0 0 0px;padding: 0 0 0px;}
		.teaser {color: #444;font-size: 12px;line-height: 18px;margin: 5px 20px;text-align: left;}
	</style>
</head>
<body>
<?php if(isset($_GET['n'])){?>
</body>
</html>
<?
exit;
}
?>

--- <h2>Image Folder Checks</h2><br/>
<?
	flushNow(1);

/////////////////////////////////////////////////////////////////////////
// check image folder

	echo_msg('--- Checking for images in the image upload directorys <br/>',2);
	echo_msg('---<br/>',1);

// list images
	$not_found_in_db =array();
	$remove_img =array();
	$file_array = array();
	$cheFormats = array('png'=>'png', 'jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif');
	$d = opendir(CFIMAGEPATH);
	while($file = readdir($d)) {
		$path_info = pathinfo(CFIMAGEPATH.$file);
		if(isset($path_info['extension']) && isset($cheFormats[strtolower($path_info['extension'])])){
			$file_array[$path_info['filename']] = $file;
			$full_list[$path_info['filename']] = $file;
		}
	}

// check for Mid thumbs
	$d = opendir(CFTHUMBPATH);
	while($file = readdir($d)) {
		$path_info = pathinfo(CFTHUMBPATH.$file);
		if(isset($path_info['extension']) && isset($cheFormats[strtolower($path_info['extension'])])){
			$file_array_tm[$path_info['filename']] = $file;
		// not found in image folder
			if(!isset($full_list[$path_info['filename']])){
				$full_list[$path_info['filename']] = $file;
			}

		}
	}

// check for thumbs
	$d = opendir(CFSMALLTHUMBPATH);
	while($file = readdir($d)) {
		$path_info = pathinfo(CFSMALLTHUMBPATH.$file);
		if(isset($path_info['extension']) && isset($cheFormats[strtolower($path_info['extension'])])){
			$file_array_t[$path_info['filename']] = $file;
			if(!isset($full_list[$path_info['filename']])){
				$full_list[$path_info['filename']] = $file;
			}
		}
	}
	

// not found in folder
	$not_in_all_folders = array();
	foreach($full_list as $k => $v){
		if(!isset($file_array[$k]) || !isset($file_array_t[$k]) || !isset($file_array_tm[$k]) ){
			$not_in_all_folders[$k]=$v;
		}
	}

	echo_msg('--- Found in ("'.CFIMAGEPATH.'") '.count($file_array).' images<br/>');
	echo_msg('--- Found in ("'.CFSMALLTHUMBPATH.'") '.count($file_array_t).' images<br/>');
	echo_msg('--- Found in ("'.CFTHUMBPATH.'") '.count($file_array_tm).' images<br/>');
	if(empty($not_in_all_folders)){
		echo_msg('--- All images were found in all folders.<br/>');
	}else{
		echo_msg('--- '.count($not_in_all_folders).' images not found in all folders.<br/>');
	}

	echo_msg('----<br/>',1);


	if(!empty($not_in_all_folders)){
		echo_msg( '--- Removing images not found in all folders<br/>');
		echo_msg( '---<br/>',1);

		foreach($not_in_all_folders as $k=>$v){
			@unlink(CFIMAGEPATH.$v);
			@unlink(CFTHUMBPATH.$v);
			@unlink(CFSMALLTHUMBPATH.$v);
			@unlink(CFBANDWIDTHPATH.$k.'_imgbw.db');
		}
		echo_msg('---<br/>',1);
	}
	unset($not_in_all_folders);

	echo_msg('--- Done<br/>--- Total Time Elapsed: '.round($stopwatch->elapsed(),4).' seconds<br />');
	echo_msg('</body></html>');


//////////////////////////////////////////////////////////////////////////////////////////
// functions

	function echo_msg($msg,$flush = 0){
		global $autouse;
		if($autouse) return;
		echo $msg;
		if($flush) flushNow($flush);
	}

	function get_extension($imagetype, $includeDot = false){
		global $acceptedFormats;
		if(empty($imagetype)) return false;
		$dot = $includeDot ? '.' : '';
		if(isset($acceptedFormats[$imagetype])){
			return $dot.$acceptedFormats[$imagetype];
		}
		return false;
	}

	class StopWatch {
		public $total;
		public $time;
		public function __construct() {$this->total = $this->time = microtime(true);}
		public function clock() {return -$this->time + ($this->time = microtime(true));}
		public function elapsed() {return microtime(true) - $this->total;}
		public function reset() {$this->total=$this->time=microtime(true);}
	}


function flushNow($now = null){
	echo(str_repeat(' ',256));
	// check that buffer is actually set before flushing
	if (ob_get_length()){           
		@ob_flush();
		@flush();
		@ob_end_flush();
	}
	@ob_start();
	if(is_null($now)) usleep(rand(2,4)*100000);
}