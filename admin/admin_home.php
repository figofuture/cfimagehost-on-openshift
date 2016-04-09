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
 *   Used For:     Admin Dashboard
 *   Last edited:  19/12/2012
 *
 *************************************************************************************************************/

 // Image Report List
		if ($act == "report" && cl($_GET['id']) != ''){
			if(db_remove_from_report(cl($_GET['id']))){
				user_feedback('success',_T("admin_home_report_remove_suc"),'image_report_remove');
			}else{
				user_feedback('error',_T("admin_home_report_remove_err"),'admin_home_report_remove');
			}
		}

// set any header hooks
	add_code_line('admin-footer','<script type= "text/javascript">
		$(document).ready(function() {
			$("#overview").load(\'admin/ajax/admin_dashboard_ajax.php'.(!$ADMIN_DASHBOARD_FULL?'?t=b':'').'\', function(response, status, xhr) {
				if (status == "error") {
					// handle error
				}else{
					$(this).fadeIn();
					'.($settings['SET_ALLOW_REPORT']?'
					$("#report").load(\'admin/ajax/admin_report_ajax.php\', function() {
							$(this).fadeIn();
							removeImageFromList(this);
					});':'').'
				}
			});
		});
	</script>');


// page settings
	$page['id']				= 'home';
	$page['title']			= _T("admin_menu_home");
	$page['description']	= '';
	$page['tipsy'] 			= true;
	$page['lightbox']		= true;

// load page header
	require CFADMINPATH.'admin_page_header.php';
?>
	<div id="ajaxload"><div id="overview"></div></div>
	<div id="loader"><span><?php echo _T('admin_home_dashboard_loading');?></span><img src="<?php echo $settings['SET_SITEURL'];?>/img/loading.gif" alt="loading..." /></div>

	<div id="report"></div>
	<div class="clear"></div>
<?php
	require CFADMINPATH.'admin_page_footer.php';
	die();
	exit;