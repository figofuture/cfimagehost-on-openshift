<?php if(!defined('cfih') or !cfih) die('This file cannot be directly accessed.');
/**************************************************************************************************************
 *
 *   CF Image Hosting Script
 *   ---------------------------------
 *
 *   Author: codefuture.co.uk
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
 *   Used For:     Image upload Class
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/

/***
 * Error Codes
 *
 * 100 - empty source
 *
 * 110 - Image can not be found or the server with the image on has denied access - _T("site_upload_err_no_image");
 * 111 - The image url is not Valid
 *
 * 120 - Image format(mime) not accepted - _T("site_upload_types_accepted")
 * 121 - Image to small (pixels) - _T("site_upload_to_small")
 * 122 - Image to big (pixels) - _T("site_upload_to_big")
 * 123 - image file size to big (kb) - _T("site_upload_size_accepted")
 * 124 - error opening image
 * 125 - error moving image - _T("site_upload_err")
 *
 */


	/***
	* image format class/functions
	*/
	require CFLIBPATH.'psd.class.php'; //imagecreatefrompsd()
	//require CFLIBPATH.'ico.class.php'; //imagecreatefromico() // not working that good :(
	require CFLIBPATH.'bmp.class.php'; //imagecreatefrombmp()

	/***
	 * require gif split / merge classes 
	 */
	require CFLIBPATH.'gifsplit.php'; 
 

class upload {

// image upload
	public $accepted_formats = array('image/bmp'=>'bmp','image/gif'=>'gif','image/jpg'=>'jpg','image/jpeg'=>'jpg','image/png'=>'png');
	public $max_filesize = 512000; // 500k
	public $min_dimensions = 10;
	public $max_dimensions = 3300;
	public $image_id_length = 4;
	public $upload_dir;
	public $temp_dir;
	public $info; // image info
	private $source;
	private $base_source;
	
	private $img_resource;
	public $error_code = false;

// duplicates/fingerprinting
	public $dup_size = 32;// Sets the width and height of the thumbnail sized image we use for deep comparison.
	public $dup_thumbWidth = 150;// Width for thumbnail images we use for fingerprinting.
	public $sensitivity = 2;// Sets how sensitive the fingerprinting will be. Higher numbers are less sensitive (more likely to match). Floats are allowed.
	public $deviation = 1;// Sets how much deviation is tolerated between two images when doing an thorough comparison.

// Image Resized
	private $imageResized;
	private $showMLimit = false;
	private $MemoryLimit = false;
	private $TweakFactor = 1.8;
	private $stretch = false;
	private $transparency = true;
	private $smaller = false;
	
	private $ani_gif = false;
	private $ani_delays;
	private $ani_imageResized;
	private $ani_transparent;
	private $ani_disposal;
	private $ani_allow = true;

	public function destroyImage(){
		@imagedestroy($this->img_resource);
		@imagedestroy($this->imageResized);
		unset($this->info);
		unset($this->source);
	}


	/***
	 * Grab the source
	 */
	function __construct($source) {
		$this->base_source = $source;
	}


	/***
	 * process
	 */
	public function process($multiupload = null){
	
		// fix multiupload $_FILES array
		//if(isset($this->base_source['name'][$multiupload])){
                if(!empty($this->base_source['name']) && strlen($this->base_source['name']) >4){
			$this->source = array(
								'name'			=> $this->base_source['name'][$multiupload],
								'type'			=> $this->base_source['type'][$multiupload],
								'tmp_name'	=> $this->base_source['tmp_name'][$multiupload],
								'error'			=> $this->base_source['error'][$multiupload],
								'size'			=> $this->base_source['size'][$multiupload]
									);
		}else{
			$this->source = $this->base_source;
		}

	 // check for empty source
		if(empty($this->source)){
			$this->error_code = 100; 
			return false;
		}

	// size error
		if(!empty($this->source['error']) && $this->source['error'] == 2){
			$this->error_code = 123; 
			return false;
		}

	// check type of source (url|image $_file)
		if(!empty($this->source['name']) && strlen($this->source['name']) >4){
			return  $this->image_checks();
		}
		
		return  $this->url_process();
	}


