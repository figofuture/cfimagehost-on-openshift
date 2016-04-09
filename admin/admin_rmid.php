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
 *   Used For:     ReMakeImage Database
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

	$stopwatch = new StopWatch();
	
	if(!isset($autouse))$autouse =0;

	if(!$autouse&&!checklogin()){
		header('Location: ../index.php');
		exit();
	}

if(!$autouse){
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>Database check and fix</title>
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
<?php
	if(isset($_GET['n'])){
		echo '</body></html>';
		exit;
	}
?>

--- <h2>Checking Datebase</h2><br/>
--- Looking for Images in the datebase<br/>
<?
	flushNow(1);
/////////////////////////////////////////////////////////////////////////
// check db
}

	$img_db = loadDBGlobal();
	$imgArray = $img_db->fetch_all();
	$img_arr_count = count($imgArray);
	$img_id_arr = array();
	$not_found_in_folder = array();
	
	if(!$img_arr_count > 0){
		echo_msg('--- No datebase found<br/>');
		@unlink(CFDBIMGPATH);
	}else{
		foreach($imgArray as $k => $v){
			if(isset($img_id_arr[$v['id']])) $not_found_in_folder[$v['id']]=$v['deleteid'];
			$img_id_arr[$v['id']] = $v; 
		}
		echo_msg('--- Found datebase with '.$img_arr_count.' images in.<br/>');
	}
	unset($img_db);
	unset($imgArray);

	echo_msg('--- Checking against images in the uploaded image folder<br/>',2);

// list images
	$not_found_in_db = array();
	$remove_img = array();
	$file_array = array();
	$cheFormats = array('png'=>'png', 'jpg'=>'jpg', 'jpeg'=>'jpeg', 'gif'=>'gif');
	$d = opendir(CFIMAGEPATH);

	while($file = readdir($d)) {
		$path_info = pathinfo(CFIMAGEPATH.$file);
		if(isset($path_info['extension']) && isset($cheFormats[strtolower($path_info['extension'])])){
			$file_array[$path_info['filename']] = $file;

		// not found in db
			if(!isset($img_id_arr[$path_info['filename']])) $not_found_in_db[$path_info['filename']] = $file;
		}
	}

// not found in folder
	foreach($img_id_arr as $k => $v){
		if(!isset($file_array[$k])){
			$not_found_in_folder[$k]=$v['deleteid'];
		}
	}

	echo_msg('--- Found '.count($file_array).' images<br/>');
	echo_msg('---<br/>',1);

// remove image from database
	if(!empty($not_found_in_folder)){
		echo_msg( '--- Removing images from the database not found in upload folder<br/>',1);

		$img_db = loadDBGlobal();
		foreach($not_found_in_folder as $k=>$v){
			$img_db->remove_row('deleteid',$v);
			echo $k.'<br/>';
		}
		$img_db -> save_db_now();

		echo_msg('--- Removed '.count($not_found_in_folder).' indexed images from the database<br/>');
		echo_msg('---<br/>',1);
	}else{
		echo_msg('--- No images needed to be removed<br/>');
	}
	unset($not_found_in_folder);
	unset($img_db);
	unset($imgArray);

// add images found in image folders
	if(!empty($not_found_in_db)){
		echo_msg( '--- Adding Image found in the upload folder that are not in the image database<br/>',1);

	// ProgressBar
		$sx =100/count($not_found_in_db);
		$i =0;
  		$p = new ProgressBar();
		echo '<div style="width: 300px;">';
		$p->render();
		echo '</div>';

		foreach($not_found_in_db as $k=>$v){
			$path_info = pathinfo(CFIMAGEPATH.$v);
		// Make image info array to save to db
			$newImageArray = array(
									'id'				=> $k,
									'name'			=> $v,
									'alt'				=> $path_info['filename'],
									'added'		=> filemtime(CFSMALLTHUMBPATH.$v),
									'ext'				=> $path_info['extension'],
									'ip'				=> '0.0.0.0',
									'size'			=> filesize(CFIMAGEPATH.$v),
									'deleteid'		=> $path_info['filename'].create_hash(5),
									'thumbsize' 	=> filesize(CFSMALLTHUMBPATH.$v),
									'sthumbsize'=> filesize(CFTHUMBPATH.$v),
									'private'		=> 0,
									'report'			=> 0,
									'shorturl'		=> null,
									'fingerprint'	=> fingerprint(CFSMALLTHUMBPATH.$v)
									);
		//save new image to database
			addNewImage($newImageArray);
		// ProgressBar
			$p->setProgressBarProgress($i*$sx);
			$i++;
			
		}
		$p->setProgressBarProgress(100);
		echo_msg('--- Added '.count($not_found_in_db).' images to the image database<br/>');
		echo_msg('---<br/>',1);
	}else{
		echo_msg('--- No images needed to be Added<br/>');
	}
	echo_msg('--- Done<br/>--- Total Time Elapsed: '.round($stopwatch->elapsed(),4).' seconds<br />');
	echo_msg('</body></html>');


