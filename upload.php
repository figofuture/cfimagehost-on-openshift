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
 *   Used For:     Image Upload Code
 *   Last edited:  20/12/2012
 *
 *************************************************************************************************************/

 	if(!defined('cfih')) define('cfih', 'upload');
	elseif(cfih != 'admin') die('This file cannot be directly accessed.');
	require_once './inc/cfih.php';

// check to see if upload has been disable (set in the admin panel), if so then send user to the index/home page
	if($settings['SET_DIS_UPLOAD']){
		if(!checklogin()){
			header('Location: index.php');
			exit();
		}
	}

// set time out timer to 10mins
	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

//unset session
	unset($_SESSION['upload']);
	unset($_SESSION['err']);

////////////////////////////////////////////////////////////////////////////////////
// UPLOAD CODE START

//testing
	$settings['SET_ALLOW_ANIMATION'] = false;// true;

// see if user is banned
	if (db_isBanned()){
		user_feedback('error',_T("site_upload_banned"),'Banned');
	}

// check for image file or url
	if((!isset($_POST['imgUrl']) || empty($_POST['imgUrl'])) && (!isset($_FILES['file']) || $_FILES['file']['error'][0] > 0)){
		user_feedback('error',_T("site_upload_err_no_image"),'NoImage');
	}

	if(($_SERVER['REQUEST_METHOD'] == 'POST' || isset($admin_upload)) && !isset($_SESSION['err']) || isset($_SERVER['HTTP_X_FILENAME'])){
	
	// what source
		$source = isset($_POST['imgUrl']) && !empty($_POST['imgUrl'])?$_POST['imgUrl']:$_FILES['file'];

	// setup upload class
		require CFLIBPATH.'upload.class.php';
		$imgUp = new upload($source);
		$imgUp->set_memory_limit($IMG_MEMORY_LIMIT);
		$imgUp->set_tweak_factor($IMG_TWEAK_FACTOR);
		$imgUp->set_accepted_formats($acceptedFormats);
		$imgUp->set_max_filesize($settings['SET_MAXSIZE']); // in bits
		$imgUp->set_min_dimensions($IMG_MIN_SIZE); // pixels
		$imgUp->set_max_dimensions($IMG_MAX_SIZE); // pixels
		$imgUp->set_upload_dir(CFIMAGEPATH);
		$imgUp->set_temp_dir(CFIMGTEMPPATH);
		$imgUp->set_allow_animation($settings['SET_ALLOW_ANIMATION']);
		$imgUp->set_id_length($IMG_ID_LENGTH);

		$imgCount = 0; // used to limit uploads
		$number_of_uploads = !isset($_POST['imgUrl']) || empty($_POST['imgUrl'])?count($_FILES['file']['name']):1;

		for($i=0; $i < $number_of_uploads;++$i){
				
			if(!$imgUp->process($i)){
				if($imgUp->error_code == 110) user_feedback('error',$imgUp->info['full_name'].' - '._T("site_upload_url_err_no_image"),'image_404');
				if($imgUp->error_code == 120) user_feedback('error',$imgUp->info['full_name'].' - '._T("site_upload_types_accepted",implode(", ",$imgFormats)),'extension');
				if($imgUp->error_code == 121) user_feedback('error',$imgUp->info['full_name'].' - '._T("site_upload_to_small",$IMG_MIN_SIZE.'x'.$IMG_MIN_SIZE),'sizetosmall');
				if($imgUp->error_code == 122) user_feedback('error',$imgUp->info['full_name'].' - '._T("site_upload_to_big",$IMG_MAX_SIZE.'x'.$IMG_MAX_SIZE),'sizetobig');
				if($imgUp->error_code == 123) user_feedback('error',$imgUp->info['full_name'].' - '._T("site_upload_size_accepted",format_size($settings['SET_MAXSIZE'])),'filetobig');
				if($imgUp->error_code == 124) user_feedback('error',$imgUp->info['full_name'].' - '._T("site_upload_opening_image"),'opening');
				continue;
			}

			$imgUp->fingerprint();
		// need to check for duplicate images?
			if($settings['SET_NODUPLICATE']){
			//check for Duplicate Images
				if($fp=findImage('fingerprint',$imgUp->info['fingerprint'])){
				// If similar files exist, check them
					foreach($fp as $fpItem){
						if ($imgUp->are_duplicates(imageAddress(1,$fpItem))){
							$dupFound = true;
							$dup = $fpItem;
							break;
						}
					}
					if(isset($dupFound)){
						$err_add = '<br/><a href="'.imageAddress(2,$dup,'pm').'" title="'.$dup['alt'].'" >Duplicate Images</a>';
						user_feedback('error','<b>'.$imgUp->info['full_name'].'</b> '._T("upload_duplicate_found").'  '.$err_add,'duplicate ');
						continue;
					}
				}
			}

			if(!$imgUp->finish_upload((isset($admin_upload)?1:null))){
				if($imgUp->error_code == 125) user_feedback('error','<b>'.$imgUp->info['full_name'].'</b> '._T("site_upload_err").' .','filemove');
				continue;
			}

		//Resize image if needed
			if ($settings['SET_RESIZE_IMG_ON']) {

				if((isset($_POST['new_width'][$i]) && !empty($_POST['new_width'][$i])) ||
					(isset($_POST['new_height'][$i]) && !empty($_POST['new_height'][$i]))){

					$imgUp->stretchSmallImages(true);

					if(!empty($_POST['new_width'][$i]) && !empty($_POST['new_height'][$i])){
						$imgUp->resizeImage($_POST['new_width'][$i], $_POST['new_height'][$i], 'exact');
					}
					elseif(!empty($_POST['new_width'][$i]) && empty($_POST['new_height'][$i])){
						$imgUp->resizeImage($_POST['new_width'][$i], $imgUp->info['width'], 'landscape');
					}
					elseif(empty($_POST['new_width'][$i]) && !empty($_POST['new_height'][$i])){
						$imgUp->resizeImage($imgUp->info['height'], $_POST['new_height'][$i], 'portrait');
					}

					$imgUp->saveImage($imgUp->info['address'],100,null,true);
					$imgUp->info['size'] = filesize($imgUp->info['address']); // get new image file size
					$imgUp->stretchSmallImages(false); // set it back to false
				}
			}

		// check for theme Settings
			$THUMB_OPTION = theme_setting('thumb_option',$THUMB_OPTION);
			$THUMB_MAX_WIDTH = theme_setting('thumb_max_width',$THUMB_MAX_WIDTH);
			$THUMB_MAX_HEIGHT = theme_setting('thumb_max_height',$THUMB_MAX_HEIGHT);

		// make thumb
			$thumb_mid_address = CFTHUMBPATH.$imgUp->info['new'];
			$imgUp -> resizeImage($THUMB_MID_MAX_WIDTH, $THUMB_MID_MAX_HEIGHT, $THUMB_MID_OPTION);
			$imgUp -> saveImage($thumb_mid_address, ($imgUp->info['ext'] == 'png'?$PNG_QUALITY:$JPG_QUALITY));

		// make small thumb
			$thumb_address = CFSMALLTHUMBPATH.$imgUp->info['new'];
			$imgUp -> resizeImage($THUMB_MAX_WIDTH, $THUMB_MAX_HEIGHT, $THUMB_OPTION);
			$imgUp -> saveImage($thumb_address, ($imgUp->info['ext'] == 'png'?$PNG_QUALITY:$JPG_QUALITY));
		
			$image = $imgUp->info;
			$imgUp->destroyImage();
			
			$image['alt'] = removeSymbols(cl(!empty($_POST['alt'][$i])?$_POST['alt'][$i]:$image['name']));

		//see if thumb's got made
			if(!file_exists($thumb_address) || !file_exists($thumb_mid_address)){
				@unlink($image['address']);
				@unlink($thumb_address);
				@unlink($thumb_mid_address);
				user_feedback('error','<b>'.$image['name'].'</b> '._T("site_upload_err").' ..','thumbmade');
				continue;
			}

		// see if we need to get a short url for the image
			$shorturl = null;
			if (isset($_POST['shorturl'][$i]) && $_POST['shorturl'][$i] == 1 && $settings['SET_SHORT_URL_ON']){
				$shorturl = shorturl_url('http://'.$_SERVER['HTTP_HOST'].preg_replace('/\/([^\/]+?)$/', '/', $_SERVER['PHP_SELF']).'?di='.$image['id']);
			}

		// get thumb's file size
			$thumbsize = filesize($thumb_mid_address);
			$sthumbsize = filesize($thumb_address);

		// Make image info array to save to db
			$newImageArray = array(
									'id'		=> $image['id'],
									'name'		=> $image['name'],
									'alt'		=> $image['alt'],
									'added'		=> time(),
									'ext'		=> $image['ext'],
									'ip'		=> $_SERVER['REMOTE_ADDR'],
									'size'		=> $image['size'],
									'deleteid'	=> $image['did'],
									'thumbsize' => $thumbsize,
									'sthumbsize'=> $sthumbsize,
									'private'	=> (isset($_POST['private'][$i])?1:0),
									'report'	=> 0,
									'shorturl'	=> $shorturl,
									'fingerprint'	=>$image['fingerprint'],
									);

		//save new image to database
			if(addNewImage($newImageArray)){

			// save image to upload array to be sent to thumb page
				$_SESSION['upload'][] = array('id' => $image['id'],'did' => $image['did']);

			// count images uploaded
				$imgCount++;
				if($imgCount >= $settings['SET_MAX_UPLOAD'] && !isset($admin_upload)){
					break; // break upload loop as you have updated max number of images in one go...
				}

			}else{
				user_feedback('error','<b>'.$image['name'].'</b> '._T("site_index_delete_image_err_db"),'savedb');
				continue;
			}
		}// end image upload loop
	}
// error uploading image
	elseif(!isset($_SESSION['err'])){
		user_feedback('error',_T("site_upload_err").' ...','unknown_error');
	}

// remove temp images
	if(isset($removeList)){
		foreach ($removeList as $tempImg){
			// remove old file
				if(file_exists($tempImg)){
					unlink($tempImg);
				}
		}
	}


////////////////////////////////////////////////////////////////////////////////////
// send to page

// admin bulk uploader 
	if(isset($admin_upload)){
		header('Location: '. $settings['SET_SITEURL'].'admin.php?act=bulk');
		exit();
	}

// error send back to home page and show the error
	if(!isset($_SESSION['upload'])){
		header('Location: '. $settings['SET_SITEURL'].'index.php');
		exit();
	}

// open thumb page and show upload images
	header('Location: '. $settings['SET_SITEURL'].'thumbnail.php');
	die();
