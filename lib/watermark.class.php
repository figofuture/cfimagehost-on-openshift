<?php if(!defined('cfih') or !cfih) die('This file cannot be directly accessed.');
/**************************************************************************************************************
 *
 *   CF Image Hosting 
 *   ------------------
 *
 *   Author:    codefuture.co.uk
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
 *   Used For:     Image watermark Class
 *
 *************************************************************************************************************/

class watermark 
{

	private $image;
	private $width;
	private $height;
	private $watermark;
	private $watermark_width;
	private $watermark_height;
	private $image_type;
	private $placementX;
	private $placementY;
	private $cacheName;

	public $opacityVal = null;
	public $cacheDir = null;
	public $browserCacheControl = TRUE;
	public $saveQuality = 5;
	public $padding = 0;
	public $watermark_to_big = 0.75;



	function __construct($src_or_resource,$watermark_s_or_r=null){

	// image cache name
		$this->cacheName = md5($src_or_resource.$watermark_s_or_r);

// image to watermark
	// image resource
		if (is_resource($src_or_resource)) $this->image = $src_or_resource;
	// Open image file
		else{
			$this->image = $this->openImage($src_or_resource);
			$this->imageInfo = pathinfo($src_or_resource);
		}
	// Get width and height
		$this->width  = @imagesx($this->image);
		$this->height = @imagesy($this->image);


	// watermark image if one is set
		if(!is_null($watermark_s_or_r)){
		// image resource
			if (is_resource($watermark_s_or_r)) $this->watermark = $watermark_s_or_r;
		// Open image file
			else $this->watermark = $this->openImage($watermark_s_or_r);
		// Get width and height
			$this->watermark_width  = @imagesx($this->watermark);
			$this->watermark_height = @imagesy($this->watermark);
		}

	// see if the watermark image is bigger then the image getting the watermark
		if($this->watermark_width > $this->width || $this->watermark_height > $this->height){
			$this->watermarkSizing($this->watermark_to_big);
		}
	}


	/*
	 * textWatermark
	 *
	 * $text = text to use as the watermark
	 * $fontSize = size of the text
	 * $fontAddress = address of the font font to use
	 */
	public function textWatermark($text,$fontSize,$fontAddress){

	// update image cache name
		$this->cacheName = md5($this->cacheName.$text.$fontSize);

	// make text Watermark cache name
		$textWatermarkCache = $this->cacheDir.'/'.md5($text.$fontSize).'.png';

	// see if the text Watermark exists
		if (file_exists($textWatermarkCache)){ 
			$this->watermark = $this->openImage($textWatermarkCache);
			$this->watermark_width  = @imagesx($this->watermark);
			$this->watermark_height = @imagesy($this->watermark);
		}
	// if no cache exists then make text image
		else{

			$bbox = imagettfbbox($fontSize, 0, $fontAddress, $text);
			$size_w = abs($bbox[2] - $bbox[0]);// width: right corner X - left corner X
			$size_h = abs($bbox[7] - $bbox[1]);// height: top Y - bottom Y

			$x = -abs($bbox[0])+$this->padding; 
			$y = ($size_h - abs($bbox[1]))+$this->padding;

			$im = imagecreatetruecolor($size_w+($this->padding*2), $size_h+($this->padding*2));// creating image
			$back = imagecolorallocate($im, 250, 250, 250); // background color
			$fore = imagecolorallocate($im, 0, 0, 0);// foreground color
			$sh = imagecolorallocate($im, 250, 250, 250); // background color

			imagealphablending($im, false);
			$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
			imagefill($im, 0, 0, $color);
			imagesavealpha($im, true);
			imagealphablending($im, true);
			imagettftext($im, $fontSize, 0, $x-1, $y-1, $sh , $fontAddress, $text);// rendering text
			imagettftext($im, $fontSize, 0, $x, $y, $fore, $fontAddress, $text);// rendering text

			$this->watermark = $im;
			$this->watermark_width  = $size_w+($this->padding*2);//@imagesx($this->watermark);//@
			$this->watermark_height = $size_h+($this->padding*2);//@imagesy($this->watermark);//@

			if(!is_null($this->cacheDir)){// save cache file 
				imagepng($this->watermark, $textWatermarkCache, $this->saveQuality); // outputing PNG image to file cache 
			}
		}

	}


	/*
	 * openImage
	 *
	 * $src = address of the image 
	 * return image resource
	 */
	private function openImage($src){

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
			default:
				die('can\'t open image');
				break;
		}

