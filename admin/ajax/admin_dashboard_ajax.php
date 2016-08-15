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
 *   Used For:     Admin Dashboard
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

 	define('cfih', 'admin');
	define('CFROOTPATH', dirname(dirname(dirname( __FILE__ ))).'/');
	include_once(CFROOTPATH.'inc/cfih.php');
	if(!checklogin()){ exit("Direct access not permitted.");}

//var for totals
	$total_bw = 0; //bandwidth
	$lr_total_bw = 0;
	$total_is = 0; //image size
	$mostBwImage = array('id' => null,'alt' => '', 'bandwidth' => 0,'hotlink' =>0, 'ext' => '','lr_bandwidth' => 0,'lr_hotlink' =>0,);
	$mostViewImage = $mostBwImage;
	$lr_mostBwImage = $mostBwImage;
	$lr_mostViewImage = $mostBwImage;

	
// workout the last reset date for home page and image list
	if ($settings['SET_BANDWIDTH_RESET'] == 'm'){
		$resetdate = strtotime('01 '.date('M Y'));
		$n_resetdate = mktime(0,0,0,date('m')+1,1,date('Y'));
	}else{
		$resetdate = strtotime('last monday', strtotime('tomorrow'));
		$n_resetdate = strtotime("next Monday");
	}


// Check for images in database
	if(imageList(0,1)){

	// get image index
		$db_img = imageList(0,'all');
	// Add bandwidth & counter to image index
		foreach ($db_img as $k => $v){
		// total space used
			$total_is += $v['size'] + $v['thumbsize'] + $v['sthumbsize'];

			if(isset($_GET['t'])) continue;
		
		// get image bandwidth & counter index
			if(!$hc = db_imageCounterList(null,$v['id'])){
				$hc = array('image'=>0,'thumb_mid'=>0,'thumb'=>0,'bandwidth'=>0,
									'lr_image'=>0,'lr_thumb_mid'=>0,'lr_thumb'=>0,'lr_bandwidth'=>0);
			}
		// add bandwidth & counter index to image index
			$db_img[$k]['lr_bandwidth']= $hc['lr_bandwidth'];
			$db_img[$k]['bandwidth']	= $hc['bandwidth'];
			$db_img[$k]['lr_hotlink']	= 0+$hc['lr_image']+$hc['lr_thumb_mid']+$hc['lr_thumb'];
			$db_img[$k]['hotlink']		= 0+$hc['image']+$hc['thumb_mid']+$hc['thumb'];

		// Totals
			$total_bw += $db_img[$k]['bandwidth'];
			$lr_total_bw += $db_img[$k]['lr_bandwidth'];

		// Top images
			if(!isset($mostBwImage) || $mostBwImage['bandwidth'] < $db_img[$k]['bandwidth']){
				$mostBwImage = $db_img[$k];
			}
			if(!isset($mostViewImage) || $mostViewImage['hotlink'] < $db_img[$k]['hotlink']){
				$mostViewImage = $db_img[$k];
			}
			if(!isset($lr_mostBwImage) || $lr_mostBwImage['lr_bandwidth'] < $db_img[$k]['lr_bandwidth']){
				$lr_mostBwImage = $db_img[$k];
			}
			if(!isset($lr_mostViewImage) || $lr_mostViewImage['lr_hotlink'] < $db_img[$k]['lr_hotlink']){
				$lr_mostViewImage = $db_img[$k];
			}

		}
	// empty memory
		unset($hc);
		unset($db_img);
	}