	/***
	 * url_process
	 * copys the image from a url and save it to temp dir
	 */
	private function url_process(){

	// First url verification
		if(empty($this->source) || is_array($this->source) || !preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $this->source)) {
			$this->error_code = 111;
			return false;
		}

		$this->info['URL'] = $this->source; // this lets finish_upload know to copy and not move_uploaded_file
		$destination = $this->temp_dir.'temp_'.$this->create_hash(32); // name temp

	// get image for url
		$ch = curl_init($this->source);
		$fp = fopen($destination, "wb");
		$options = array(CURLOPT_FILE => $fp,CURLOPT_HEADER => false,CURLOPT_RETURNTRANSFER => true,CURLOPT_AUTOREFERER => true,CURLOPT_TIMEOUT => 120);
		curl_setopt_array($ch, $options);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_exec($ch);
		fclose($fp);
	// Check for 404 (file not found).
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $content_type = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
                $content_length = curl_getinfo($ch,CURLINFO_CONTENT_LENGTH_DOWNLOAD);
		curl_close($ch); 
		if($httpCode == 404) {
			@unlink($destination);
		}

	// check temp file got saved
		if(!file_exists($destination)){
			$this->error_code = 110;
			return false;
		}

	// set source for file_proces
		$this->source = Array ('name' =>  basename($this->source),	'type' =>  $content_type,	'tmp_name' => $destination,	'error' =>  0,	'size' => $content_length );

