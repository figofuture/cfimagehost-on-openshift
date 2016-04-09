<?php if(!defined('cfih') or !cfih or cfih!='admin') die('This file cannot be directly accessed.');
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
 *   Used For:     Admin Edit Image Info
 *   Last edited:  11/01/2012
 *
 *************************************************************************************************************/

// Get Image ID
	$edit_img_id = cl($_GET['id']);

// Update Image Info If Needed
	if(isset($_POST['update'])){
		$pam = array (
					'alt'		=> cl($_POST['setAlt']),
					'private'	=> (cl($_POST['setPrivate']) == 1? true:false)
					);
		if(db_update($edit_img_id,$pam)){
			user_feedback('success',_T("admin_iep_suc"),'edit_image');
		}
	}

// get image info
	$edit_image = db_get_image($edit_img_id);
	$cdb = db_imageCounterList(null,$edit_img_id);

// page settings
	$page['id']			= 'edit';
	$page['title']		= _T("admin_iep_title");
	$page['description']= '';
	$page['tipsy'] 		= true;
	$page['lightbox']	= true;

	require CFADMINPATH.'admin_page_header.php';
?>
<!-- admin image edit -->
			<div class="ibox top_img"><a href="<?php echo imageAddress(1,$edit_image);?>" target="_blank" title="<?php echo $edit_image['alt'];?>" class="imglink lightbox"><img src="<?php echo imageAddress(2,$edit_image);?>" title="<?php echo $edit_image['alt'];?>" class=" imgedit_img" /></a></div>
			<div class="ibox"><h2><?php echo _T("admin_iep_bandwidth_views");?></h2>
				<div class="quickview">
					<h3><?php echo _T("admin_iep_since_uploaded");?></h3>
					<ul>
					<li><?php echo _T("admin_iep_img_views");?>: <span class="number"><?php echo issetor($cdb['image'],0);?></span></li>
					<li><?php echo _T("admin_iep_thumb_views");?>: <span class="number"><?php echo issetor($cdb['thumb_mid'],0);?></span></li>
					<li><?php echo _T("admin_iep_small_thumb_views");?>: <span class="number"><?php echo issetor($cdb['thumb'],0);?></span></li>
					<li><?php echo _T("admin_iep_gallery_views");?>: <span class="number"><?php echo issetor($cdb['gallery'],0);?></span></li>
					<li><?php echo _T("admin_iep_bandwidth_used");?>: <span class="number"><?php echo format_size(issetor($cdb['bandwidth'],0));?></span></li>
					</ul>
				</div>
				
				<div class="quickview">
					<h3><?php echo _T("admin_iep_from_last_reset");?> (<?php echo date("F j, Y",issetor($cdb['lr_date']));?>)</h3>
					<ul>
					<li><?php echo _T("admin_iep_img_views");?>: <span class="number"><?php echo issetor($cdb['lr_image'],0);?></span></li>
					<li><?php echo _T("admin_iep_thumb_views");?>: <span class="number"><?php echo issetor($cdb['lr_thumb_mid'],0);?></span></li>
					<li><?php echo _T("admin_iep_small_thumb_views");?>: <span class="number"><?php echo issetor($cdb['lr_thumb'],0);?></span></li>
					<li><?php echo _T("admin_iep_gallery_views");?>: <span class="number"><?php echo issetor($cdb['lr_gallery'],0);?></span></li>
					<li><?php echo _T("admin_iep_bandwidth_used");?>: <span class="number"><?php echo format_size(issetor($cdb['lr_bandwidth'],0));?></span></li>
					</ul>
				</div>
			</div>
			<div class="ibox">
				<h2><?php echo _T("admin_iep_title");?></h2>
				<form method="POST" action="admin.php?act=edit&id=<?php echo $edit_img_id;?>" class="">
					<div class="code_box"><label><?php echo _T("admin_iep_des_title");?> :</label><input class="text_input" type="text" name="setAlt" value="<?php echo $edit_image['alt'];?>" size="20" /></div>
					<div class="code_box"><label><?php echo _T("admin_iep_pp_title");?> :</label>
						<select name="setPrivate" class="text_input">
							<option value="0" <?php echo (!$edit_image['private'] ? 'selected="selected"':'');?>><?php echo _T("admin_iep_public");?></option>
							<option value="1" <?php echo ($edit_image['private'] ? 'selected="selected"':'');?>><?php echo _T("admin_iep_private");?></option>
						</select></div>
					<div class="code_box"><label></label><input class="button button_cen" onclick="" type="submit" value="<?php echo _T("admin_iep_button");?>" name="update"></div>
				</form>
			</div>
			<div class="ibox"><h2><?php echo _T("admin_iep_img_info");?></h2>
				<div class="quickview">
					<ul>
					<li><?php echo _T("admin_home_id");?>: <span class="number"><?php echo $edit_image['id'];?></span></li>
					<li><?php echo _T("admin_home_name");?>: <span class="number"><?php echo $edit_image['name'];?></span></li>
					<li><?php echo _T("admin_iep_des_title");?> : <span class="number"><?php echo $edit_image['alt'];?></span></li>
					<li><?php echo _T("admin_iep_uploaded");?>: <span class="number"><?php echo date("F j, Y",$edit_image['added']);?></span></li>
					<li><?php echo _T("admin_iep_time");?>: <span class="number"><?php echo date("g:i a",$edit_image['added']);?></span></li>
					<li><?php echo _T("admin_iep_format");?>: <span class="number"><?php echo $edit_image['ext'];?></span></li>
					<li><?php echo _T("admin_iep_ip_uploaded");?>: <span class="number"><?php echo $edit_image['ip'];?></span></li>
					<li><?php echo _T("admin_iep_ip_find_uploaded");?>: <span class="number">
						<a href="admin.php?act=images&ip=<?php echo $edit_image['ip'];?>" class="tip" title="<?php echo sprintf(_T("admin_ilp_ipsearch_alt"),$edit_image['ip']);?>">
							<img src="img/user-search.png" height="16" width="16" border="0" alt="<?php echo sprintf(_T("admin_ilp_ipsearch_alt"),$edit_image['ip']);?>" />
						</a>
					</span></li>
					<li><?php echo _T("admin_iep_img_size");?>: <span class="number"><?php echo format_size($edit_image['size']);?></span></li>
					<li><?php echo _T("admin_iep_thumb_size");?>: <span class="number"><?php echo format_size($edit_image['thumbsize']);?></span></li>
					<li><?php echo _T("admin_iep_small_thumb_size");?>: <span class="number"><?php echo format_size($edit_image['sthumbsize']);?></span></li>
					<li><?php echo _T("admin_iep_short_url");?>: <span class="number"><?php echo (empty($edit_image['shorturl'])?'none':$edit_image['shorturl']);?></span></li>
					<li><?php echo _T("admin_iep_last_viewd");?>: <span class="number"><?php echo date("F j, Y",issetor($cdb['date'],$edit_image['added']));?></span></li>
					</ul>
				</div>
			</div>

			<div class="clear"></div>

<?php 
	require CFADMINPATH.'admin_page_footer.php';
	die();
	exit;
?>