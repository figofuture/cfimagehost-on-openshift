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
 *   Used For:     Text File database class and functions
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/
 
class array_db{

// holds the loaded DB
	private $db;
	private $db_address;
	var $temp;
	private $UseBackup;

	/**
	 * array_db(load db file/array)
	 *
	 * @param address/array $db_load
	 */
	public function array_db($db_load=null,$backup=false){
		$this->UseBackup = $backup;
		if(!is_array($db_load) && strlen($db_load) > 0){
			$this->db_address = $db_load;
			$this->db = $this->load_db();
			$this->dbcount = count($this->db);
		}elseif(is_null($db_load)){// is only use in search.php
			$this->db_address = null;
			$this->db = array();
			$this->dbcount = 0;
		}
	}

	/**
	 * SET DB (repace DB with new db)
	 *
	 * @param array $new_db
	 */
	public function set_db($new_db){
		if(is_array($new_db))
			$this->db = $new_db;
	}

	/**
	 * fetch DB
	 *
	 * @return array
	 */
	public function fetch_all($start=null,$numItems=null){
		if(!is_null($start) && !is_null($numItems))	return array_slice($this->db, $start, $numItems);
		return $this->db;
	}

	/**
	 * DB COUNT
	 *
	 * @param key to look in ($key)
	 * @param value to look for ($value)
	 *
	 * @return string
	 */
	public function db_count($key=null,$value=null){
		$count_items = 0;
		if($key!=null && $value!=null){
			foreach ($this->db as $k => $v){
				if (array_key_exists($key,$v) && $v[$key] == $value){
					$count_items++;
				}
			}
		}else
			$count_items = count($this->db);
		return $count_items;
	}

	/**
	 * Random rows
	 *
	 * @Param value $nuber_of number of rows to find (default is 1)
	 *
	 * @return array [row]
	 */
	public function rand_item($number = 1,$items=null){

		if($this->db_count('private',0) < $number){
			return false;
		}

		$rand_keys = array_rand($this->db, $number);

		if(!is_null($items)){
			foreach($rand_keys as $index){
				$result[] = $this->fetch_key_value($index);
			}
		}else
			$result = $rand_keys;

		return $result;
	}

	/**
	 * DB not empty
	 *
	 * @return boolean
	 */
	public function db_not_empty(){
		if (empty($this->db))
			return false;
		return true;
	}

	/**
	 * save_db_now
	 *
	 * @param address $address(if no address then save to the address where it was loaded from)
	 * 
	 * @return boolean
	 */
	public function save_db_now($address = null){
		if ($address == null)
			return $this->save_db();
		else
			return $this->save_db($address);
		
	}

	/**
	 * order db by
	 *
	 * @param name $field
	 * @param string $order (DESC|321)(ASC|123)
	 */
	public function order_by($field, $order = 123) {
		if ($order == 'ASC' || $order == 123)$order = '$b,$a';
		if ($order == 'DESC' || $order == 321)$order = '$a,$b';
		$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
		@usort($this->db, create_function($order, $code));
	}

	/**
	 * item_exists
	 *
	 * @param name $field_key
	 * @param string $value_key
	 * @return boolean
	 */
	public function item_exists($field_key,$value_key){
		$row_key = $this->fetch_key($field_key,$value_key);
		if(!empty($row_key) || $row_key === 0)
			return true;
		return false;
	}

	/**
	 * fetch row
	 *
	 * @param name $field_key
	 * @param string $value_key
	 * @return array
	 */
	public function fetch_row($field_key,$value_key){
		$row_key = $this->fetch_key($field_key,$value_key);
		if(!empty($row_key) || $row_key === 0){
			return $this->db[$row_key];
		}
	}

	/**
	 * rand_db 
	 *
	 * @param 
	 */
	public function rand_db(){
		$rand_keys = array_rand($this->db,1);
		
		return $this->random_array_element($this->db);// $this->db[$rand_keys] ;
	}
	function random_array_element(&$a){
		mt_srand((double)microtime()*1000000);  
	// get all array keys:
		$k = array_keys($a);
	// find a random array key:
		$r = mt_rand(0, count($k)-1);
		$rk = $k[$r];
	// return the random key (if exists):
		return isset($a[$rk]) ? $a[$rk] : '';
	}