	// check for errors (if so remove temp image)
		if(!$this->image_checks()){
			unlink($destination);
			return false;
		}
		return true;
	}


	/***
	 * image_checks
	 * checks image file 
	 */
	private  function image_checks(){
	 
		// setup image var
			$pieces = explode("?", $this->source['name']);
			$this->info['full_name'] = $pieces[0];

			$pathInfo = pathinfo($this->source['name']);
			$this->info['name'] = $pathInfo['filename'];
			$this->info['tmp_name'] = $this->source['tmp_name'];
			
			$imgSize = @getimagesize($this->info['tmp_name']);
			$this->info['width'] = $imgSize[0];
			$this->info['height'] = $imgSize[1];

		 //check the mime type & set image extension (we do not check the extension
			if(!$this->info['ext'] = $this->get_extension($imgSize['mime'])){
				$this->error_code = 120; 
				return false;
			}

		//min size(pixels)
			if ($this->info['width'] < $this->min_dimensions || $this->info['height'] < $this->min_dimensions ){
				$this->error_code = 121; 
				return false;
			}

		// max size(pixels)
			if ($this->info['width'] > $this->max_dimensions || $this->info['height'] > $this->max_dimensions ){
				$this->error_code = 122; 
				return false;
			}

		//Check file size (kb)
			$this->info['size'] = ($this->source['size'] < 1?@filesize($this->info['tmp_name']):$this->source['size']);
			if($this->info['size'] > $this->max_filesize){
				$this->error_code = 123; 
				return false;
			}

		// now open image 
			if(!$this->img_resource = $this->openImage($this->info['tmp_name'])){
				$this->error_code = 124; 
				return false;
			}

			unset($this->source);
		// return checks ok
			return true;
	}


	/***
	 * finish_upload
	 * finishes the upload process by setings save
	 * name and moving file tho the image folder.
	 */
	public function finish_upload($adminUpload = null){
		//New random name
			$this->info['id'] = $this->create_hash($this->image_id_length,true);
		//random delete ID
			$this->info['did'] = $this->info['id'].$this->create_hash(6);
		//Image address
			$this->info['new'] = $this->info['id'].'.'.$this->info['ext'];
			$this->info['address'] = $this->upload_dir.$this->info['new'];

		// convert BMP to JPG and PSD/ICO to PNG
			$converttypes =  array(
								'bmp'=>'jpg',
								'psd'=>'png',
								'ico'=>'png'
									);
			if(isset($converttypes[$this->info['ext']])){
			//Reset Image address
				$this->info['ext'] = $converttypes[$this->info['ext']];
				$this->info['new'] = $this->info['id'].'.'.$this->info['ext'];
				$this->info['address'] = $this->upload_dir.$this->info['new'];
				$this->imageConvert($this->info['address'],($this->info['ext'] == 'bmp'?90:60),null,true);
				$this->info['contenttype'] = ($this->info['ext'] == 'bmp'?IMAGETYPE_JPEG:IMAGETYPE_PNG);
			// remove temp file
				if(is_file($this->info['tmp_name'])){
					unlink($this->info['tmp_name']);
				}
			}
		//move image from remote server
			elseif((isset($this->info['URL']) && !empty($this->info['URL'])) || !is_null($adminUpload)){
				copy($this->info['tmp_name'],$this->info['address']);
				unlink($this->info['tmp_name']);
			}
		//move uploaded image
			else{
				move_uploaded_file($this->info['tmp_name'],$this->info['address']);
			}

		// check that the file moved ok
			if(!file_exists($this->info['address'])){
				$this->error_code = 125;
				return false;
			}

		// return uploaded done
			return true;
	}


	/***
	 * Open image resource
	 */
	private function openImage($src){
		if($this->MemoryLimit)$this->setMemoryForImage($src,$this->TweakFactor);
		switch( ( $this->info['contenttype'] = exif_imagetype($src))){
			case IMAGETYPE_PNG:
				$img = imagecreatefrompng($src);
				break;
			case IMAGETYPE_GIF:
				$img = $this->imagecreatefrom_gif($src);
				break;
			case IMAGETYPE_JPEG:
				$img = @imagecreatefromjpeg($src);
				break;
			case IMAGETYPE_BMP:
				$img = @ImageCreateFromBMP($src);
				break;
			case IMAGETYPE_PSD:
				$img = @imagecreatefrompsd($src);
				break;
		//	case IMAGETYPE_ICO:
		//		$img = imagecreatefrom_ico($src);
		//		break;
			default:
				$img = false;
				break;
		}
		return $img;
	}


	/***
	 * imagecreatefrom_gif
	 *	opens a Gif file 
	 */
	private function imagecreatefrom_gif($src) {
		if($this->ani_allow && $this->ani_gif = $this->is_ani($src)){
			$gif = new GIFDecoder(file_get_contents($src));
			$img = $gif->GIFGetFrames();
			foreach ($img as $k => $frame){
				$img[$k] = imagecreatefromstring($frame);
				if($k===0){
					$img_sizex = imagesx($img[$k]);
					$img_sizey = imagesy($img[$k]);
				}elseif($img_sizex <> imagesx($img[$k]) || $img_sizey <> imagesy($img[$k])){
					unset($img);
					$this->ani_gif = false;
					$this->ani_offset = null;
					break;
				}
			}
		}

		if(!$this->ani_gif || count($img) <= 0){
			$img = imagecreatefromgif($src);
			$this->ani_gif = false;
		}else{
		// set var when 100%
			$this->ani_delays = $gif->GIFGetDelays();
			$this->ani_disposal = $gif->GIFGetDisposal();
			$this->ani_transparent['r'] = $gif->GIFGetTransparentR ( );
			$this->ani_transparent['g'] = $gif->GIFGetTransparentG ( );
			$this->ani_transparent['b'] = $gif->GIFGetTransparentB ( );
		}
		return $img;
	}


	/***
	 * is_ani
	 *	checks to see if a gif image is animated or not
	 */
	private function is_ani($filename) {
		if(!is_file($filename)) return false;
		return (bool)preg_match('/\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)/s', file_get_contents($filename), $m);
	}


	/***
	 *   Fingerprint
	 *
	 *   This function analyses the filename passed to it and
	 *   returns an md5 checksum of the file's histogram.
	 */
	public function fingerprint() {

	// Create thumbnail sized copy for fingerprinting
		$ratio = $this->dup_thumbWidth / $this->info['width'];
		$newwidth = $this->dup_thumbWidth;
		$newheight = round($this->info['height'] * $ratio); 
		$smallimage = imagecreatetruecolor($newwidth, $newheight);
		if(!$this->ani_gif)
			imagecopyresampled($smallimage, $this->img_resource, 0, 0, 0, 0, $newwidth, $newheight, $this->info['width'], $this->info['height']);
		else
			imagecopyresampled($smallimage, $this->img_resource[0], 0, 0, 0, 0, $newwidth, $newheight, $this->info['width'], $this->info['height']);
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
			$h = @(@$normhist[$i] / $max) * $this->sensitivity;
			if ($i < 0) {
				$index = 0;
			} else {
				$index = $i;
			}
			$height = round($h);
			$histstring .= $height;
		}

	// Destroy all the images that we've created
		imagedestroy($smallimage);
		imagedestroy($palette);
		imagedestroy($gsimage);

	// Generate an md5sum of the histogram values and return it
		return $this->info['fingerprint'] = md5($histstring);

	}


	/***
	 *   Are Duplicates
	 *
	 *   This function compares two images by resizing them
	 *   to a common size and then analysing the colours of
	 *   each pixel and calculating the difference between
	 *   both images for each colour channel and returns
	 *   an index representing how similar they are.
	 */
	public function are_duplicates($file2) {
	// Load in both images and resize them
		$image1_small = $this->dup_resizeImage();
		$image2_small = $this->dup_resizeImage($file2);

	// Compare the pixels of each image and figure out the colour difference between them
		$difference =0;
		for ($x = 0; $x < $this->dup_size; $x++) {
			for ($y = 0; $y < $this->dup_size; $y++) {
				$image1_color = imagecolorsforindex($image1_small, 
				imagecolorat($image1_small, $x, $y));
				$image2_color = imagecolorsforindex($image2_small, 
				imagecolorat($image2_small, $x, $y));
				$difference +=  abs($image1_color['red'] - $image2_color['red']) + 
								abs($image1_color['green'] - $image2_color['green']) +
								abs($image1_color['blue'] - $image2_color['blue']);
			}
		}
		$difference = $difference / 256;

		if ($difference <= $this->deviation) {
			return true;
		} else {
			return false;
		}
	}


	/***
	 * dup_resizeImage
	 * used for Are Duplicates only
	 */
	private function dup_resizeImage($originalImage=null){
		$imageResized = imagecreatetruecolor($this->dup_size,$this->dup_size);
		if(!is_null($originalImage)){
			list($width, $height) = getimagesize($originalImage);
			$imageTmp = $this->openImage($originalImage);
		}else{
			$width = $this->info['width'];
			$height = $this->info['height'];
			if(!$this->ani_gif) $imageTmp = $this->img_resource;
			else $imageTmp = $this->img_resource[0];
		}
		imagecopyresampled($imageResized, $imageTmp, 0, 0, 0, 0, $this->dup_size,$this->dup_size, $width, $height);
		return $imageResized;
	} 


	/***
	 * reszieImage
	 * used to to make thumbs
	 */ 
	public function resizeImage($newWidth, $newHeight, $option="auto"){
	//Get optimal width and height - based on $option
		$dimArr = $this->getDimensions($newWidth, $newHeight, $option);
	// check for ani gif
		if($this->ani_gif){
			$this->ani_resizeImage($newWidth, $newHeight,$dimArr['optimalWidth'],$dimArr['optimalHeight'], $option);
		}
	// all other image resizing
		else{
			$this->imageResized = imagecreatetruecolor($dimArr['optimalWidth'], $dimArr['optimalHeight']);//  Resample - create image canvas of x, y size
			if($this->transparency && (($this->info['contenttype'] == IMAGETYPE_GIF) || ($this->info['contenttype'] == IMAGETYPE_PNG)))	$this->setTransparency();
			imagecopyresized($this->imageResized, $this->img_resource, 0, 0, 0, 0, $dimArr['optimalWidth'], $dimArr['optimalHeight'], $this->info['width'], $this->info['height']);
			if ($option == 'crop') $this->crop($dimArr['optimalWidth'], $dimArr['optimalHeight'], $newWidth, $newHeight);// if option is 'crop', then crop too
		}
	}


	/***
	 * ani_reszieImage
	 * used to to make animated thumbs
	 */ 
	public function ani_resizeImage($newWidth, $newHeight,$optimalWidth,$optimalHeight, $option="auto"){
		foreach($this->img_resource as $k => $oldimg){
			$this->imageResized = null;
			$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);// Resample - create image canvas of x, y size
			$this->setTransparency($oldimg);
			imagecopyresized($this->imageResized, $oldimg, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->info['width'], $this->info['height']);
			if ($option == 'crop') $this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);// if option is 'crop', then crop too
			$this->ani_imageResized[] =	$this->imageResized;
		}
	}

	private function getDimensions($newWidth, $newHeight, $option){

		if ($newHeight > $this->info['height'] && $newWidth > $this->info['width'] && !$this->stretch){
		//already smaller than the thumbnail
			$optimalWidth = $this->info['width'];
			$optimalHeight= $this->info['height'];
		}
		else{
			switch ($option){
				case 'exact':
					$optimalWidth = $newWidth;
					$optimalHeight= $newHeight;
					break;
				case 'portrait':
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
					break;
				case 'landscape':
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
					break;
				case 'auto':
					$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
					$optimalWidth = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];
					break;
				case 'crop':
					$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
					$optimalWidth = $optionArray['optimalWidth'];
					$optimalHeight = $optionArray['optimalHeight'];
					break;
			}
		}
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function getSizeByFixedHeight($newHeight){
		$ratio = $this->info['width'] / $this->info['height'];
		$newWidth = $newHeight * $ratio;
		return $newWidth;
	}

	private function getSizeByFixedWidth($newWidth){
		$ratio = $this->info['height'] / $this->info['width'];
		$newHeight = $newWidth * $ratio;
		return $newHeight;
	}

	private function getSizeByAuto($newWidth, $newHeight){
		if ($this->info['height'] < $this->info['width']){
		// *** Image to be resized is wider (landscape)
			$optimalWidth = $newWidth;
			$optimalHeight= $this->getSizeByFixedWidth($newWidth);
		}elseif ($this->info['height'] > $this->info['width']){
		// *** Image to be resized is taller (portrait)
			$optimalWidth = $this->getSizeByFixedHeight($newHeight);
			$optimalHeight= $newHeight;
		}else{
		// *** Image to be resizerd is a square
			if ($newHeight < $newWidth) {
				$optimalWidth = $newWidth;
				$optimalHeight= $this->getSizeByFixedWidth($newWidth);
			} else if ($newHeight > $newWidth) {
				$optimalWidth = $this->getSizeByFixedHeight($newHeight);
				$optimalHeight= $newHeight;
			} else {
				// *** Sqaure being resized to a square
				$optimalWidth = $newWidth;
				$optimalHeight= $newHeight;
			}
		}
		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function getOptimalCrop($newWidth, $newHeight){

		$heightRatio = $this->info['height'] / $newHeight;
		$widthRatio  = $this->info['width'] /  $newWidth;

		if ($heightRatio < $widthRatio) {
			$optimalRatio = $heightRatio;
		} else {
			$optimalRatio = $widthRatio;
		}

		$optimalHeight = $this->info['height'] / $optimalRatio;
		$optimalWidth  = $this->info['width']  / $optimalRatio;

		return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
	}

	private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight){
		if (($newHeight < $this->info['height'] || $newWidth < $this->info['width']) || $this->stretch){
			// *** Find center - this will be used for the crop
			$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
			$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

			$crop = $this->imageResized;
			//imagedestroy($this->imageResized);

			// *** Now crop from center to exact requested size
			$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
			imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
		}
	}

	private function setTransparency($img_res = null){
		$transparencyIndex = @imagecolortransparent((is_null($img_res)?$this->img_resource:$img_res));
		if ($transparencyIndex >= 0) {
			$transparencyColor = @imagecolorsforindex((is_null($img_res)?$this->img_resource:$img_res), $transparencyIndex); 
			$transparencyIndex = imagecolorallocate($this->imageResized, $transparencyColor['red'], $transparencyColor['green'], $transparencyColor['blue']);
			imagealphablending($this->imageResized, false);
			imagesavealpha($this->imageResized, true);
			imagepalettecopy($this->imageResized,(is_null($img_res)?$this->img_resource:$img_res));
			imagefill($this->imageResized, 0, 0, $transparencyIndex);
			imagecolortransparent($this->imageResized, $transparencyIndex);
		}elseif ($this->info['contenttype'] == IMAGETYPE_PNG) {
			imagealphablending($this->imageResized, false);
			$color = imagecolorallocatealpha($this->imageResized, 0, 0, 0, 127);
			imagefill($this->imageResized, 0, 0, $color);
			imagesavealpha($this->imageResized, true);
		  }
	}


	/***
	 * Convert image then Save the image
	 * $savePath - save Path ( /img_dir/imagename.jpg)
	 * $saveQuality - image save quality ( 1 - 100 png and jpg only)
	 * $saveExt - save extension(type) (IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG)
	 */
	public function imageConvert($savePath,$saveQuality=100,$saveExt=null, $main_resource=false){
		$this->imageResized = $this->img_resource;
		$this->saveImage($savePath,$saveQuality,$saveExt,$main_resource);
	}


	/***
	 * Save the image to a different location
	 * $savePath - save Path ( /img_dir/imagename.jpg)
	 * $saveQuality - image save quality ( 1 - 100 png and jpg only)
	 * $saveExt - save extension(type) (IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_JPEG)
	 * $main_resource - set saved imge resource to $img_resource (used for resizing the upload image)
	 */
	public function saveImage($savePath,$saveQuality=100,$saveExt=null, $main_resource=false){

		if(is_null($saveExt)){
			// *** Get extension
			$extension = strrchr($savePath, '.');
			$extension = strtolower($extension);
			switch($extension){
				case '.jpg':
				case '.jpeg':
					$saveExt = IMAGETYPE_JPEG;
					break;
				case '.gif':
					$saveExt = IMAGETYPE_GIF;
					break;
				case '.png':
					$saveExt = IMAGETYPE_PNG;
					break;
				default:
					$saveExt = IMAGETYPE_PNG;
					break;
			}
		}

	// check that we need to save or copy
		if($this->smaller && ($saveExt == $this->info['contenttype'])){
			copy($this->info['address'],$savePath); // copy image
			$this->smaller = false; // reset
			return true;
		}

		switch($saveExt){
			case IMAGETYPE_PNG:
				$scaleQuality = round(($saveQuality/100) * 9); // Scale quality from 0-100 to 0-9
				$invertScaleQuality = 9 - $scaleQuality; // Invert quality setting as 0 is best, not 9
				imagepng($this->imageResized, $savePath, $invertScaleQuality);
				break;
			case IMAGETYPE_GIF:
				$this->image_gif($savePath);
				break;
			case IMAGETYPE_JPEG:
				imagejpeg($this->imageResized, $savePath, $saveQuality);
				break;
		}

		if($main_resource){
			$this->img_resource = $this->imageResized;
			$this->info['width']  = @imagesx($this->img_resource);
			$this->info['height'] = @imagesy($this->img_resource);
		}
		else{
			@imagedestroy($this->imageResized);
			$this->ani_imageResized = null;
		}

	}


	/***
	 * image_gif
	 * Save a gif image file
	 */
	private function image_gif($savePath){
		if($this->ani_gif && is_array($this->ani_imageResized) && count($this->ani_imageResized) > 1){
			$newa = array();
			foreach($this->ani_imageResized as $i){
				ob_start();
				imagegif($i);
				$gifdata = ob_get_clean();
				$newa[] = $gifdata;
			}

			if($gifmerge = new GIFEncoder($newa, $this->ani_delays, 9999, $this->ani_disposal, $this->ani_transparent['r'], $this->ani_transparent['g'], $this->ani_transparent['b'], "bin")){
				FWrite(FOpen($savePath, "wb"), $gifmerge->GetAnimation() );
			}else{
				imagegif($this->ani_imageResized[0], $savePath);
			}
        }else{
			imagegif($this->imageResized, $savePath);
		}
	}


	/***
	 * Settings functions
	 */
	public function stretchSmallImages($var=true){$this->stretch = $var;}
	public function DontkeepImageTransparency($var=false){ $this->transparency = $var;}
	public function show_MemoryLimit ($var=false){ $this->showMLimit = ($var?true:false);}
	public function set_Memory_Limit ($var=false){$this->setMemoryLimit = ($var?true:false);}
	public function set_Tweak_Factor($var=1.8){$this->TweakFactor = ((int)$var?$var:1.8);}
	public function set_accepted_formats($var){$this->accepted_formats = $var;}
 	public function set_max_filesize($var){$this->max_filesize = $var; }
	public function set_min_dimensions($var){$this->min_dimensions = $var;}
 	public function set_max_dimensions($var){$this->max_dimensions = $var;}
	public function set_upload_dir($var){$this->upload_dir = $var;}
	public function set_temp_dir($var){$this->temp_dir = $var;}
	public function set_allow_animation($var){$this->ani_allow = ($var?true:false);}
	public function set_id_length($var=4){$this->image_id_length = ((int)$var?$var:4);}


	/***
	 * Get file ext from mine
	 * $imagetype - image/png (file mine)
	 * $requireDot - false|true
	 */
	private function get_extension($imagetype, $requireDot = false){
		if(empty($imagetype)) return false;
		if(isset($this->accepted_formats[$imagetype])) return ($requireDot ? '.' : '').$this->accepted_formats[$imagetype];
		return false;
	}


	/***
	 * very simple hash function to generate a random string
	 * Number of Possible Combinations
	 *		3 digit code 46656
	 *		4 digit code 1679616
	 *		5 digit code 60466176
	 */
	private function create_hash($length=5,$check=false){
		$valid	= false;
		$hash	= '';
		while(!$valid){
			for($i=0;$i<$length;++$i){
			// if you want to add letters you will have to change the id to varchar instead of int
				$possibleValues = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
			// pick a random character from the possible ones
	    		$hash .= substr($possibleValues, mt_rand(0, strlen($possibleValues)-1), 1);
				
			}
			$valid = true;
			if($check){
				// checks if the hash allready exists in the db
				$files = glob($this->upload_dir.$hash.".{jpg,gif,png}", GLOB_BRACE);
				if(!empty($files)){
					$valid = false;
				}
			}
		}
		return $hash;
	}

	public function __destruct() {
		foreach ($this as $key => $value) {
			unset($this->$key);
		}
	} 

}


/***
 * Missing functions
 */
if( ! function_exists('exif_imagetype') ){
	function exif_imagetype ( $f ){
		if ( false !== ( list(,,$type,) = getimagesize( $f ) ) )
			return $type;
		return IMAGETYPE_PNG; // meh
	}
}
