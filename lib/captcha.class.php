<?php
/**************************************************************************************************************
 *
 *   CF Image Hosting
 *   -----------------
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
 *   Used For:     Contact form Captcha
 *   Last edited:  07/03/2012
 *
 *************************************************************************************************************/
 
// settings
if(!isset($settings)){
// load settings
	define( 'CFIHP', '1.5' );
	@include_once(CFSETTINGS); 
}


// Cookie timer
	define('COOKIE_TIMER', (5*60) );

// Cookie Salting Pick a random code
	define('SALTING', $settings['SET_SALTING']);

// check_captcha() Error Messages
//	define('ERROR_MESSAGE_CAPTCHA',				'Verification code can\'t be blank');
//	define('ERROR_MESSAGE_CAPTCHA_INVALID',		'Verification code is invalid');
//	define('ERROR_MESSAGE_CAPTCHA_TIMED_OUT',	'Sorry the verification code is only valid for '.COOKIE_TIMER.' minutes');
//	define('ERROR_MESSAGE_CAPTCHA_COOKIE',		'No captcha cookie given. Make sure cookies are enabled.');


//////////////////////////////////////////////////////////////////////////////////////////////////
// Check Captcha


/**
 * Holds any errors from check_captcha
 * you could use it like this
 * <div style="color:red"><?=$error_captcha;?></div>
 */
	$captcha_error = null;

/**
 * check_captcha()
 *
 * @param string $captcha
 * @param boolean $timer (on|off)(true|false)
 * @return boolean
 */
function check_captcha($captcha,$timer = true){
	global $error_captcha;

	$captcha = htmlspecialchars(stripslashes(trim($captcha)));

	if(empty($captcha)){
		$error_captcha = ERROR_MESSAGE_CAPTCHA;
		remove_cookie();
		return false;

	}elseif (isset($_COOKIE['Captcha']) ){
		list($Hash, $Time) = explode('.', $_COOKIE['Captcha']);
		if ( md5(SALTING.$captcha.$_SERVER['REMOTE_ADDR'].$Time) != $Hash ){
			$error_captcha = ERROR_MESSAGE_CAPTCHA_INVALID;
			remove_cookie();
			return false;
		}elseif( (time() - COOKIE_TIMER*60) > $Time && $timer){
			$error_captcha = ERROR_MESSAGE_CAPTCHA_TIMED_OUT;
			remove_cookie();
			return false;
		}

	}else{
		$error_captcha = ERROR_MESSAGE_CAPTCHA_COOKIE;
		return false;
	}

	return true;
}

function remove_cookie(){
	$domain = $_SERVER['HTTP_HOST'];
	if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
		$domain = substr($domain, 4);
	if ( substr($domain, 0, 1) != '.' )
		$domain = '.'.$domain;
	setcookie('Captcha', '', time() - 3600, '/',$domain);
	unset($_COOKIE['Captcha']); 
}


//////////////////////////////////////////////////////////////////////////////////////////////////
// make image

/**
 * the time() at the end of the address is just to keep the image from being cached
 * <img id="captcha_img" src="./captcha/cf.captcha.php?img=<?=time();?>" />
 *
 */
	if(isset($_GET['img'])){
		$capt = new captcha;
		if(isset($_GET['bg'])){
			//$capt->transparent_bg(false);
			//$capt->bg_color($_GET['bg']);
			$capt->colorshad='d';
		}
		$capt -> add_wave();
		$capt->display();
		exit();
	}

//////////////////////////////////////////////////////////////////////////////////////////////////
// captcha Class


class captcha {

	private $UserString;
	/**
	* The width of the captcha image
	* @var integer
	*/
	private $width = 120;
	/**
	* The height of the captcha image
	* @var unknown_type
	*/
	private $height = 30;
	/**
	* The noice in percent - should be less that 20, otherwise the image will be very hard to read
	* @var integer
	*/
	private $noise = 0;
	 /**
	* Charachters to use in the captcha image.
	* Some common chars are not on the list (e.g. l and 1, 0 and O)
	* @var array
	*/
	private $string_a = array("a","b","c","d","e","f","g","h","j","k",
						  "m","n","p","r","s","t","u","v","w","x",
						  "y","z","2","3","4","5","6","7","8","9");

