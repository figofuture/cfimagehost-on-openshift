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
 *   Used For:     database backup functions
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/

function backup_unzip($zipfile='',$err_show=1){
	require_once "unzip.class.php";
	
	if(!file_exists(CFBACKUPPATH.$zipfile)){
		if($err_show) user_feedback('error','can\'t find file!','backup_unzip');
		return false;
	}
	
	$file_ext = explode(".", $zipfile);
	$file_type = explode("_", $zipfile);
	if (end($file_ext)  == 'zip'){
		if($file_type[0]=='imgdb'){
			$unzip_to = CFDATAPATH; //CFBACKUPPATH.'uncompressed';
		}elseif($file_type[0]=='bandwidth' && $err_show){
			$unzip_to = CFBANDWIDTHPATH;
		}
	}else{
		if($err_show) user_feedback('error','can\'t Unzip file!','backup_unzip');
		return false;
	}

	$zip = new dUnzip2(CFBACKUPPATH.$zipfile);
	$zip->unzipAll($unzip_to);
	if($zip->getLastError()){
		if($err_show) user_feedback('success','Unzipped files!','backup_unzip');
		return true;
	}
	if($err_show) user_feedback('error','can\'t Unzip file!','backup_unzip2');
	return false;
}

function backup_imgdb($backup=1,$err_show=1){
	require "zip.class.php"; // Get the zipfile class
	if($backup==1){
		$files_to_zip	= array(CFDATAPATH.'imgdb.db');
		$savt_as		= CFBACKUPPATH.'imgdb_backup_'.date("Y").'-'.date("m").'-'.date("d").'_'.date("H.i.s").'.zip';
	}else{
		$files_to_zip	= array(CFBANDWIDTHPATH."*.db");
		$savt_as		= CFBACKUPPATH.'bandwidth_backup_'.date("Y").'-'.date("m").'-'.date("d").'_'.date("H.i.s").'.zip';
	}
	$test = new zip_file($savt_as);
	$test->set_options(array(
							'overwrite'	=> 1,// Overwrite /var/www/htdocs/test/test.tgz if it already exists
							'inmemory'	=> 0,// Create archive in memory
							'recurse'	=> 0,// Do not recurse through subdirectories
							'storepaths'=> 0 // Do not store file paths in archive
							));
// Add lib/archive.php to archive
	$test->add_files($files_to_zip);

// Create file
	$test->create_archive();
	$test->download_file();

// Check for errors (you can check for errors at any point)
	if(!$test->errors){
		if($err_show) user_feedback('success','Backup done!','backup_imgdb');
		return true;// Process errors here
	}
	if($err_show) user_feedback('error','Can\'t made backup zip file!','backup_imgdb');
	return false;
}

function getDirectoryList(){
// Grab all files from the desired folder
	$files = glob( CFBACKUPPATH.'*.zip' );

// Sort files by modified time, latest to earliest
// Use SORT_ASC in place of SORT_DESC for earliest to latest
	array_multisort(
				array_map( 'filemtime', $files ),
				SORT_NUMERIC,
				SORT_DESC,
				$files
					);
	$file_index['imgdb'] = array();
	$file_index['bandwidth'] = array();
	foreach( $files as $file){
	$file_name = basename($file);
		$file_type = explode("_", $file_name);
			if($file_type[0]=='imgdb'){
				$file_index['imgdb'][] = $file_name;
			}elseif($file_type[0]=='bandwidth'){
				$file_index['bandwidth'][] =$file_name;
			}
	}
	return $file_index;
}

function get_last_added($file_type,$err_show=1){
	$backup_list = getDirectoryList();
	if(isset($backup_list[$file_type])){
		return $backup_list[$file_type][0];
	}
	return false;
}

function remove_backup($file){
	if(!file_exists(CFBACKUPPATH.$file)){
		user_feedback('error',_T('admin_db_backup_not_found'),'remove_backup');
		return false;
	}
	@unlink(CFBACKUPPATH.$file);
	if(!file_exists(CFBACKUPPATH.$file)){
	user_feedback('success',_T('admin_db_backup_deleted'),'remove_backup');
		return  true;
	}
	user_feedback('error',_T('admin_db_backup_delete_error'),'remove_backup');
	return false;
}

function remove_old_blackups($ntk = 7){
	$backup_list = getDirectoryList();
	foreach( $backup_list as $file){
		if(count($file) > $ntk){
			$remove_list = array_slice($file, $ntk);
			foreach( $remove_list as $rfile){
				unlink(CFBACKUPPATH.$rfile);
			}
		}
	}
}

function download_backup($file){
	if(!file_exists(CFBACKUPPATH.$file)){
		user_feedback('error',_T('admin_db_backup_not_found'),'download_backup');
		return false;
	}
	downloadFile(CFBACKUPPATH.$file);
}

function downloadFile( $fullPath ){
	// Must be fresh start
	if( headers_sent()) die('Headers Sent');

	// Required for some browsers
	if(ini_get('zlib.output_compression')) ini_set('zlib.output_compression', 'Off');

	// File Exists?
	if( file_exists($fullPath) ){

		// Parse Info / Get Extension
		$fsize = filesize($fullPath);
		$path_parts = pathinfo($fullPath);
		$ext = strtolower($path_parts["extension"]);

		// Determine Content Type
		switch ($ext) {
			case "pdf": $ctype="application/pdf"; break;
			case "exe": $ctype="application/octet-stream"; break;
			case "zip": $ctype="application/zip"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":
			case "jpg": $ctype="image/jpg"; break;
			default: $ctype="application/force-download";
		}

		header('Content-Description: File Transfer');
		header('Content-Type: application/zip');
		header('Content-Disposition: attachment; filename='.basename($fullPath));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' .$fsize);
		ob_clean();
		flush();
		readfile( $fullPath );

	}else{
		die('File Not Found');
	}
}