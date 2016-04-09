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
 *   Used For:     Site setup
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

// ***** do not edit below this line ***** //

// Start session
	session_name();
	if (!session_start()) die('session error');

// Define directory address
	if(!defined('CFROOTPATH')) define('CFROOTPATH', dirname(__DIR__).'/');

// load fixed settings
	if(!require(CFROOTPATH.'inc/config.php')) die('Can\'t find config.php');

// check for install folder
// Make sure the install folder is deleted for normal usage
// you can remove this if you have installed the script
        if(!file_exists(CFINSTALLED)){
                header('Location: install/index.php');
                exit();
        }

//debug, show PHP errors
	if($debug){
		error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
	}
// Hide all error messages from the public
	else{
		error_reporting(E_ALL^E_NOTICE);
		ini_set('display_errors', 0);
	}

	if(!require(CFINCPATH.'functions.php')) die('Can\'t find functions.php');
	if(!require(CFSETTINGS)) die("Can't find setings or installer!");
	if(!require(CFLIBPATH.'arraydb.class.php')) die('Can\'t find arraydb.class.php');


// auto backup image database
	if($settings['SET_BACKUP_AUTO_ON']){
		if(time()>($settings['SET_LAST_BACKUP_IMAGE']+($settings['SET_BACKUP_AUTO_TIME']*(24 * 60 * 60)))){
				require_once(CFLIBPATH.'backup.class.php');
				backup_imgdb(1,0);
				$settings['SET_LAST_BACKUP_IMAGE'] =time();
				saveSettings(CFSETTINGS,$settings);
				remove_old_blackups();
		}
	}

//run auto delete ( remove old and un-viewed images)
	if($settings['SET_AUTO_DELETED']){
		autoDeleted();
	}