	/**
	 * fetch value
	 *
	 * @param name $field_key
	 * @param string $value_key
	 * @param name $return_value
	 * @return string
	 */
	public function fetch_value($field_key,$value_key,$return_value){
		$row_key = $this->fetch_key($field_key,$value_key);
		if((!empty($row_key) || $row_key === 0) && array_key_exists($return_value,$this->db[$row_key]))
			return $this->db[$row_key][$return_value];
	}

	/**
	 * set_value (where $field_key == $value_key SET $set_key to $set_value)
	 *
	 * @param name $field_key
	 * @param string $value_key
	 * @param name $set_key
	 * @param string $set_value
	 */
	public function set_value($field_key,$value_key,$set_key,$set_value){
		$row_key = $this->fetch_key($field_key,$value_key);
		if(!empty($row_key) || $row_key === 0)
			$this->db[$row_key][$set_key] = $set_value;
	}

	/**
	 * Remove Row (where $field_key == $value_key)
	 *
	 * @param name $field_key
	 * @param string $value_key
	 * 
	 * @return boolean
	 */
	public function remove_row($field_key,$value_key,$rows=null,$eq2=null){
		if ($rows == null){
			$row_key = $this->fetch_key($field_key,$value_key);
			if(!empty($row_key) || $row_key === 0){
				unset($this->db[$row_key]);
				return true;
			}
		}else{
			foreach ($this->db as $k => $v){
				if (is_null($eq2) && array_key_exists($field_key,$v) && $v[$field_key] == $value_key){
					unset($this->db[$k]);
				}
				if (!is_null($eq2) && array_key_exists($field_key,$v) && $v[$field_key] != $value_key){
					unset($this->db[$k]);
				}
				
			}
		}
	}

	/**
	 * Add Row
	 * @param array $row
	 * @return boolean
	 */
	public function add_row($row){
		if (!empty($row)){
			$this->db[] = $row;
			return true;
		}
	}


	/**
	 * save DB file if exists else return empty array
	 *
	 * @param address $fileaddress default db address
	 * @return boolean
	 */
	private function save_db($fileaddress = null){
		if($fileaddress==null)$fileaddress = $this->db_address;
		$fp = fopen($fileaddress, 'w+') or die("I could not open ".$fileaddress);
		while (!flock($fp, LOCK_EX | LOCK_NB)) {
			//Lock not acquired, try again in:
			usleep(round(rand(0, 100)*1000)); //0-100 miliseconds
		}
		fwrite($fp, base64_encode(serialize($this->db)));
		flock($fp, LOCK_UN); // release the lock
		fclose($fp);
		return true;
	}

	/**
	 * db_search
	 * @param array() $search(
	 * @param array() $key_in(key to look in - can be empty to search all keys)
	 * @return array()
	 */
	public function db_search($search,$key_in=null){
		$found_inx = array();
		$split_search = preg_split("/[\s,]+/",$search );

		foreach ($this->db as $k => $v) {
			foreach ($split_search as $key => $val) {
				if( $val!='' && strlen ( $val ) > 0 ){
					if ($this->array_search_bit($val,$this->db[$k],($key_in==null?'':$key_in)))
						if(!in_array($k,$found_inx)){
							$found_inx[] = $k;
							$found_img[] = $this->db[$k];
						}
				}
			}
		}
		return isset($found_img)?$found_img: array();
	}

