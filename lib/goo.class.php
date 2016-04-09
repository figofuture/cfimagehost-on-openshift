<?php if(!defined('cfih') or !cfih) die('This file cannot be directly accessed.');
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
 *   Used For:     Google URL Shortener Class
 *
 *************************************************************************************************************/
class GoogleUrlApi{
	
	private $APIKey;//application key
	private $API = "https://www.googleapis.com/urlshortener/v1/url";//api url

	function GoogleUrlApi($apiKey=""){
		if ($apiKey != ""){
			$this->APIKey = $apiKey;
		}
	}

	function expand($shortURL , $analytics = false){
		$url = $this->API.'?shortUrl='.$shortURL;
		if ($this->APIKey)	$url .= '&key='.$this->APIKey;
		if ($analytics)		$url .= '&projection=FULL';
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);  
		curl_close($ch);
		$array = json_decode($result, true);
		return $array;    
	}

	function shorten($longURL){
		$vars = "";
		if ($this->APIKey) $vars .= "?key=$this->APIKey";
		$ch = curl_init($this->API.$vars);  
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"longUrl": "' . $longURL . '"}');
		$result=curl_exec($ch);  
		curl_close($ch);
		$response = json_decode($result, true);
		return isset($response['id']) ? $response['id'] : false;
	}
}