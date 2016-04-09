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
 *   Used For:     Admin Ban User & Banned User list
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	// ban user
		if(isset($_POST['changesettings'])) {
			$banIp = cl($_POST['banIP']);
			$banDescription = cl($_POST['banDes']);
			if ($banIp != ''){
				if(db_ban_user($banIp,$banDescription)){
					user_feedback('success',_T("admin_ban_suc",$banIp),'banned');
				}else{
					user_feedback('error',_T("admin_ban_err_save_db"),'admin_ban_saving_ban');
				}
			}else{
				user_feedback('error',_T("admin_ban_err_no_ip"),'admin_ban_no_ip');
			}
		}

	//unban user
		$ip_to_unban = isset($_GET['ip']) ? cl($_GET['ip']):'';
		if ($ip_to_unban != ''){
			if (db_remove_ban($ip_to_unban)){
				user_feedback('success',_T("admin_ban_suc_unbanned",$ip_to_unban),'unban');
			}
		}
	// find ip from image ID
		$id_to_ban = isset($_GET['id']) ? cl($_GET['id']):'';
		if ($id_to_ban != ''){
			$BAN_IP = db_get_image($id_to_ban);
			$BAN_IP = $BAN_IP['ip'];
		}

	// List Banned IP's
		$banned_list='';
		if($banList = db_list_banned_uers()){
			foreach ($banList as $k => $v){
				$odd_class = empty($odd_class) ? ' class="odd"' : '';
				$banned_list .= '<tr'.$odd_class.'>
								<td class="textleft">
									<a href="admin.php?act=ban&ip='.$v['ip'].'" class="tip" title="'._T("admin_ban_alt_unban").'"><img src="img/User-Ok.png" height="16" width="16" border="0" alt="'._T("admin_ban_alt_unban").'"/></a>
									<a href="admin.php?act=images&ip='.$v['ip'].'" class="tip" title="'._T("admin_ilp_ipsearch_alt",$v['ip']).'"><img src="img/user-search.png" height="16" width="16" border="0" alt="'._T("admin_ilp_ipsearch_alt",$v['ip']).'" /></a>
								</td>
								<td class="textleft">'.date('d M y',$v['date']).'</td>
								<td class="textleft">'.$v['ip'].'</td><td class="textleft">'.$v['des'].'</td></tr>';
			}
		}


// page settings
	$page['id']				= 'ban';
	$page['title']			= _T("admin_ban_form_title");
	$page['description']	= '';
	$page['tipsy'] 			= true;

	require CFADMINPATH.'admin_page_header.php';
?>
<!-- admin Ban -->
			<div class="ibox banForm">
				<h2><?php echo _T("admin_ban_form_title");?></h2>
				<form method="POST" action="admin.php?act=ban">
					<div class="code_box <?php echo (isset($ERR_PI)?$ERR_PI:'');?>"><label><?php echo _T("admin_ban_form_ip");?> : </label><input class="text_input" type="text" name="banIP" value="<?php echo (isset($BAN_IP)?$BAN_IP:'');?>" size="20" /></div>
					<div class="code_box"><label><?php echo _T("admin_ban_form_reason");?> : </label><input class="text_input" type="text" name="banDes" value="" size="20" /></div>
					<div class="code_box"><input class="button button_cen" onclick="" type="submit" value="<?php echo _T("admin_ban_form_button");?>" name="changesettings"></div>
				</form>
			</div>
			<div class="ibox">
				<h2><?php echo _T("admin_ban_form_title");?></h2>
				<table class="table_small">
					<thead>
					<tr class="odd">
						<th>&nbsp;</th>
						<th scope="col" title="<?php echo _T("admin_ban_list_tt_date_banned");?>"><?php echo _T("admin_ban_list_date_banned");?></th>
						<th scope="col" title="<?php echo _T("admin_ban_list_tt_ip");?>"><?php echo _T("admin_ban_list_ip");?></th>
						<th scope="col" title="<?php echo _T("admin_ban_list_tt_reason");?>"><?php echo _T("admin_ban_list_reason");?></th>
					</tr>
					</thead>
					<tbody>
						<?php echo (isset($banned_list)?$banned_list:'');?>
					</tbody>
				</table>
			</div>
			<div class="clear"></div>
		</div>
<?php
	require CFADMINPATH.'admin_page_footer.php';
	die();
	exit;