	/**
	 * Load DB file if exists else return empty array
	 *
	 * @param address $fileaddress default db address
	 * @return array()
	 */
	private function load_db($fileaddress = null){
		if($fileaddress==null)$fileaddress = $this->db_address;
		if (file_exists($fileaddress)){
			$fp = fopen($fileaddress, 'r') or die("I could not read ".$fileaddress);
			while (!flock($fp, LOCK_EX | LOCK_NB)) {
				//Lock not acquired, try again in:
				usleep(round(rand(0, 100)*1000)); //0-100 miliseconds
			}
			$filearray = @unserialize(base64_decode(@fread($fp, filesize($fileaddress))));
			flock($fp, LOCK_UN);
			fclose($fp);
			if($backup_file = $this->check_DB($filearray,$fileaddress)){
				return $backup_file; // backup found and needed
			}else{
				return $filearray; // backup not found or not needed
			}
		}
	 //db file not found
		else{
			$filearray = array();
			return $filearray;
		}
	}

	private function check_DB($db,$fileaddress){
		if(!$this->UseBackup||is_array($db)){
			return false; // no need to use backup
		}
		require_once('lib/backup.class.php');
		$backup_file =  get_last_added(basename ($fileaddress,'.db'),1);
		if(backup_unzip($backup_file,0)){
			$autouse=1;
			require_once('admin/admin_rmid.php');
			return $this->load_db();
		}
		return false; // no need to use backup/can't find a backup
	}

	/**
	 * fetch row key
	 *
	 * @param name $field_key
	 * @param string $value_key
	 * @return string
	 */
	private function fetch_key($field_key,$value_key){
		if ($field_key ==null || $value_key ==null)return;
		if (!is_array($this->db))return;
		foreach ($this->db as $k => $v){
			if (array_key_exists($field_key,$v) && $v[$field_key] == $value_key){
				return $k;
			}
		}
	}

	/**
	 * fetch key value
	 *
	 * @param name $key
	 * @return value(string)
	 */
	public function fetch_key_value($key){
		if(array_key_exists($key,$this->db))
			return $this->db[$key];
	}

	/**
	 * array_search_bit
	 *
	 * @param string $search
	 * @param array $db
	 * @param array $searchIn (array of keys to search in can be empty to search all keys)
	 * @return boolean
	 */
	private function array_search_bit($search, $db, $searchIn=array()){
		foreach ($db as $k => $v){
			if (in_array(strtolower($k), $searchIn) || empty($searchIn)){
				if (strpos(strtolower($v), strtolower($search)) !== false){
					return true;
				}
			}
		}
		return false;
	}
	
	public function __destruct() {
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    } 
}

///////////////////////////////////////////////////////////////////////////////
// Image db

function loadDBGlobal(){
	global $IMAGEDB,$settings;
	if(empty($IMAGEDB)){
		$IMAGEDB = new array_db(CFDBIMGPATH,$settings['SET_BACKUP_AUTO_USE']);
	}
	return $IMAGEDB;
}

function findImage($where,$is){
	global $IMAGEDB;
	if(is_object($IMAGEDB)){
		$db=$IMAGEDB;
	}else{
		$db=loadDBGlobal();
	}

	if ($fp=$db->db_search($is,array($where))){
		return $fp;
	}
	return false;
}

function addNewImage($imgArray){
	global $IMAGEDB;
	if(is_object($IMAGEDB)){
		$db=$IMAGEDB;
	}else{
		$db=loadDBGlobal();
	}
	if($db->add_row($imgArray) && $db->save_db_now()){
		return true;
	}
	return false;
}

// use in admin edit image
function db_update($id,$param){
	global $IMAGEDB;
	if(is_object($IMAGEDB)){
		$db=$IMAGEDB;
	}else{
		$db=loadDBGlobal();
	}
	foreach($param as $k => $v){
		$db->set_value('id',$id,$k,cl($v));
	}
	if($db->save_db_now()){
		return true;
	}
	return false;
}

function db_get_image($id,$where = 'id'){
	global $IMAGEDB;
	if(is_object($IMAGEDB)){
		$db=$IMAGEDB;
	}else{
		$db=loadDBGlobal();
	}

	if ($item = $db->fetch_row($where,$id)){
		return $item;
	}
	return false;
}

function removeImageDb($id,$where='id'){
	global $IMAGEDB;
	if(is_object($IMAGEDB)){
		$db=$IMAGEDB;
	}else{
		$db=loadDBGlobal();
	}
	if ($db->remove_row($where,$id)){
		if($db -> save_db_now()){
			return true;
		}
	}
	return false;
}