		return $img;
	}


		/*
		 * watermarkPosition
		 *
		 * $x 
		 * $y
		 */
	// where to place the watermark?
		public function watermarkPosition($x,$y=null){
			if(is_null($y)){
				switch($x){
					case 1:$y = 'top';$x = 'left';break;
					case 2:$y = 'top';$x = 'center';break;
					case 3:$y = 'top';$x = 'right';break;
					case 4:$y = 'center';$x = 'left';break;
					case 5:$y = 'center';$x = 'center';break;
					case 6:$y = 'center';$x = 'right';break;
					case 7:$y = 'bottom';$x = 'left';break;
					case 8:$y = 'bottom';$x = 'center';break;
					case 9:$y = 'bottom';$x = 'right';break;
					default:$y = 'center';$x = 'center';break;
				}
			}

			switch($x){
			// find the X coord for placement
				case 'left':
					$this->placementX = $this->padding;
					break;
				case 'center':
					$this->placementX =  round(($this->width - $this->watermark_width) / 2);
					break;
				default://case 'right':
					$this->placementX = $this->width - $this->watermark_width - $this->padding;
					break;
			}

			switch($y){// find the Y coord for placement
				case 'top':
					$this->placementY = $this->padding;
					break;
				case 'center':
					$this->placementY =  round(($this->height - $this->watermark_height) / 2);
					break;
				default://case 'bottom':
					$this->placementY = $this->height - $this->watermark_height - $this->padding;
					break;
			}
		
			$this->cacheName = md5($this->cacheName.$x.$y);
		
		}

		public function watermarkSizing($size){
			$this->cacheName = md5($this->cacheName.$size);
			$newWidth=round($this->width * floatval($size));
			$newHeight=round($this->height * floatval($size));

			if($size==1){
				$newWidth-=2*$this->padding;
				$newHeight-=2*$this->padding;
			}

			$percentage = (double)$newWidth/$this->watermark_width;
			$destHeight = round($this->watermark_height*$percentage)+1;
			$destWidth = round($this->watermark_width*$percentage)+1;

			if($destHeight > $newHeight){
				// if the width produces a height bigger than we want, calculate based on height
				$percentage=(double)$newHeight/$this->watermark_height;
				$destHeight=round($this->watermark_height*$percentage)+1;
				$destWidth=round($this->watermark_width*$percentage)+1;
			}

			$im=imagecreatetruecolor($destWidth,$destHeight);
			imagealphablending($im, false);
			$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
			imagefill($im, 0, 0, $color);
			imagesavealpha($im, true);
			imagecopyresampled(	$im,
								$this->watermark,
								0,0,0,0,
								$destWidth,
								$destHeight,
								$this->watermark_width,
								$this->watermark_height);

			$this->watermark_width = $destWidth;
			$this->watermark_height = $destHeight;
			$this->watermark = $im;
			return true;
		}

		public function makeImage(){

			if(!is_null($this->cacheDir)) if(!$saveName = $this->loadCache())return;

			imagealphablending($this->image, TRUE);
			if (!is_null($this->opacityVal)) $this->imagecopymerge_alpha($this->image, $this->watermark, $this->placementX, $this->placementY, 0, 0, $this->watermark_width, $this->watermark_height, $this->opacityVal);
			else imagecopy($this->image, $this->watermark, $this->placementX, $this->placementY, 0, 0, $this->watermark_width, $this->watermark_height);

			$this->browserCache(time());

			if($this->imageInfo['extension'] == 'png'){
				header("Content-type: image/png");
				imagealphablending($this->image,false);
				imagesavealpha($this->image,true);
				imagepng($this->image);
			}
			else{
				header("Content-type: image/jpeg");
				imagejpeg($this->image);
			}

			if(!is_null($this->cacheDir)){// save cache file 
				if($this->imageInfo['extension'] == 'png'){ // outputing PNG image to file cache 
					imagepng($this->image, $saveName,$this->saveQuality);
				}
				else{
					imagejpeg($this->image, $saveName,$this->saveQuality); 
				}
			}
			imagedestroy($this->image);
			imagedestroy($this->watermark);
		}

	// checking for cached file:
	// if cached file exists output it and quit
		private function loadCache(){
			$cache_file = $this->cacheDir.'/'.$this->cacheName.'.'.$this->imageInfo['extension'];
			if (file_exists($cache_file)){ 
				if(array_key_exists("HTTP_IF_MODIFIED_SINCE",$_SERVER)){
					$if_modified_since=strtotime(preg_replace('/;.*$/','',$_SERVER["HTTP_IF_MODIFIED_SINCE"]));
					if($if_modified_since >= filemtime($cache_file)){
						header("HTTP/1.0 304 Not Modified");
						exit();
					}
				}
				$this->browserCache(filemtime($cache_file));
				if('jpg'==$this->imageInfo['extension']) $this->imageInfo['extension'] = 'jpeg';
				header("Content-type: image/".$this->imageInfo['extension']);
				readfile($cache_file);
				return false;
				//exit();
			}
			return $cache_file;
		}

		private function browserCache($time){
			if(!$this->browserCacheControl)return;
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT', true, 200);
			header('Expires: '.gmdate('D, d M Y H:i:s', $time + 86400*365).' GMT', true, 200);
			header("Pragma: public");
			header("Cache-Control: maxage=".(86400*14));
		}

		/**
		 * PNG ALPHA CHANNEL SUPPORT for imagecopymerge();
		 * by Sina Salek
		 *
		 * Bugfix by Ralph Voigt (bug which causes it
		 * to work only for $src_x = $src_y = 0.
		 * Also, inverting opacity is not necessary.)
		 * 08-JAN-2011
		 *
		 **/ 
		private function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
			$cut = imagecreatetruecolor($src_w, $src_h);// creating a cut resource
			imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);// copying relevant section from background to the cut resource
			imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);// copying relevant section from watermark to the cut resource
			imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);// insert cut resource to destination image
		} 

}

if ( ! function_exists( 'exif_imagetype' ) ) {
	function exif_imagetype ( $filename ) {
		if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
			return $type;
		}
	return false;
	}
}
