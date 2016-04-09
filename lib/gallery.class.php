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
 *   Used For:     Gallery Page
 *   Last edited:  23/04/2012
 *
 *************************************************************************************************************/

class gallery{

// gallery var
	public $imgOnPage;
	public $pageNmber = null;
	public $imagesFound = 0;
	private $modRewrite = true;
	private $siteUrl;

// search var
	private $stopwords = array(" find ", " about ", " me ", " ever ", " each ", " the ", " jpg ", " gif ", " png ", " bmp ");//you need to extend this big time.
	private $symbols = array('/','\\','\'','"',',','.','<','>','?',';',':','[',']','{','}','|','=','+','-','_',')','(','*','&','^','%','$','#','@','!','~','`');//this will remove punctuation
	public $searchFor = null;
	public $searchErr = false;

	function __construct() {
		global $settings;
		$this->pageNmber	= (isset($_GET['p']) && is_int((int)$_GET['p'])?$_GET['p']:1);
		$this->modRewrite	= $settings['SET_MOD_REWRITE'];
		$this->siteUrl			= $settings['SET_SITEURL'];
	}
	
	function setNumberOfImagesToShow($var){
		if(is_numeric($var)) $this->imgOnPage = $var;
	}
	
	function setPageShow($var){
		if(is_numeric($var)) $this->pageNmber = $var;
	}

	function galleryList(){
		global $DBCOUNT;
		if($this->searchErr)return array();
		$img = array();
		if($imageList = imageList((($this->pageNmber-1)*$this->imgOnPage),$this->imgOnPage,'added','ASC',$this->searchFor)){
			foreach($imageList as $k=>$image){
				$img[] = array(	'thumbSmallUrl'	=> imageAddress(3,$image,"dt"), // get image address
											'thumbLink'			=> imageAddress(2,$image,"pm"), // get thumb page address
											'imageAlt'				=> ($image['alt'] !="" ? $image['alt']:$image['name']), //see if there is a alt(title) if not use the image name
											'id'						=> $image['id']
										);
			}// endfor
		}
		$this->imagesFound = $DBCOUNT;
		return $img;
	}

	function pagination($paginationShow=null){
		if(!is_numeric($paginationShow))$paginationShow = null;
	// setup pagination address
		$pagination_address = $this->siteUrl.'gallery';
		if(!is_null($this->searchFor)) $pagination_address .= '.php?p=%1$s&search='.str_replace(' ','_',$this->searchFor);
		elseif($this->modRewrite) $pagination_address .= '/%1$s/';
		else $pagination_address .= '.php?p=%1$s';
	// page pagination
		$pagination = pagination($this->pageNmber-1, $this->imgOnPage, $this->imagesFound,$pagination_address,$paginationShow);
		return $pagination;
	}


	/***
	 * Search functions
	 */
	function gallerySearch($search){

		if(empty($search)){
			$this->searchErr = true;
			return _T("site_search_err_blank");
		}

		$search = $this->parseString($search);

		if(empty($search)){
			$this->searchErr = true;
			return _T("site_search_err_blank");
		}

		$newSearch = '';
		foreach(array_unique(split( " ",$search)) as $k => $v ){
			if(strlen($v) >= 3)
				$newSearch .= ''.$v.' ';
		}

		$newSearch = substr($newSearch,0, (strLen($newSearch) -1));
		if (strlen($newSearch) < 3){
			$this->searchErr = true;
			return _T("site_search_err_short");
		}

		$this->searchFor = $newSearch;
		return true;
	}

	private function parseString($string) {
		$string = ' '.$string.' ';
		$string = $this->removeStopwords($string);
		$string = $this->removeSymbols($string);
		return $string;
	}
	
	private function removeStopwords($string) {
		for ($i = 0; $i < sizeof($this->stopwords); $i++) {
			$string = str_replace($this->stopwords[$i],' ',$string);
		}
		return trim($string);
	}

	private function removeSymbols($string) {
		for ($i = 0; $i < sizeof($this->symbols); $i++) {
			$string = str_replace($this->symbols[$i],' ',$string);
		}
		return trim($string);
	}
	
	
}