function imageList($startFrom=0,$numberToList=null,$orderBy='added',$order = 'ASC',$searchFor=null){
	global $settings,$ADD_DB_REPORT,$DBCOUNT,$DbPrivate, $IMAGEDB;
	if(is_object($IMAGEDB)){
		$db=$IMAGEDB;
	}else{
		$db=loadDBGlobal();
	}
	$imageList = false;
	if($db->db_not_empty()){

	// do image search
		if(!is_null($searchFor)){
			$db->set_db($db->db_search($searchFor,array('alt','name')));
		}

	// order image db by new to old
		$db->order_by($orderBy,$order);//ASC|DESC
		$img_db = $db->fetch_all();

	//count private images
		$DbPrivate = $db->db_count('private',1);
	//remove private images
		if(!checklogin() && $settings['SET_PRIVATE_IMG_ON']){
			$img_db = remove_row($img_db,'private',1);
		}

	// setup global $DBCOUNT
		$DBCOUNT = count($img_db);

		if(!is_numeric($startFrom) && $startFrom == 'rand'){
			if(is_null($numberToList)){
				$numberToList = 4;
			}
			if($DBCOUNT >=$numberToList){
				$imageList = uniqueRand($numberToList,$img_db);
			}
		}else{
			if(is_null($numberToList))$numberToList = $settings['SET_IMG_ON_PAGE'];
			if($numberToList == 'all')$numberToList = null;
			$imageList = array_slice($img_db, $startFrom,$numberToList);
		}
	}

	return $imageList;
}

function uniqueRand($n, $db){
	$ind = array();
	$dbcount = count($db);
	for($i=0;$i<$n;++$i){
		$nID = mt_rand(0,$dbcount);
		if(!isset($ind[$nID]) && isset($db[$nID])){
			$return[$i] = $db[$nID];
			$ind[$nID] = $nID;
		}else{
			--$i;
		}
	}
	return $return;
}

// used to remove private and reported images
	function remove_row($db,$field_key,$value_key){
		foreach ($db as $k => $v){
			if (array_key_exists($field_key,$v) && $v[$field_key] == $value_key){
				unset($db[$k]);
			}
		}
		return $db;
	}


function db_maxedBandwidth($id){
	global $settings;
	$db = new array_db(CFBANDWIDTHPATH.$id.'_imgbw.db');
	$dbAll = $db->fetch_all();
	if (empty($dbAll)) return false; 
	if (!isset($dbAll['lr_date']) || !isset($dbAll['lr_bandwidth'])) return false; 
	
// workout the last reset date
	if ($settings['SET_BANDWIDTH_RESET'] == 'm') $resetdate = strtotime('01 '.date('M Y'));
	else $resetdate = strtotime('last monday', strtotime('tomorrow'));
// if image last reset date is old then the new reset date then reset the info
	if($dbAll['lr_date'] <$resetdate){
		$dbAll['lr_date'] = $resetdate;
		$dbAll['lr_image'] = 0;
		$dbAll['lr_thumb_mid'] = 0;
		$dbAll['lr_thumb'] = 0;
		$dbAll['lr_gallery'] = 0;
		$dbAll['lr_bandwidth'] = 0;
		$db->set_db($dbAll);
		$db->save_db_now();
		return false; 
	}
	return ($settings['SET_MAX_BANDWIDTH']*1048576)>$dbAll['lr_bandwidth']?false:true; 
}

function search($array, $key, $value){
	$results = array();
	search_r($array, $key, $value, $results);
	return $results;
}

function search_r($array, $key, $value, &$results){
	if (!is_array($array)) return;
	if (isset($array[$key]) && $array[$key] == $value) $results[] = $array;
	foreach ($array as $subarray) search_r($subarray, $key, $value, $results);
}

///////////////////////////////////////////////////////////////////////////////
// Banned user

function db_loadDBGlobalBanned(){
	global $ImageDbBanned;
	if(empty($ImageDbBanned)){
		$ImageDbBanned = new array_db(CFDBBANPATH);
	}
	return $ImageDbBanned;
}

