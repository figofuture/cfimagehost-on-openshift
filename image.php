<?php
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
 *   Used For:     Show image code
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	$debug_img = false; // [true|false]

//debug, show PHP errors
	if($debug_img){
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	}
// Hide all error messages from the public
	else{
		error_reporting(E_ALL^E_NOTICE);
		ini_set('display_errors', 0);
	}


 	define('cfih', 'image');
// load settings
	if(!require './inc/config.php') die('Can\'t find config.php');
	if(!require CFSETTINGS) die('Can\'t find set.php');

// check for image call
	if(!isset($_GET['di']) && !isset($_GET['dm']) && !isset($_GET['dt']) && !isset($_GET['dl'])){
		if(!$debug_img) header('Content-type: '.$imgNotFound['type']);
		readfile($imgNotFound['address']);
		exit();
	}

//load function && class
	if(!require CFLIBPATH.'arraydb.class.php') die('Can\'t find arraydb.class.php');// check for and load fixed settings
	if(!require CFINCPATH.'functions.php') die('Can\'t find functions.php');// check for and load settings

	if (isset($_GET['di'])){
		$img['id']		= $_GET['di'];
		$img['type']	= 1;
	}elseif (isset($_GET['dm'])){
		$img['id']		= $_GET['dm'];
		$img['type']	= 2;
	}elseif (isset($_GET['dt'])){
		$img['id']		= $_GET['dt'];
		$img['type']	= 3;
	}elseif (isset($_GET['dl'])){
		$img['id']		= $_GET['dl'];
		$img['type']	= 4;
	}

	$img['id'] = cl($img['id']);

	if(preg_replace("/[^0-9A-Z]/","",$img['id']) != $img['id']){
		if(!$debug_img) header('Content-type: image/png');
		readfile('img/notfound.png');
		exit();
	}

	if($image = db_get_image($img['id'])){
		$image_time = $image['added'];
		if(array_key_exists("HTTP_IF_MODIFIED_SINCE",$_SERVER)){
			$if_modified_since=strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"]));
			if($if_modified_since >= $image_time && !$debug_img){
				header("HTTP/1.0 304 Not Modified");
				exit();
			}
		}

		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $image_time).' GMT', true, 200);
		header('Expires: '.gmdate('D, d M Y H:i:s',  $image_time + 86400*365).' GMT', true, 200);
		header("Pragma: public");
		header("Cache-Control: maxage=".(86400*14));

		$img_address = imageAddress($img['type'],$image);

		$pathinfo = pathinfo($img_address);
		$img['ext'] = strtolower($pathinfo['extension']);
		if($img['ext']=='jpg') $img['ext'] = 'jpeg';

		if(!hotlink()){
			if(!$debug_img) header('Content-type: image/'.$img['ext']);
		// donwload image header
			if($img['type'] == 4){
				header('Content-Length: '.$image['size']);
				header('Content-Disposition: attachment;filename="'.$image['name'].'"');
			}
			readfile($img_address);
			$img['type'] = 4;
		}else{
			not_max_bandwidth($image,$img['type']);
			if($settings['SET_WATERMARK']){
				watermarkImage($img_address);
			}else{
				if(!$debug_img) header('Content-type: image/'.$img['ext']);
				readfile($img_address);
			}
		}
	}else{
		if(!$debug_img) header('Content-type: image/png');
		readfile('img/notfound.png');
	}

	if($img['type'] != 4){
	//	flushNow(1);
		db_imageCounterSave($image,$img['type']);
	}

	exit();

function watermarkImage($SourceFile) {
	global $settings,$DIR_TEMP;

	$font = CFLIBPATH.'font/arial.ttf';// the location on the server that the font can be found
	$font_size = 40;// size of the font

	require CFLIBPATH.'watermark.class.php';
	$img = new watermark($SourceFile, (empty($settings['SET_WATERMARK_IMAGE'])?null:$settings['SET_WATERMARK_IMAGE']));
	$img->cacheDir = $DIR_TEMP;
	//$img->saveQuality = 9;
	if(empty($settings['SET_WATERMARK_IMAGE'])){
		$img->padding = 10;
		$img->textWatermark($settings['SET_WATERMARK_TEXT'],$font_size,$font );
		$img->opacityVal = 30;
		$img->watermarkSizing(0.75);
	}
	$img->watermarkPosition($settings['SET_WATERMARK_PLACED']);
	$img->makeImage();
	return;
}

function hotlink($ref=''){
	global $settings;
	$referrer		= $ref !='' ? $ref:getenv( "HTTP_REFERER" );
	$ref_address	= explode('/',str_replace('www.', '', str_replace('http://', '',$referrer)));
	$home_address	= explode('/',str_replace('www.', '', str_replace('http://', '',$settings['SET_SITEURL'])));
	if($ref_address[0] == $home_address[0])
		return false;
	return true;
}

function not_max_bandwidth($image,$imgType){
	global $settings,$debug_img;
	if(!$settings['SET_MAX_BANDWIDTH'] == 0){
		if(db_maxedBandwidth($image['id'])){
			if(!$debug_img) header('Content-type: image/png');
			readfile('img/bandwidth.png');
			exit();
		}
	}
	return true;
}
