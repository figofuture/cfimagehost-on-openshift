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
 *   Used For:     Admin Dashboard -report
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	ini_set("max_execution_time", "600");
	ini_set("max_input_time", "600");

 	define('cfih', 'admin');
	define('CFROOTPATH', dirname(dirname(dirname( __FILE__ ))).'/');
	include_once(CFROOTPATH.'inc/cfih.php');
	if(!checklogin() || !$settings['SET_ALLOW_REPORT']){ exit("Direct access not permitted.");}

	$report_list = null;

	if($full_report = db_imageReportList()){
		foreach ($full_report as $k => $image){
			$odd_class = empty($odd_class) ? ' class="odd"' : '';
			$img_alt = $image['alt'];
			$img_name = $image['name'];
			$img_deleteid = $image['deleteid'];
			$img_url = imageAddress(1,$image);
			$img_thumb_url = imageAddress(3,$image);
			$report_list .= '<tr'.$odd_class.'>
							<td>
								<a href="admin.php?act=report&id='.$image['id'].'" class="tip" title="'._T("admin_home_report_alt_remove").'"><img src="img/Image-Ok.png" height="16" width="16" border="0" alt="'._T("admin_home_report_alt_remove").'" /></a>
								<a href="#" id="'.$img_deleteid.'" class="tip delete"  title="'._T("admin_home_report_alt_delete").'" ret="'.sprintf(_T("admin_home_report_delete"),$image['id']).'"><img src="img/Image-Del.png" height="16" width="16" border="0" alt="'._T("admin_home_report_alt_delete").'" /></a>
								<a href="admin.php?act=ban&id='.$image['id'].'" class="tip" title="'._T("admin_home_report_alt_ban").'"><img src="img/User-Block.png" height="16" width="16" border="0" alt="'._T("admin_home_report_alt_ban").'" /></a>
								<a href="admin.php?act=images&ip='.$image['ip'].'" class="tip" title="'.sprintf(_T("admin_ilp_ipsearch_alt"),$image['ip']).'"><img src="img/user-search.png" height="16" width="16" border="0" alt="'.sprintf(_T("admin_ilp_ipsearch_alt"),$image['ip']).'" /></a>
							</td>
							<td><a href="'.$img_url.'" target="_blank" title="'.$img_alt.'" img_src="<img src=\''.$img_thumb_url.'\' />" class="imglink img_tooltip lightbox">'.$img_name.'</a></td>';
		}
	}
?>
		<div class="ibox">
			<h2><?php echo _T("admin_home_reported_images");?></h2>
			<?php if(isset($report_list) && $report_list!=null){?>
			<table class="table_small">
				<thead><tr><th>&nbsp;</th><th scope="col" title="<?php echo _T("admin_home_tooltip_image_name");?>"><?php echo _T("admin_home_image_name");?></th></tr></thead>
				<tbody><?php echo $report_list;?></tbody>
			</table>
			<?php }else{?>
				<center><?php echo _T("admin_home_noreported");?></center>
			<?php } ?>
		</div>
