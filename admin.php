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
 *   Used For:     Admin home/menu page
 *   Last edited:  11/01/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

 	define('cfih', 'admin');
	require './inc/cfih.php';

// used to check admin pages are being loaded from here....
	$admin_page = true;

// Waht page to load?
	$act = isset($_GET['act']) ? cl($_GET['act']):'home';

// check if admin is loged in, if not show login page...
	require CFADMINPATH.'admin_login.php';

// load admin page
	switch( $act ) {

	// Delete image
		case 'remove' && (isset($_GET['d']) && cl($_GET['d']) != ''):
			$img_del_code = cl($_GET['d']);
		//removed without error
			if(removeImage($img_del_code)){
				echo json_encode(array('status'=>1,'suc'=>show_feedback(false)));
			}
		// error when removing
			else echo json_encode(array('status'=>0,'error'=>show_feedback(false)));
			exit;
			break;

	// Site ad page
		case 'ads':
			require CFADMINPATH.'admin_ads.php';
			break;
	
	// Remake image database
		case 'rmid':
			require CFADMINPATH.'admin_rmid.php';
			break;
	
	// ckech image folders
		case 'checkfoulder':
			require CFADMINPATH.'admin_check_imgs.php';
			break;

	// Settings page
		case 'set':
			require CFADMINPATH.'admin_settings.php';
			break;

	// Image list
		case 'images':
			require CFADMINPATH.'admin_imagelist.php';
			break;

	// Edit image title/private
		case 'edit':
			require CFADMINPATH.'admin_image_edit.php';
			break;

	// User ban page
		case 'ban':
			require CFADMINPATH.'admin_ban.php';
			break;
			
	// database tools
		case 'db':
			require CFADMINPATH.'admin_db_tools.php';
			break;
			
	// database tools
		case 'bulk':
			require CFADMINPATH.'admin_bulkupload.php';
			break;

	// Admin home
		case 'home':
		default:
			require CFADMINPATH.'admin_home.php';
	}
