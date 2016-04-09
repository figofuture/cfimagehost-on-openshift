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
 *   Used For:     Admin Database Tools
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

	require_once('lib/backup.class.php');
	$backup_index = getDirectoryList();
////////////////////////////////////////////////////////////////////////////////////
//SAVE NEW SETTINGS

	if(isset($_POST['changesettings'])) {
		$settings['SET_BACKUP_AUTO_ON']			= cl($_POST['setBackupAuto']) == 1? 1:0;
		$settings['SET_BACKUP_AUTO_TIME']			= cl($_POST['setBackupTime']);
		$settings['SET_BACKUP_AUTO_USE']			= cl($_POST['setUseBackup']) == 1? 1:0;
		$settings['SET_BACKUP_AUTO_REBUILD']	= cl($_POST['setRebuildBackup']) == 1? 1:0;

	// save settings
		if (empty($Err)){
			if(saveSettings(CFSETTINGS,$settings)){
				$Suc['saveing_settings'] = _T("admin_set_suc_update");
			}else
				$Err['saveing_settings'] = _T("admin_set_err_saveing_settings");
		}
	}

// page settings
	$page['id']					= 'db';
	$page['title']				= _T("admin_db_title_database_setting");
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['fancybox']		= false;


	require_once('admin/admin_page_header.php');
?>
<!-- admin db tools -->
		<div id="msg"></div>
		<form method="POST" action="admin.php?act=db">
			<div class="tabs">
					<ul class="tabs_list tabNavigation">
						<li><a href="#setAuto"><?php echo _T("admin_db_menu_auto");?></a></li>
						<li><a href="#setImageDB"><?php echo _T("admin_db_menu_image");?></a></li>
						<li><a href="#setBandwidthDB"><?php echo _T("admin_db_menu_bandwidth");?></a></li>
						<li><a href="#setRebuildDB"><?php echo _T("admin_db_menu_rebuild");?></a></li>
					</ul>

				<!--Auto DB Setting-->
					<div id="setAuto" class="panel ibox">
					<?php
						optionTitle(_T("admin_db_auto_title"));
						optionOnOff(_T("admin_db_auto_backup"),'setBackupAuto',$settings['SET_BACKUP_AUTO_ON'],null);
						optionList(_T("admin_db_auto_every"),'setBackupTime',$settings['SET_BACKUP_AUTO_TIME'], array('0.25'	=>_T("admin_db_auto_every_6hours"),
																																																		'0.5'	=>_T("admin_db_auto_every_12hours"),
																																																		'1'		=>_T("admin_db_auto_every_day"),
																																																		'7'		=>_T("admin_db_auto_every_week")));
						optionOnOff(_T("admin_db_auto_error"),'setUseBackup',$settings['SET_BACKUP_AUTO_USE']);
						optionOnOff(_T("admin_db_auto_rebuild"),'setRebuildBackup',$settings['SET_BACKUP_AUTO_REBUILD']);
						submitButton();
					?>
					</div>
				<!-- Image DB Setting-->
					<div id="setImageDB" class="panel ibox">
					<?php
						optionTitle(_T("admin_db_database_image_title"));

					// image database backup file list
						$imgdb_bk_list ='';
						foreach($backup_index['imgdb'] as $file_link ){
								$odd_class = empty($odd_class) ? ' class="odd"' : '';
								$imgdb_bk_list .=  '<tr'.$odd_class.'>
															<td class="textleft">
																<a href="#" id="1" class="tip unzip" alt="'.$file_link.'" title="'._T("admin_db_database_image_replace_tip").'" ret="'.sprintf(_T("admin_db_database_image_replace"),$file_link).'">
																	<img src="img/database_go.png" height="16" width="16" border="0" alt="'._T("admin_db_database_image_replace_tip").'"/>
																</a>
																<a href="#" id="1" class="tip remove" alt="'.$file_link.'" title="'._T("admin_db_database_delete_backup_tip").'" ret="'.sprintf(_T("admin_db_database_delete_backup"),$file_link).'">
																	<img src="img/database_delete.png" height="16" width="16" border="0" alt="'._T("admin_db_database_delete_backup_tip").'"/>
																</a>
																<a href="cfajax.php?name='.$file_link.'&act=download" class="tip download" alt="'.$file_link.'" title="'._T("admin_db_database_download_backup").'">
																	<img src="img/database_save.png" height="16" width="16" border="0" alt="'._T("admin_db_database_download_backup").'"/>
																</a>
															</td>
															<td class="textleft">'.date ("F d Y H:i:s", filemtime(CFBACKUPPATH.$file_link)).'</td>
															<td class="textleft">'.$file_link.'</td></tr>';
						}
					// image database backup table
						echo '
				<div class="code_box"><label>'._T("admin_db_database_image_backup").'</label><a href="#" id="1" class="button backup" title="'._T("admin_db_database_image_backup").'" ret="'._T("admin_db_database_image_backup").'" ><img src="img/database_edit.png" height="16" width="16" border="0" alt="'._T("admin_db_database_image_backup").'"/>'._T("admin_db_database_now").'</a></div>
				<table class="table_set">
					<thead>
					<tr class="odd">
						<th>&nbsp;</th>
						<th scope="col" title="'._T("admin_db_database_backup_table_date_tip").'">'._T("admin_db_database_backup_table_date").'</th>
						<th scope="col" title="'._T("admin_db_database_backup_table_name_tip").'">'._T("admin_db_database_backup_table_name").'</th>
					</tr>
					</thead>
					<tbody>
						'.(isset($imgdb_bk_list)?$imgdb_bk_list:'').'
					</tbody></table>';
					?>
					</div>
				<!-- Bandwidth DB Settings -->
					<div id="setBandwidthDB" class="panel ibox">
					<?php
					optionTitle(_T("admin_db_database_bandwidth_title"));
					// bandwidth database backup file list
						$bandwidth_bk_list ='';
						foreach($backup_index['bandwidth'] as $file_link ){
								$odd_class = empty($odd_class) ? ' class="odd"' : '';
								$bandwidth_bk_list .=  '<tr'.$odd_class.'>
															<td class="textleft">
																<a href="#" id="2" class="tip unzip" alt="'.$file_link.'" title="'._T("admin_db_database_image_replace_tip").'" ret="'.sprintf(_T("admin_db_database_bandwidth_replace"),$file_link).'">
																	<img src="img/database_go.png" height="16" width="16" border="0" alt="'._T("admin_db_database_image_replace_tip").'"/>
																</a>
																<a href="#" id="2" class="tip remove" alt="'.$file_link.'" title="'._T("admin_db_database_delete_backup_tip").'" ret="'.sprintf(_T("admin_db_database_delete_backup"),$file_link).'">
																	<img src="img/database_delete.png" height="16" width="16" border="0" alt="'._T("admin_db_database_delete_backup_tip").'"/>
																</a>
																<a href="cfajax.php?name='.$file_link.'&act=download" class="tip download" alt="'.$file_link.'" title="'._T("admin_db_database_download_backup").'">
																	<img src="img/database_save.png" height="16" width="16" border="0" alt="'._T("admin_db_database_download_backup").'"/>
																</a>
															</td>
															<td class="textleft">'.date("F d Y H:i:s", filemtime(CFBACKUPPATH.$file_link)).'</td>
															<td class="textleft">'.$file_link.'</td></tr>';
						}
					// image database backup table
						echo '
				<div class="code_box"><label>'._T("admin_db_database_bandwidth_backup").'</label><a href="#" id="2" class="button backup" title="'._T("admin_db_database_bandwidth_backup").'" ret="'._T("admin_db_database_bandwidth_backup").'" ><img src="img/database_edit.png" height="16" width="16" border="0" alt="'._T("admin_db_database_bandwidth_backup").'"/>'._T("admin_db_database_now").'</a></div>
				<table class="table_set">
					<thead>
					<tr class="odd">
						<th>&nbsp;</th>
						<th scope="col" title="'._T("admin_db_database_backup_table_date_tip").'">'._T("admin_db_database_backup_table_date").'</th>
						<th scope="col" title="'._T("admin_db_database_backup_table_name_tip").'">'._T("admin_db_database_backup_table_name").'</th>
					</tr>
					</thead>
					<tbody>
						'.(isset($bandwidth_bk_list)?$bandwidth_bk_list:'').'
					</tbody></table>';
					?>
					</div>
				<!--Rebuild DB-->
					<div id="setRebuildDB" class="panel ibox">
					<?php
						optionTitle(_T("admin_db_rebuild_title"));
						optionDescription(_T("admin_db_image_description"));
					?>
						<div class="code_box"><label><?php echo _T("admin_db_image_check");?></label><input type="button" class="button" value="now" onclick="document.getElementById('dbt').src ='admin.php?act=checkfoulder'" /></div>
						<?php optionDescription(_T("admin_db_rebuild_description"));?>
						<div class="code_box"><label><?php echo _T("admin_db_rebuild_check");?></label><input type="button" class="button" value="now" onclick="document.getElementById('dbt').src ='admin.php?act=rmid'" /></div>
						
						<iframe id="dbt" src="admin.php?act=rmid&n" width="650" height="200" class="if_db">
							<p>Your browser does not support iframes.</p>
						</iframe>
					</div>
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</form>