	private $transparent = true;

	private $bg_color = 'ffffff';

	private $amplitude = 10;
	private $period = 20;
	private $wave_text = 0;//true;
	public $colorshad = 'l';


	private function font(){
		switch(rand(1,2)){
			case 1: return dirname(__FILE__).'/font/arial.ttf'; break;
			case 2: return dirname(__FILE__).'/font/verdana.ttf'; break;
			default : return dirname(__FILE__).'/font/arial.ttf'; break; 
		}
	}

	public function transparent_bg($var){$this->transparent = ($var != true ? false : true);}
	public function bg_color($var){$this->bg_color = $var;}

	private function LoadPNG(){
		if($this->transparent){
			$im = $this->transparentImage($this->width, $this->height);
		}else{
			$im = imagecreatetruecolor($this->width, $this->height);
			$rgb = $this->html2rgb($this->bg_color);
			$bgcolor = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
			imagefill($im, 0, 0, $bgcolor);
		}
		return $im;
	}

	private function task_string(){
		$image = $this->LoadPNG(); 

		$x = 0;
		for($i = 0; $i < 5; $i++){
			$x += $this->rand_x();
			$temp = $this->string_a[rand(0,29)];
			$this->UserString .= $temp;
			imagettftext($image, $this->rand_fontsize(), $this->rand_angle(), $x, $this->rand_Y(), $this->rand_color($image), $this->font(), $temp);
		}
		return $image;
	}

	private	function transparentImage($width,$height){
		$im = imagecreatetruecolor($width, $height);
		imagealphablending($im, false);
		$color = imagecolorallocatealpha($im, 0, 0, 0, 127);
		imagefill($im, 0, 0, $color);
		imagesavealpha($im, true);
		imagealphablending($im, true);
		return $im;
	}
	private function rand_color($im){
		if($this->colorshad=='d'){
			return imagecolorallocate($im, rand(100,255), rand(100,255), rand(100,255));
		}else{
			return imagecolorallocate($im, rand(0,155), rand(0,155), rand(0,155));
		}
	}
	private function rand_angle(){return rand(-16,16);}
	private function rand_Y(){
		$yStart = $this->height * 0.6;
		$yEnd = $this->height * 0.8;
		return rand($yStart,$yEnd);
	}
	private function rand_x($length=5){
		return ($this->width/($length +2)) + mt_rand(-(($this->width/($length +2))/3), (($this->width/($length +2))/5));//rand($xStart,$xEnd);
	}
	private function rand_fontsize($length=5){	 // font size
		$fsmin = min($this->width / $length, $this->height) * 0.6;
		$fsmax = min($this->width / $length, $this->height) * 0.8;
		return rand($fsmin,$fsmax);
	}

	private function task_sum(){
		$image	= $this->LoadPNG(); 
		$x		= $this->rand_x(6);
		$sum	= rand(1,3);
		$number1= $sum != 3 ? rand(10,99) : rand(1,9);
		$number2= rand(1,9);
		imagettftext($image, $this->rand_fontsize(6), $this->rand_angle(), $x, $this->rand_Y(), $this->rand_color($image), $this->font(),($sum ==3?'':substr($number1, 0,1)));
		imagettftext($image, $this->rand_fontsize(6), $this->rand_angle(), ($x += $this->rand_x(6)), $this->rand_Y(), $this->rand_color($image), $this->font(),($sum ==3?$number1:substr($number1, -1)) );
		imagettftext($image, $this->rand_fontsize(6), 0, ($x += ($this->rand_x(6)*1.1)), $this->rand_Y(), $this->rand_color($image), $this->font(),($sum ==1?'+':($sum ==2?'-':'*')));
		imagettftext($image, $this->rand_fontsize(6), $this->rand_angle(), ($x += $this->rand_x(6)), $this->rand_Y(), $this->rand_color($image), $this->font(), $number2);
		imagettftext($image, $this->rand_fontsize(6), $this->rand_angle(), ($x += $this->rand_x(6)), $this->rand_Y(), $this->rand_color($image), $this->font(),'=');
		imagettftext($image, $this->rand_fontsize(6), $this->rand_angle(), ($x += $this->rand_x(6)), $this->rand_Y(), $this->rand_color($image), $this->font(),'?');
		$this->UserString = ($sum ==1?$number1+$number2:($sum == 2?$number1-$number2:$number1*$number2));
		return $image; 
	}