function db_list_banned_uers(){
	$db_ban = db_loadDBGlobalBanned();
	if($db_ban->db_not_empty()){
		return $db_ban ->fetch_all();
	}else{
		return false;
	}
}

function db_ban_user($ip,$description){
	$db_ban = db_loadDBGlobalBanned();
	$db_ban->add_row(array(
							'ip'	=> cl($ip),
							'des'	=> cl($description),
							'date'	=> time()
							));
	if ($db_ban->save_db_now()){
		return true;
	}
	return false;
}

function db_remove_ban($ip){
	$db_ban = db_loadDBGlobalBanned();
	if($db_ban->remove_row('ip',cl($ip)) && $db_ban->save_db_now()){
		return true;
	}
	return false;
}

function db_isBanned($ip=null){
	$db_ban = db_loadDBGlobalBanned();
	if(is_null($ip)){
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	if ($db_ban->item_exists('ip',$ip)){
		return true;
	}
	return false;
}

///////////////////////////////////////////////////////////////////////////////
// Report Image

function db_imageReportList(){
	$db=loadDBGlobal();
	if($list = $db->db_search(1,array('report'))){
		return $list;
	}else{
		return false;
	}
}

function db_remove_from_report($id){
	$db=loadDBGlobal();
	$db->set_value('id',$id,'report',0);
	if($db->save_db_now()){
		return true;
	}
	return false;
}

function db_addReport($id){
	$db=loadDBGlobal();
	$db->set_value('id',$id,'report',1);
	if($db->save_db_now()){
		return true;
	}
	return false;
}

////////////////////////////////
// image counter

function db_imageCounterList($formDate=null,$id=null){
	$dbCounter = new array_db(CFBANDWIDTHPATH.$id.'_imgbw.db');
		
	if(!$dbCounter ->db_not_empty()){
		return array();
	}
	$dbCounterAll = $dbCounter->fetch_all();
	if(is_null($formDate)){
		return $dbCounterAll;
	}
	$newDbCounter = array();
	// only add itmes from last reset to now
	foreach($dbCounterAll as $k => $v){
		if($v['date'] >= $formDate && (is_null($id) || $id == $v['id'])){
			$newDbCounter[$k] = $v;
		}
	}
	return $newDbCounter;
}

function db_imageCounterSave($image,$imgType){
	$db =  new array_db(CFBANDWIDTHPATH.$image['id'].'_imgbw.db');

	$icl = $db->fetch_all();

	if(empty($icl) || !isset($icl['id'])){
		$icl = array(
					'id' => $image['id'],
					'date' => time(),
					'image' => 0,
					'thumb_mid' => 0,
					'thumb' => 0,
					'gallery' => 0,
					'bandwidth' => 0,
					'lr_date' => time(),
					'lr_image' => 0,
					'lr_thumb_mid' => 0,
					'lr_thumb' => 0,
					'lr_gallery' => 0,
					'lr_bandwidth' => 0,
					);
	}

	switch($imgType){
		case 1:
			$image_typ = 'size';
			$icl['image']++;
			$icl['lr_image']++;
			break;
		case 2:
			$image_typ = 'thumbsize';
			$icl['thumb_mid']++;
			$icl['lr_thumb_mid']++;
			break;
		case 3:
			$image_typ = 'sthumbsize';
			$icl['thumb']++;
			$icl['lr_thumb']++;
			break;
		case 4:
		default:
			$image_typ = 'thumbsize';//gallery
			$icl['gallery']++;
			$icl['lr_gallery']++;
			break;
	}
	$icl['date'] = time();
	$icl['bandwidth'] += $image[$image_typ];
	$icl['lr_bandwidth'] += $image[$image_typ];

	$db->set_db($icl);
	if ($db->save_db_now()){
		return true;
	}
	return false;
}


function db_addCounter($param){
	$db =  new array_db(CFBANDWIDTHPATH.$param['id'].'_imgbw.db');
	$db->add_row($param);
	if ($db->save_db_now()){
		return true;
	}
	return false;
}