?>

			<div class="ibox top_img">
				<h2><?php echo _T("admin_menu_home");?></h2>
				<div class="quickview">
					<h3><?php echo _T("admin_home_overview");?></h3>
					<ul>
					<li><?php echo _T("admin_home_total_images");?>: <span class="number"><?php echo $DBCOUNT;?></span></li>
					<li><?php echo _T("admin_home_private_images");?>: <span class="number"><?php echo $DbPrivate;?></span></li>
					<li><?php echo _T("admin_home_filespace_used");?>: <span class="number"><?php echo format_size($total_is);?></span></li>
					<li><?php echo _T("admin_home_last_backup");?>: <span class="number"><?php echo date("d M Y, H:i",$settings['SET_LAST_BACKUP_IMAGE']);?></span></li>
					</ul>
				</div>
				<div class="quickview">
					<h3>Bandwidth</h3>
					<ul>
			<?php if(!isset($_GET['t'])){?>
					<li><?php echo _T("admin_home_total_bandwidth");?>: <span class="number"><?php echo format_size($total_bw);?></span></li>
					<li><?php echo _T("admin_home_total_since_last_reset");?>: <span class="number"><?php echo format_size($lr_total_bw);?></span></li>
			<?php }?>
					<li><?php echo _T("admin_home_last_reset_date");?>: <span class="number"><?php echo date('d M Y',$resetdate);?></span></li>
					<li><?php echo _T("admin_home_next_reset_date");?>: <span class="number"><?php echo date('d M Y',$n_resetdate);?></span></li>
					</ul>
				</div>
			</div>

			<?php if(!isset($_GET['t'])){?>
			<div class="ibox top_img">
				<h2><?php echo _T("admin_home_top_image");?> (all time)</h2>
				<div class="quickview tInfo">
					<h3><?php echo _T("admin_home_by_bandwidth");?></h3>
					<ul>
						<li><?php echo _T("admin_home_id");?>: <span class="number"><a href="<?php echo imageAddress(1,$mostBwImage,"di");?>"  target="_blank" title="<?php echo $mostBwImage['alt'];?>" img_src="<img src='<?php echo imageAddress(3,$mostBwImage,"di");?>' />" class="imglink img_tooltip lightbox"><?php echo $mostBwImage['id'];?></a></span></li>
						<li><?php echo _T("admin_home_name");?>: <span class="number"> <?php echo $mostBwImage['alt'];?></span></li>
						<li><?php echo _T("admin_home_uploaded_date");?>: <span class="number"> <?php echo date("d M Y",$mostBwImage['added']);?></span></li>
						<li><?php echo _T("admin_home_bandwidth");?>: <span class="number"> <?php echo format_size($mostBwImage['bandwidth']);?></span></li>
						<li><?php echo _T("admin_home_hotlink_views");?>: <span class="number"> <?php echo $mostBwImage['hotlink'];?></span></li>
					</ul>
				</div>
				<div class="quickview tInfo">
					<h3><?php echo _T("admin_home_by_hotlink_views");?></h3>
					<ul>
						<li><?php echo _T("admin_home_id");?>: <span class="number"><a href="<?php echo imageAddress(1,$mostViewImage,"di");?>"  target="_blank" title="<?php echo $mostViewImage['alt'];?>" img_src="<img src='<?php echo imageAddress(3,$mostViewImage,"di")?>' />" class="imglink img_tooltip lightbox"><?php echo $mostViewImage['id'];?></a></span></li>
						<li><?php echo _T("admin_home_name");?>: <span class="number"> <?php echo $mostViewImage['alt'];?></span></li>
						<li><?php echo _T("admin_home_uploaded_date");?>: <span class="number"> <?php echo date("d M Y",$mostViewImage['added']);?></span></li>
						<li><?php echo _T("admin_home_bandwidth");?>: <span class="number"> <?php echo format_size($mostViewImage['bandwidth']);?></span></li>
						<li><?php echo _T("admin_home_hotlink_views");?>: <span class="number"> <?php echo $mostViewImage['hotlink'];?></span></li>
					</ul>
				</div>
			</div>

			<div class="ibox top_img">
				<h2><?php echo _T("admin_home_top_image");?> (since Last Reset)</h2>
				<div class="quickview tInfo">
					<h3><?php echo _T("admin_home_by_bandwidth");?></h3>
					<ul>
						<li><?php echo _T("admin_home_id");?>: <span class="number"><a href="<?php echo imageAddress(1,$lr_mostBwImage,"di");?>"  target="_blank" title="<?php echo $lr_mostBwImage['alt'];?>" img_src="<img src='<?php echo imageAddress(3,$lr_mostBwImage,"di");?>' />" class="imglink img_tooltip lightbox"><?php echo $lr_mostBwImage['id'];?></a></span></li>
						<li><?php echo _T("admin_home_name");?>: <span class="number"> <?php echo $lr_mostBwImage['alt'];?></span></li>
						<li><?php echo _T("admin_home_uploaded_date");?>: <span class="number"> <?php echo date("d M Y",$lr_mostBwImage['added']);?></span></li>
						<li><?php echo _T("admin_home_bandwidth");?>: <span class="number"> <?php echo format_size($lr_mostBwImage['lr_bandwidth']);?></span></li>
						<li><?php echo _T("admin_home_hotlink_views");?>: <span class="number"> <?php echo $lr_mostBwImage['lr_hotlink'];?></span></li>
					</ul>
				</div>
				<div class="quickview tInfo">
					<h3><?php echo _T("admin_home_by_hotlink_views");?></h3>
					<ul>
						<li><?php echo _T("admin_home_id");?>: <span class="number"><a href="<?php echo imageAddress(1,$lr_mostViewImage,"di");?>"  target="_blank" title="<?php echo $lr_mostViewImage['alt'];?>" img_src="<img src='<?php echo imageAddress(3,$lr_mostViewImage,"di")?>' />" class="imglink img_tooltip lightbox"><?php echo $lr_mostViewImage['id'];?></a></span></li>
						<li><?php echo _T("admin_home_name");?>: <span class="number"> <?php echo $lr_mostViewImage['alt'];?></span></li>
						<li><?php echo _T("admin_home_uploaded_date");?>: <span class="number"> <?php echo date("d M Y",$lr_mostViewImage['added']);?></span></li>
						<li><?php echo _T("admin_home_bandwidth");?>: <span class="number"> <?php echo format_size($lr_mostViewImage['lr_bandwidth']);?></span></li>
						<li><?php echo _T("admin_home_hotlink_views");?>: <span class="number"> <?php echo $lr_mostViewImage['lr_hotlink'];?></span></li>
					</ul>
				</div>
			</div>
		
			<?php }?>