	private function cookie(){
		$time = time();
		$domain = $_SERVER['HTTP_HOST'];
		if ( strtolower( substr($domain, 0, 4) ) == 'www.' )
			$domain = substr($domain, 4);	// Fix the domain to accept domains with and without 'www.'. 
		if ( substr($domain, 0, 1) != '.' )
			$domain = '.'.$domain;	// Add the dot prefix to ensure compatibility with subdomains
		setcookie('Captcha', md5(SALTING.$this->UserString.$_SERVER['REMOTE_ADDR'].$time).'.'.$time, null, '/',$domain);
	}

	public function display(){
		switch(rand(1,2)){
			case 1:  $im = $this->task_string();break;
			case 2:  $im = $this->task_sum();	break;
			default: $im = $this->task_sum();	break;
		}

		if($this->wave_text)$im=$this->wave_area($im);
		if($this->noise)$im=$this->image_noise($im);

		$this->cookie();
		header("Content-type: image/png");
		imagepng($im,null,9);
		imagedestroy($im);
	}

	/**
	* Adds some 'noise' to the captcha image.
	* @param integer $percent - 0 for no noise
	* @return null
	*/
	public function add_noise($percent) {
		$this->noise = $percent;
	}
	
	private function image_noise($image){
		// Add some noise to the image.
		for ($i = 0; $i < $this->noise; $i++) {
			$color = imagecolorallocate($image, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			for ($j = 0; $j < $this->width * $this->height / 100; $j++) {
				imagesetpixel($image, mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
			}
		}
		return $image; 
	}

	public function add_wave() {
		$this->wave_text = 1;
	}

	private function wave_area($img){
		// Make a copy of the image twice the size
		$height2 = $this->height * 2;
		$width2 = $this->width * 2;

		if($this->transparent){
			$img2 = $this->transparentImage($width2, $height2);
			$img3 = $this->transparentImage($width2, $height2);
		}else{
			$img2 = imagecreatetruecolor($width2, $height2);
			$rgb = $this->html2rgb($this->bg_color);
			$color = imagecolorallocate($img2, $rgb[0], $rgb[1], $rgb[2]);
			imagefill($img2, 0, 0, $color);
		}

		$x=0;
		$y=0;
		imagecopyresampled($img2, $img, 0, 0, $x, $y, $width2, $height2, $this->width, $this->height);
		
		imagedestroy($img);
		if($this->period == 0) $this->period = 1;
		// Wave it
		for($i = 0; $i < $width2; $i += 2)
			imagecopy($img3, $img2, $x + $i - 2, $y + sin($i / $this->period) * ($this->amplitude), $x + $i, $y, 2, $height2);
		// Resample it down again
		$img = $this->transparentImage($this->width, $this->height);
		imagecopyresampled($img, $img3, $x, $y, 0, 0, $this->width, $this->height, $width2, $height2);
		return $img; 
	} 


	private function html2rgb($color){
		if ($color[0] == '#')
			$color = substr($color, 1);

		if (strlen($color) == 6)
			list($r, $g, $b) = array($color[0].$color[1],$color[2].$color[3],$color[4].$color[5]);
		elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
			return false;

		$r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

		return array($r, $g, $b);
	}
}

?>
