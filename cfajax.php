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
 *   Used For:     Site ajax php
 *   Last edited:  08/01/2013
 *
 *************************************************************************************************************/
 
 	define('cfih', 'ajax');
	require './inc/cfih.php';

// check for ajax call
	if ( empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest' ){
		header('Location: '.$settings['SET_SITEURL'].'index.php?err=404');
		exit();
	}

////////////////////////////////////////////////
// Image Widgit
	if(isset($_POST['widgit']) && check_set('SET_IMAGE_WIDGIT')){
		$widgetHtml = ImageWidget(theme_setting('widgit_row',4),false);
		echo $widgetHtml;
		exit;
	}

////////////////////////////////////////////////
// Image report
	if(isset($_POST['report']) && check_set('SET_ALLOW_REPORT')){
		if(!report_img(cl($_POST['report']))){
			echo json_encode(array('status'=>0,'error'=>show_feedback(false)));
			exit;
		}
		echo json_encode(array('status'=>1,'suc'=>show_feedback(false)));
		exit;
	}

////////////////////////////////////////////////
// admin only code below

// check if user is loged in as admin
	if(!checklogin()){
		header('Location: index.php');
		exit();
	}

////////////////////////////////////////////////
// database backup

	if(isset($_POST['act']) && $_POST['act'] == 'backup'){
		$backup = (isset($_POST['id'])?$_POST['id']:1);
		require_once('lib/backup.class.php');
		if(backup_imgdb($backup)){
			echo json_encode(array('status'=>1,'suc'=>show_feedback(false)));
			exit;
		}else{
			echo json_encode(array('status'=>0,'error'=>show_feedback(false)));
			exit;
		 }
	 }

////////////////////////////////////////////////
// unzip  database backup
	if(isset($_POST['act']) && $_POST['act'] == 'unzip'){
		$file = (isset($_POST['name'])?$_POST['name']:1);
		require_once('lib/backup.class.php');
		if(backup_unzip($file)){
			echo json_encode(array('status'=>1,'suc'=>show_feedback(false)));
			exit;
		}else{
			echo json_encode(array('status'=>0,'error'=>show_feedback(false)));
			exit;
		 }
	 }

////////////////////////////////////////////////
// remove  database backup
	if(isset($_POST['act']) && $_POST['act'] == 'remove'){
		$file = (isset($_POST['name'])?$_POST['name']:1);
		require_once('lib/backup.class.php');
		if(remove_backup($file)){
			echo json_encode(array('status'=>1,'suc'=>show_feedback(false)));
			exit;
		}else{
			echo json_encode(array('status'=>0,'error'=>show_feedback(false)));
			exit;
		 }
	 }

////////////////////////////////////////////////
// download database backup
	if(isset($_GET['act']) && $_GET['act'] == 'download'){
		$file = (isset($_GET['name'])?$_GET['name']:1);
		require_once('lib/backup.class.php');
		if(download_backup($file)){
		//	echo json_encode(array('status'=>1,'suc'=>show_feedback(false)));
			exit;
		}else{
		//	echo json_encode(array('status'=>0,'error'=>show_feedback(false)));
			exit;
		 }
	 }