//////////////////////////////////////////////////////////////////////////////////////////
// functions

	function create_hash($length=5){
		$hash	= '';
		for($i=0;$i<$length;++$i){
		// if you want to add letters you will have to change the id to varchar instead of int
			$possibleValues = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		// pick a random character from the possible ones
	    	$hash .= substr($possibleValues, mt_rand(0, strlen($possibleValues)-1), 1);
		}
		return $hash;
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

	function echo_msg($msg,$flush = 4){
		global $autouse;
		if($autouse) return;
		echo $msg;
		if($flush) flushNow($flush);
	}

	class StopWatch {
		public $total;
		public $time;
		public function __construct() {$this->total = $this->time = microtime(true);}
		public function clock() {return -$this->time + ($this->time = microtime(true));}
		public function elapsed() {return microtime(true) - $this->total;}
		public function reset() {$this->total=$this->time=microtime(true);}
	}


/* *******************************************************
*   Fingerprint
*
*   This function analyses the filename passed to it and
*   returns an md5 checksum of the file's histogram.
******************************************************* */
	function fingerprint($src_or_resource) {
		$thumbWidth = 150;
		$sensitivity = 2;

		if (!$image = @openImage($src_or_resource)) return -1;


	// Create thumbnail sized copy for fingerprinting
		$width = imagesx($image);
		$height = imagesy($image);
		$ratio = $thumbWidth / $width;
		$newwidth = $thumbWidth;
		$newheight = round($height * $ratio); 
		$smallimage = imagecreatetruecolor($newwidth, $newheight);
		imagecopyresampled($smallimage, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		$palette = imagecreatetruecolor(1, 1);
		$gsimage = imagecreatetruecolor($newwidth, $newheight);

	// Convert each pixel to greyscale, round it off, and add it to the histogram count
		$numpixels = $newwidth * $newheight;
		$histogram = array();
		for ($i = 0; $i < $newwidth; $i++) {
			for ($j = 0; $j < $newheight; $j++) {
				$pos = imagecolorat($smallimage, $i, $j);
				$cols = imagecolorsforindex($smallimage, $pos);
				$r = $cols['red'];
				$g = $cols['green'];
				$b = $cols['blue'];
				// Convert the colour to greyscale using 30% Red, 59% Blue and 11% Green
				$greyscale = round(($r * 0.3) + ($g * 0.59) + ($b * 0.11));                 
				$greyscale++;
				$value = (round($greyscale / 16) * 16) -1;
				@$histogram[$value]++;
			}
		}

	// Normalize the histogram by dividing the total of each colour by the total number of pixels
		$normhist = array();
		foreach ($histogram as $value => $count) {
			$normhist[$value] = $count / $numpixels;
		}

	// Find maximum value (most frequent colour)
		$max = 0;
		for ($i=0; $i<255; $i++) {
			if (@$normhist[$i] > $max) {
				$max = $normhist[$i];
			}
		}   

	// Create a string from the histogram (with all possible values)
		$histstring = "";
		for ($i = -1; $i <= 255; $i = $i + 16) {
			$h = @(@$normhist[$i] / $max) * $sensitivity;
			if ($i < 0) {
				$index = 0;
			} else {
				$index = $i;
			}
			$height = round($h);
			$histstring .= $height;
		}

	// Destroy all the images that we've created
		imagedestroy($image);
		imagedestroy($smallimage);
		imagedestroy($palette);
		imagedestroy($gsimage);

	// Generate an md5sum of the histogram values and return it
		$checksum = md5($histstring);
		return $checksum;

	}

	function openImage($src){
		switch( exif_imagetype($src)){
			case IMAGETYPE_PNG:
				$img = @imagecreatefrompng($src);
				break;
			case IMAGETYPE_GIF:
				$img = @imagecreatefromgif($src);
				break;
			case IMAGETYPE_JPEG:
				$img = @imagecreatefromjpeg($src);
				break;
			case IMAGETYPE_BMP:
				$img = @ImageCreateFromBMP($src);
				break;
			case IMAGETYPE_PSD:
				$img = @imagecreatefrompsd($src);//@
				break;
			default:
				$img = false;
				break;
		}
		return $img;
	}

if( ! function_exists('exif_imagetype') ){
	function exif_imagetype ( $f ){
		if ( false !== ( list(,,$type,) = getimagesize( $f ) ) )
			return $type;
		return IMAGETYPE_PNG; // meh
	}
}

    
/**
 * Progress bar for a lengthy PHP process
 * http://spidgorny.blogspot.com/2012/02/progress-bar-for-lengthy-php-process.html
 */
 
class ProgressBar {
	var $percentDone = 0;
	var $pbid;
	var $pbarid;
	var $tbarid;
	var $textid;
	var $decimals = 1;
 
	function __construct($percentDone = 0) {
		$this->pbid = 'pb';
		$this->pbarid = 'progress-bar';
		$this->tbarid = 'transparent-bar';
		$this->textid = 'pb_text';
		$this->percentDone = $percentDone;
	}
 
	function render() {
		$this->percentDone = floatval($this->percentDone);
		$percentDone = number_format($this->percentDone, $this->decimals, '.', '') .'%';
		echo '<div id="'.$this->pbid.'" class="pb_container">
				<div id="'.$this->textid.'" class="'.$this->textid.'">'.$percentDone.'</div>
				<div class="pb_bar">
						<div id="'.$this->pbarid.'" class="pb_before"
						style="width: '.$percentDone.';"></div>
						<div id="'.$this->tbarid.'" class="pb_after"></div>
				</div>
				<br style="height: 1px; font-size: 1px;"/>
		</div>
		<style>
				.pb_container {
						position: relative;
				}
				.pb_bar {
						width: 100%;
						height: 1.3em;
						border: 1px solid silver;
						-moz-border-radius-topleft: 5px;
						-moz-border-radius-topright: 5px;
						-moz-border-radius-bottomleft: 5px;
						-moz-border-radius-bottomright: 5px;
						-webkit-border-top-left-radius: 5px;
						-webkit-border-top-right-radius: 5px;
						-webkit-border-bottom-left-radius: 5px;
						-webkit-border-bottom-right-radius: 5px;
				}
				.pb_before {
						float: left;
						height: 1.3em;
						background-color: #43b6df;
						-moz-border-radius-topleft: 5px;
						-moz-border-radius-bottomleft: 5px;
						-webkit-border-top-left-radius: 5px;
						-webkit-border-bottom-left-radius: 5px;
				}
				.pb_after {
						float: left;
						background-color: #FEFEFE;
						-moz-border-radius-topright: 5px;
						-moz-border-radius-bottomright: 5px;
						-webkit-border-top-right-radius: 5px;
						-webkit-border-bottom-right-radius: 5px;
				}
				.pb_text {
					left: 48%;
					padding-top: 0px;
					position: absolute;
					top: -4px;
				}
		</style>'."\r\n";
		flushNow(1);
	}
 
	function setProgressBarProgress($percentDone, $text = '') {
		$this->percentDone = $percentDone;
		$text = $text ? $text : number_format($this->percentDone, $this->decimals, '.', '').'%';
		echo '<script type="text/javascript">
		if (document.getElementById("'.$this->pbarid.'")) {
				document.getElementById("'.$this->pbarid.'").style.width = "'.$percentDone.'%";';
		if ($percentDone == 100) echo 'document.getElementById("'.$this->pbid.'").style.display = "none";';
		else echo 'document.getElementById("'.$this->tbarid.'").style.width = "'.(100-$percentDone).'%";';

		if ($text) echo'document.getElementById("'.$this->textid.'").innerHTML = "'.htmlspecialchars($text).'";';
		echo '}</script>'."\n";
		flushNow(1);
	}

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

