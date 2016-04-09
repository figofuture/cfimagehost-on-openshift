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
 *   Used For:     Script Installer
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/
 
 
// stop direct access
	if(!defined('cfih_i') or !cfih_i) exit("Direct access not permitted.");

// set time out timer to 10mins
	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

// set version
	$SET_VERSION = '1.65';

	$Err_found = false;
// Define directory address
	define('CFROOTPATH', dirname(dirname( __FILE__ )).'/');

// stop direct access
	define('cfih', 'installer');
	include_once('../inc/config.php');
	include_once('../inc/functions.php');
	flushNow();


function mb_pathinfo($filepath) {
	preg_match('%^(.*?)[\\\\/]*(([^/\\\\]*?)(\.([^\.\\\\/]+?)|))[\\\\/\.]*$%im',$filepath,$m);
	if($m[1]) $ret['dirname']=$m[1];
	if($m[2]) $ret['basename']=$m[2];
	if($m[5]) $ret['extension']=$m[5];
	if($m[3]) $ret['filename']=$m[3];
	return $ret;
}

function load_old_db($fileaddress){
	if (file_exists($fileaddress )){
		$filearray = @unserialize(file_get_contents($fileaddress));
		if(!is_array($filearray)){
			$filearray = unserialize(base64_decode(file_get_contents($fileaddress)));
		}
	}else{
		$filearray = array();
	}
	return $filearray;
}

function save_new_db($fileaddress,$db){
	$fp = fopen($fileaddress, 'w+') or die("I could not open ".$fileaddress);
	while (!flock($fp, LOCK_EX | LOCK_NB)) {
		//Lock not acquired, try again in:
		usleep(round(rand(0, 100)*1000)); //0-100 miliseconds
	}
	fwrite($fp, base64_encode(serialize($db)));
	flock($fp, LOCK_UN); // release the lock
	fclose($fp);
	return true;
}

function folder_nw($path){
	user_note('Folder Exists but Not writable<br />
		Please make sure PHP scripts can write to the <b>'.$path.'</b> folder.
		On Linux servers CHMOD this file to 777 (rwxrwxrwx).<br/>Then reload this page.',1);
}
function folder_nc($path){
	user_note('Can not create a folder.<br />
		Please make sure a folder called <b>'.$path.'</b> exists on your server
		and that PHP scripts have permission to write to it.
		On Linux servers CHMOD this folder to 777 (rwxrwxrwx).<br/>Then reload this page.',1);
}

function rmkdir($folder,$writable = true) {
	global $Err_found;
	if(@is_dir($folder)){
		if($writable){
			// testing dir is really writable to PHP scripts
			$tf = $folder.'/'.md5(rand()).".test";
			$f = @fopen($tf, "w");
			if ($f == false){
				@folder_nw($folder);
				$Err_found = true;
				return false;
			}
			fclose($f);
			unlink($tf); 
		}
		return true;
	}
	folder_nc($folder);
	$Err_found = true;
	return false;
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

/***
 * user_note( note, type, return)
 * types
 * errors = [1 | err]
 * success = [2 | suc]
 * information = [any thing other then errors & success]
 */
function user_note($mynotes,$type='info',$return = false) {
	if(empty($mynotes)) return;
	if(!is_array($mynotes)) $notes[] = $mynotes;
	else $notes = $mynotes;
	switch($type){
		case 1:
			$type = 'err';
			break;
		case 2:
			$type = 'suc';
			break;
		default:
			$type = 'info';
	}
	foreach ($notes as $k=>$note){
		$notes_html = '<div id="'.$k.'_'.$type.'" class="notification '.($type=='err'?'error':($type=='suc'?'success':'information')).'"><a class="close" href="#" alt="close" title="Close this notification"> </a>'.$note.'</div>';
	}
	if($return) return $notes_html ;
	echo $notes_html ;
}