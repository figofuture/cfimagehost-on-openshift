<?php if(!defined('cfih_i') or !cfih_i) die('This file cannot be directly accessed.');
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

		$check_bw = true;

	// Image Database
		echo '<div class="ibox"><h2>Checking image database</h2>';
		if (!file_exists(CFDBIMGPATH)){
			user_note('No image datebase found, Making new database...',3);
			false;
			if(save_new_db(CFDBIMGPATH,array())){
				user_note('New database Made',2);
				
			}
		}else{
			user_note('Image Database file exists, and no update was needed!',3);
		}
		echo '</div>';
		flushNow();

	// Bandwidth Database
		if($check_bw){
			echo '<div class="ibox"><h2>Checking Bandwidth database</h2>';
			$bw_files = glob(CFBANDWIDTHPATH . "*.db");

			if(count($bw_files) > 0){
				user_note('Found '.count($bw_files).' Bandwidth database files. Checking if any need updating....',3);
				flushNow();
				// var counters
				$r = 0; // number removed files
				$u = 0; // number of updated files
				foreach($bw_files as $bw_file){
					$bw_array = load_old_db($bw_file);
				//	preprint($bw_array);
				// check if db 
					if(!isset($bw_array[0]['id']) && !isset($bw_array['id'])){
						unlink($bw_file);
						$r++;
					}
				// check if updated if needed
					elseif(isset($bw_array[0]['id'])){
						$u++;
						$file_info = mb_pathinfo($bw_file);
						$icl = array(
										'id' => $bw_array[0]['id'],
										'date' => time(),
										'image' => 0,
										'thumb_mid' => 0,
										'thumb' => 0,
										'gallery' => 0,
										'bandwidth' => 0,
										'lr_date' => 0,
										'lr_image' => 0,
										'lr_thumb_mid' => 0,
										'lr_thumb' => 0,
										'lr_gallery' => 0,
										'lr_bandwidth' => 0,
										);
							
					// workout the last reset date for home page and image list (use month for now)
					//	if ($settings['SET_BANDWIDTH_RESET'] == 'm'){
							 $resetdate = strtotime('01 '.date('M Y'));
							 $n_resetdate = strtotime('next month');
					//	}else{
					//		 $resetdate = strtotime('last monday', strtotime('tomorrow'));
					//		 $n_resetdate = strtotime("next Monday");
					//	}

						foreach($bw_array as $bw_item){
							if($bw_item['date']>$resetdate){
								if($bw_item['date']>$icl['lr_date'])$icl['lr_date'] =$bw_item['date'];
								if($bw_item['image'])$icl['lr_image']++;
								if($bw_item['thumb_mid'])$icl['lr_thumb_mid']++;
								if($bw_item['thumb'])$icl['lr_thumb']++;
								if($bw_item['gallery'])$icl['lr_gallery']++;
								$icl['lr_bandwidth']+= $bw_item['bandwidth'];
							}
							if($bw_item['image'])$icl['image']++;
							if($bw_item['thumb_mid'])$icl['thumb_mid']++;
							if($bw_item['thumb'])$icl['thumb']++;
							if($bw_item['gallery'])$icl['gallery']++;
							$icl['bandwidth']+= $bw_item['bandwidth'];
						}// end $bw_array
						save_new_db($bw_file,$icl);
					}
				} // end $bw_files foreach
				user_note(''.$u.' files where updated and '.$r.' files removed.',2);
			}else{
				user_note('No Bandwidth datebase files found',3);
			}
			echo '</div>';
			flushNow();
		}

	// move on
		if(!$Err_found){
			echo '<form method="post" action="?i=set" class="ins">
				<input type="submit" value="Next Checking Settings" class="button" name="db">
			</form>';
		}else{
			echo '<div class="ibox">';
			user_note('please Fix the errors above before moving on',1);
			echo '</div>';
		}