<?php
// SETTINGS FUNCTIONS
	function optionList($label,$name,$setting,$list,$return=0){
		$html = '
		<div class="code_box"><label>'.$label.' :</label>
		<select name="'.$name.'" class="text_input">';
		foreach ($list  as $k => $v){
			$html .=  '<option value="'.$k.'" '.($setting==$k?'selected="selected"':'').'>'.$v.'</option>';
		}
		$html .=  '</select></div>';
		if($return) return $html;
		echo $html;
	}
	function optionOnOff($label,$name,$setting,$info = null,$return=0){
		$html = '
		<div class="code_box"><label>'.$label.' :</label>
		<select name="'.$name.'" class="text_input">
			<option value="0" '.(!$setting?'selected="selected"':'').'>'._T("admin_set_option_off").'</option>
			<option value="1" '.($setting?'selected="selected"':'').'>'._T("admin_set_option_on").'</option>
		</select>'.(!is_null($info)?'<span>'.$info.'</span>':'').'</div>';
		if($return) return $html;
		echo $html;
	}
	function optionText($label,$name,$setting,$size=null,$type=null){$eClass = (is_null($size)?'text_input':'text_input long');$eType = (is_null($type)?'text':$type);echo '<div class="code_box"><label>'.$label.' :</label><input class="'.$eClass.'" type="'.$eType.'" name="'.$name.'" value="'.$setting.'" size="20" /></div>';}
	function submitButton(){echo '<div class="code_box"><label></label><input class="button button_cen" type="submit" value="'._T("admin_set_save_button").'" name="changesettings[]"></div>';}
	function optionTitle($title){echo '<h2>'.$title.'</h2>';}
	function optionDescription($des){echo '<p class="teaser">'.$des.'</p>';}

// PAGE END
	require_once('admin/admin_page_footer.php');
	die();
	exit;
