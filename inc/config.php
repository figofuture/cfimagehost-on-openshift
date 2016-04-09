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
 *   Used For:     Site Config File
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

// debug enable/disable the PHP error reporting
	$debug = true; // [true|false]

// Define directory address
	if(!defined('CFROOTPATH')) define('CFROOTPATH', dirname(__DIR__).'/');

	define('CFLANGPATH', CFROOTPATH. 'languages/');
	define('CFINCPATH', CFROOTPATH. 'inc/');
	define('CFLIBPATH', CFROOTPATH. 'lib/');
	define('CFADMINPATH', CFROOTPATH. 'admin/');
// upload dir
	define('CFUPLOADPATH', getenv('OPENSHIFT_DATA_DIR'). 'upload/');
	define('CFIMAGEPATH', CFUPLOADPATH. 'images/');
	define('CFTHUMBPATH', CFUPLOADPATH. 'thumbs/');
	define('CFSMALLTHUMBPATH', CFUPLOADPATH. 'smallthumbs/');
	define('CFIMGTEMPPATH', CFUPLOADPATH. 'temp/');
	define('CFIMGCACHEPATH', CFUPLOADPATH. 'cache/');
	define('CFDATAPATH', CFUPLOADPATH. 'data/');
	define('CFBANDWIDTHPATH', CFUPLOADPATH. 'bandwidth/');
	define('CFBACKUPPATH', CFUPLOADPATH. 'backup/');
	define('CFBULKUPLOADPATH', CFUPLOADPATH. 'bulkupload/');

// Define DB FILES
	define('CFDBIMGPATH', CFDATAPATH. 'imgdb.db');
	define('CFDBBANPATH', CFDATAPATH. 'ban.db');

// Define installed file
        define('CFINSTALLED', CFUPLOADPATH. '.installed');
// Define set.php
        define('CFSETTINGS', CFUPLOADPATH. 'set.php');

///////////////////////////////////////////////////////////////////////////////
// Fixed Settings

/*
 * The length of the image id (you only need to edit if you are going to have more the 1,679,616 images hosted)
 * Number of Possible Combinations
 *		3 digit code 46656
 *		4 digit code 1679616
 *		5 digit code 60466176
 */
	$IMG_ID_LENGTH = 4;

// the maximum number of images allowed to be uploaded at one time
	$bulk_upload_max = 25;

// upload image size(pixels)
	$IMG_MIN_SIZE = '8';
	$IMG_MAX_SIZE = '3300';

//setMemoryForImage in resize.class.php only needed if max image size is bigger
// them 2500 most of the time..
	$IMG_MEMORY_LIMIT = false;// [true|false]
	$IMG_TWEAK_FACTOR = 1.8; //setMemoryForImage multiplier 

//Thumb settins(both)
	$PNG_SAVE_EXT = 'png';	// used for PSD and any png Thumb
	$PNG_QUALITY = 60;		// used for PSD and any png Thumb (1-100)
	$JPG_SAVE_EXT = 'jpg';	// used for BMP and any jpg Thumb
	$JPG_QUALITY = 90;		// used for BMP and any jpg Thumb (1-100)

//Small Thumb settins
	$THUMB_OPTION		= 'auto'; //crop, auto, exact
	$THUMB_MAX_WIDTH	= 150;
	$THUMB_MAX_HEIGHT	= 150;

//Thumb settins
	$THUMB_MID_OPTION		= 'auto'; //crop, auto, exact
	$THUMB_MID_MAX_WIDTH	= 320;
	$THUMB_MID_MAX_HEIGHT	= 320;

// Image Formats
	$imgFormats = array('png', 'jpg', 'jpeg', 'gif', 'bmp', 'psd');
	$acceptedFormats =  array(
							'image/x-ms-bmp'=>'bmp',
							'image/bmp'		=>'bmp',
							'image/gif'		=>'gif',
							'image/pjpeg'	=>'jpg',
							'image/jpg'		=>'jpg',
							'image/jpeg'	=>'jpg',
							'image/tiff'	=>'tif',
							'image/x-icon'	=>'ico',
							'image/x-png'	=>'png',
							'image/png'		=>'png',
							'image/psd'		=>'psd',
							'application/octet-stream' =>'psd',
							'image/vnd.adobe.photoshop' =>'psd'
							);

	$ADMIN_DASHBOARD_FULL = 1;//false;

